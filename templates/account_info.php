<?php global $current_user;
      get_currentuserinfo();

      $dict = array(
          'en-US' => array(
              'ccca_member_profile' => 'CCCA Member Profile',
              'company_information' => 'Company Information',
              'company_name' => 'Company Name',
              'company_address' => 'Company Address',
              'company_telephone' => 'Company Telephone',
              'company_website' => 'Company Website',
              'company_type' => 'Company Type',
              'company_industry' => 'Company Industry',
              'company_branch' => 'Company Branch',
              'person_in_charge' => 'Person in Charge',
              'first_name' => 'Person in Charge First Name',
              'last_name' => 'Person in Charge Last Name',
              'title' => 'Person in Charge Title',
              'mobile' => 'Person in Charge Mobile',
              'telephone' => 'Person in Charge Telephone',
              'email' => 'Person in Charge Email',
              'other_contacts' => 'Other Contacts',
              'first_contact_name' => 'First Contact Name',
              'first_contact_mobile' => 'First Contact Mobile',
              'first_contact_email' => 'First Contact Email',
              'second_contact_name' => 'Second Contact Name',
              'second_contact_mobile' => 'Second Contact Mobile',
              'second_contact_email' => 'Second Contact Email',
              'cce' => 'Chinese Central Enterprise',
              'csoe' => 'Chinese State-Owned Enterprise',
              'cpe' => 'Chinese Private Enterprise',
              'pts' => 'Partnership',
              'alcc' => 'Australian Local Chinese Company',
              'alc' => 'Australian Local Company',
              'oth' => 'Other(Fill Details in Comment)',
              'sydney' => 'Sydney Branch',
              'melbourne' => 'Melbourne Branch',
              'perth' => 'Perth Branch',
              'brisbane' => 'Brisbane Branch',
              'adelaide' => 'Adelaide Branch',
              'comment' => 'Comment'
          ),

          'zh-CN' => array(
            'ccca_member_profile' => '商会会员资料',
            'company_information' => '公司信息',
            'company_name' => '公司名称',
            'company_address' => '公司地址',
            'company_telephone' => '公司电话',
            'company_website' => '公司网站',
            'company_type' => '公司属性',
            'company_industry' => '公司行业',
            'company_branch' => '所属分会',
            'person_in_charge' => '负责人信息',
            'first_name' => '负责人姓氏',
            'last_name' => '负责人名字',
            'title' => '负责人职位',
            'mobile' => '负责人手机',
            'telephone' => '负责人电话',
            'email' => '负责人邮件',
            'other_contacts' => '其他联系人',
            'first_contact_name' => '第一联系人姓名',
            'first_contact_mobile' => '第一联系人手机',
            'first_contact_email' => '第一联系人邮箱',
            'second_contact_name' => '第二联系人姓名',
            'second_contact_mobile' => '第二联系人手机',
            'second_contact_email' => '第二联系人邮箱',
            'cce' => '中资央企',
            'csoe' => '中资国企',
            'cpe' => '中资民营',
            'pts' => '合伙制',
            'alcc' => '澳州当地华人企业',
            'alc' => '澳洲当地的外资企业',
            'oth' => '其他(请在备注中填写)',
            'sydney' => '悉尼分会',
            'melbourne' => '墨尔本分会',
            'perth' => '珀斯分会',
            'brisbane' => '布里斯班分会',
            'adelaide' => '阿德莱德分会',
            'comment' => '备注'
        )
    );

    $lang = get_bloginfo("language");

    $strings = $dict[$lang];

    $ccca_profile = get_the_author_meta( 'ccca_profile', $current_user->ID );

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
    }
?>

<h2 class="mbr-section-subtitle mbr-fonts-style align-center pb-5 mbr-light display-5"><?php echo $strings['ccca_member_profile']; ?></h2>

