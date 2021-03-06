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
    .form-control {
        background-color: #ffffff;
    }
  </style>
<?php
$user_info = get_userdata($current_user->ID);
?>
  <h2><?php _e('Edit Member Profile', 'max-user'); ?></h2>

  <form id="editform" action="<?php echo esc_url( add_query_arg( 'para', 'save', esc_url(get_permalink()) ) )?>" method="post" class="mbr-form">
    <label class="form-control-label mbr-fonts-style display-3"><?php _e( 'Company Information', 'max-user' ); ?></label><br>
    <div class="row row-sm-offset">
      <div class="col-md-2 multi-horizontal"></div>
      <div class="col-md-8 multi-horizontal">
        <div class="form-group">
          <label class="form-control-label mbr-fonts-style display-7" for="company_name_en"><?php _e( 'Company Name English', 'max-user' ); ?></label><br>
          <span><input type="text" name="company_name_en" id="company_name_en" class="form-control" value="<?php echo $user_info->first_name; ?>"></span><br>

          <label class="form-control-label mbr-fonts-style display-7" for="company_name_zh"><?php _e( 'Company Name Chinese', 'max-user' ); ?></label><br>
          <span><input type="text" name="company_name_zh" id="company_name_zh" class="form-control" value="<?php echo $user_info->last_name; ?>"></span><br>

          <label class="form-control-label mbr-fonts-style display-7" for="company_email"><?php _e( 'Company Email', 'max-user' ); ?></label><br>
          <span><input type="email" name="company_email" id="company_email" class="form-control" value="<?php echo $user_info->company_email; ?>"></span><br>

          <label class="form-control-label mbr-fonts-style display-7" for="company_address"><?php _e( 'Company Address', 'max-user' ); ?></label><br>
          <span><input type="text" name="company_address" id="company_address" class="form-control" value="<?php echo $user_info->company_address; ?>"></span><br>

          <label class="form-control-label mbr-fonts-style display-7" for="company_phone"><?php _e( 'Company Telephone', 'max-user' ); ?></label><br>
          <span><input type="tel" name="company_phone" id="company_phone" class="form-control" value="<?php echo $user_info->company_phone; ?>"></span><br>

          <label class="form-control-label mbr-fonts-style display-7" for="company_website"><?php _e( 'Company Website', 'max-user' ); ?></label><br>
          <span><input type="text" name="company_website" id="company_website" class="form-control" value="<?php echo $user_info->user_url; ?>"></span><br>

          <label class="form-control-label mbr-fonts-style display-7" for="second_email"><?php _e( 'Second Email', 'max-user' ); ?></label><br>
          <span><input type="text" name="second_email" id="second_email" class="form-control" value="<?php echo $user_info->second_email; ?>"></span><br>

          <label class="form-control-label mbr-fonts-style display-7" for="third_email"><?php _e( 'Third Email', 'max-user' ); ?></label><br>
          <span><input type="email" name="third_email" id="third_email" class="form-control" value="<?php echo $user_info->third_email; ?>"></span><br>

          <label class="form-control-label mbr-fonts-style display-7" for="forth_email"><?php _e( 'Forth Email', 'max-user' ); ?></label><br>
          <span><input type="email" name="forth_email" id="forth_email" class="form-control" value="<?php echo $user_info->forth_email; ?>"></span><br>

          <label class="form-control-label mbr-fonts-style display-7" for="company_branch"><?php _e( 'Company Branch', 'max-user' ); ?></label><br>
          <span><select name="company_branch" id="company_branch" class="form-control">
            <option value="test_branch" data-installed="1" <?php if ( implode(', ', $user_info->roles) == 'test_branch' ) echo 'selected="selected"'; ?>><?php _e( 'Please Select', 'max-user' ); ?></option>
            <option value="sydney_branch" data-installed="1" <?php if ( implode(', ', $user_info->roles) == 'sydney_branch' ) echo 'selected="selected"'; ?>><?php _e( 'Sydney Branch', 'max-user' ); ?></option>
            <option value="melbourne_branch" data-installed="1" <?php if ( implode(', ', $user_info->roles) == 'melbourne_branch' ) echo 'selected="selected"'; ?>><?php _e( 'Melbourne Branch', 'max-user' ); ?></option>
            <option value="perth_branch" data-installed="1" <?php if ( implode(', ', $user_info->roles) == 'perth_branch' ) echo 'selected="selected"'; ?>><?php _e( 'Perth Branch', 'max-user' ); ?></option>
            <option value="brisbane_branch" data-installed="1" <?php if ( implode(', ', $user_info->roles) == 'brisbane_branch' ) echo 'selected="selected"'; ?>><?php _e( 'Brisbane Branch', 'max-user' ); ?></option>
            <option value="adelaide_branch" data-installed="1" <?php if ( implode(', ', $user_info->roles) == 'adelaide_branch' ) echo 'selected="selected"'; ?>><?php _e( 'Adelaide Branch', 'max-user' ); ?></option>
          </select></span><br>

          <label class="form-control-label mbr-fonts-style display-7" for="comment"><?php _e( 'Comment', 'max-user' ); ?></label><br>
          <span><textarea name="comment" id="comment" rows="3" class="area form-control" value="<?php echo $user_info->description; ?>"></textarea></span>

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
    'ID'                => $current_user->ID,
    'first_name'        => sanitize_text_field( $_POST['company_name_en'] ),
    'last_name'         => sanitize_text_field( $_POST['company_name_zh'] ),
    'user_url'          => $_POST['company_website'],
    'user_email'        => sanitize_email( $_POST['company_email'] ),
    'role'              => $_POST['company_branch'],
    'description'       => sanitize_textarea_field($_POST['comment']),
    'company_email'     => sanitize_email($_POST['company_email']),
    'company_address'   => $_POST['company_address'],
    'company_phone'     => $_POST['company_phone'],
    'second_email'      => sanitize_text_field($_POST['second_email']),
    'third_email'       => sanitize_email($_POST['third_email']),
    'forth_email'       => sanitize_email($_POST['forth_email']),
  );

    wp_update_user( $user_info );
    echo '<script> location.href=\'' . esc_url(get_permalink()) . '\'; </script>';
    exit;
}
$user_info = get_userdata($current_user->ID);
?>
<h2 class="mbr-section-subtitle mbr-fonts-style align-center pb-5 mbr-light display-5"><?php _e( 'CCCA Member Profile', 'max-user' ); ?> <?php echo $user_info->user_login; ?></h2>

