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
        <div class="col-md-6 multi-horizontal">
          <div class="form-group">
            <label class="form-control-label mbr-fonts-style display-7" for="company_name_en"><?php _e( 'Company Name English', 'max-user' ); ?></label><br>
            <span><input type="text" name="company_name_en" id="company_name_en" class="form-control"></span><br>

            <label class="form-control-label mbr-fonts-style display-7" for="company_email"><?php _e( 'Company Email', 'max-user' ); ?></label><br>
            <span><input type="email" name="company_email" id="company_email" class="form-control"></span><br>

            <label class="form-control-label mbr-fonts-style display-7" for="company_address"><?php _e( 'Company Address', 'max-user' ); ?></label><br>
            <span><input type="text" name="company_address" id="company_address" class="form-control"></span><br>

            <label class="form-control-label mbr-fonts-style display-7" for="first_name"><?php _e( 'Person in Charge First Name', 'max-user' ); ?></label><br>
            <span><input type="text" name="first_name" id="first-name" class="form-control"></span><br>

            <label class="form-control-label mbr-fonts-style display-7" for="company_branch"><?php _e( 'Company Branch', 'max-user' ); ?></label><br>
            <span><select name="company_branch" id="company_branch" class="form-control">
              <option value="sel" data-installed="1" selected="selected"><?php _e( 'Please Select', 'max-user' ); ?></option>
              <option value="Sydney" data-installed="1"><?php _e( 'Sydney Branch', 'max-user' ); ?></option>
              <option value="Melbourne" data-installed="1"><?php _e( 'Melbourne Branch', 'max-user' ); ?></option>
              <option value="Perth" data-installed="1"><?php _e( 'Perth Branch', 'max-user' ); ?></option>
              <option value="Brisbane" data-installed="1"><?php _e( 'Brisbane Branch', 'max-user' ); ?></option>
              <option value="Adelaide" data-installed="1"><?php _e( 'Adelaide Branch', 'max-user' ); ?></option>
            </select></span><br>

          </div>
        </div>
        <div class="col-md-6 multi-horizontal">
          <div class="form-group">
            <label class="form-control-label mbr-fonts-style display-7" for="company_name_zh"><?php _e( 'Company Name Chinese', 'max-user' ); ?></label><br>
            <span><input type="text" name="company_name_zh" id="company_name_zh" class="form-control"></span><br>

            <label class="form-control-label mbr-fonts-style display-7" for="company_website"><?php _e( 'Company Website', 'max-user' ); ?></label><br>
            <span><input type="url" name="company_website" id="company_website" class="form-control"></span><br>

            <label class="form-control-label mbr-fonts-style display-7" for="company_phone"><?php _e( 'Company Telephone', 'max-user' ); ?></label><br>
            <span><input type="tel" name="company_phone" id="company_phone" class="form-control"></span><br>

            <label class="form-control-label mbr-fonts-style display-7" for="email"><?php _e( 'Contact Email', 'max-user' ); ?></label><br>
            <span><input type="email" name="email" id="email" class="form-control"></span><br>

            <label class="form-control-label mbr-fonts-style display-7" for="comment"><?php _e( 'Comment', 'max-user' ); ?></label><br>
            <span><textarea name="comment" id="comment" rows="3" class="area form-control"></textarea></span>
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
