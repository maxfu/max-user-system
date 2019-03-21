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

  <h2><?php _e('Edit Member Profile', 'max-user'); ?></h2>

  <form id="editform" action="<?php echo esc_url( add_query_arg( 'para', 'save', esc_url(get_permalink()) ) )?>" method="post" class="mbr-form">
    <style type="text/css">
      .form-control {
        background-color: #ffffff;
      }
    </style>
    <div class="row row-sm-offset">
      <div class="col-md-4 multi-horizontal">
        <div class="form-group">
          <label class="form-control-label mbr-fonts-style display-3"><?php _e( 'Company Information', 'max-user' ); ?></label><br>
          <label class="form-control-label mbr-fonts-style display-7" for="email"><?php _e( 'Company Email(Will be used as Username)', 'max-user' ); ?></label><br>
          <span><input type="text" name="email" id="email" class="form-control"></span><br>
          <label class="form-control-label mbr-fonts-style display-7" for="company_name"><?php _e( 'Company Name', 'max-user' ); ?></label><br>
          <span><input type="text" name="company_name" id="company_name" class="form-control"></span><br>
          <label class="form-control-label mbr-fonts-style display-7" for="company_address"><?php _e( 'Company Address', 'max-user' ); ?></label><br>
          <span><input type="text" name="company_address" id="company_address" class="form-control"></span><br>
          <label class="form-control-label mbr-fonts-style display-7" for="company_phone"><?php _e( 'Company Telephone', 'max-user' ); ?></label><br>
          <span><input type="tel" name="company_phone" id="company_phone" class="form-control"></span><br>
          <label class="form-control-label mbr-fonts-style display-7" for="company_website"><?php _e( 'Company Website', 'max-user' ); ?></label><br>
          <span><input type="url" name="company_website" id="company_website" class="form-control"></span><br>
          <label class="form-control-label mbr-fonts-style display-7" for="company_industry"><?php _e( 'Company Industry', 'max-user' ); ?></label><br>
          <span><input type="text" name="company_industry" id="company_industry" class="form-control"></span><br>
          <label class="form-control-label mbr-fonts-style display-7" for="company_type"><?php _e( 'Company Type', 'max-user' ); ?></label><br>
          <span><select name="company_type" id="company_type" class="form-control">
            <option value="sel" data-installed="1" selected="selected"><?php _e( 'Please Select', 'max-user' ); ?></option>
            <option value="cce" data-installed="1"><?php _e( 'Chinese Central Enterprise', 'max-user' ); ?></option>
            <option value="csoe" data-installed="1"><?php _e( 'Chinese State-Owned Enterprise', 'max-user' ); ?></option>
            <option value="cpe" data-installed="1"><?php _e( 'Chinese Private Enterprise', 'max-user' ); ?></option>
            <option value="pts" data-installed="1"><?php _e( 'Partnership', 'max-user' ); ?></option>
            <option value="alcc" data-installed="1"><?php _e( 'Australian Local Chinese Company', 'max-user' ); ?></option>
            <option value="alc" data-installed="1"><?php _e( 'Australian Local Company', 'max-user' ); ?></option>
            <option value="oth" data-installed="1"><?php _e( 'Other(Fill Details in Comment)', 'max-user' ); ?></option>
          </select></span><br>
          <label class="form-control-label mbr-fonts-style display-7" for="company_branch"><?php _e( 'Company Branch', 'max-user' ); ?></label><br>
          <span><select name="company_branch" id="company_branch" class="form-control">
            <option value="sel" data-installed="1" selected="selected"><?php _e( 'Please Select', 'max-user' ); ?></option>
            <option value="Sydney" data-installed="1"><?php _e( 'Sydney Branch', 'max-user' ); ?></option>
            <option value="Melbourne" data-installed="1"><?php _e( 'Melbourne Branch', 'max-user' ); ?></option>
            <option value="Perth" data-installed="1"><?php _e( 'Perth Branch', 'max-user' ); ?></option>
            <option value="Brisbane" data-installed="1"><?php _e( 'Brisbane Branch', 'max-user' ); ?></option>
            <option value="Adelaide" data-installed="1"><?php _e( 'Adelaide Branch', 'max-user' ); ?></option>
          </select></span>
        </div>
      </div>
      <div class="col-md-4 multi-horizontal">
        <div class="form-group">
          <label class="form-control-label mbr-fonts-style display-3"><?php _e( 'Person in Charge', 'max-user' ); ?></label><br>
          <label class="form-control-label mbr-fonts-style display-7" for="first_name"><?php _e( 'Person in Charge First Name', 'max-user' ); ?></label><br>
          <span><input type="text" name="first_name" id="first-name" class="form-control"></span><br>
          <label class="form-control-label mbr-fonts-style display-7" for="last_name"><?php _e( 'Person in Charge Last Name', 'max-user' ); ?></label><br>
          <span><input type="text" name="last_name" id="last-name" class="form-control"></span><br>
          <label class="form-control-label mbr-fonts-style display-7" for="pic_title"><?php _e( 'Person in Charge Title', 'max-user' ); ?></label><br>
          <span><input type="text" name="pic_title" id="pic_title" class="form-control"></span><br>
          <label class="form-control-label mbr-fonts-style display-7" for="pic_mobile"><?php _e( 'Person in Charge Mobile', 'max-user' ); ?></label><br>
          <span><input type="tel" name="pic_mobile" id="pic_mobile" class="form-control"></span><br>
          <label class="form-control-label mbr-fonts-style display-7" for="pic_phone"><?php _e( 'Person in Charge Telephone', 'max-user' ); ?></label><br>
          <span><input type="tel" name="pic_phone" id="pic_phone" class="form-control"></span><br>
          <label class="form-control-label mbr-fonts-style display-7" for="pic_email"><?php _e( 'Person in Charge Email', 'max-user' ); ?></label><br>
          <span><input type="email" name="pic_email" id="pic_email" class="form-control"></span><br>
          <label class="form-control-label mbr-fonts-style display-7" for="comment"><?php _e( 'Comment', 'max-user' ); ?></label><br>
          <span><textarea name="comment" id="comment" rows="3" class="area form-control"></textarea></span>
        </div>
      </div>
      <div class="col-md-4 multi-horizontal">
        <div class="form-group">
          <label class="form-control-label mbr-fonts-style display-3"><?php _e( 'Other Contacts', 'max-user' ); ?></label><br>
          <label class="form-control-label mbr-fonts-style display-7" for="contact_1_name"><?php _e( 'First Contact Name', 'max-user' ); ?></label><br>
          <span><input type="text" name="contact_1_name" id="contact_1_name" class="form-control"></span><br>
          <label class="form-control-label mbr-fonts-style display-7" for="contact_1_mobile"><?php _e( 'First Contact Mobile', 'max-user' ); ?></label><br>
          <span><input type="tel" name="contact_1_mobile" id="contact_1_mobile" class="form-control"></span><br>
          <label class="form-control-label mbr-fonts-style display-7" for="contact_1_email"><?php _e( 'First Contact Email', 'max-user' ); ?></label><br>
          <span><input type="email" name="contact_1_email" id="contact_1_email" class="form-control"></span><br>
          <label class="form-control-label mbr-fonts-style display-7" for="contact_2_name"><?php _e( 'First Contact Name', 'max-user' ); ?></label><br>
          <span><input type="text" name="contact_2_name" id="contact_2_name" class="form-control"></span><br>
          <label class="form-control-label mbr-fonts-style display-7" for="contact_2_mobile"><?php _e( 'First Contact Mobile', 'max-user' ); ?></label><br>
          <span><input type="tel" name="contact_2_mobile" id="contact_2_mobile" class="form-control"></span><br>
          <label class="form-control-label mbr-fonts-style display-7" for="contact_2_email"><?php _e( 'First Contact Email', 'max-user' ); ?></label><br>
          <span><input type="email" name="contact_2_email" id="contact_2_email" class="form-control"></span>
        </div>
      </div>
    </div>

      <p class="form-row">
          <?php _e( 'Note: Your password will be generated automatically and sent to your email address.', 'max-user' ); ?>
      </p>

      <p class="signup-submit input-group-btn">
          <input type="submit" name="submit" class="register-button btn btn-primary btn-form display-4" value="<?php _e( 'Submit', 'max-user' ); ?>"/>
      </p>
  </form>

