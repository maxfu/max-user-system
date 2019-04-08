<?php // WP_User_Query arguments
$args = array(
	'role'           => 'sydney_branch',
);

// The User Query
$user_query = new WP_User_Query( $args );

// The User Loop
if ( ! empty( $user_query->results ) ) { ?>
<h2 class="mbr-section-subtitle mbr-fonts-style align-center pb-5 mbr-light display-5"><?php _e( 'CCCA Member Profile', 'max-user' ); ?> <?php echo $user_info->user_login; ?></h2>
<table class="form-table table isSearch" id="ccca-member-profile">
  <tbody>
        <tr>
            <th class="body-item mbr-fonts-style display-7"><?php _e( 'Company Name English', 'max-user' ); ?></td>
            <th class="body-item mbr-fonts-style display-7"><?php _e( 'Company Name Chinese', 'max-user' ); ?></td>
            <th class="body-item mbr-fonts-style display-7"><?php _e( 'Company Email', 'max-user' ); ?></td>
            <th class="body-item mbr-fonts-style display-7"><?php _e( 'Company Website', 'max-user' ); ?></th>
            <th class="body-item mbr-fonts-style display-7"><?php _e( 'Company Address', 'max-user' ); ?></td>
            <th class="body-item mbr-fonts-style display-7"><?php _e( 'Company Telephone', 'max-user' ); ?></td>
            <th class="body-item mbr-fonts-style display-7"><?php _e( 'Second Email', 'max-user' ); ?></td>
            <th class="body-item mbr-fonts-style display-7"><?php _e( 'Third Email', 'max-user' ); ?></td>
            <th class="body-item mbr-fonts-style display-7"><?php _e( 'Forth Email', 'max-user' ); ?></td>
        </tr>
<?php foreach ( $user_query->results as $user ) { ?>
        <tr>
            <td class="body-item mbr-fonts-style display-7"><?php echo $user->first_name; ?></td>
            <td class="body-item mbr-fonts-style display-7"><?php echo $user->last_name; ?></td>
            <td class="body-item mbr-fonts-style display-7"><?php echo $user->company_email; ?></td>
            <td class="body-item mbr-fonts-style display-7"><?php echo $user->user_url; ?></td>
            <td class="body-item mbr-fonts-style display-7"><?php echo $user->company_address; ?></td>
            <td class="body-item mbr-fonts-style display-7"><?php echo $user->company_phone; ?></td>
            <td class="body-item mbr-fonts-style display-7"><?php echo $user->second_email; ?></td>
            <td class="body-item mbr-fonts-style display-7"><?php echo $user->third_email; ?></td>
            <td class="body-item mbr-fonts-style display-7"><?php echo $user->forth_email; ?></td>
        </tr>
	<?php } ?>
  <tbody>
</table>
<?php } else {
	// no users found
} ?>
