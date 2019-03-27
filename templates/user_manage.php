<?php
global $current_user;
get_currentuserinfo();

// WP_User_Query arguments
$args = array(
	'role'           => 'brisbane_branch',
//	'search'         => '@',
//	'search_columns' => array( 'user_login' ),
);

// The User Query
$user_query = new WP_User_Query( $args );

// User Loop
if ( ! empty( $user_query->get_results() ) ) {
	foreach ( $user_query->get_results() as $user ) {
        $message  = 'Dear member,' . "\r\n\r\n";
        $message .= '' . "\r\n\r\n";
        $message .= 'Welcome to the new official website of China Chamber of Commerce in Australia!' . "\r\n\r\n";
        $message .= '' . "\r\n\r\n";
        $message .= 'Your member account has been created with email address ' . $user->user_login . ' as the login user name.' . "\r\n\r\n";
        $message .= 'Please click below link, type in your user name and follow process to select your password.' . "\r\n\r\n";
        $message .= 'https://www.cccaau.org/en/member-password-reset/' . "\r\n\r\n";
        $message .= '' . "\r\n\r\n";
        $message .= 'Thanks for your support.' . "\r\n\r\n";
        $message .= '' . "\r\n\r\n";
        $message .= 'CCCA team' . "\r\n";
        $subject = 'Your CCCA member account is ready.';

        $mail_sent = wp_mail( $user->user_login, $subject, $message, $headers = '', $attachments = array() );
        if ( $mail_sent ) { echo $user->user_login  . '</br>'; }
        $mail_sent = wp_mail( get_the_author_meta( 'second_email', $user->ID ), $subject, $message, $headers = '', $attachments = array() );
        if ( $mail_sent ) { echo get_the_author_meta( 'second_email', $user->ID ) . '</br>'; }
        $mail_sent = wp_mail( get_the_author_meta( 'third_email', $user->ID ), $subject, $message, $headers = '', $attachments = array() );
        if ( $mail_sent ) { echo get_the_author_meta( 'third_email', $user->ID ) . '</br>'; }
        $mail_sent = wp_mail( get_the_author_meta( 'forth_email', $user->ID ), $subject, $message, $headers = '', $attachments = array() );
        if ( $mail_sent ) { echo get_the_author_meta( 'forth_email', $user->ID ) . '</br>'; }
	}
} else {
	echo 'No users found.';
}
?>