<?php } else { ?>
<?php if ( $my_para == 'save' ) {
  $ccca_meta_profile = array(
    'company_name'      => $_POST['company_name'],
    'company_address'   => $_POST['company_address'],
    'company_phone'     => $_POST['company_phone'],
    'company_website'   => $_POST['company_website'],
    'company_type'      => $_POST['company_type'],
    'company_industry'  => $_POST['company_industry'],
    'company_branch'    => $_POST['company_branch'],
    'pic_fname'         => $_POST['pic_fname'],
    'pic_lname'         => $_POST['pic_lname'],
    'pic_title'         => $_POST['pic_title'],
    'pic_mobile'        => $_POST['pic_mobile'],
    'pic_phone'         => $_POST['pic_phone'],
    'pic_email'         => $_POST['pic_email'],
    'contact_1_name'    => $_POST['contact_1_name'],
    'contact_1_mobile'  => $_POST['contact_1_mobile'],
    'contact_1_email'   => $_POST['contact_1_email'],
    'contact_2_name'    => $_POST['contact_2_name'],
    'contact_2_mobile'  => $_POST['contact_2_mobile'],
    'contact_2_email'   => $_POST['contact_2_email'],
    'comment'           => $_POST['comment'],
  );
  if ( !current_user_can( 'edit_user', $current_user->ID ) )
  return false;

  /* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
  update_usermeta( $current_user->ID, 'ccca_profile', $ccca_meta_profile ); ?>
<?php } ?>
<h2 class="mbr-section-subtitle mbr-fonts-style align-center pb-5 mbr-light display-5"><?php _e( 'CCCA Member Profile', 'max-user' ); ?></h2>

<table class="form-table table isSearch" id="ccca-member-profile">
  <thead>
    <tr class="table-heads ">
      <th class="head-item mbr-fonts-style display-7"><h3><?php _e( 'Company Information', 'max-user' ); ?></h3></th>
      <th class="head-item mbr-fonts-style display-7"><h3><?php _e( 'Person in Charge', 'max-user' ); ?></h3></th>
      <th class="head-item mbr-fonts-style display-7"><h3><?php _e( 'Other Contacts', 'max-user' ); ?></h3></th>
    </tr>
  </thead>

<?php $ccca_profile = get_the_author_meta( 'ccca_profile', $current_user->ID );

  if ( ! is_array( $ccca_profile ) ) {
      $ccca_profile = array(
          'company_name'      => '',
          'company_address'   => '',
          'company_phone'     => '',
          'company_website'   => '',
          'company_type'      => 'cce',
          'company_industry'  => '',
          'company_branch'    => 'Sydney',
          'pic_fname'         => '',
          'pic_lname'         => '',
          'pic_title'         => '',
          'pic_mobile'        => '',
          'pic_phone'         => '',
          'pic_email'         => '',
          'contact_1_name'    => '',
          'contact_1_mobile'  => '',
          'contact_1_email'   => '',
          'contact_2_name'    => '',
          'contact_2_mobile'  => '',
          'contact_2_email'   => '',
          'comment'           => '',
      );
  } ?>

  <tbody>
  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Company Name', 'max-user' ); ?>: <?php echo esc_attr( $ccca_profile['company_name'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Person in Charge First Name', 'max-user' ); ?>: <?php echo esc_attr( $ccca_profile['pic_fname'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'First Contact Name', 'max-user' ); ?>: <?php echo esc_attr( $ccca_profile['contact_1_name'] ); ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Company Address', 'max-user' ); ?>: <?php echo esc_attr( $ccca_profile['company_address'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Person in Charge Last Name', 'max-user' ); ?>: <?php echo esc_attr( $ccca_profile['pic_lname'] ); ?></label></th>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'First Contact Mobile', 'max-user' ); ?>: <?php echo esc_attr( $ccca_profile['contact_1_mobile'] ); ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Company Telephone', 'max-user' ); ?>: <?php echo esc_attr( $ccca_profile['company_phone'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Person in Charge Title', 'max-user' ); ?>: <?php echo esc_attr( $ccca_profile['pic_title'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'First Contact Email', 'max-user' ); ?>: <?php echo esc_attr( $ccca_profile['contact_1_email'] ); ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Company Website', 'max-user' ); ?>: <?php echo esc_attr( $ccca_profile['company_website'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Person in Charge Mobile', 'max-user' ); ?>: <?php echo esc_attr( $ccca_profile['pic_mobile'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'First Contact Name', 'max-user' ); ?>: <?php echo esc_attr( $ccca_profile['contact_2_name'] ); ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Company Type', 'max-user' ); ?>: <?php echo esc_attr( $ccca_profile['company_type'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Person in Charge Telephone', 'max-user' ); ?>: <?php echo esc_attr( $ccca_profile['pic_phone'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'First Contact Mobile', 'max-user' ); ?>: <?php echo esc_attr( $ccca_profile['contact_2_mobile'] ); ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Company Industry', 'max-user' ); ?>: <?php echo esc_attr( $ccca_profile['company_industry'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Person in Charge Email', 'max-user' ); ?>: <?php echo esc_attr( $ccca_profile['pic_email'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'First Contact Email', 'max-user' ); ?>: <?php echo esc_attr( $ccca_profile['contact_2_email'] ); ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Company Branch', 'max-user' ); ?>: <?php echo esc_attr( $ccca_profile['company_branch'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php _e( 'Comment', 'max-user' ); ?>: <?php echo esc_attr( $ccca_profile['comment'] ); ?></td>
  </tr>
  <tbody>
</table>

<p><a class="register-button btn btn-primary btn-form display-4" href="<?php echo esc_url( add_query_arg( 'para', 'edit', esc_url(get_permalink()) ) )?>">Edit Profile</a></p>

<h3 class="mbr-section-title mbr-fonts-style align-center pb-3 display-2"><?php _e( 'Author Archives for ', 'max-user' ); echo $current_user->user_login; ?></h3>

<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; ?>
<?php $custom_loop = new WP_Query(array( 'post_type' => 'post', 'author' => $current_user->ID, 'posts_per_page' => $posts_per_page, 'paged' => $paged )); ?>
<ul>
<?php while ( $custom_loop->have_posts() ) : $custom_loop->the_post(); ?>
  <li class="mbr-title pt-2 mbr-fonts-style display-7"><a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a></li>
<?php endwhile; ?>
</ul>
<?php if (function_exists("ccca_pagination")) { ccca_pagination($custom_loop->max_num_pages); } ?>
<?php wp_reset_postdata(); ?>
<?php } ?>