<table class="form-table table isSearch" id="ccca-member-profile">
  <tbody>
  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Company Name English', 'max-user' ); ?>: <?php echo $user_info->first_name; ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Company Name Chinese', 'max-user' ); ?>: <?php echo $user_info->last_name; ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Company Email', 'max-user' ); ?>: <?php echo $user_info->company_email; ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Company Website', 'max-user' ); ?>: <?php echo $user_info->user_url; ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Company Address', 'max-user' ); ?>: <?php echo $user_info->company_address; ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Company Telephone', 'max-user' ); ?>: <?php echo $user_info->company_phone; ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Second Email', 'max-user' ); ?>: <?php echo $user_info->second_email; ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Third Email', 'max-user' ); ?>: <?php echo $user_info->third_email; ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Forth Email', 'max-user' ); ?>: <?php echo $user_info->forth_email; ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Company Branch', 'max-user' ); ?>: <?php _e( implode(', ', $user_info->roles), 'max-user' ); ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Comment', 'max-user' ); ?>: <?php echo $user_info->description; ?></td>
  </tr>
  </tbody>
</table>

<p><a class="register-button btn btn-primary btn-form display-4" href="<?php echo esc_url( add_query_arg( 'para', 'edit', esc_url(get_permalink()) ) )?>">Edit Profile</a></p>

<?php } ?>
