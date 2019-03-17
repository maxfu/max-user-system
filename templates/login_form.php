<div class="login-form-container">
    <?php if ( $attributes['show_title'] ) : ?>
        <h2><?php _e( 'Sign In', 'max-user-sys' ); ?></h2>
    <?php endif; ?>

    <!-- Show errors if there are any -->
    <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
        <?php foreach ( $attributes['errors'] as $error ) : ?>
            <p class="login-error">
                <?php echo $error; ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ( $attributes['registered'] ) : ?>
        <p class="login-info">
            <?php
                printf(
                    __( 'You have successfully registered to <strong>%s</strong>. We have emailed your password to the email address you entered.', 'max-user-sys' ),
                    get_bloginfo( 'name' )
                );
            ?>
        </p>
    <?php endif; ?>

    <?php if ( $attributes['lost_password_sent'] ) : ?>
        <p class="login-info">
            <?php _e( 'Check your email for a link to reset your password.', 'max-user-sys' ); ?>
        </p>
    <?php endif; ?>

    <?php if ( $attributes['password_updated'] ) : ?>
        <p class="login-info">
            <?php _e( 'Your password has been changed. You can sign in now.', 'max-user-sys' ); ?>
        </p>
    <?php endif; ?>

    <!-- Show logged out message if user just logged out -->
    <?php if ( $attributes['logged_out'] ) : ?>
        <p class="login-info">
            <?php _e( 'You have signed out. Would you like to sign in again?', 'max-user-sys' ); ?>
        </p>
    <?php endif; ?>

    <?php
        wp_login_form(
            array(
                'label_username' => __( 'Email', 'max-user-sys' ),
                'label_log_in' => __( 'Sign In', 'max-user-sys' ),
                'redirect' => $attributes['redirect'],
            )
        );
    ?>

    <a class="forgot-password" href="<?php echo wp_lostpassword_url(); ?>">
        <?php _e( 'Forgot your password?', 'max-user-sys' ); ?>
    </a>
</div>
