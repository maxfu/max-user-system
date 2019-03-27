<?php
/**
 * Plugin Name:       Max User System
 * Description:       A plugin that replaces the WordPress login flow with a custom page. Built according to article 'Build a Custom WordPress User Flow' by Jarkko Laine.
 * Version:           0.0.2
 * Author:            Max
 * License:           GPL-2.0+
 * Text Domain:       max-user-sys
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
        add_action( 'init', array( $this, 'max_user_load_textdomain' ) );
        add_shortcode( 'custom-login-form', array( $this, 'render_login_form' ) );
        add_shortcode( 'custom-register-form', array( $this, 'render_register_form' ) );
        add_shortcode( 'custom-password-lost-form', array( $this, 'render_password_lost_form' ) );
        add_shortcode( 'custom-password-reset-form', array( $this, 'render_password_reset_form' ) );
        add_shortcode( 'account-info', array( $this, 'render_account_info' ) );
        add_action( 'login_form_login', array( $this, 'redirect_to_custom_login' ) );
        add_filter( 'authenticate', array( $this, 'maybe_redirect_at_authenticate' ), 101, 3 );
        add_action( 'wp_logout', array( $this, 'redirect_after_logout' ) );
        add_filter( 'query_vars', array( $this, 'mus_add_custom_query_var' ) );
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

        add_role( 'sydney_branch', __( 'Sydney Branch', 'max-user' ), array( 'read' => true, ) );
        add_role( 'melbourne_branch', __( 'Melbourne Branch', 'max-user' ), array( 'read' => true, ) );
        add_role( 'perth_branch', __( 'Perth Branch', 'max-user' ), array( 'read' => true, ) );
        add_role( 'brisbane_branch', __( 'Brisbane Branch', 'max-user' ), array( 'read' => true, ) );
        add_role( 'adelaide_branch', __( 'Adelaide Branch', 'max-user' ), array( 'read' => true, ) );
    }

    public function mus_add_custom_query_var( $vars ){
      $vars[] = "para";
      return $vars;
    }

    function max_user_load_textdomain() {
      load_plugin_textdomain( 'max-user', false, basename( dirname( __FILE__ ) ) . '/languages');
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
                'title' => __( 'Sign In', 'max-user' ),
                'content' => '[custom-login-form]'
            ),
            'member-account' => array(
                'title' => __( 'Your Account', 'max-user' ),
                'content' => '[account-info]'
            ),
            'member-register' => array(
                'title' => __( 'Register', 'max-user' ),
                'content' => '[custom-register-form]'
            ),
            'member-password-reset' => array(
                'title' => __( 'Reset Password', 'max-user' ),
                'content' => '[custom-password-lost-form]'
            ),
            'pick-new-password' => array(
                'title' => __( 'Pick a New Password', 'max-user' ),
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
            return __( 'You are already signed in.', 'max-user' );
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
            return __( 'You are already signed in.', 'max-user' );
        }

        if ( ! get_option( 'users_can_register' ) ) {
            return __( 'Registering new users is currently not allowed.', 'max-user' );
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
            return __( 'You are already signed in.', 'max-user' );
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
            return __( 'You are already signed in.', 'max-user' );
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
                return __( 'Invalid password reset link.', 'max-user' );
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
            return __( 'You are not signed in yet.', 'max-user' );
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
                $first_name = sanitize_text_field( $_POST['company_name_en'] );
                $last_name = sanitize_text_field( $_POST['company_name_zh'] );
                $user_url = $_POST['company_website'];

                $result = $this->register_user( $email, $first_name, $last_name, $user_url );

                if ( is_wp_error( $result ) ) {
                    // Parse errors into a string and append as parameter to redirect
                    $errors = join( ',', $result->get_error_codes() );
                    $redirect_url = add_query_arg( 'register-errors', $errors, $redirect_url );
                } else {
                    $current_user = get_current_user_id();
                    wp_set_current_user( $result );
                    /* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
                	  update_usermeta( $result, 'company_email', sanitize_email($_POST['company_email']) );
                    update_usermeta( $result, 'company_address', $_POST['company_address'] );
                    update_usermeta( $result, 'company_phone', $_POST['company_phone'] );
                    update_usermeta( $result, 'company_branch', $_POST['company_branch'] );
                    update_usermeta( $result, 'contact_name', sanitize_text_field($_POST['contact_name']) );
                    update_usermeta( $result, 'contact_email', sanitize_email($_POST['contact_email']) );
                    update_usermeta( $result, 'comment', sanitize_textarea_field($_POST['comment']) );

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

            wp_redirect( home_url( 'member-password-reset' ) );
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
                $redirect_url = home_url( 'member-password-reset' );
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
        $msg  = __( 'Hello!', 'max-user' ) . "\r\n\r\n";
        $msg .= sprintf( __( 'You asked us to reset your password for your account using the email address %s.', 'max-user' ), $user_login ) . "\r\n\r\n";
        $msg .= __( "If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", 'max-user' ) . "\r\n\r\n";
        $msg .= __( 'To reset your password, visit the following address:', 'max-user' ) . "\r\n\r\n";
        $msg .= site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . "\r\n\r\n";
        $msg .= __( 'Thanks!', 'max-user' ) . "\r\n";

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

            $redirect_url = home_url( 'pick-new-password' );
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
                    $redirect_url = home_url( 'pick-new-password' );

                    $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                    $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                    $redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );

                    wp_redirect( $redirect_url );
                    exit;
                }

                if ( empty( $_POST['pass1'] ) ) {
                    // Password is empty
                    $redirect_url = home_url( 'pick-new-password' );

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
                return __( 'You do have an email address, right?', 'max-user' );

            case 'empty_password':
                return __( 'You need to enter a password to login.', 'max-user' );

            case 'invalid_username':
                return __(
                    "We don't have any users with that email address. Maybe you used a different one when signing up?",
                    'max-user'
                );

            case 'incorrect_password':
                $err = __(
                    "The password you entered wasn't quite right. <a href='%s'>Did you forget your password</a>?",
                    'max-user'
                );
                return sprintf( $err, wp_lostpassword_url() );

            // Registration errors
            case 'email':
                return __( 'The email address you entered is not valid.', 'max-user' );

            case 'email_exists':
                return __( 'An account exists with this email address.', 'max-user' );

            case 'closed':
                return __( 'Registering new users is currently not allowed.', 'max-user' );

            // Lost password
            case 'empty_username':
                return __( 'You need to enter your email address to continue.', 'max-user' );

            case 'invalid_email':
            case 'invalidcombo':
                return __( 'There are no users registered with this email address.', 'max-user' );

            // Reset password
            case 'expiredkey':
            case 'invalidkey':
                return __( 'The password reset link you used is not valid anymore.', 'max-user' );

            case 'password_reset_mismatch':
                return __( "The two passwords you entered don't match.", 'max-user' );

            case 'password_reset_empty':
                return __( "Sorry, we don't accept empty passwords.", 'max-user' );

            default:
                break;
        }

        return __( 'An unknown error occurred. Please try again later.', 'max-user' );
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
    private function register_user( $email, $first_name, $last_name, $user_url ) {
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
            'user_url'      => $user_url,
        );

        $user_id = wp_insert_user( $user_data );
        wp_new_user_notification( $user_id, $password );

        return $user_id;
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
