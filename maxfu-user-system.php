<?php
/**
 * Plugin Name:       Max Fu's User System
 * Description:       A plugin that replaces the WordPress login flow with a custom page. Built according to article 'Build a Custom WordPress User Flow' by Jarkko Laine. Re-developed by Max Fu.
 * Version:           0.0.2
 * Author:            Max Fu
 * License:           GPL-2.0+
 * Text Domain:       maxfu-user-system
 * Domain Path:       /languages
 */

class Personalize_Login_Plugin {

    /**
     * Initializes the plugin.
     *
     * To keep the initialization fast, only add filter and action
     * hooks in the constructor.
     */
    public function __construct() {
        // Localisation Support
        load_plugin_textdomain('maxfu-user-system', false, 'maxfu-user-system/languages');
        add_shortcode( 'custom-login-form', array( $this, 'render_login_form' ) );
        add_shortcode( 'custom-register-form', array( $this, 'render_register_form' ) );
        add_shortcode( 'custom-password-lost-form', array( $this, 'render_password_lost_form' ) );
        add_shortcode( 'custom-password-reset-form', array( $this, 'render_password_reset_form' ) );
        add_shortcode( 'account-info', array( $this, 'render_account_info' ) );
//        add_action( 'login_form_login', array( $this, 'redirect_to_custom_login' ) );
        add_filter( 'authenticate', array( $this, 'maybe_redirect_at_authenticate' ), 101, 3 );
//        add_action( 'wp_logout', array( $this, 'redirect_after_logout' ) );
        add_filter( 'login_redirect', array( $this, 'redirect_after_login' ), 10, 3 );
        add_action( 'login_form_register', array( $this, 'redirect_to_custom_register' ) );
        add_action( 'login_form_register', array( $this, 'do_register_user' ) );
        add_action( 'login_form_lostpassword', array( $this, 'redirect_to_custom_lostpassword' ) );
        add_action( 'login_form_lostpassword', array( $this, 'do_password_lost' ) );
        add_filter( 'retrieve_password_message', array( $this, 'replace_retrieve_password_message' ), 10, 4 );
        add_action( 'login_form_rp', array( $this, 'redirect_to_custom_password_reset' ) );
        add_action( 'login_form_resetpass', array( $this, 'redirect_to_custom_password_reset' ) );
        add_action( 'login_form_rp', array( $this, 'do_password_reset' ) );
        add_action( 'login_form_resetpass', array( $this, 'do_password_reset' ) );
        // Extra User Fields
        add_action( 'show_user_profile', array( $this, 'my_show_extra_profile_fields' ) );
        add_action( 'edit_user_profile', array( $this, 'my_show_extra_profile_fields' ) );
        add_action( 'personal_options_update', array( $this, 'my_save_extra_profile_fields' ) );
        add_action( 'edit_user_profile_update', array( $this, 'my_save_extra_profile_fields' ) );
    }

    /**
     * Plugin activation hook.
     *
     * Creates all WordPress pages needed by the plugin.
     */
    public static function plugin_activated() {
        // Information needed for creating the plugin's pages
        $page_definitions = array(
            'member-login' => array(
                'title' => __( 'Sign In', 'maxfu-user-system' ),
                'content' => '[custom-login-form]'
            ),
            'member-account' => array(
                'title' => __( 'Your Account', 'maxfu-user-system' ),
                'content' => '[account-info]'
            ),
            'member-register' => array(
                'title' => __( 'Register', 'maxfu-user-system' ),
                'content' => '[custom-register-form]'
            ),
            'member-password-lost' => array(
                'title' => __( 'Forgot Your Password?', 'maxfu-user-system' ),
                'content' => '[custom-password-lost-form]'
            ),
            'member-password-reset' => array(
                'title' => __( 'Pick a New Password', 'maxfu-user-system' ),
                'content' => '[custom-password-reset-form]'
            ),
        );

        foreach ( $page_definitions as $slug => $page ) {
            // Check that the page doesn't exist already
            $query = new WP_Query( 'pagename=' . $slug );
            if ( ! $query->have_posts() ) {
                // Add the page using the data from the array above
                wp_insert_post(
                    array(
                        'post_content'   => $page['content'],
                        'post_name'      => $slug,
                        'post_title'     => $page['title'],
                        'post_status'    => 'publish',
                        'post_type'      => 'page',
                        'ping_status'    => 'closed',
                        'comment_status' => 'closed',
                    )
                );
            }
        }
    }

    /**
     * A shortcode for rendering the login form.
     *
     * @param  array   $attributes  Shortcode attributes.
     * @param  string  $content     The text content for shortcode. Not used.
     *
     * @return string  The shortcode output
     */
    public function render_login_form( $attributes, $content = null ) {
        // Parse shortcode attributes
        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );
        $show_title = $attributes['show_title'];

        if ( is_user_logged_in() ) {
            return __( 'You are already signed in.', 'maxfu-user-system' );
        }

