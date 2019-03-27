<?php
global $current_user;
get_currentuserinfo();

// WP_User_Query arguments
$args = array(
	'role'           => 'test_branch',
//	'search'         => '@',
//	'search_columns' => array( 'user_login' ),
);

// The User Query
$user_query = new WP_User_Query( $args );

// User Loop
if ( ! empty( $user_query->get_results() ) ) {
	foreach ( $user_query->get_results() as $user ) {
        $msg  = __( 'Hello!', 'max-user' ) . $user->display_name . "\r\n\r\n";
        $msg .= sprintf( __( 'You asked us to reset your password for your account using the email address %s.', 'max-user' ), $user->user_login) . "\r\n\r\n";
        $msg .= __( "If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", 'max-user' ) . "\r\n\r\n";
        $msg .= __( 'To reset your password, visit the following address:', 'max-user' ) . "\r\n\r\n";
        $msg .= site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user->user_login ), 'login' ) . "\r\n\r\n";
        $msg .= __( 'Thanks!', 'max-user' ) . "\r\n";
        echo $msg;
		echo '<p>' . $user->ID . '</p>';
	}
} else {
	echo 'No users found.';
}
?>