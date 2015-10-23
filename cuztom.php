<?php

if( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'Cuztom_Initializer' ) ) :

/**
 * Cuztom_Initializer handles init of Cuztom
 *
 * @author 	Gijs Jorissen
 * @since  	2.3
 *
 */
class Cuztom_Initializer
{
	private static $instance;

	/**
	 * Public function to set the instance
	 *
	 * @author 	Gijs Jorissen
	 * @since  	2.3
	 *
	 */
	public static function instance()
	{
		if ( ! isset( self::$instance ) )
		{
			self::$instance = new Cuztom_Initializer;
			self::$instance->setup_constants();
			self::$instance->includes();
			self::$instance->add_hooks();
		}

		return self::$instance;
	}

	/**
	 * Setup all the constants
	 *
	 * @author 	Gijs Jorissen
	 * @since   2.3
	 *
	 */
	private function setup_constants()
	{
		if( ! defined( 'CUZTOM_VERSION' ) )
			define( 'CUZTOM_VERSION', '2.9.17' );

		if( ! defined( 'CUZTOM_DIR' ) )
			define( 'CUZTOM_DIR', plugin_dir_path( __FILE__ ) );

		if( ! defined( 'CUZTOM_URL' ) )
			define( 'CUZTOM_URL', self::get_cuztom_url( __FILE__ ) );
	}

	/**
	 * Include the necessary files
	 *
	 * @author 	Gijs Jorissen
	 * @since   2.3
	 *
	 */
	private function includes()
	{
		include( CUZTOM_DIR . 'classes/cuztom.class.php' );
		include( CUZTOM_DIR . 'classes/notice.class.php' );
		include( CUZTOM_DIR . 'classes/post_type.class.php' );
		include( CUZTOM_DIR . 'classes/taxonomy.class.php' );
		include( CUZTOM_DIR . 'classes/sidebar.class.php' );

		include( CUZTOM_DIR . 'classes/meta.class.php' );
		include( CUZTOM_DIR . 'classes/meta/meta_box.class.php' );
		include( CUZTOM_DIR . 'classes/meta/user_meta.class.php' );
		include( CUZTOM_DIR . 'classes/meta/term_meta.class.php' );

		include( CUZTOM_DIR . 'classes/field.class.php' );
		include( CUZTOM_DIR . 'classes/fields/bundle.class.php' );
		include( CUZTOM_DIR . 'classes/fields/tabs.class.php' );
		include( CUZTOM_DIR . 'classes/fields/accordion.class.php' );
		include( CUZTOM_DIR . 'classes/fields/tab.class.php' );
		include( CUZTOM_DIR . 'classes/fields/text.class.php' );
		include( CUZTOM_DIR . 'classes/fields/textarea.class.php' );
		include( CUZTOM_DIR . 'classes/fields/codearea.class.php' );
		include( CUZTOM_DIR . 'classes/fields/checkbox.class.php' );
		include( CUZTOM_DIR . 'classes/fields/yesno.class.php' );
		include( CUZTOM_DIR . 'classes/fields/select.class.php' );
		include( CUZTOM_DIR . 'classes/fields/multi_select.class.php' );
		include( CUZTOM_DIR . 'classes/fields/checkboxes.class.php' );
		include( CUZTOM_DIR . 'classes/fields/radios.class.php' );
		include( CUZTOM_DIR . 'classes/fields/wysiwyg.class.php' );
		include( CUZTOM_DIR . 'classes/fields/image.class.php' );
		include( CUZTOM_DIR . 'classes/fields/file.class.php' );
		include( CUZTOM_DIR . 'classes/fields/date.class.php' );
		include( CUZTOM_DIR . 'classes/fields/time.class.php' );
		include( CUZTOM_DIR . 'classes/fields/datetime.class.php' );
		include( CUZTOM_DIR . 'classes/fields/color.class.php' );
		include( CUZTOM_DIR . 'classes/fields/post_select.class.php' );
		include( CUZTOM_DIR . 'classes/fields/post_checkboxes.class.php' );
		include( CUZTOM_DIR . 'classes/fields/term_select.class.php' );
		include( CUZTOM_DIR . 'classes/fields/term_checkboxes.class.php' );
		include( CUZTOM_DIR . 'classes/fields/hidden.class.php' );

		include( CUZTOM_DIR . 'functions/post_type.php' );
		include( CUZTOM_DIR . 'functions/taxonomy.php' );
	}

