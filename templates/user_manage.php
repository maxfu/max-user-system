<?php // WP_User_Query arguments
$args = array(
	'role'           => 'sydney_branch',
);

// The User Query
$user_query = new WP_User_Query( $args );

// The User Loop
if ( ! empty( $user_query->results ) ) { ?>
  <style type="text/css">
    #ccca-member-list th, #ccca-member-list td {
        white-space: nowrap;
    }
  </style>
<h2 class="mbr-section-subtitle mbr-fonts-style align-center pb-5 mbr-light display-5"><?php _e( 'CCCA Member List', 'max-user' ); ?> <?php echo $user_info->user_login; ?></h2>
<table class="form-table table isSearch" id="ccca-member-list">
  <tbody>
        <tr>
            <th><?php _e( 'Company Name English', 'max-user' ); ?></td>
            <th><?php _e( 'Company Name Chinese', 'max-user' ); ?></td>
            <th><?php _e( 'Company Email', 'max-user' ); ?></td>
            <th><?php _e( 'Company Website', 'max-user' ); ?></th>
            <th><?php _e( 'Company Address', 'max-user' ); ?></td>
            <th><?php _e( 'Company Telephone', 'max-user' ); ?></td>
            <th><?php _e( 'Second Email', 'max-user' ); ?></td>
            <th><?php _e( 'Third Email', 'max-user' ); ?></td>
            <th><?php _e( 'Forth Email', 'max-user' ); ?></td>
        </tr>
<?php foreach ( $user_query->results as $user ) { ?>
        <tr>
            <td><?php echo $user->first_name; ?></td>
            <td><?php echo $user->last_name; ?></td>
            <td><?php echo $user->company_email; ?></td>
            <td><?php echo $user->user_url; ?></td>
            <td><?php echo $user->company_address; ?></td>
            <td><?php echo $user->company_phone; ?></td>
            <td><?php echo $user->second_email; ?></td>
            <td><?php echo $user->third_email; ?></td>
            <td><?php echo $user->forth_email; ?></td>
        </tr>
	<?php } ?>
  </tbody>
</table>
<?php } else {
	// no users found
} ?>
