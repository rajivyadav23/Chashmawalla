<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php

global $wp_filesystem;
// Initialize the WordPress filesystem, no more using file_put_contents function
if ( empty( $wp_filesystem ) ) {
	require_once ABSPATH . '/wp-admin/includes/file.php';
	WP_Filesystem();
}

$data = array(
	'wp_uploads'     => wp_get_upload_dir(),
	'memory_limit'   => wp_convert_hr_to_bytes( @ini_get( 'memory_limit' ) ),
	'time_limit'     => ini_get( 'max_execution_time' ),
	'max_input_vars' => ini_get( 'max_input_vars' ),
);

$status = array(
	'uploads'        => wp_is_writable( $data['wp_uploads']['basedir'] ),
	'fs'             => ( $wp_filesystem || WP_Filesystem() ) ? true : false,
	'zip'            => class_exists( 'ZipArchive' ),
	'suhosin'        => extension_loaded( 'suhosin' ),
	'memory_limit'   => $data['memory_limit'] >= 268435456,
	'time_limit'     => ( ( $data['time_limit'] >= 600 ) || ( 0 == $data['time_limit'] ) ) ? true : false,
	'max_input_vars' => $data['max_input_vars'] >= 2000,
);

?>

<div class="molla_mini_status">

	<ul class="system-status ul-vertical">
		<li>
			<?php if ( $status['uploads'] ) : ?>
				<span class="status step-id status-yes"></span>
			<?php else : ?>
				<span class="status step-id status-no"></span>
			<?php endif; ?>

			<span class="label"><?php esc_html_e( 'Uploads folder writable', 'molla' ); ?></span>

			<?php if ( ! $status['uploads'] ) : ?>
				<p class="status-notice status-error"><?php esc_html_e( 'Uploads folder must be writable. Please set write permission to your wp-content/uploads folder.', 'molla' ); ?></p>
			<?php endif; ?>
		</li>

		<li>
			<?php if ( $status['fs'] ) : ?>
				<span class="status step-id status-yes"></span>
			<?php else : ?>
				<span class="status step-id status-no"></span>
			<?php endif; ?>

			<span class="label"><?php esc_html_e( 'WP File System', 'molla' ); ?></span>

			<?php if ( ! $status['fs'] ) : ?>
				<p class="status-notice status-error"><?php esc_html_e( 'File System access is required for pre-built websites and plugins installation. Please contact your hosting provider.', 'molla' ); ?></p>
			<?php endif; ?>
		</li>

		<li>
			<?php if ( $status['zip'] ) : ?>
				<span class="status step-id status-yes"></span>
			<?php else : ?>
				<span class="status step-id status-no"></span>
			<?php endif; ?>

			<span class="label"><?php esc_html_e( 'ZipArchive', 'molla' ); ?></span>

			<?php if ( ! $status['zip'] ) : ?>
				<p class="status-notice status-error"><?php esc_html_e( 'ZipArchive is required for pre-built websites and plugins installation. Please contact your hosting provider.', 'molla' ); ?></p>
			<?php endif; ?>
		</li>

		<?php if ( $status['suhosin'] ) : ?>

			<li>
				<span class="label"><?php esc_html_e( 'SUHOSIN Installed', 'molla' ); ?></span>
				<span class="status step-id status-info"></span>
				<p class="status-notice"><?php esc_html_e( 'Suhosin may need to be configured to increase its data submission limits.', 'molla' ); ?></p>
			</li>

		<?php else : ?>

			<li>
				<?php if ( $status['memory_limit'] ) : ?>
					<span class="status step-id status-yes"></span>
				<?php else : ?>
					<?php if ( $data['memory_limit'] < 134217728 ) : ?>
						<span class="status step-id status-no"></span>
					<?php else : ?>
						<span class="status step-id status-info"></span>
					<?php endif; ?>
				<?php endif; ?>

				<span class="label"><?php esc_html_e( 'PHP Memory Limit', 'molla' ); ?></span>

				<?php if ( $status['memory_limit'] ) : ?>
					<span class="desc">(<?php echo size_format( $data['memory_limit'] ); ?>)</span>
				<?php else : ?>
					<?php if ( $data['memory_limit'] < 134217728 ) : ?>
						<span class="desc">(<?php echo size_format( $data['memory_limit'] ); ?>)</span>
						<p class="status-notice status-error">
						<?php
							echo wp_kses(
								__( 'Minimum <strong>128 MB</strong> is required, <strong>256 MB</strong> is recommended.', 'molla' ),
								array(
									'strong' => array(),
								)
							);
						?>
						</p>
					<?php else : ?>
						<span class="desc">(<?php echo size_format( $data['memory_limit'] ); ?>)</span>
						<p class="status-notice status-error">
						<?php
							echo wp_kses(
								__( 'Current memory limit is OK, however <strong>256 MB</strong> is recommended.', 'molla' ),
								array(
									'strong' => array(),
								)
							);
						?>
						</p>
					<?php endif; ?>
				<?php endif; ?>
			</li>

			<li>
				<?php if ( $status['time_limit'] ) : ?>
					<span class="status step-id status-yes"></span>
				<?php else : ?>
					<?php if ( $data['time_limit'] < 300 ) : ?>
						<span class="status step-id status-no"></span>
					<?php else : ?>
						<span class="status step-id status-info"></span>
					<?php endif; ?>
				<?php endif; ?>

				<span class="label"><?php esc_html_e( 'PHP max_execution_time', 'molla' ); ?></span>

				<?php if ( $status['time_limit'] ) : ?>
					<span class="desc">(<?php echo esc_html( $data['time_limit'] ); ?>)</span>
				<?php else : ?>
					<?php if ( $data['time_limit'] < 300 ) : ?>
						<span class="desc">(<?php echo esc_html( $data['time_limit'] ); ?>)</span>
						<p class="status-notice status-error">
						<?php
							echo wp_kses(
								__( 'Minimum <strong>300</strong> is required, <strong>600</strong> is recommended.', 'molla' ),
								array(
									'strong' => array(),
								)
							);
						?>
						</p>
					<?php else : ?>
						<span class="desc">(<?php echo esc_html( $data['time_limit'] ); ?>)</span>
						<p class="status-notice status-error">
						<?php
							echo wp_kses(
								__( 'Current time limit is OK, however <strong>600</strong> is recommended.', 'molla' ),
								array(
									'strong' => array(),
								)
							);
						?>
						</p>
					<?php endif; ?>
				<?php endif; ?>
			</li>

			<li>
				<?php if ( $status['max_input_vars'] ) : ?>
					<span class="status step-id status-yes"></span>
				<?php else : ?>
					<span class="status step-id status-no"></span>
				<?php endif; ?>

				<span class="label"><?php esc_html_e( 'PHP max_input_vars', 'molla' ); ?></span>

				<?php if ( $status['max_input_vars'] ) : ?>
					<span class="desc">(<?php echo esc_html( $data['max_input_vars'] ); ?>)</span>
				<?php else : ?>
					<span class="desc">(<?php echo esc_html( $data['max_input_vars'] ); ?>)</span>
					<p class="status-notice status-error"><?php esc_html_e( 'Minimum 2000 is required', 'molla' ); ?></p>
				<?php endif; ?>
			</li>
			<p class="info-qt"><?php esc_html_e( 'Do not worry if you are unable to update your server configuration due to hosting limit, you can use "Alternative Import" method in Demo Content import page.', 'molla' ); ?></p>

			<p>
			<?php
				printf(
					wp_kses(
						__( 'php.ini values are shown above. Real values may vary, please check your limits using <a target="_blank" href="%s">php_info()</a>', 'molla' ),
						array(
							'a' => array(
								'href'   => array(),
								'target' => array(),
							),
						)
					),
					'http://php.net/manual/en/function.phpinfo.php'
				);
			?>
			</p>
		<?php endif; ?>

	</ul>

</div>