	/**
	 * Add hooks
	 *
	 * @author 	Gijs Jorissen
	 * @since   2.3
	 *
	 */
	private function add_hooks()
	{
		// Add actions
		add_action( 'admin_init', array( &$this, 'register_styles' ) );
		add_action( 'admin_print_styles', array( &$this, 'enqueue_styles' ) );

		add_action( 'admin_init', array( &$this, 'register_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_scripts' ) );

		// Ajax
		add_action( 'wp_ajax_cuztom_field_ajax_save', array( 'Cuztom_Field', 'ajax_save' ) );
		add_action( 'wp_ajax_nopriv_cuztom_field_ajax_save', array( 'Cuztom_Field', 'ajax_save' ) );
	}

	/**
	 * Registers styles
	 *
	 * @author 	Gijs Jorissen
	 * @since 	0.3
	 *
	 */
	function register_styles()
	{
		wp_register_style( 'cuztom-jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/base/jquery-ui.css', false, CUZTOM_VERSION, 'screen' );
		wp_register_style( 'cuztom', CUZTOM_URL . '/assets/css/style.css', false, CUZTOM_VERSION, 'screen' );
		wp_register_style( 'codemirror-css', CUZTOM_URL . '/assets/codemirror/lib/codemirror.css', false, CUZTOM_VERSION, 'screen' );
		wp_register_style( 'codemirror-dialog-css', CUZTOM_URL . '/assets/codemirror/addon/dialog/dialog.css', false, CUZTOM_VERSION, 'screen' );
	}

