<?php
/**
 * Frontend Reset Password - Reset Form
 * 
 * @version	1.0
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
						<span><?php echo esc_html( $error ); ?></span>
					</p>
				<?php endforeach; ?>
			</div>

		<?php endif; ?>

	<?php endif; ?>

	<form id="resetpasswordform" method="post" class="account-page-form account-form-wrapper background">
		<fieldset>
		<h2 style="margin-bottom: 0.3em;"><?php echo $form_title; ?></h2>

			<div class="somfrp-lost-pass-form-text">
				<?php echo $reset_text_output; ?>
			</div>

			<br /><br />

			<div class="input-wrap">
				<label for="som_new_user_pass"><?php _e( 'New Password', 'frontend-reset-password' ); ?></label>

				<?php if ( ! empty( $min_length ) ) { ?>
					<input name="som_new_user_pass" id="som_new_user_pass" class="disblock som-password-input som-pass-strength-input" type="password" pattern=".{<?php echo $min_length; ?>,}" required>
				<?php } else { ?>
					<input name="som_new_user_pass" id="som_new_user_pass" class="disblock som-password-input som-pass-strength-input" type="password" required>
				<?php } ?>

				<?php do_action( 'som_after_change_new_pass_input' ); ?>
			</div>
			<div class="input-wrap password">
				<label for="som_new_user_pass_again"><?php _e( 'Re-enter Password', 'frontend-reset-password' ); ?></label>

				<?php if ( ! empty( $min_length ) ) { ?>
					<input name="som_new_user_pass_again" id="som_new_user_pass_again" class="disblock som-password-input password" type="password" pattern=".{<?php echo $min_length; ?>,}" required>
				<?php } else { ?>
					<input name="som_new_user_pass_again" id="som_new_user_pass_again" class="disblock som-password-input password" type="password" required>
				<?php } ?>
			</div>

			<div class="wp-block-button lostpassword-submit">
				<?php wp_nonce_field( 'somfrp_reset_pass', 'somfrp_nonce' ); ?>
				<input type="hidden" name="submitted" id="submitted" value="true">
				<input type="hidden" name="somfrp_action" id="somfrp_post_action" value="somfrp_reset_pass">
				<button class="wp-block-button__link wp-element-button" id="reset-pass-submit" name="reset-pass-submit" type="submit" disabled>Please Wait..</button>
			</div>

		</fieldset>
	</form>

</div>
