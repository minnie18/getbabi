<div class="feature-section import-demo-data">
	<div class="warning-msg">
		<p><?php esc_html_e('Please install Recommended Plugins for demo to be imported completely.','eightmedi-lite');?></p>
		<p><?php esc_html_e('And Make sure you download demo in a fresh install.','eightmedi-lite');?></p>
		<p><?php esc_html_e('Your Old Data might be deleted.','eightmedi-lite');?></p>
	</div>
	<?php
	wp_enqueue_style( 'plugin-install' );
	wp_enqueue_script( 'plugin-install' );
	wp_enqueue_script( 'updates' );
	$eightmedi_lite_req_plugins = $this->eightmedi_lite_req_plugins;

	foreach($eightmedi_lite_req_plugins as $slug=>$plugin) :
		if($plugin['bundled'] == false) {
			?>
			<div class="action-tab warning">
				<h3><?php printf( // WPCS: XSS OK.
					/* translators: 1: plugin name. */
					esc_html__("Install : %s Plugin", 'eightmedi-lite'), $plugin['name'] ); ?></h3>
					<p><?php esc_html_e('Please check the plugins folder inside theme and upload the zip of plugins from plugin uploader.','eightmedi-lite');?></p>
				</div>
				<?php
			} else {
				$github_repo = isset($plugin['github_repo']) ? $plugin['github_repo'] : false;
				$github = false;

				if($github_repo) {
					$plugin['location'] = $this->get_local_dir_path($plugin);
					$github = true;
				}

				$status = $this->check_active($plugin);

				switch($status['needs']) {
					case 'install' :
					$btn_class = 'install-offline button';
					$label = esc_html__('Install and Activate', 'eightmedi-lite');
					$link = $plugin['location'];
					break;

					case 'deactivate' :
					$btn_class = 'button';
					$label = esc_html__('Deactivate', 'eightmedi-lite');
					$link = admin_url('plugins.php');
					break;

					case 'activate' :
					$btn_class = 'activate-offline button button-primary';
					$label = esc_html__('Activate', 'eightmedi-lite');
					$link = $plugin['location'];
					break;
				}
				if(!class_exists($plugin['class'])) : ?>
					<div class="action-tab warning">
					<h3><?php printf( // WPCS: XSS OK.
						/* translators: 1: plugin name. */
						esc_html__("Install : %s Plugin", 'eightmedi-lite'), esc_html($plugin['name'])); ?>
					</h3>
					<p><?php echo esc_html($plugin['info']); ?></p>
					<span class="plugin-card-<?php echo esc_attr($plugin['slug']); ?>" action_button>
						<a class="<?php echo esc_attr($btn_class); ?>" data-github="<?php echo esc_attr($github); ?>" data-file='<?php echo esc_attr($plugin['slug']).'/'.esc_attr($plugin['filename']); ?>' data-slug="<?php echo esc_attr($plugin['slug']); ?>" href="<?php echo esc_html($link); ?>"><?php echo esc_html($label); ?></a>
					</span>
				</div>
				<?php
			endif;
		}
	endforeach;
	do_action('instant_demo_importer');
	?>
</div>