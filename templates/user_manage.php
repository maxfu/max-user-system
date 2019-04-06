<?php
// WP_User_Query arguments
$args = array(
	'role'           => 'test_branch',
	'fields'         => array( 'ID', 'user_login', 'user_url', 'company_email', 'company_phone', 'company_address', 'second_email', 'third_email', 'forth_email' ),
//	'role__in'           => array(
//        'test_branch',
//        'sydney_branch',
//        'melbourne_branch',
//        'perth_branch',
//        'brisbane_branch',
//        'adelaide_branch',
//    ),
//	'search'         => '@',
//	'search_columns' => array( 'user_login' ),
);

// The User Query
$user_query = new WP_User_Query( $args );

// User Loop
if ( ! empty( $user_query->results ) ) {
	foreach ( $user_query->results as $user ) {
//        $message  = 'Dear member,' . "\r\n\r\n";
//        $message .= '' . "\r\n\r\n";
//        $message .= 'Welcome to the new official website of China Chamber of Commerce in Australia!' . "\r\n\r\n";
//        $message .= '' . "\r\n\r\n";
//        $message .= 'Your member account has been created with email address ' . $user->user_login . ' as the login user name.' . "\r\n\r\n";
//        $message .= 'Please click below link, type in your user name and follow process to select your password.' . "\r\n\r\n";
//        $message .= 'https://www.cccaau.org/en/member-password-reset/' . "\r\n\r\n";
//        $message .= '' . "\r\n\r\n";
//        $message .= 'Thanks for your support.' . "\r\n\r\n";
//        $message .= '' . "\r\n\r\n";
//        $message .= 'CCCA Team' . "\r\n";
//        $subject = 'Your CCCA member account is ready.';
//        $headers[] = 'From: Notification <notification@dm.cccaau.org>';

        echo $user->ID;
        echo $user->user_login;
        echo $user->user_url;
        echo $user->company_email;

//       $user_info = get_userdata( $user->ID );
//        echo implode(',', $user_info );
//        $user_info['company_email'] = get_the_author_meta( 'company_email', $user->ID );
//        echo $user_info['company_email'];
//        $user_info['company_address'] = get_the_author_meta( 'company_address', $user->ID );
//        echo $user_info['company_address'];
//        $user_info['company_phone'] = get_the_author_meta( 'company_phone', $user->ID );
//        echo $user_info['company_phone'];
//        $user_info['second_email'] = get_the_author_meta( 'second_email', $user->ID );
//        echo $user_info['second_email'];
//        $user_info['third_email'] = get_the_author_meta( 'third_email', $user->ID );
//        echo $user_info['third_email'];
//        $user_info['forth_email'] = get_the_author_meta( 'forth_email', $user->ID );
//        echo $user_info['forth_email'];

        // wp_mail( $first_email, $subject, $message, $headers );
        // echo $first_email;
        // wp_mail( $second_email, $subject, $message, $headers );
        // echo $second_email;
        // wp_mail( $third_email, $subject, $message, $headers );
        // echo $third_email;
        // wp_mail( $forth_email, $subject, $message, $headers );
        // echo $forth_email;
	}
} else {
	echo 'No users found.';
}
?>