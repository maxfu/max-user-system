<?php
global $current_user;
get_currentuserinfo();

// WP_User_Query arguments
$args = array(
	'role'           => 'sydney_branch',
//	'search'         => '@',
//	'search_columns' => array( 'user_login' ),
);

// The User Query
$user_query = new WP_User_Query( $args );

// User Loop
if ( ! empty( $user_query->get_results() ) ) {
	foreach ( $user_query->get_results() as $user ) {
		echo '<p>' . $user->user_login . '</p>';
		echo '<p>' . $user->ID . '</p>';
	}
} else {
	echo 'No users found.';
}
?>