        // Pass the redirect parameter to the WordPress login functionality: by default,
        // don't specify a redirect, but if a valid redirect URL has been passed as
        // request parameter, use it.
        $attributes['redirect'] = '';
        if ( isset( $_REQUEST['redirect_to'] ) ) {
            $attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'], $attributes['redirect'] );
        }

        // Error messages
        $errors = array();
        if ( isset( $_REQUEST['login'] ) ) {
            $error_codes = explode( ',', $_REQUEST['login'] );

            foreach ( $error_codes as $code ) {
                $errors []= $this->get_error_message( $code );
            }
        }
        $attributes['errors'] = $errors;

        // Check if user just logged out
        $attributes['logged_out'] = isset( $_REQUEST['logged_out'] ) && $_REQUEST['logged_out'] == true;

        // Check if the user just registered
        $attributes['registered'] = isset( $_REQUEST['registered'] );

        // Check if the user just requested a new password
        $attributes['lost_password_sent'] = isset( $_REQUEST['checkemail'] ) && $_REQUEST['checkemail'] == 'confirm';

        // Check if user just updated password
        $attributes['password_updated'] = isset( $_REQUEST['password'] ) && $_REQUEST['password'] == 'changed';

        // Render the login form using an external template
        return $this->get_template_html( 'login_form', $attributes );
    }

    /**
     * A shortcode for rendering the new user registration form.
     *
     * @param  array   $attributes  Shortcode attributes.
     * @param  string  $content     The text content for shortcode. Not used.
     *
     * @return string  The shortcode output
     */
    public function render_register_form( $attributes, $content = null ) {
        // Parse shortcode attributes
        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );

        if ( is_user_logged_in() ) {
            return __( 'You are already signed in.', 'maxfu-user-system' );
        }

        if ( ! get_option( 'users_can_register' ) ) {
            return __( 'Registering new users is currently not allowed.', 'maxfu-user-system' );
        }

        // Retrieve possible errors from request parameters
        $attributes['errors'] = array();
        if ( isset( $_REQUEST['register-errors'] ) ) {
            $error_codes = explode( ',', $_REQUEST['register-errors'] );

            foreach ( $error_codes as $error_code ) {
                $attributes['errors'] []= $this->get_error_message( $error_code );
            }
        }

        return $this->get_template_html( 'register_form', $attributes );
    }

    /**
     * A shortcode for rendering the form used to initiate the password reset.
     *
     * @param  array   $attributes  Shortcode attributes.
     * @param  string  $content     The text content for shortcode. Not used.
     *
     * @return string  The shortcode output
     */
    public function render_password_lost_form( $attributes, $content = null ) {
        // Parse shortcode attributes
        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );

        if ( is_user_logged_in() ) {
            return __( 'You are already signed in.', 'maxfu-user-system' );
        }

        // Retrieve possible errors from request parameters
        $attributes['errors'] = array();
        if ( isset( $_REQUEST['errors'] ) ) {
            $error_codes = explode( ',', $_REQUEST['errors'] );

            foreach ( $error_codes as $error_code ) {
                $attributes['errors'] []= $this->get_error_message( $error_code );
            }
        }

        return $this->get_template_html( 'password_lost_form', $attributes );

    }

    /**
     * A shortcode for rendering the form used to reset a user's password.
     *
     * @param  array   $attributes  Shortcode attributes.
     * @param  string  $content     The text content for shortcode. Not used.
     *
     * @return string  The shortcode output
     */
    public function render_password_reset_form( $attributes, $content = null ) {
        // Parse shortcode attributes
        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );

        if ( is_user_logged_in() ) {
            return __( 'You are already signed in.', 'maxfu-user-system' );
        } else {
            if ( isset( $_REQUEST['login'] ) && isset( $_REQUEST['key'] ) ) {
                $attributes['login'] = $_REQUEST['login'];
                $attributes['key'] = $_REQUEST['key'];

                // Error messages
                $errors = array();
                if ( isset( $_REQUEST['error'] ) ) {
                    $error_codes = explode( ',', $_REQUEST['error'] );

                    foreach ( $error_codes as $code ) {
                        $errors []= $this->get_error_message( $code );
                    }
                }
                $attributes['errors'] = $errors;

                return $this->get_template_html( 'password_reset_form', $attributes );
            } else {
                return __( 'Invalid password reset link.', 'maxfu-user-system' );
            }
        }
    }

    /**
     * A shortcode for rendering the form used to reset a user's password.
     *
     * @param  array   $attributes  Shortcode attributes.
     * @param  string  $content     The text content for shortcode. Not used.
     *
     * @return string  The shortcode output
     */
    public function render_account_info( $attributes, $content = null ) {
        // Parse shortcode attributes
        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );

        if ( is_user_logged_in() ) {
            // Error messages
            $errors = array();
            if ( isset( $_REQUEST['error'] ) ) {
                $error_codes = explode( ',', $_REQUEST['error'] );

                foreach ( $error_codes as $code ) {
                    $errors []= $this->get_error_message( $code );
                }
            }
            $attributes['errors'] = $errors;

            return $this->get_template_html( 'account_info', $attributes );
        } else {
            return __( 'You are not signed in yet.', 'maxfu-user-system' );
        }
    }

    /**
     * Redirect the user to the custom login page instead of wp-login.php.
     */
    function redirect_to_custom_login() {
        if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
            $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : null;

            if ( is_user_logged_in() ) {
                $this->redirect_logged_in_user( $redirect_to );
                exit;
            }

            // The rest are redirected to the login page
            $login_url = home_url( 'member-login' );
            if ( ! empty( $redirect_to ) ) {
                $login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );
            }

            wp_redirect( $login_url );
            exit;
        }
    }

    /**
     * Redirects the user to the correct page depending on whether he / she
     * is an admin or not.
     *
     * @param string $redirect_to   An optional redirect_to URL for admin users
     */
    private function redirect_logged_in_user( $redirect_to = null ) {
        $user = wp_get_current_user();
        if ( user_can( $user, 'manage_options' ) ) {
            if ( $redirect_to ) {
                wp_safe_redirect( $redirect_to );
            } else {
                wp_redirect( admin_url() );
            }
        } else {
            wp_redirect( home_url( 'member-account' ) );
        }
    }

    /**
     * Redirect the user after authentication if there were any errors.
     *
     * @param Wp_User|Wp_Error  $user       The signed in user, or the errors that have occurred during login.
     * @param string            $username   The user name used to log in.
     * @param string            $password   The password used to log in.
     *
     * @return Wp_User|Wp_Error The logged in user, or error information if there were errors.
     */
    function maybe_redirect_at_authenticate( $user, $username, $password ) {
        // Check if the earlier authenticate filter (most likely,
        // the default WordPress authentication) functions have found errors
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            if ( is_wp_error( $user ) ) {
                $error_codes = join( ',', $user->get_error_codes() );

                $login_url = home_url( 'member-login' );
                $login_url = add_query_arg( 'login', $error_codes, $login_url );

                wp_redirect( $login_url );
                exit;
            }
        }

        return $user;
    }

    /**
     * Redirect to custom login page after the user has been logged out.
     */
    public function redirect_after_logout() {
        $redirect_url = home_url( 'member-login?logged_out=true' );
        wp_safe_redirect( $redirect_url );
        exit;
    }

    /**
     * Returns the URL to which the user should be redirected after the (successful) login.
     *
     * @param string           $redirect_to           The redirect destination URL.
     * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
     * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
     *
     * @return string Redirect URL
     */
    public function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {
        $redirect_url = home_url();

        if ( ! isset( $user->ID ) ) {
            return $redirect_url;
        }

        if ( user_can( $user, 'manage_options' ) ) {
            // Use the redirect_to parameter if one is set, otherwise redirect to admin dashboard.
            if ( $requested_redirect_to == '' ) {
                $redirect_url = admin_url();
            } else {
                $redirect_url = $requested_redirect_to;
            }
        } else {
            // Non-admin users always go to their account page after login
            $redirect_url = home_url( 'member-account' );
        }

        return wp_validate_redirect( $redirect_url, home_url() );
    }

    /**
     * Redirects the user to the custom registration page instead
     * of wp-login.php?action=register.
     */
    public function redirect_to_custom_register() {
        if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
            if ( is_user_logged_in() ) {
                $this->redirect_logged_in_user();
            } else {
                wp_redirect( home_url( 'member-register' ) );
            }
            exit;
        }
    }

    /**
     * Handles the registration of a new user.
     *
     * Used through the action hook "login_form_register" activated on wp-login.php
     * when accessed through the registration action.
     */
    public function do_register_user() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
            $redirect_url = home_url( 'member-register' );

            if ( ! get_option( 'users_can_register' ) ) {
                // Registration closed, display error
                $redirect_url = add_query_arg( 'register-errors', 'closed', $redirect_url );
            } else {
                $email = $_POST['email'];
                $first_name = sanitize_text_field( $_POST['first_name'] );
                $last_name = sanitize_text_field( $_POST['last_name'] );

                $result = $this->register_user( $email, $first_name, $last_name );

                if ( is_wp_error( $result ) ) {
                    // Parse errors into a string and append as parameter to redirect
                    $errors = join( ',', $result->get_error_codes() );
                    $redirect_url = add_query_arg( 'register-errors', $errors, $redirect_url );
                } else {
                    $current_user = get_current_user_id();
                    wp_set_current_user( $result );
                    $ccca_meta_profile = array(
                        'company_name'      => $_POST['company_name'],
                        'company_address'   => $_POST['company_address'],
                        'company_phone'     => $_POST['company_phone'],
                        'company_website'   => $_POST['company_website'],
                        'company_type'      => $_POST['company_type'],
                        'company_industry'  => $_POST['company_industry'],
                        'company_branch'    => $_POST['company_branch'],
                        'pic_fname'         => $_POST['first_name'],
                        'pic_lname'         => $_POST['last_name'],
                        'pic_title'         => $_POST['pic_title'],
                        'pic_mobile'        => $_POST['pic_mobile'],
                        'pic_phone'         => $_POST['pic_phone'],
                        'pic_email'         => $_POST['pic_email'],
                        'contact_1_name'    => $_POST['contact_1_name'],
                        'contact_1_mobile'  => $_POST['contact_1_mobile'],
                        'contact_1_email'   => $_POST['contact_1_email'],
                        'contact_2_name'    => $_POST['contact_2_name'],
                        'contact_2_mobile'  => $_POST['contact_2_mobile'],
                        'contact_2_email'   => $_POST['contact_2_email'],
                        'comment'           => $_POST['comment'],
                    );
                    /* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
                	  update_usermeta( $result, 'ccca_profile', $ccca_meta_profile );

                    wp_set_current_user( $current_user );

                    // Success, redirect to login page.
                    $redirect_url = home_url( 'member-login' );
                    $redirect_url = add_query_arg( 'registered', $email, $redirect_url );
                }
            }

            wp_redirect( $redirect_url );
            exit;
        }
    }

    /**
     * Redirects the user to the custom "Forgot your password?" page instead of
     * wp-login.php?action=lostpassword.
     */
    public function redirect_to_custom_lostpassword() {
        if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
            if ( is_user_logged_in() ) {
                $this->redirect_logged_in_user();
                exit;
            }

            wp_redirect( home_url( 'member-password-lost' ) );
            exit;
        }
    }

    /**
     * Initiates password reset.
     */
    public function do_password_lost() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
            $errors = retrieve_password();
            if ( is_wp_error( $errors ) ) {
                // Errors found
                $redirect_url = home_url( 'member-password-lost' );
                $redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );
            } else {
                // Email sent
                $redirect_url = home_url( 'member-login' );
                $redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
            }

            wp_redirect( $redirect_url );
            exit;
        }
    }

    /**
     * Returns the message body for the password reset mail.
     * Called through the retrieve_password_message filter.
     *
     * @param string  $message    Default mail message.
     * @param string  $key        The activation key.
     * @param string  $user_login The username for the user.
     * @param WP_User $user_data  WP_User object.
     *
     * @return string   The mail message to send.
     */
    public function replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {
        // Create new message
        $msg  = __( 'Hello!', 'maxfu-user-system' ) . "\r\n\r\n";
        $msg .= sprintf( __( 'You asked us to reset your password for your account using the email address %s.', 'maxfu-user-system' ), $user_login ) . "\r\n\r\n";
        $msg .= __( "If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", 'maxfu-user-system' ) . "\r\n\r\n";
        $msg .= __( 'To reset your password, visit the following address:', 'maxfu-user-system' ) . "\r\n\r\n";
        $msg .= site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . "\r\n\r\n";
        $msg .= __( 'Thanks!', 'maxfu-user-system' ) . "\r\n";

        return $msg;
    }

    /**
     * Redirects to the custom password reset page, or the login page
     * if there are errors.
     */
    public function redirect_to_custom_password_reset() {
        if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
            // Verify key / login combo
            $user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
            if ( ! $user || is_wp_error( $user ) ) {
                if ( $user && $user->get_error_code() === 'expired_key' ) {
                    wp_redirect( home_url( 'member-login?login=expiredkey' ) );
                } else {
                    wp_redirect( home_url( 'member-login?login=invalidkey' ) );
                }
                exit;
            }

            $redirect_url = home_url( 'member-password-reset' );
            $redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
            $redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );

            wp_redirect( $redirect_url );
            exit;
        }
    }

    /**
     * Resets the user's password if the password reset form was submitted.
     */
    public function do_password_reset() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
            $rp_key = $_REQUEST['rp_key'];
            $rp_login = $_REQUEST['rp_login'];

            $user = check_password_reset_key( $rp_key, $rp_login );

            if ( ! $user || is_wp_error( $user ) ) {
                if ( $user && $user->get_error_code() === 'expired_key' ) {
                    wp_redirect( home_url( 'member-login?login=expiredkey' ) );
                } else {
                    wp_redirect( home_url( 'member-login?login=invalidkey' ) );
                }
                exit;
            }

            if ( isset( $_POST['pass1'] ) ) {
                if ( $_POST['pass1'] != $_POST['pass2'] ) {
                    // Passwords don't match
                    $redirect_url = home_url( 'member-password-reset' );

                    $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                    $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                    $redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );

                    wp_redirect( $redirect_url );
                    exit;
                }

                if ( empty( $_POST['pass1'] ) ) {
                    // Password is empty
                    $redirect_url = home_url( 'member-password-reset' );

                    $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                    $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                    $redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );

                    wp_redirect( $redirect_url );
                    exit;
                }

                // Parameter checks OK, reset password
                reset_password( $user, $_POST['pass1'] );
                wp_redirect( home_url( 'member-login?password=changed' ) );
            } else {
                echo "Invalid request.";
            }

            exit;
        }
    }

    /**
     * Renders the contents of the given template to a string and returns it.
     *
     * @param string $template_name The name of the template to render (without .php)
     * @param array  $attributes    The PHP variables for the template
     *
     * @return string               The contents of the template.
     */
    private function get_template_html( $template_name, $attributes = null ) {
        if ( ! $attributes ) {
            $attributes = array();
        }

        ob_start();

        do_action( 'personalize_login_before_' . $template_name );

        require( 'templates/' . $template_name . '.php');

        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    /**
     * Finds and returns a matching error message for the given error code.
     *
     * @param string $error_code    The error code to look up.
     *
     * @return string               An error message.
     */
    private function get_error_message( $error_code ) {
        switch ( $error_code ) {
            case 'empty_username':
                return __( 'You do have an email address, right?', 'maxfu-user-system' );

            case 'empty_password':
                return __( 'You need to enter a password to login.', 'maxfu-user-system' );

            case 'invalid_username':
                return __(
                    "We don't have any users with that email address. Maybe you used a different one when signing up?",
                    'maxfu-user-system'
                );

            case 'incorrect_password':
                $err = __(
                    "The password you entered wasn't quite right. <a href='%s'>Did you forget your password</a>?",
                    'maxfu-user-system'
                );
                return sprintf( $err, wp_lostpassword_url() );

            // Registration errors
            case 'email':
                return __( 'The email address you entered is not valid.', 'maxfu-user-system' );

            case 'email_exists':
                return __( 'An account exists with this email address.', 'maxfu-user-system' );

            case 'closed':
                return __( 'Registering new users is currently not allowed.', 'maxfu-user-system' );

            // Lost password
            case 'empty_username':
                return __( 'You need to enter your email address to continue.', 'maxfu-user-system' );

            case 'invalid_email':
            case 'invalidcombo':
                return __( 'There are no users registered with this email address.', 'maxfu-user-system' );

            // Reset password
            case 'expiredkey':
            case 'invalidkey':
                return __( 'The password reset link you used is not valid anymore.', 'maxfu-user-system' );

            case 'password_reset_mismatch':
                return __( "The two passwords you entered don't match.", 'maxfu-user-system' );

            case 'password_reset_empty':
                return __( "Sorry, we don't accept empty passwords.", 'maxfu-user-system' );

            default:
                break;
        }

        return __( 'An unknown error occurred. Please try again later.', 'maxfu-user-system' );
    }

    /**
     * Validates and then completes the new user signup process if all went well.
     *
     * @param string $email         The new user's email address
     * @param string $first_name    The new user's first name
     * @param string $last_name     The new user's last name
     *
     * @return int|WP_Error         The id of the user that was created, or error if failed.
     */
    private function register_user( $email, $first_name, $last_name ) {
        $errors = new WP_Error();

        // Email address is used as both username and email. It is also the only
        // parameter we need to validate
        if ( ! is_email( $email ) ) {
            $errors->add( 'email', $this->get_error_message( 'email' ) );
            return $errors;
        }

        if ( username_exists( $email ) || email_exists( $email ) ) {
            $errors->add( 'email_exists', $this->get_error_message( 'email_exists') );
            return $errors;
        }

        // Generate the password so that the subscriber will have to check email...
        $password = wp_generate_password( 12, false );

        $user_data = array(
            'user_login'    => $email,
            'user_email'    => $email,
            'user_pass'     => $password,
            'first_name'    => $first_name,
            'last_name'     => $last_name,
            'nickname'      => $first_name,
        );

        $user_id = wp_insert_user( $user_data );
        wp_new_user_notification( $user_id, $password );

        return $user_id;
    }

    /**
     * Extra User Fields
     */
    public function my_show_extra_profile_fields( $user ) {
      $dict = array(
          'en-US' => array(
              'ccca_member_profile' => 'CCCA Member Profile',
              'company_information' => 'Company Information',
              'company_name' => 'Company Name',
              'company_address' => 'Company Address',
              'company_telephone' => 'Company Telephone',
              'company_website' => 'Company Website',
              'company_type' => 'Company Type',
              'company_industry' => 'Company Industry',
              'company_branch' => 'Company Branch',
              'person_in_charge' => 'Person in Charge',
              'first_name' => 'Person in Charge First Name',
              'last_name' => 'Person in Charge Last Name',
              'title' => 'Person in Charge Title',
              'mobile' => 'Person in Charge Mobile',
              'telephone' => 'Person in Charge Telephone',
              'email' => 'Person in Charge Email',
              'other_contacts' => 'Other Contacts',
              'first_contact_name' => 'First Contact Name',
              'first_contact_mobile' => 'First Contact Mobile',
              'first_contact_email' => 'First Contact Email',
              'second_contact_name' => 'Second Contact Name',
              'second_contact_mobile' => 'Second Contact Mobile',
              'second_contact_email' => 'Second Contact Email',
              'sel' => 'Please Select',
              'cce' => 'Chinese Central Enterprise',
              'csoe' => 'Chinese State-Owned Enterprise',
              'cpe' => 'Chinese Private Enterprise',
              'pts' => 'Partnership',
              'alcc' => 'Australian Local Chinese Company',
              'alc' => 'Australian Local Company',
              'oth' => 'Other(Fill Details in Comment)',
              'sydney' => 'Sydney Branch',
              'melbourne' => 'Melbourne Branch',
              'perth' => 'Perth Branch',
              'brisbane' => 'Brisbane Branch',
              'adelaide' => 'Adelaide Branch',
              'comment' => 'Comment'
          ),

          'zh-CN' => array(
            'ccca_member_profile' => '商会会员资料',
            'company_information' => '公司信息',
            'company_name' => '公司名称',
            'company_address' => '公司地址',
            'company_telephone' => '公司电话',
            'company_website' => '公司网站',
            'company_type' => '公司属性',
            'company_industry' => '公司行业',
            'company_branch' => '所属分会',
            'person_in_charge' => '负责人信息',
            'first_name' => '负责人姓氏',
            'last_name' => '负责人名字',
            'title' => '负责人职位',
            'mobile' => '负责人手机',
            'telephone' => '负责人电话',
            'email' => '负责人邮件',
            'other_contacts' => '其他联系人',
            'first_contact_name' => '第一联系人姓名',
            'first_contact_mobile' => '第一联系人手机',
            'first_contact_email' => '第一联系人邮箱',
            'second_contact_name' => '第二联系人姓名',
            'second_contact_mobile' => '第二联系人手机',
            'second_contact_email' => '第二联系人邮箱',
            'sel' => '请选择',
            'cce' => '中资央企',
            'csoe' => '中资国企',
            'cpe' => '中资民营',
            'pts' => '合伙制',
            'alcc' => '澳州当地华人企业',
            'alc' => '澳洲当地的外资企业',
            'oth' => '其他(请在备注中填写)',
            'sydney' => '悉尼分会',
            'melbourne' => '墨尔本分会',
            'perth' => '珀斯分会',
            'brisbane' => '布里斯班分会',
            'adelaide' => '阿德莱德分会',
            'comment' => '备注'
          )
      );

      $lang = get_bloginfo("language");

      $strings = $dict[$lang];

      $ccca_profile = get_the_author_meta( 'ccca_profile', $user->ID );

    	if ( ! is_array( $ccca_profile ) ) {
          $ccca_profile = array(
              'company_name'      => '',
              'company_address'   => '',
              'company_phone'     => '',
              'company_website'   => '',
              'company_type'      => 'sel',
              'company_industry'  => '',
              'company_branch'    => 'sel',
              'pic_fname'         => '',
              'pic_lname'         => '',
              'pic_title'         => '',
              'pic_mobile'        => '',
              'pic_phone'         => '',
              'pic_email'         => '',
              'contact_1_name'    => '',
              'contact_1_mobile'  => '',
              'contact_1_email'   => '',
              'contact_2_name'    => '',
              'contact_2_mobile'  => '',
              'contact_2_email'   => '',
              'comment'           => '',
            );
        }

      ?>
      <style type="text/css">
        #ccca-member-profile th {
          margin-bottom: 9px;
          padding: 0 10px;
          line-height:1.3;
          vertical-align:middle;
        }
        #ccca-member-profile td {
          margin-bottom: 9px;
          padding: 0px 10px;
          line-height:1.3;
          vertical-align:middle;
        }
        #ccca-member-profile h3 {
          margin: 0;
        }
      </style>

    	<h2><?php _e('CCCA Member Profile', 'maxfu-user-system'); ?></h2>

    	<table class="form-table" id="ccca-member-profile">

        <tr>
    			<th><h3><?php echo $strings['company_information']; ?></h3></th>
          <th><h3><?php echo $strings['person_in_charge']; ?></h3></th>
          <th><h3><?php echo $strings['other_contacts']; ?></h3></th>
    		</tr>

        <tr>
          <th><label for="company_name"><?php echo $strings['company_name']; ?></label></th>
          <th><label for="pic_fname"><?php echo $strings['first_name']; ?></label></th>
          <th><label for="contact_1_name"><?php echo $strings['first_contact_name']; ?></label></th>
        </tr>
        <tr>
          <td>
    				<input type="text" name="company_name" id="company_name" value="<?php echo esc_attr( $ccca_profile['company_name'] ); ?>" class="regular-text" /><br />
    			</td>
          <td>
    				<input type="text" name="pic_fname" id="pic_fname" value="<?php echo esc_attr( $ccca_profile['pic_fname'] ); ?>" class="regular-text" /><br />
    			</td>
          <td>
    				<input type="text" name="contact_1_name" id="contact_1_name" value="<?php echo esc_attr( $ccca_profile['contact_1_name'] ); ?>" class="regular-text" /><br />
    			</td>
        </tr>

        <tr>
          <th><label for="company_address"><?php echo $strings['company_address']; ?></label></th>
          <th><label for="pic_lname"><?php echo $strings['last_name']; ?></label></th>
          <th><label for="contact_1_mobile"><?php echo $strings['first_contact_mobile']; ?></label></th>
        </tr>
        <tr>
          <td>
    				<input type="text" name="company_address" id="company_address" value="<?php echo esc_attr( $ccca_profile['company_address'] ); ?>" class="regular-text" /><br />
    			</td>
          <td>
    				<input type="text" name="pic_lname" id="pic_lname" value="<?php echo esc_attr( $ccca_profile['pic_lname'] ); ?>" class="regular-text" /><br />
    			</td>
          <td>
    				<input type="text" name="contact_1_mobile" id="contact_1_mobile" value="<?php echo esc_attr( $ccca_profile['contact_1_mobile'] ); ?>" class="regular-text" /><br />
    			</td>
        </tr>

        <tr>
          <th><label for="company_phone"><?php echo $strings['company_telephone']; ?></label></th>
          <th><label for="pic_title"><?php echo $strings['title']; ?></label></th>
          <th><label for="contact_1_email"><?php echo $strings['first_contact_email']; ?></label></th>
        </tr>
        <tr>
          <td>
    				<input type="text" name="company_phone" id="company_phone" value="<?php echo esc_attr( $ccca_profile['company_phone'] ); ?>" class="regular-text" /><br />
    			</td>
          <td>
    				<input type="text" name="pic_title" id="pic_title" value="<?php echo esc_attr( $ccca_profile['pic_title'] ); ?>" class="regular-text" /><br />
    			</td>
          <td>
    				<input type="text" name="contact_1_email" id="contact_1_email" value="<?php echo esc_attr( $ccca_profile['contact_1_email'] ); ?>" class="regular-text" /><br />
    			</td>
        </tr>

    		<tr>
          <th><label for="company_website"><?php echo $strings['company_website']; ?></label></th>
          <th><label for="pic_mobile"><?php echo $strings['mobile']; ?></label></th>
          <th><label for="contact_2_name"><?php echo $strings['second_contact_name']; ?></label></th>
    		</tr>
        <tr>
          <td>
    				<input type="text" name="company_website" id="company_website" value="<?php echo esc_attr( $ccca_profile['company_website'] ); ?>" class="regular-text" /><br />
    			</td>
          <td>
    				<input type="text" name="pic_mobile" id="pic_mobile" value="<?php echo esc_attr( $ccca_profile['pic_mobile'] ); ?>" class="regular-text" /><br />
    			</td>
          <td>
    				<input type="text" name="contact_2_name" id="contact_2_name" value="<?php echo esc_attr( $ccca_profile['contact_2_name'] ); ?>" class="regular-text" /><br />
    			</td>
    		</tr>

        <tr>
          <th><label for="company_industry"><?php echo $strings['company_industry']; ?></label></th>
          <th><label for="pic_phone"><?php echo $strings['telephone']; ?></label></th>
          <th><label for="contact_2_mobile"><?php echo $strings['second_contact_mobile']; ?></label></th>
    		</tr>
        <tr>
          <td>
    				<input type="text" name="company_industry" id="company_industry" value="<?php echo esc_attr( $ccca_profile['company_industry'] ); ?>" class="regular-text" /><br />
    			</td>
          <td>
    				<input type="text" name="pic_phone" id="pic_phone" value="<?php echo esc_attr( $ccca_profile['pic_phone'] ); ?>" class="regular-text" /><br />
    			</td>
          <td>
    				<input type="text" name="contact_2_mobile" id="contact_2_mobile" value="<?php echo esc_attr( $ccca_profile['contact_2_mobile'] ); ?>" class="regular-text" /><br />
    			</td>
        </tr>

        <tr>
          <th><label for="company_type"><?php echo $strings['company_type']; ?></label></th>
          <th><label for="pic_email"><?php echo $strings['email']; ?></label></th>
          <th><label for="contact_2_email"><?php echo $strings['second_contact_email']; ?></label></th>
        </tr>
        <tr>
          <td>
        		<select name="company_type" id="company_type">
              <option value="sel" data-installed="1" <?php if ( $ccca_profile['company_type'] == 'sel' ) {echo 'selected="selected"';} ?>><?php echo $strings['sel']; ?></option>
              <option value="cce" data-installed="1" <?php if ( $ccca_profile['company_type'] == 'cce' ) {echo 'selected="selected"';} ?>><?php echo $strings['cce']; ?></option>
              <option value="csoe" data-installed="1" <?php if ( $ccca_profile['company_type'] == 'csoe' ) {echo 'selected="selected"';} ?>><?php echo $strings['csoe']; ?></option>
              <option value="cpe" data-installed="1" <?php if ( $ccca_profile['company_type'] == 'cpe' ) {echo 'selected="selected"';} ?>><?php echo $strings['cpe']; ?></option>
              <option value="pts" data-installed="1" <?php if ( $ccca_profile['company_type'] == 'pts' ) {echo 'selected="selected"';} ?>><?php echo $strings['pts']; ?></option>
              <option value="alcc" data-installed="1" <?php if ( $ccca_profile['company_type'] == 'alcc' ) {echo 'selected="selected"';} ?>><?php echo $strings['alcc']; ?></option>
              <option value="alc" data-installed="1" <?php if ( $ccca_profile['company_type'] == 'alc' ) {echo 'selected="selected"';} ?>><?php echo $strings['alc']; ?></option>
              <option value="oth" data-installed="1" <?php if ( $ccca_profile['company_type'] == 'oth' ) {echo 'selected="selected"';} ?>><?php echo $strings['oth']; ?></option>
            </select>
          </td>
          <td>
    				<input type="text" name="pic_email" id="pic_email" value="<?php echo esc_attr( $ccca_profile['pic_email'] ); ?>" class="regular-text" /><br />
    			</td>
          <td>
    				<input type="text" name="contact_2_email" id="contact_2_email" value="<?php echo esc_attr( $ccca_profile['contact_2_email'] ); ?>" class="regular-text" /><br />
    			</td>
        </tr>

        <tr>
          <th><label for="company_branch"><?php echo $strings['company_branch']; ?></label></th>
          <th><label for="comment"><?php echo $strings['comment']; ?></label></th>
        </tr>
        <tr>
          <td>
            <select name="company_branch" id="company_branch">
              <option value="sel" data-installed="1" <?php if ( $ccca_profile['company_branch'] == 'sel' ) echo 'selected="selected"'; ?>><?php echo $strings['sel']; ?></option>
              <option value="Sydney" data-installed="1" <?php if ( $ccca_profile['company_branch'] == 'Sydney' ) echo 'selected="selected"'; ?>><?php echo $strings['sydney']; ?></option>
              <option value="Melbourne" data-installed="1" <?php if ( $ccca_profile['company_branch'] == 'Melbourne' ) echo 'selected="selected"'; ?>><?php echo $strings['melbourne']; ?></option>
              <option value="Perth" data-installed="1" <?php if ( $ccca_profile['company_branch'] == 'Perth' ) echo 'selected="selected"'; ?>><?php echo $strings['perth']; ?></option>
              <option value="Brisbane" data-installed="1" <?php if ( $ccca_profile['company_branch'] == 'Brisbane' ) echo 'selected="selected"'; ?>><?php echo $strings['brisbane']; ?></option>
              <option value="Adelaide" data-installed="1" <?php if ( $ccca_profile['company_branch'] == 'Adelaide' ) echo 'selected="selected"'; ?>><?php echo $strings['adelaide']; ?></option>
            </select>
          </td>
          <td>
    				<input type="text" name="comment" id="comment" value="<?php echo esc_attr( $ccca_profile['comment'] ); ?>" class="regular-text" /><br />
    			</td>
        </tr>

    	</table>
    <?php }

    public function my_save_extra_profile_fields( $user_id ) {
      $ccca_meta_profile = array(
          'company_name'      => $_POST['company_name'],
          'company_address'   => $_POST['company_address'],
          'company_phone'     => $_POST['company_phone'],
          'company_website'   => $_POST['company_website'],
          'company_type'      => $_POST['company_type'],
          'company_industry'  => $_POST['company_industry'],
          'company_branch'    => $_POST['company_branch'],
          'pic_fname'         => $_POST['pic_fname'],
          'pic_lname'         => $_POST['pic_lname'],
          'pic_title'         => $_POST['pic_title'],
          'pic_mobile'        => $_POST['pic_mobile'],
          'pic_phone'         => $_POST['pic_phone'],
          'pic_email'         => $_POST['pic_email'],
          'contact_1_name'    => $_POST['contact_1_name'],
          'contact_1_mobile'  => $_POST['contact_1_mobile'],
          'contact_1_email'   => $_POST['contact_1_email'],
          'contact_2_name'    => $_POST['contact_2_name'],
          'contact_2_mobile'  => $_POST['contact_2_mobile'],
          'contact_2_email'   => $_POST['contact_2_email'],
          'comment'           => $_POST['comment'],
      );

    	if ( !current_user_can( 'edit_user', $user_id ) )
    		return false;

    	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
    	update_usermeta( $user_id, 'ccca_profile', $ccca_meta_profile );
    }

    public function my_author_box() { ?>
    	<div class="author-profile vcard">
    		<?php echo get_avatar( get_the_author_meta( 'user_email' ), '96' ); ?>

    		<h4 class="author-name fn n">Article written by <?php the_author_posts_link(); ?></h4>

    		<p class="author-description author-bio">
    			<?php the_author_meta( 'description' ); ?>
    		</p>

    		<?php if ( get_the_author_meta( 'twitter' ) ) { ?>
    			<p class="twitter clear">
    				<a href="http://twitter.com/<?php the_author_meta( 'twitter' ); ?>" title="Follow <?php the_author_meta( 'display_name' ); ?> on Twitter">Follow <?php the_author_meta( 'display_name' ); ?> on Twitter</a>
    			</p>
    		<?php } // End check for twitter ?>
    	</div><?php
    }
}

// Initialize the plugin
$personalize_login_pages_plugin = new Personalize_Login_Plugin();

// Create the custom pages at plugin activation
register_activation_hook( __FILE__, array( 'Personalize_Login_Plugin', 'plugin_activated' ) );
