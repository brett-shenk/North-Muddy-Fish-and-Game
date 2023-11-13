<?php
/**
 * Displays the frontend submission form.
 *
 * This template can be overridden by copying it to yourtheme/ptp_templates/submission-form.php.
 *
 * @package   Barn2/document-library-pro
 * @author    Barn2 Media <info@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 */

// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$is_success = isset( $_GET['success'] ) && isset($_GET['active_tab']) && $_GET['active_tab'] == 'submit-document' && $_GET['success'] === '1'; //phpcs:ignore

?>

<?php if ( ! empty( $errors ) ) : ?>
	<div id="toast-container" data-type="error">
		<i class="icon-notification"></i>
		<?php foreach ( $errors as $error ) : ?>
			<p><?php echo wp_kses_post( $error ); ?></p>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<?php if ( $is_success && empty( $errors ) ) : ?>
	<div id="toast-container" data-type="confirm">
		<i class="icon-checkmark"></i>
		<p><?php esc_html_e( 'Document successfully uploaded.', 'document-library-pro' ); ?></p>
	</div>
<?php endif; ?>

<form action="<?php echo esc_url( home_url('/my-account/?active_tab=submit-document') ); ?>" method="post" id="dlp-submit-form" class="account-form-wrapper dlp-submission-form dlp-theme-<?php echo esc_attr( $theme ); ?>" enctype="multipart/form-data">

	<?php do_action( 'dlp_before_submission_form' ); 
	
	$document_fields = get_option('dlp_document_fields', []);
	$field_moderation = isset( $document_fields['fronted_moderation'] ) ? $document_fields['fronted_moderation'] : ''; 
	$field_moderation = ($field_moderation === '1') ? $field_moderation : '';

	if( $field_moderation ){
		echo '<p>New documents will be held for moderation by an administrator first.</p><br /><br />';
	}
	?>

	<?php foreach ( $fields as $key => $field ) : 
		if( $field['label'] !== 'Email' ){
			$class = ( in_array(esc_attr( $field['type'] ), ['taxonomy', 'select'] ) ) ? ' select' : '';
			$class .= ( esc_attr( $field['type'] ) == 'file' ) ? ' submit-document dropzone' : '';
			$label_class = ( esc_attr( $field['type'] ) == 'file' ) ? ' class="sr-only"' : '';
			?>
			<fieldset class="fieldset-<?php echo esc_attr( $key ); ?> fieldset-type-<?php echo esc_attr( $field['type'] ); ?> <?php echo esc_attr( isset( $field['class'] ) ? $field['class'] : '' ); ?>">
				<div class="input-wrap<?php echo $class; ?>">
					<label for="<?php echo esc_attr( $key ); ?>"<?php echo $label_class; ?>><?php echo wp_kses_post( $field['label'] ); ?></label>
					<div class="field <?php echo $field['required'] ? 'required-field' : ''; ?>">
						<?php echo $templates->get_template( 'form-fields/' . $field['type'] . '-field.php', [ 'key' => $key, 'field' => $field, 'templates' => $templates ] ); //phpcs:ignore ?>
					</div>
				</div>
			</fieldset><?php 
		}
	endforeach; ?>

	<div class="wp-block-button">
		<?php wp_nonce_field( 'dlp_frontend_submission', 'dlp_frontend_nonce' ); ?>
		<input type="hidden" name="dlp_fe_sub_btn_txt" id="dlp_fe_sub_btn_txt" value="<?php echo esc_attr( $submit_button_text ); ?>" />
		<button class="wp-block-button__link wp-element-button" type="submit" name="submit_job" id="submit_job" disabled>Please Wait..</button>
	</div>

	<?php do_action( 'dlp_after_submission_form' ); ?>

</form>