<table class="form-table table isSearch" id="ccca-member-profile">
  <thead>
    <tr class="table-heads ">
      <th class="head-item mbr-fonts-style display-7"><h3><?php echo $strings['company_information']; ?></h3></th>
      <th class="head-item mbr-fonts-style display-7"><h3><?php echo $strings['person_in_charge']; ?></h3></th>
      <th class="head-item mbr-fonts-style display-7"><h3><?php echo $strings['other_contacts']; ?></h3></th>
    </tr>
  </thead>
  <tbody>
  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php echo $strings['company_name']; ?>: <?php echo esc_attr( $ccca_profile['company_name'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php echo $strings['first_name']; ?>: <?php echo esc_attr( $ccca_profile['pic_fname'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php echo $strings['first_contact_name']; ?>: <?php echo esc_attr( $ccca_profile['contact_1_name'] ); ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php echo $strings['company_address']; ?>: <?php echo esc_attr( $ccca_profile['company_address'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php echo $strings['last_name']; ?>: <?php echo esc_attr( $ccca_profile['pic_lname'] ); ?></label></th>
    <td class="body-item mbr-fonts-style display-7"><?php echo $strings['first_contact_mobile']; ?>: <?php echo esc_attr( $ccca_profile['contact_1_mobile'] ); ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php echo $strings['company_telephone']; ?>: <?php echo esc_attr( $ccca_profile['company_phone'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php echo $strings['title']; ?>: <?php echo esc_attr( $ccca_profile['pic_title'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php echo $strings['first_contact_email']; ?>: <?php echo esc_attr( $ccca_profile['contact_1_email'] ); ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php echo $strings['company_website']; ?>: <?php echo esc_attr( $ccca_profile['company_website'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php echo $strings['mobile']; ?>: <?php echo esc_attr( $ccca_profile['pic_mobile'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php echo $strings['second_contact_name']; ?>: <?php echo esc_attr( $ccca_profile['contact_2_name'] ); ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php echo $strings['company_type']; ?>: <?php echo esc_attr( $ccca_profile['company_type'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php echo $strings['telephone']; ?>: <?php echo esc_attr( $ccca_profile['pic_phone'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php echo $strings['second_contact_mobile']; ?>: <?php echo esc_attr( $ccca_profile['contact_2_mobile'] ); ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php echo $strings['company_industry']; ?>: <?php echo esc_attr( $ccca_profile['company_industry'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php echo $strings['email']; ?>: <?php echo esc_attr( $ccca_profile['pic_email'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php echo $strings['second_contact_email']; ?>: <?php echo esc_attr( $ccca_profile['contact_2_email'] ); ?></td>
  </tr>

  <tr>
    <td class="body-item mbr-fonts-style display-7"><?php echo $strings['company_branch']; ?>: <?php echo esc_attr( $ccca_profile['company_branch'] ); ?></td>
    <td class="body-item mbr-fonts-style display-7"><?php echo $strings['comment']; ?>: <?php echo esc_attr( $ccca_profile['comment'] ); ?></td>
  </tr>
  <tbody>
</table>

<p><a class="register-button btn btn-primary btn-form display-4" href="https://www.cccaau.org/wp-admin/profile.php">Edit Profile</a></p>

<h3 class="mbr-section-title mbr-fonts-style align-center pb-3 display-2"><?php _e( 'Author Archives for ', 'maxfu-user-system' ); echo $current_user->user_login; ?></h3>

<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; ?>
<?php $custom_loop = new WP_Query(array( 'post_type' => 'post', 'author' => $current_user->ID, 'posts_per_page' => $posts_per_page, 'paged' => $paged )); ?>
<ul>
<?php while ( $custom_loop->have_posts() ) : $custom_loop->the_post(); ?>
  <li class="mbr-title pt-2 mbr-fonts-style display-7"><a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a></li>
<?php endwhile; ?>
</ul>
<?php if (function_exists("ccca_pagination")) { ccca_pagination($custom_loop->max_num_pages); } ?>
<?php wp_reset_postdata();
