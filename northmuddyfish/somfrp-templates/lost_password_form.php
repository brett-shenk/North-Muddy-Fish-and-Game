<?php
/**
 * Frontend Reset Password - Main lost password form
 * 
 * @version 1.1.91
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div id="password-lost-form-wrap">

	<?php if ( ! empty( $errors ) ) : ?>

		<?php if ( is_array( $errors ) ) : ?>

			<div id="toast-container" data-type="error">
                <i class="icon-notification"></i>
				<?php foreach ( $errors as $error ) : ?>
					<p>
						<?php echo esc_html( $error ); ?>
					</p>
				<?php endforeach; ?>
			</div>

		<?php endif; ?>

	<?php endif; ?>

	<?php if ( $email_confirmed ) : ?>
		<?php $confirmed_text = esc_html__( 'An email has been sent. Please check your inbox.' , 'frontend-reset-password' ); ?>
		<div id="toast-container" data-type="confirm">
            <i class="icon-checkmark"></i>
			<p>
				<?php echo esc_html( $confirmed_text ); ?>
			</p>
		</div>
	<?php endif; ?>

	<form id="lostpasswordform" method="post" class="account-page-form account-form-wrapper background">
		<fieldset>
			<h2 style="margin-bottom: 0.3em;"><?php echo $form_title; ?></h2>

			<div class="somfrp-lost-pass-form-text">
				<?php echo $lost_text_output; ?>
			</div>

			<br /><br />

			<div class="input-wrap">
				<label for="somfrp_user_info"><?php _e( 'Email Address or Username', 'frontend-reset-password' ); ?></label>
				<input type="text" name="somfrp_user_info" id="somfrp_user_info" required autocomplete="username">
			</div>
			

			<div class="wp-block-button lostpassword-submit">
				<?php wp_nonce_field( 'somfrp_lost_pass', 'somfrp_nonce' ); ?>
				<input type="hidden" name="submitted" id="submitted" value="true">
				<input type="hidden" name="somfrp_action" id="somfrp_post_action" value="somfrp_lost_pass">
				<input type="hidden" name="somfrp_btn_txt" id="somfrp_btn_txt" value="<?php echo $button_text; ?>" />
				<button class="wp-block-button__link wp-element-button" id="reset-pass-submit" name="reset-pass-submit" type="submit" disabled>Please Wait..</button>
			</div>

		</fieldset>
	</form>

</div>
