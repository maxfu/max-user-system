<div id="register-form" class="widecolumn">
    <?php if ( $attributes['show_title'] ) : ?>
        <h3><?php _e( 'Register', 'max-user' ); ?></h3>
    <?php endif; ?>

    <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
        <?php foreach ( $attributes['errors'] as $error ) : ?>
            <p>
                <?php echo $error; ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>

    <form id="signupform" action="<?php echo wp_registration_url(); ?>" method="post" class="mbr-form">
      <style type="text/css">
        .form-control {
          background-color: #ffffff;
        }
      </style>
      <div class="row row-sm-offset">
        <div class="col-md-4 multi-horizontal">
          <div class="form-group">
            <label class="form-control-label mbr-fonts-style display-3"><?php _e( 'Company Information', 'max-user' ); ?></label><br>
            <label class="form-control-label mbr-fonts-style display-7" for="email">Company Email(Will be used as Username)</label><br>
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
            <label class="form-control-label mbr-fonts-style display-7" for="contact_2_name"><?php _e( 'Second Contact Name', 'max-user' ); ?></label><br>
            <span><input type="text" name="contact_2_name" id="contact_2_name" class="form-control"></span><br>
            <label class="form-control-label mbr-fonts-style display-7" for="contact_2_mobile"><?php _e( 'Second Contact Mobile', 'max-user' ); ?></label><br>
            <span><input type="tel" name="contact_2_mobile" id="contact_2_mobile" class="form-control"></span><br>
            <label class="form-control-label mbr-fonts-style display-7" for="contact_2_email"><?php _e( 'Second Contact Email', 'max-user' ); ?></label><br>
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
</div>
