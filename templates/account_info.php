<?php global $current_user; ?>
<?php get_currentuserinfo(); ?>
<?php $my_para = get_query_var( 'para' ); ?>

<?php if ( $my_para == 'edit' ) { ?>
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
<?php
$user_info = get_userdata($current_user->ID);
$company_branch = get_the_author_meta( 'company_branch', $current_user->ID );
?>
  <h2><?php _e('Edit Member Profile', 'max-user'); ?></h2>

  <form id="editform" action="<?php echo esc_url( add_query_arg( 'para', 'save', esc_url(get_permalink()) ) )?>" method="post" class="mbr-form">
    <style type="text/css">
      .form-control {
        background-color: #ffffff;
      }
    </style>
    <label class="form-control-label mbr-fonts-style display-3"><?php _e( 'Company Information', 'max-user' ); ?></label><br>
    <div class="row row-sm-offset">
      <div class="col-md-6 multi-horizontal">
        <div class="form-group">
          <label class="form-control-label mbr-fonts-style display-7" for="company_name_en"><?php _e( 'Company Name English', 'max-user' ); ?></label><br>
          <span><input type="text" name="company_name_en" id="company_name_en" class="form-control" value="<?php echo $user_info->first_name; ?>"></span><br>

          <label class="form-control-label mbr-fonts-style display-7" for="company_email"><?php _e( 'Company Email', 'max-user' ); ?></label><br>
          <span><input type="email" name="company_email" id="company_email" class="form-control" value="<?php echo get_the_author_meta( 'company_email', $current_user->ID ); ?>"></span><br>

          <label class="form-control-label mbr-fonts-style display-7" for="company_address"><?php _e( 'Company Address', 'max-user' ); ?></label><br>
          <span><input type="text" name="company_address" id="company_address" class="form-control" value="<?php echo get_the_author_meta( 'company_address', $current_user->ID ); ?>"></span><br>

          <label class="form-control-label mbr-fonts-style display-7" for="contact_name"><?php _e( 'Contact Name', 'max-user' ); ?></label><br>
          <span><input type="text" name="contact_name" id="contact_name" class="form-control" value="<?php echo get_the_author_meta( 'contact_name', $current_user->ID ); ?>"></span><br>

          <label class="form-control-label mbr-fonts-style display-7" for="company_branch"><?php _e( 'Company Branch', 'max-user' ); ?></label><br>
          <span><select name="company_branch" id="company_branch" class="form-control">
            <option value="test_branch" data-installed="1" <?php if ( implode(', ', $user_info->roles) == 'test_branch' ) echo 'selected="selected"'; ?>><?php _e( 'Please Select', 'max-user' ); ?></option>
            <option value="sydney_branch" data-installed="1" <?php if ( implode(', ', $user_info->roles) == 'sydney_branch' ) echo 'selected="selected"'; ?>><?php _e( 'Sydney Branch', 'max-user' ); ?></option>
            <option value="melbourne_branch" data-installed="1" <?php if ( implode(', ', $user_info->roles) == 'melbourne_branch' ) echo 'selected="selected"'; ?>><?php _e( 'Melbourne Branch', 'max-user' ); ?></option>
            <option value="perth_branch" data-installed="1" <?php if ( implode(', ', $user_info->roles) == 'perth_branch' ) echo 'selected="selected"'; ?>><?php _e( 'Perth Branch', 'max-user' ); ?></option>
            <option value="brisbane_branch" data-installed="1" <?php if ( implode(', ', $user_info->roles) == 'brisbane_branch' ) echo 'selected="selected"'; ?>><?php _e( 'Brisbane Branch', 'max-user' ); ?></option>
            <option value="adelaide_branch" data-installed="1" <?php if ( implode(', ', $user_info->roles) == 'adelaide_branch' ) echo 'selected="selected"'; ?>><?php _e( 'Adelaide Branch', 'max-user' ); ?></option>
          </select></span><br>

        </div>
      </div>
      <div class="col-md-6 multi-horizontal">
        <div class="form-group">
          <label class="form-control-label mbr-fonts-style display-7" for="company_name_zh"><?php _e( 'Company Name Chinese', 'max-user' ); ?></label><br>
          <span><input type="text" name="company_name_zh" id="company_name_zh" class="form-control" value="<?php echo $user_info->last_name; ?>"></span><br>

          <label class="form-control-label mbr-fonts-style display-7" for="company_website"><?php _e( 'Company Website', 'max-user' ); ?></label><br>
          <span><input type="text" name="company_website" id="company_website" class="form-control" value="<?php echo $user_info->user_url; ?>"></span><br>

          <label class="form-control-label mbr-fonts-style display-7" for="company_phone"><?php _e( 'Company Telephone', 'max-user' ); ?></label><br>
          <span><input type="tel" name="company_phone" id="company_phone" class="form-control" value="<?php echo get_the_author_meta( 'company_phone', $current_user->ID ); ?>"></span><br>

          <label class="form-control-label mbr-fonts-style display-7" for="contact_email"><?php _e( 'Contact Email', 'max-user' ); ?></label><br>
          <span><input type="email" name="contact_email" id="contact_email" class="form-control" value="<?php echo get_the_author_meta( 'contact_email', $current_user->ID ); ?>"></span><br>

          <label class="form-control-label mbr-fonts-style display-7" for="comment"><?php _e( 'Comment', 'max-user' ); ?></label><br>
          <span><textarea name="comment" id="comment" rows="3" class="area form-control" value="<?php echo get_the_author_meta( 'comment', $current_user->ID ); ?>"></textarea></span>
        </div>
      </div>
    </div>
    <p class="signup-submit input-group-btn">
        <input type="submit" name="submit" class="register-button btn btn-primary btn-form display-4" value="<?php _e( 'Submit', 'max-user' ); ?>"/>
    </p>
  </form>

<?php } else {
if ( $my_para == 'save' ) {
  if ( !current_user_can( 'edit_user', $current_user->ID ) )
  return false;

  $user_info = array(
    'ID'         => $current_user->ID,
    'first_name' => sanitize_text_field( $_POST['company_name_en'] ),
    'last_name'  => sanitize_text_field( $_POST['company_name_zh'] ),
    'user_url'   => $_POST['company_website'],
    'user_email' => sanitize_email( $_POST['company_email'] ),
    'role'       => $_POST['company_branch'],
  );

  /* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
  wp_update_user( $user_info );
//  update_usermeta( $current_user->ID, 'company_email', sanitize_email($_POST['company_email']) );
  update_usermeta( $current_user->ID, 'company_address', $_POST['company_address'] );
  update_usermeta( $current_user->ID, 'company_phone', $_POST['company_phone'] );
//  update_usermeta( $current_user->ID, 'company_branch', $_POST['company_branch'] );
  update_usermeta( $current_user->ID, 'contact_name', sanitize_text_field($_POST['contact_name']) );
  update_usermeta( $current_user->ID, 'contact_email', sanitize_email($_POST['contact_email']) );
  update_usermeta( $current_user->ID, 'comment', sanitize_textarea_field($_POST['comment']) );
}
$user_info = get_userdata($current_user->ID);
?>
<h2 class="mbr-section-subtitle mbr-fonts-style align-center pb-5 mbr-light display-5"><?php _e( 'CCCA Member Profile', 'max-user' ); ?></h2>

<table class="form-table table isSearch" id="ccca-member-profile">
  <tbody>
  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Company Name English', 'max-user' ); ?>: <?php echo esc_attr( $user_info->first_name ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Company Name Chinese', 'max-user' ); ?>: <?php echo esc_attr( $user_info->last_name ); ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Company Email', 'max-user' ); ?>: <?php echo esc_attr( $user_info->user_email ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Company Website', 'max-user' ); ?>: <?php echo esc_attr( $user_info->user_url ); ?></label></th>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Company Address', 'max-user' ); ?>: <?php echo esc_attr( get_the_author_meta( 'company_address', $current_user->ID ) ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Company Telephone', 'max-user' ); ?>: <?php echo esc_attr( get_the_author_meta( 'company_phone', $current_user->ID ) ); ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Contact Name', 'max-user' ); ?>: <?php echo esc_attr( get_the_author_meta( 'contact_name', $current_user->ID ) ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Contact Email', 'max-user' ); ?>: <?php echo esc_attr( get_the_author_meta( 'contact_email', $current_user->ID ) ); ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Company Branch', 'max-user' ); ?>: <?php echo implode(', ', $user_info->roles); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Comment', 'max-user' ); ?>: <?php echo esc_attr( get_the_author_meta( 'comment', $current_user->ID ) ); ?></td>
  </tr>
  <tbody>
</table>

<p><a class="register-button btn btn-primary btn-form display-4" href="<?php echo esc_url( add_query_arg( 'para', 'edit', esc_url(get_permalink()) ) )?>">Edit Profile</a></p>

<?php } ?>
