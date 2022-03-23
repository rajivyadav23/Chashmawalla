<?php
/**
 * Molla Customizer Header Builder
 *
 * @author     Molla Themes
 * @category   Admin Functions
 * @since      4.8.0
 */

defined( 'ABSPATH' ) || exit;

class Molla_Header_Builder {

	public $transport = 'postMessage';

	public $elements;

	protected $container_elements = array( 'row' );

	protected $infinite_elements = array( 'row', 'custom_menu', 'molla_block', 'html', 'divider' );

	protected $desktop_elements = array( 'main_menu' );

	protected $mobile_elements = array();

	/**
	 * Constructor.
	 */
	public function __construct() {

		global $wp_customize;
		if ( ! isset( $wp_customize->selective_refresh ) ) {
			$this->transport = 'refresh';
		}
		$this->elements = array(
			'logo'              => array( esc_html__( 'Logo', 'molla' ), 'site_logo' ),
			'menu-icon'         => esc_html__( '☰ Mobile Menu Icon', 'molla' ),
			'main_menu'         => array( esc_html__( 'Main Menu', 'molla' ), 'main_menu_skin' ),
			'nav-top'           => array( esc_html__( 'Responsive Group', 'molla' ), 'top_nav_items' ),
			'custom_menu'       => array( esc_html__( 'Custom Menu', 'molla' ), 'molla_header_custom_menu_id' ),
			'search-form'       => array( esc_html__( 'Search Form', 'molla' ), 'search_content_type' ),
			'login-form'        => array( esc_html__( 'Log in/out form', 'molla' ), 'log_in_label' ),
			'social'            => array( esc_html__( 'Social Icons', 'molla' ), 'facebook' ),
			'shop'              => array( esc_html__( 'Shop Icons', 'molla' ), 'shop_icons' ),
			'currency_switcher' => esc_html__( 'Currency Switcher', 'molla' ),
			'lang_switcher'     => esc_html__( 'Language Switcher', 'molla' ),
			'molla_block'       => array( esc_html__( 'Molla Block', 'molla' ), 'molla_header_layouts_block_element' ),
			'html'              => array( esc_html__( 'HTML', 'molla' ), 'molla_header_layouts_html_element' ),
			'row'               => esc_html__( 'Row', 'molla' ),
			'divider'           => esc_html__( ' | ', 'molla' ),
		);
		$this->elements = apply_filters( 'molla_header_elements', $this->elements );

		$this->header_elements = array(
			'top_left'             => array( esc_html__( 'Header Top Left', 'molla' ), '' ),
			'top_center'           => array( esc_html__( 'Header Top Center', 'molla' ), '' ),
			'top_right'            => array( esc_html__( 'Header Top Right', 'molla' ), '' ),
			'main_left'            => array( esc_html__( 'Header Main Left', 'molla' ), '[{"menu-icon":""},{"logo":""}]' ),
			'main_center'          => array( esc_html__( 'Header Main Center', 'molla' ), '[{"main_menu":""}]' ),
			'main_right'           => array( esc_html__( 'Header Main Right', 'molla' ), '[{"shop":""}]' ),
			'bottom_left'          => array( esc_html__( 'Header Bottom Left', 'molla' ), '' ),
			'bottom_center'        => array( esc_html__( 'Header Bottom Center', 'molla' ), '' ),
			'bottom_right'         => array( esc_html__( 'Header Bottom Right', 'molla' ), '' ),
			'mobile_top_left'      => array( esc_html__( 'Mobile Header Top Left', 'molla' ), '' ),
			'mobile_top_center'    => array( esc_html__( 'Mobile Header Top Center', 'molla' ), '' ),
			'mobile_top_right'     => array( esc_html__( 'Mobile Header Top Right', 'molla' ), '' ),
			'mobile_main_left'     => array( esc_html__( 'Mobile Header Main Left', 'molla' ), '' ),
			'mobile_main_center'   => array( esc_html__( 'Mobile Header Main Center', 'molla' ), '' ),
			'mobile_main_right'    => array( esc_html__( 'Mobile Header Main Right', 'molla' ), '' ),
			'mobile_bottom_left'   => array( esc_html__( 'Mobile Header Bottom Left', 'molla' ), '' ),
			'mobile_bottom_center' => array( esc_html__( 'Mobile Header Bottom Center', 'molla' ), '' ),
			'mobile_bottom_right'  => array( esc_html__( 'Mobile Header Bottom Right', 'molla' ), '' ),
		);
		add_action( 'init', array( $this, 'customize_options' ) );
		add_action( 'customize_register', array( $this, 'add_section' ) );
		add_action( 'customize_controls_print_scripts', array( $this, 'add_scripts' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'add_js' ) );
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'add_header_builder_content' ) );

		add_action( 'customize_save_after', array( $this, 'save_header_layout_values' ) );

		add_action( 'wp_ajax_molla_add_new_header_layout', array( $this, 'add_new_header_layout' ) );
		add_action( 'wp_ajax_nopriv_molla_add_new_header_layout', array( $this, 'add_new_header_layout' ) );
		add_action( 'wp_ajax_molla_delete_header_layout', array( $this, 'delete_header_layout' ) );
		add_action( 'wp_ajax_nopriv_molla_delete_header_layout', array( $this, 'delete_header_layout' ) );
		add_action( 'wp_ajax_molla_load_header_elements', array( $this, 'load_header_elements' ) );
		add_action( 'wp_ajax_nopriv_molla_load_header_elements', array( $this, 'load_header_elements' ) );
	}

	public function customize_options() {

		Molla_Option::add_section(
			'header_presets',
			array(
				'title' => esc_html__( 'Header Builder', 'molla' ),
				'panel' => 'header',
			)
		);

		Molla_Option::add_field(
			'',
			array(
				'type'     => 'custom',
				'settings' => 'title_preset',
				'label'    => '',
				'section'  => 'header_presets',
				'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Presets', 'molla' ) . '</div>',
			)
		);

		$preset_img_html = '<div class="molla_header_presets">';
		$header_presets  = array( '' => '' );
		foreach ( molla_header_presets() as $key => $preset ) {
			$header_presets[ $key ] = $preset['title'];
			$img_class              = '';
			if ( isset( molla_option( 'molla_header_builder' )['preset'] ) && $key == molla_option( 'molla_header_builder' )['preset'] ) {
				$img_class = 'active';
			}
			$preset_img_html .= '<div class="img-select' . ( $img_class ? ' ' . $img_class : '' ) . '" data-preset="' . esc_attr( $key ) . '"><img src="' . esc_url( MOLLA_CUSTOM_IMG . '/header/' . $preset['img'] ) . '"  alt="' . esc_attr( $preset['title'] ) . '"/></div>';
		}
		$preset_img_html .= '</div>';

		Molla_Option::add_field(
			'option',
			array(
				'type'     => 'custom',
				'settings' => 'header_preset_img',
				'label'    => '',
				'section'  => 'header_presets',
				'default'  => $preset_img_html,
			)
		);

		Molla_Option::add_field(
			'option',
			array(
				'type'      => 'select',
				'settings'  => 'molla_header_builder[preset]',
				'label'     => '',
				'section'   => 'header_presets',
				'default'   => '',
				'choices'   => $header_presets,
				'transport' => 'postMessage',
			)
		);

		Molla_Option::add_field(
			'',
			array(
				'type'     => 'custom',
				'settings' => 'title_builder',
				'label'    => '',
				'section'  => 'header_presets',
				'default'  => '<div class="customize-control-title options-title">' . esc_html__( 'Custom Headers', 'molla' ) . '</div>',
			)
		);

		$header_layouts = get_theme_mod( 'molla_header_builder_layouts', array() );
		if ( ! empty( $header_layouts ) ) {
			foreach ( $header_layouts as $key => $layout ) {
				if ( isset( $layout['name'] ) ) {
					$header_layouts[ $key ] = $layout['name'];
				}
			}
		}
		$header_layouts[''] = esc_html__( 'Select Header Layout...', 'molla' );

		Molla_Option::add_field(
			'option',
			array(
				'type'        => 'select',
				'settings'    => 'molla_header_builder[selected_layout]',
				'section'     => 'header_presets',
				'choices'     => $header_layouts,
				'default'     => '',
				'description' => esc_html__( 'You can create your own header type base on presets.', 'molla' ),
				'transport'   => 'postMessage',
			)
		);

		Molla_Option::add_field(
			'option',
			array(
				'type'     => 'custom',
				'settings' => 'molla_create_header_type_link',
				'label'    => '',
				'section'  => 'header_presets',
				'default'  => '<a href="#" class="molla_create_header_type_link">' . esc_html__( 'Create a new header layout', 'molla' ) . '</a>',
			)
		);

		Molla_Option::add_field(
			'option',
			array(
				'type'     => 'custom',
				'settings' => 'molla_delete_header_type_link',
				'label'    => '',
				'section'  => 'header_presets',
				'default'  => '<a href="#" class="molla_delete_header_type_link">' . esc_html__( 'Delete header layout', 'molla' ) . '</a>',
			)
		);

		Molla_Option::add_field(
			'option',
			array(
				'type'     => 'custom',
				'settings' => '',
				'label'    => '',
				'section'  => 'header_presets',
				'default'  => '<input type="text" id="customize-control-molla_header_layout_title" placeholder="' . esc_html__( 'Header Type Title', 'molla' ) . '" />',
			)
		);

		Molla_Option::add_field(
			'option',
			array(
				'type'     => 'custom',
				'settings' => 'molla_create_header_type_submit',
				'label'    => '',
				'section'  => 'header_presets',
				'default'  => '<input type="button" class="button button-primary molla_create_header_builder" value="' . esc_html__( 'Add Header Layout', 'molla' ) . '"/>',
			)
		);

		Molla_Option::add_field(
			'option',
			array(
				'type'      => 'code',
				'settings'  => 'molla_header_builder[custom_css]',
				'label'     => esc_html__( 'Additional CSS', 'molla' ),
				'section'   => 'header_presets',
				'default'   => '',
				'transport' => 'postMessage',
				'choices'   => array(
					'language' => 'css',
				),
			)
		);
	}

	public function add_section( $wp_customize ) {
		// header elements section
		// html, molla block and custom-menu

		$nav_menus         = wp_get_nav_menus();
		$possible_menus    = array();
		$possible_menus[0] = esc_html__( 'Select a Menu', 'molla' );
		foreach ( $nav_menus as $menu ) {
			$possible_menus[ $menu->slug ] = esc_html( $menu->name );
		}

		$wp_customize->add_control(
			'molla_header_custom_menu_id',
			array(
				'type'     => 'select',
				'section'  => 'header_presets',
				'choices'  => $possible_menus,
				'settings' => array(),
			)
		);
		$wp_customize->add_control(
			'molla_header_custom_menu_type',
			array(
				'type'     => 'checkbox',
				'label'    => esc_html__( 'Enable Toggle Menu.', 'molla' ),
				'section'  => 'header_presets',
				'settings' => array(),
			)
		);
		$wp_customize->add_control(
			'molla_header_toggle_menu_title',
			array(
				'type'     => 'text',
				'label'    => esc_html__( 'Toggle Menu Title', 'molla' ),
				'section'  => 'header_presets',
				'settings' => array(),
			)
		);
		$wp_customize->add_control(
			'molla_header_toggle_menu_link',
			array(
				'type'     => 'text',
				'label'    => esc_html__( 'Link Url', 'molla' ),
				'section'  => 'header_presets',
				'settings' => array(),
			)
		);
		$wp_customize->add_control(
			'molla_header_custom_menu_toggle_event',
			array(
				'type'     => 'select',
				'label'    => esc_html__( 'Menu Active Events', 'molla' ),
				'section'  => 'header_presets',
				'default'  => 'hover',
				'choices'  => array(
					'hover'   => esc_html__( 'Mouse Hover', 'molla' ),
					'toggle'  => esc_html__( 'Mouse Toggle', 'molla' ),
					'toggle2' => esc_html__( 'Mouse Toggle (active on homepage) ', 'molla' ),
				),
				'settings' => array(),
			)
		);

		$wp_customize->add_control(
			'molla_header_toggle_menu_show_icon',
			array(
				'type'     => 'checkbox',
				'section'  => 'header_presets',
				'label'    => esc_html__( 'Show Icon', 'molla' ),
				'default'  => false,
				'settings' => array(),
			)
		);

		$wp_customize->add_control(
			'molla_header_toggle_menu_icon',
			array(
				'type'     => 'text',
				'section'  => 'header_presets',
				'label'    => esc_html__( 'Icon Class', 'molla' ),
				'default'  => 'icon-bars',
				'settings' => array(),
			)
		);

		$wp_customize->add_control(
			'molla_header_toggle_menu_active_icon',
			array(
				'type'     => 'text',
				'section'  => 'header_presets',
				'label'    => esc_html__( 'Active Icon Class', 'molla' ),
				'default'  => 'icon-close',
				'settings' => array(),
			)
		);

		$wp_customize->add_control(
			'molla_header_toggle_menu_icon_pos',
			array(
				'type'     => 'select',
				'section'  => 'header_presets',
				'label'    => esc_html__( 'Icon Position', 'molla' ),
				'default'  => 'left',
				'choices'  => array(
					'left'  => esc_html__( 'Left', 'molla' ),
					'right' => esc_html__( 'Right', 'molla' ),
				),
				'settings' => array(),
			)
		);

		$wp_customize->add_control(
			'molla_header_layouts_menu_skin',
			array(
				'type'        => 'select',
				'label'       => esc_html__( 'Menu Skin', 'molla' ),
				'section'     => 'header_presets',
				'default'     => 'skin1',
				'choices'     => array(
					'skin1' => esc_html__( 'Skin 1', 'molla' ),
					'skin2' => esc_html__( 'Skin 2', 'molla' ),
					'skin3' => esc_html__( 'Skin 3', 'molla' ),
				),
				'description' => esc_html__( 'Note: You should set menu skin options in style panel.', 'molla' ),
				'settings'    => array(),
			)
		);

		$wp_customize->add_control(
			'molla_header_layouts_block_element',
			array(
				'type'     => 'text',
				'label'    => esc_html__( 'Input block id or slug.', 'molla' ),
				'section'  => 'header_presets',
				'settings' => array(),
			)
		);
		$wp_customize->add_control(
			'molla_header_layouts_html_element',
			array(
				'type'     => 'textarea',
				'label'    => esc_html__( 'HTML', 'molla' ),
				'section'  => 'header_presets',
				'settings' => array(),
			)
		);
		$wp_customize->add_control(
			'molla_header_layouts_el_class',
			array(
				'type'     => 'text',
				'label'    => esc_html__( 'Custom CSS Class', 'molla' ),
				'section'  => 'header_presets',
				'settings' => array(),
			)
		);
		$wp_customize->add_control(
			'molla_header_layouts_save_html_button',
			array(
				'type'        => 'button',
				'settings'    => array(),
				'priority'    => 10,
				'section'     => 'header_presets',
				'input_attrs' => array(
					'value' => esc_html__( 'Save', 'molla' ),
					'class' => 'button button-primary molla_header_builder_save_html molla_header_builder_popup',
				),
			)
		);

		$wp_customize->add_section(
			'molla_header_builder',
			array(
				'title'    => esc_html__( 'Header Builder Elements', 'molla' ),
				'priority' => 125,
			)
		);
		foreach ( $this->header_elements as $key => $element ) {
			$wp_customize->add_setting(
				'hb_options[' . $key . ']',
				array(
					'default'           => $element[1],
					'capability'        => 'edit_theme_options',
					'transport'         => $this->transport,
					'sanitize_callback' => 'molla_strip_script_tags',
				)
			);
			$wp_customize->add_control(
				'hb_options_' . $key,
				array(
					'type'     => 'text',
					'label'    => esc_html( $element[0] ),
					'section'  => 'molla_header_builder',
					'settings' => 'hb_options[' . $key . ']',
				)
			);
		}
	}

	public function add_new_header_layout() {
		if ( wp_verify_nonce( $_POST['nonce'], 'molla-header-builder' ) && isset( $_POST['molla_header_layout_title'] ) ) {
			$header_layouts                                        = get_theme_mod( 'molla_header_builder_layouts', array() );
			$header_layouts[ $_POST['molla_header_layout_title'] ] = array( 'name' => $_POST['molla_header_layout_title'] );
			set_theme_mod( 'molla_header_builder_layouts', $header_layouts );
			echo json_encode( array( 'result' => 'success' ) );
			die();
		}
	}

	public function delete_header_layout() {
		if ( wp_verify_nonce( $_POST['nonce'], 'molla-header-builder' ) && isset( $_POST['header_layout'] ) && $_POST['header_layout'] ) {
			$header_layouts = get_theme_mod( 'molla_header_builder_layouts', array() );
			unset( $header_layouts[ $_POST['header_layout'] ] );
			set_theme_mod( 'molla_header_builder_layouts', $header_layouts );

			echo json_encode( array( 'result' => 'success' ) );
			die();
		}
	}

	public function load_header_elements() {
		if ( wp_verify_nonce( $_POST['nonce'], 'molla-customizer' ) && isset( $_POST['header_layout'] ) ) {
			$header_layouts = get_theme_mod( 'molla_header_builder_layouts', array() );
			if ( ! empty( $_POST['header_layout'] ) && isset( $header_layouts[ $_POST['header_layout'] ] ) && isset( $header_layouts[ $_POST['header_layout'] ]['elements'] ) ) {
				echo json_encode( $header_layouts[ $_POST['header_layout'] ] );
			}
		}
		die();
	}

	public function save_header_layout_values( $obj ) {
		$molla_header_builder_elements = molla_option( 'hb_options' );
		$current_header                = molla_option( 'molla_header_builder' );
		if ( isset( $current_header['selected_layout'] ) && $current_header['selected_layout'] ) {
			$header_layouts = get_theme_mod( 'molla_header_builder_layouts', array() );
			$header_layouts[ $current_header['selected_layout'] ]['elements'] = $molla_header_builder_elements;

			if ( isset( $current_header['custom_css'] ) && $current_header['custom_css'] ) {
				$header_layouts[ $current_header['selected_layout'] ]['custom_css'] = $current_header['custom_css'];
			}
			$header_layouts[ $current_header['selected_layout'] ]['preset'] = $current_header['preset'];
			set_theme_mod( 'molla_header_builder_layouts', $header_layouts );
		}
	}

	public function add_header_builder_content() {
		?>
		<div class="molla-header-builder">
			<div class="header-builder-header">
				<h3><?php esc_html_e( 'Header Builder', 'molla' ); ?></h3>
				<div class="devices-wrapper">
					<a href="#" class="preview-desktop active"></a>
					<a href="#" class="preview-mobile"></a>
				</div>
				<div class="actions">
					<a href="https://youtu.be/pk2W281QUa8" class="button" target="_blank"><?php esc_html_e( 'Tutorial', 'molla' ); ?></a>
					<a href="#" class="button button-clear"><?php esc_html_e( 'Clear All', 'molla' ); ?></a>
					<a href="#" class="button button-close"><?php esc_html_e( 'Close', 'molla' ); ?></a>
				</div>
			</div>
			<div class="header-wrapper-desktop">
				<div class="header-builder molla-header-builder-items molla-lg-visible">
					<p class="description"><strong><?php esc_html_e( 'Elements', 'molla' ); ?></strong><br /><?php esc_html_e( 'Drag &amp; Drop', 'molla' ); ?></p>
					<div class="molla-header-builder-list molla-drop-item">
						<?php
						foreach ( $this->elements as $key => $arr ) {
							$title_attr = '';
							if ( is_array( $arr ) ) {
								$value = $arr[0];
								if ( isset( $arr[1] ) ) {
									$title_attr .= ' data-target="' . esc_attr( $arr[1] ) . '"';
								}
							} else {
								$value = $arr;
							}
							if ( in_array( $key, $this->mobile_elements ) ) {
								continue;
							}
							$class = array();
							if ( in_array( $key, $this->container_elements ) ) {
								$class[] = 'element-cont';
							}
							if ( in_array( $key, $this->infinite_elements ) ) {
								$class[] = 'element-infinite';
							}
							if ( ! empty( $class ) ) {
								$class = 'class="' . implode( ' ', $class ) . '"';
							} else {
								$class = '';
							}
							if ( in_array( $key, $this->container_elements ) ) {
								echo '<span data-id="' . $key . '"' . $class . '>' . $value . '<b class="dashicons dashicons dashicons-plus"></b></span>';
							} else {
								echo '<span data-id="' . $key . '"' . $class . $title_attr . '>' . $value . '<i class="dashicons dashicons-admin-generic"></i></span>';
							}
						}
						?>
					</div>
				</div>
				<div class="header-builder-desktop header-builder-wrapper">
					<div class="header-builder molla-header-builder-top">
						<span class="molla-header-builder-tooltip" data-target="header_top" data-type="section"><?php esc_html_e( 'Header Top', 'molla' ); ?><i class="dashicons dashicons-admin-generic"></i></span>
						<div class="molla-header-builder-left molla-drop-item" data-id="hb_options[top_left]">
						</div>
						<div class="molla-header-builder-center molla-drop-item" data-id="hb_options[top_center]">
						</div>
						<div class="molla-header-builder-right molla-drop-item" data-id="hb_options[top_right]">
						</div>
					</div>
					<div class="header-builder molla-header-builder-main">
						<span class="molla-header-builder-tooltip" data-target="header_main" data-type="section"><?php esc_html_e( 'Header Main', 'molla' ); ?><i class="dashicons dashicons-admin-generic"></i></span>
						<div class="molla-header-builder-left molla-drop-item" data-id="hb_options[main_left]">
						</div>
						<div class="molla-header-builder-center molla-drop-item" data-id="hb_options[main_center]">
						</div>
						<div class="molla-header-builder-right molla-drop-item" data-id="hb_options[main_right]">
						</div>
					</div>
					<div class="header-builder molla-header-builder-bottom">
						<span class="molla-header-builder-tooltip" data-target="header_bottom" data-type="section"><?php esc_html_e( 'Header Bottom', 'molla' ); ?><i class="dashicons dashicons-admin-generic"></i></span>
						<div class="molla-header-builder-left molla-drop-item" data-id="hb_options[bottom_left]">
						</div>
						<div class="molla-header-builder-center molla-drop-item" data-id="hb_options[bottom_center]">
						</div>
						<div class="molla-header-builder-right molla-drop-item" data-id="hb_options[bottom_right]">
						</div>
					</div>
				</div>
			</div>
			<div class="header-wrapper-mobile">
				<div class="header-builder molla-header-builder-items molla-sm-visible">
					<p class="description"><strong><?php esc_html_e( 'Elements', 'molla' ); ?></strong><br /><?php esc_html_e( 'Drag &amp; Drop', 'molla' ); ?></p>
					<div class="molla-header-builder-list molla-drop-item-mobile">
						<?php
						foreach ( $this->elements as $key => $arr ) {
							if ( in_array( $key, $this->desktop_elements ) ) {
								continue;
							}
							$title_attr = '';
							if ( is_array( $arr ) ) {
								$value = $arr[0];
								if ( isset( $arr[1] ) ) {
									$title_attr .= ' data-target="' . esc_attr( $arr[1] ) . '"';
								}
							} else {
								$value = $arr;
							}
							$class = array();
							if ( in_array( $key, $this->container_elements ) ) {
								$class[] = 'element-cont';
							}
							if ( in_array( $key, $this->infinite_elements ) ) {
								$class[] = 'element-infinite';
							}
							if ( ! empty( $class ) ) {
								$class = 'class="' . implode( ' ', $class ) . '"';
							} else {
								$class = '';
							}
							if ( in_array( $key, $this->container_elements ) ) {
								echo '<span data-id="' . $key . '"' . $class . '>' . $value . '<b class="dashicons dashicons dashicons-plus"></b></span>';
							} else {
								echo '<span data-id="' . $key . '"' . $class . $title_attr . '>' . $value . '<i class="dashicons dashicons-admin-generic"></i></span>';
							}
						}
						?>
					</div>
				</div>
				<div class="header-builder-mobile header-builder-wrapper">
					<div class="header-builder molla-header-builder-top">
						<span class="molla-header-builder-tooltip" data-target="header_top" data-type="section"><?php esc_html_e( 'Header Top', 'molla' ); ?><i class="dashicons dashicons-admin-generic"></i></span>
						<div class="molla-header-builder-left molla-drop-item-mobile" data-id="hb_options[mobile_top_left]">
						</div>
						<div class="molla-header-builder-center molla-drop-item-mobile" data-id="hb_options[mobile_top_center]">
						</div>
						<div class="molla-header-builder-right molla-drop-item-mobile" data-id="hb_options[mobile_top_right]">
						</div>
					</div>
					<div class="header-builder molla-header-builder-main">
						<span class="molla-header-builder-tooltip" data-target="header_main" data-type="section"><?php esc_html_e( 'Header Main', 'molla' ); ?><i class="dashicons dashicons-admin-generic"></i></span>
						<div class="molla-header-builder-left molla-drop-item-mobile" data-id="hb_options[mobile_main_left]">
						</div>
						<div class="molla-header-builder-center molla-drop-item-mobile" data-id="hb_options[mobile_main_center]">
						</div>
						<div class="molla-header-builder-right molla-drop-item-mobile" data-id="hb_options[mobile_main_right]">
						</div>
					</div>
					<div class="header-builder molla-header-builder-bottom">
						<span class="molla-header-builder-tooltip" data-target="header_bottom" data-type="section"" data-type="control"><?php esc_html_e( 'Header Bottom', 'molla' ); ?><i class="dashicons dashicons-admin-generic"></i></span>
						<div class="molla-header-builder-left molla-drop-item-mobile" data-id="hb_options[mobile_bottom_left]">
						</div>
						<div class="molla-header-builder-center molla-drop-item-mobile" data-id="hb_options[mobile_bottom_center]">
						</div>
						<div class="molla-header-builder-right molla-drop-item-mobile" data-id="hb_options[mobile_bottom_right]">
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	public function add_scripts() {
		?>
		<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				if (!wp.customize) {
					return;
				}
				var selectedLayout = wp.customize.instance('molla_header_builder[selected_layout]').get();
				if (!selectedLayout) {
					$('#customize-control-molla_delete_header_type_link, #customize-control-molla_header_builder-custom_css').hide();
				}
				if ('default' == selectedLayout) {
					$('#customize-control-molla_delete_header_type_link').hide();
				}
				$('#customize-control-molla_header_layout_title,.molla_create_header_builder').hide();
				$('.molla_create_header_type_link').on('click', function() {
					$('#customize-control-molla_header_layout_title,.molla_create_header_builder').fadeIn();
					$('#customize-control-molla_header_layout_title').focus();
				});
				$('#customize-control-molla_header_layout_title').on('keyup', function(e) {
					if (typeof e.keyCode != 'undefined' && e.keyCode === 13) {
						$('.molla_create_header_builder').trigger('click');
					}
				});
			
				$('#customize-control-molla_header_custom_menu_id, #customize-control-molla_header_custom_menu_type, #customize-control-molla_header_toggle_menu_title, #customize-control-molla_header_toggle_menu_link, #customize-control-molla_header_custom_menu_toggle_event, #customize-control-molla_header_layouts_menu_skin, #customize-control-molla_header_toggle_menu_show_icon, #customize-control-molla_header_toggle_menu_icon, #customize-control-molla_header_toggle_menu_active_icon, #customize-control-molla_header_toggle_menu_icon_pos, #customize-control-molla_header_layouts_block_element, #customize-control-molla_header_layouts_html_element, #customize-control-molla_header_layouts_el_class, #customize-control-molla_header_layouts_save_html_button').hide();


				$(document.body).on('click', '.molla_create_header_builder', function(e) {
					var title = $('#customize-control-molla_header_layout_title').val().trim(),
						$this = $(this);
					if (!title) {
						return;
					}
					$this.attr('disabled', 'disabled');
					$.ajax({
						url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
						data:{ wp_customize: 'on', action: 'molla_add_new_header_layout', nonce: '<?php echo wp_create_nonce( 'molla-header-builder' ); ?>', molla_header_layout_title: title},
						type: 'post',
						success: function(response) {
							$this.removeAttr('disabled');
							$('#customize-control-molla_header_layout_title').val('');
							$('#customize-control-molla_header_layout_title,.molla_create_header_builder').fadeOut();
							if (!$('#customize-control-molla_header_builder-selected_layout select option[val="' + title + '"]').length) {
								$('#customize-control-molla_header_builder-selected_layout select option:last-child').before('<option val="' + title + '">' + title + '</option>');
							}
							$('#customize-control-molla_header_builder-selected_layout select option').removeAttr('selected');
							$('#customize-control-molla_header_builder-selected_layout select option[val="' + title + '"]').attr('selected', 'selected');
							$('#customize-control-molla_header_builder-selected_layout select').trigger('change');
						}
					})
				});
				$(document.body).on('click', '.molla_delete_header_type_link', function(e) {
					if (confirm('You are about to permanently delete this header layout.\n\'Cancel\' to stop, \'OK\' to delete.')) {
						var $this = $(this);
						if ($this.hasClass('disabled')) {
							return false;
						}
						$this.addClass('disabled');
						$.ajax({
							url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
							data:{ wp_customize: 'on', action: 'molla_delete_header_layout', nonce: '<?php echo wp_create_nonce( 'molla-header-builder' ); ?>', header_layout: $('#customize-control-molla_header_builder-selected_layout select').val()},
							type: 'post',
							success: function(response) {
								$this.removeClass('disabled');
								$('#customize-control-molla_header_builder-selected_layout select option:selected').remove();
								$('#customize-control-molla_header_builder-selected_layout select').trigger('change');
							}
						});
					}
				});
			});
		</script>
		<?php
	}

	public function add_js() {
		$admin_vars = array(
			'header_builder_presets' => json_encode( molla_header_presets() ),
		);
		wp_localize_script( 'molla-admin', 'js_molla_hb_vars', $admin_vars );
	}

	public function sanitize_number_value( $value ) {
		if ( ! preg_match( '#[0-9]#', $value ) ) {
			return '';
		}
		return $value;
	}
	public function sanitize_boolean_value( $value ) {
		if ( $value ) {
			return '1';
		}
		return '';
	}
}

new Molla_Header_Builder();
