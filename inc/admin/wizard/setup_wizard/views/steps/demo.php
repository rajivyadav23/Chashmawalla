<aside class="content-left demos">
</aside>
<div class="content-right molla-setup-wizard-demos">
	<h2><?php esc_html_e( 'Demo Content Installation', 'molla' ); ?></h2>
	<p class="lead"><?php echo esc_html__( 'In this step you can upload your logo and select a demo from the list.', 'molla' ); ?></p>
	<h4><?php esc_html_e( 'Upload Your Logo:', 'molla' ); ?></h4>
	<form method="post" class="molla-install-demos">
		<input type="hidden" id="current_site_url" value="<?php echo esc_url( home_url() ); ?>">
		<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px;">
			<table>
				<tr>
					<td>
						<div id="current-logo">
						<?php
							$image_url  = molla_option( 'site_logo' );
							$logo_width = molla_option( 'logo_width' );

							$image_url  = $image_url ? $image_url : MOLLA_URI . '/assets/images/logo_white.png';
							$logo_width = $logo_width ? $logo_width : 170;

						if ( $image_url ) {
							$image = '<img class="site-logo" src="%s" alt="%s" style="max-width:%spx; height:auto" />';
							printf(
								$image,
								$image_url,
								get_bloginfo( 'name' ),
								$logo_width
							);
						}
						?>
						</div>
					</td>
					<td>
						<a href="#" class="button button-primary button-large button-upload"><?php esc_html_e( 'Upload Logo', 'molla' ); ?><i class="fas fa-upload" style="margin-left:10px;"></i></a>
						<p class="info-qt light-info"><?php esc_html_e( 'You can upload and customize this in Theme Options later.', 'molla' ); ?></p>
					</td>
				</tr>
			</table>
		</div>

		<h4 style="margin: 50px 0 20px;"><?php esc_html_e( 'Select Demo:', 'molla' ); ?></h4>
		<?php
		$demos               = $this->molla_demo_types();
		$demo_filters        = $this->molla_demo_filters();
		$memory_limit        = wp_convert_hr_to_bytes( @ini_get( 'memory_limit' ) );
		$molla_plugins_obj   = new MollaTGMPlugins();
		$required_plugins    = $molla_plugins_obj->get_plugins_list();
		$uninstalled_plugins = array();
		$all_plugins         = array();
		foreach ( $required_plugins as $plugin ) {
			if ( $plugin['required'] && is_plugin_inactive( $plugin['url'] ) ) {
				$uninstalled_plugins[ $plugin['slug'] ] = $plugin;
			}
			$all_plugins[ $plugin['slug'] ] = $plugin;
		}
		$time_limit    = ini_get( 'max_execution_time' );
		$server_status = $memory_limit >= 268435456 && ( $time_limit >= 600 || 0 == $time_limit );
		?>

		<div class="molla-install-demo mfp-hide">
			<div class="theme">
				<div class="theme-wrapper">
					<div class="theme-screenshot">
					</div>
				</div>
			</div>
			<div id="import-status"></div>
			<div id="molla-install-options">
				<h3>
					<span class="theme-name"></span>
					<?php if ( Molla()->is_registered() ) : ?>
					<span class="more-options"><?php esc_html_e( 'Details', 'molla' ); ?></span>
					<?php endif; ?>
				</h3>
				<?php if ( Molla()->is_registered() ) : ?>
				<div class="molla-install-section" style="margin-bottom: 10px;">
					<div class="molla-install-options-section" style="display: none;">
						<label for="molla-import-options"><input type="checkbox" id="molla-import-options" value="1" checked="checked"/> <?php esc_html_e( 'Import theme options', 'molla' ); ?></label>
						<input type="hidden" id="molla-install-demo-type" value="landing"/>
						<label for="molla-reset-menus"><input type="checkbox" id="molla-reset-menus" value="1" checked="checked"/> <?php esc_html_e( 'Reset menus', 'molla' ); ?></label>
						<label for="molla-reset-widgets"><input type="checkbox" id="molla-reset-widgets" value="1" checked="checked"/> <?php esc_html_e( 'Reset widgets', 'molla' ); ?></label>
						<label for="molla-import-dummy"><input type="checkbox" id="molla-import-dummy" value="1" checked="checked"/> <?php esc_html_e( 'Import dummy content', 'molla' ); ?></label>
						<label for="molla-import-widgets"><input type="checkbox" id="molla-import-widgets" value="1" checked="checked"/> <?php esc_html_e( 'Import widgets', 'molla' ); ?></label>
						<label for="molla-override-contents"><input type="checkbox" id="molla-override-contents" value="1" checked="checked" /> <?php esc_html_e( 'Override existing contents', 'molla' ); ?></label>
					</div>
					<p><?php esc_html_e( 'Do you want to install demo? It can also take a minute to complete.', 'molla' ); ?></p>
					<button class="btn <?php echo ! $server_status ? 'btn-quaternary' : 'btn-primary'; ?> molla-import-yes"<?php echo ! $server_status ? ' disabled="disabled"' : ''; ?>><?php esc_html_e( 'Standard Import', 'molla' ); ?></button>
					<?php if ( ! $server_status ) : ?>
					<p><?php esc_html_e( 'Your server performance does not satisfy Molla demo importer engine\'s requirement. We recommend you to use alternative method to perform demo import without any issues but it may take much time than standard import.', 'molla' ); ?></p>
					<?php else : ?>
					<p><?php esc_html_e( 'If you have any issues with standard import, please use Alternative mode. But it may take much time than standard import.', 'molla' ); ?></p>
					<?php endif; ?>
					<button class="btn btn-secondary molla-import-yes alternative"><?php esc_html_e( 'Alternative Mode', 'molla' ); ?></button>
				</div>
				<?php else : ?>
				<a href="<?php echo esc_url( $this->get_step_link( 'updates' ) ); ?>" class="btn btn-dark" style="display: inline-block; box-sizing: border-box; text-decoration: none; text-align: center; margin-bottom: 20px;"><?php esc_html_e( 'Activate Theme', 'molla' ); ?></a>
				<?php endif; ?>
				<a href="#" class="live-site" target="_blank"><?php esc_html_e( 'Live Preview', 'molla' ); ?></a>
			</div>
		</div>
		<div class="demo-sort-filters">
			<label><?php esc_html_e( 'Filter By:', 'molla' ); ?></label>
			<ul data-sort-id="theme-install-demos" class="sort-source">
			<?php foreach ( $demo_filters as $filter_class => $filter_name ) : ?>
				<li data-filter-by="<?php echo esc_attr( $filter_class ); ?>" data-active="<?php echo ( 'all' == $filter_class ? 'true' : 'false' ); ?>"><a href="#"><?php echo esc_html( $filter_name ); ?></a></li>
			<?php endforeach; ?>
			</ul>
			<div class="clear"></div>
		</div>
		<div id="theme-install-demos">
		<?php foreach ( $demos as $demo => $demo_details ) : ?>
			<?php
			$uninstalled_demo_plugins = $uninstalled_plugins;
			if ( ! empty( $demo_details['plugins'] ) ) {
				foreach ( $demo_details['plugins'] as $plugin ) {
					if ( is_plugin_inactive( $all_plugins[ $plugin ]['url'] ) ) {
						$uninstalled_demo_plugins[ $plugin ] = $all_plugins[ $plugin ];
					}
				}
			}

			if ( 'landing' == $demo ) {
			} else {
				$demo_sites = $this->molla_url . $demo;
			}
			?>
			<div class="theme <?php echo esc_attr( $demo_details['filter'] ); ?>">
				<div class="theme-wrapper">
					<div class="theme-screenshot" style="background-image: url('<?php echo esc_url( $demo_details['img'] ); ?>');">
					</div>
					<h3 class="theme-name" id="<?php echo esc_attr( $demo ); ?>" data-live-url="<?php echo esc_attr( $demo_sites ); ?>"><?php echo molla_filter_output( $demo_details['alt'] ); ?></h3>
					<?php if ( ! empty( $uninstalled_demo_plugins ) ) : ?>
						<ul class="plugins-used">
							<?php foreach ( $uninstalled_demo_plugins as $plugin ) : ?>
								<li>
									<div class="thumb">
										<img src="<?php echo esc_url( $plugin['image_url'] ); ?>" />
									</div>
									<div>
										<h5><?php echo esc_html( $plugin['name'] ); ?></h5>
									</div>
								</li>
							<?php endforeach; ?>
							<li>
								<?php /* translators: %s: Plugins step link */ ?>
								<p><?php printf( esc_html__( 'Please go to %1$sPlugins step%2$s and install required plugins.', 'molla' ), '<a href="' . esc_url( $this->get_step_link( 'default_plugins' ) ) . '">', '</a>' ); ?></p>
							</li>
						</ul>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
		</div>
		<br />
		<p class="info-qt light-info icon-fixed" style="padding-left: 20px;"><?php esc_html_e( 'Installing a demo provides pages, posts, menus, images, theme options, widgets and more.', 'molla' ); ?>
		<br /><strong><?php esc_html_e( 'IMPORTANT: ', 'molla' ); ?> </strong><span><?php esc_html_e( 'The included plugins need to be installed and activated before you install a demo.', 'molla' ); ?></span>
		<?php /* translators: $1: opening A tag which has link to the plugins step $2: closing A tag */ ?>
		<br /><?php printf( esc_html__( 'Please check the %1$sStatus%2$s step to ensure your server meets all requirements for a successful import. Settings that need attention will be listed in red.', 'molla' ), '<a href="' . esc_url( $this->get_step_link( 'status' ) ) . '">', '</a>' ); ?></p>

		<input type="hidden" name="new_logo_id" id="new_logo_id" value="">

		<p class="molla-setup-actions step">
			<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-outline button-next button-icon-hide"><?php esc_html_e( 'Skip this step', 'molla' ); ?></a>
			<button type="submit" class="button-primary button button-large button-next" name="save_step"><?php esc_html_e( 'Continue', 'molla' ); ?></button>
			<?php wp_nonce_field( 'molla-setup-wizard' ); ?>
		</p>
	</form>
</div>