	/**
	 * Enqueues styles
	 *
	 * @author 	Gijs Jorissen
	 * @since 	0.3
	 *
	 */
	function enqueue_styles()
	{
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'codemirror-css' );
		wp_enqueue_style( 'codemirror-dialog-css' );
        wp_enqueue_style( 'cuztom-jquery-ui' );
		wp_enqueue_style( 'cuztom' );
	}

	/**
	 * Registers scripts
	 *
	 * @author 	Gijs Jorissen
	 * @since 	0.3
	 *
	 */
	function register_scripts()
	{
		wp_register_script( 'jquery-timepicker', 	CUZTOM_URL . '/assets/js/jquery.timepicker.js', 	array( 'jquery' ), CUZTOM_VERSION, true );
		wp_register_script( 'cuztom', 				CUZTOM_URL . '/assets/js/functions.js', 			array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker', 'jquery-ui-tabs', 'jquery-ui-accordion', 'jquery-ui-sortable', 'wp-color-picker', 'jquery-timepicker', 'jquery-ui-slider', 'codemirror-js' ), CUZTOM_VERSION, true );
        //CodeMirror
        wp_register_script( 'codemirror-js', 	CUZTOM_URL . '/assets/codemirror/lib/codemirror.js', 	array(), CUZTOM_VERSION, true );
        wp_register_script( 'codemirror-addon-searchcursor', 	CUZTOM_URL . '/assets/codemirror/addon/search/searchcursor.js', 	array('codemirror-js'), CUZTOM_VERSION, true );
        wp_register_script( 'codemirror-addon-search', 	CUZTOM_URL . '/assets/codemirror/addon/search/search.js', 	array('codemirror-js'), CUZTOM_VERSION, true );
        wp_register_script( 'codemirror-addon-dialog', 	CUZTOM_URL . '/assets/codemirror/addon/dialog/dialog.js', 	array('codemirror-js'), CUZTOM_VERSION, true );
        wp_register_script( 'codemirror-addon-fullscreen', 	CUZTOM_URL . '/assets/codemirror/addon/display/fullscreen.js', 	array('codemirror-js'), CUZTOM_VERSION, true );
        wp_register_script( 'codemirror-addon-closetag', 	CUZTOM_URL . '/assets/codemirror/addon/edit/closetag.js', 	array('codemirror-js'), CUZTOM_VERSION, true );
        wp_register_script( 'codemirror-addon-closebrackets', 	CUZTOM_URL . '/assets/codemirror/addon/edit/closebrackets.js', 	array('codemirror-js'), CUZTOM_VERSION, true );
        wp_register_script( 'codemirror-addon-active-line', 	CUZTOM_URL . '/assets/codemirror/addon/selection/active-line.js', 	array('codemirror-js'), CUZTOM_VERSION, true );
        wp_register_script( 'codemirror-addon-show-hint', 	CUZTOM_URL . '/assets/codemirror/addon/hint/show-hint.js', 	array('codemirror-js'), CUZTOM_VERSION, true );
        wp_register_script( 'codemirror-addon-xml-hint', 	CUZTOM_URL . '/assets/codemirror/addon/hint/xml-hint.js', 	array('codemirror-js'), CUZTOM_VERSION, true );
        wp_register_script( 'codemirror-addon-html-hint', 	CUZTOM_URL . '/assets/codemirror/addon/hint/html-hint.js', 	array('codemirror-js'), CUZTOM_VERSION, true );
        wp_register_script( 'codemirror-mode-xml', 	CUZTOM_URL . '/assets/codemirror/mode/xml/xml.js', 	array('codemirror-js'), CUZTOM_VERSION, true );
        wp_register_script( 'codemirror-mode-javascript', 	CUZTOM_URL . '/assets/codemirror/mode/javascript/javascript.js', 	array('codemirror-js'), CUZTOM_VERSION, true );
        wp_register_script( 'codemirror-mode-css', 	CUZTOM_URL . '/assets/codemirror/mode/css/css.js', 	array('codemirror-js'), CUZTOM_VERSION, true );
        wp_register_script( 'codemirror-mode-htmlmixed', 	CUZTOM_URL . '/assets/codemirror/mode/htmlmixed/htmlmixed.js', 	array('codemirror-js'), CUZTOM_VERSION, true );
	}

	/**
	 * Enqueues scripts
	 *
	 * @author 	Gijs Jorissen
	 * @since 	0.3
	 *
	 */
	function enqueue_scripts()
	{
		if( function_exists( 'wp_enqueue_media' ) ) wp_enqueue_media();

		wp_enqueue_script( 'thickbox' );
		wp_enqueue_script( 'cuztom' );
		wp_enqueue_script( 'media-upload' );
        //CodeMirror
        wp_enqueue_script( 'codemirror-js' );
        wp_enqueue_script( 'codemirror-addon-searchcursor' );
        wp_enqueue_script( 'codemirror-addon-search' );
        wp_enqueue_script( 'codemirror-addon-dialog' );
        wp_enqueue_script( 'codemirror-addon-fullscreen' );
        wp_enqueue_script( 'codemirror-addon-closetag' );
        wp_enqueue_script( 'codemirror-addon-closebrackets' );
        wp_enqueue_script( 'codemirror-addon-active-line' );
        wp_enqueue_script( 'codemirror-addon-show-hint' );
        wp_enqueue_script( 'codemirror-addon-xml-hint' );
        wp_enqueue_script( 'codemirror-addon-html-hint' );
        wp_enqueue_script( 'codemirror-mode-xml' );
        wp_enqueue_script( 'codemirror-mode-javascript' );
        wp_enqueue_script( 'codemirror-mode-css' );
        wp_enqueue_script( 'codemirror-mode-htmlmixed' );

		self::localize_scripts();
	}

	/**
	 * Localizes scripts
	 *
	 * @author 	Gijs Jorissen
	 * @since 	1.1.1
	 *
	 */
	function localize_scripts()
	{
		wp_localize_script( 'cuztom', 'Cuztom', array(
			'home_url'			=> get_home_url(),
			'ajax_url'			=> admin_url( 'admin-ajax.php' ),
			'date_format'		=> get_option( 'date_format' ),
			'wp_version'		=> get_bloginfo( 'version' ),
			'remove_image'		=> __( 'Remove current image', 'cuztom' ),
			'remove_file'		=> __( 'Remove current file', 'cuztom' )
		) );
	}

	/**
	 * Recursive method to determine the path to the Cuztom folder
	 *
	 * @param 	string 			$path
	 * @return 	string
	 *
	 * @author 	Gijs Jorissen
	 * @since 	0.4.1
	 *
	 */
	function get_cuztom_url( $path = __FILE__, $url = array() )
	{
		$path = dirname( $path );
		$path = str_replace( '\\', '/', $path );
		$explode_path = explode( '/', $path );

		$current_dir = $explode_path[count( $explode_path ) - 1];
		array_push( $url, $current_dir );

		if( $current_dir == 'wp-content' )
		{
			// Build new paths
			$path = '';
			$directories = array_reverse( $url );

			foreach( $directories as $dir )
			{
				$path = $path . '/' . $dir;
			}

			return $path;
		}
		else
		{
			return $this->get_cuztom_url( $path, $url );
		}
	}
}

endif; // End class_exists check

Cuztom_Initializer::instance();