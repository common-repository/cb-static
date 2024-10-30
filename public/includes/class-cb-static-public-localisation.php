<?php

/**
 * This class is responsible for localizing the public part of the plugin.
 *
 * @link              https://github.com/demispatti/cb-static
 * @since             0.1.0
 * @package           cb_static
 * @subpackage        cb_static/public/includes
 *                    Author:            Demis Patti <demis@demispatti.ch>
 *                    Author URI:        http://demispatti.ch
 *                    License:           GPL-2.0+
 *                    License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class cb_static_public_localisation {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string $plugin_name
	 */
	private $plugin_name;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string $plugin_domain
	 */
	private $plugin_domain;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string $plugin_version
	 */
	private $plugin_version;

	/**
	 * The array holding the meta data.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      array $post_meta
	 */
	private $post_meta;

	/**
	 * The string that holds the meta key to access post meta data.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string $meta_key
	 */
	private $meta_key;

	/**
	 * The array holding a set of default values for the background image display.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      array $default
	 */
	private $default;

	/**
	 * Maintains the allowed option values.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    array $allowed
	 */
	public $allowed;

	/**
	 * Sets the default values for a custom background.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @return   void
	 */
	private function set_default_values() {

		// Set up an array with default values.
		$this->default = array(
			'background_repeat' => 'no-repeat',
			'position_x'        => 'center',
			'position_y'        => 'center',
			'attachment'        => 'fixed',
		);
	}
	
	/**
	 * A whitelist of allowed options.
	 *
	 * @todo     : refactor all "allowed options" from all files -> one class.
	 * @since    0.1.0
	 * @access   private
	 * @return   void
	 */
	private function set_allowed_options() {
		
		// Image options for a static background image.
		$this->allowed['position_x'] = array(
			'left'   => 'left',
			'center' => 'center',
			'right'  => 'right',
		);
		
		$this->allowed['position_y'] = array(
			'top'    => 'top',
			'center' => 'center',
			'bottom' => 'bottom',
		);
		
		$this->allowed['attachment'] = array(
			'fixed'  => 'fixed',
			'scroll' => 'scroll',
		);
		
		$this->allowed['repeat'] = array(
			'no-repeat' => 'no-repeat',
			'repeat'    => 'repeat',
			'repeat-x'  => 'repeat horizontally',
			'repeat-y'  => 'repeat vertically',
		);
		
		// Image options for a dynamic background image.
		$this->allowed['parallax'] = array(
			'off' => false,
			'on'  => true,
		);
		
		$this->allowed['direction'] = array(
			'vertical'   => 'vertical',
			'horizontal' => 'horizontal',
		);
		
		$this->allowed['vertical_scroll_direction'] = array(
			'top'    => 'to top',
			'bottom' => 'to bottom',
		);
		
		$this->allowed['horizontal_scroll_direction'] = array(
			'left'  => 'to the left',
			'right' => 'to the right',
		);
		
		$this->allowed['horizontal_alignment'] = array(
			'left'   => 'left',
			'center' => 'center',
			'right'  => 'right',
		);
		
		$this->allowed['vertical_alignment'] = array(
			'top'    => 'top',
			'center' => 'center',
			'bottom' => 'bottom',
		);
		
		$this->allowed['overlay_image'] = array(
			'none' => 'none',
			'01'   => '01.png',
			'02'   => '02.png',
			'03'   => '03.png',
			'04'   => '04.png',
			'05'   => '05.png',
			'06'   => '06.png',
			'07'   => '07.png',
			'08'   => '08.png',
			'09'   => '09.png',
		);
		
		$this->allowed['overlay_opacity'] = array(
			'default' => 'default',
			'0.1'     => '0.1',
			'0.2'     => '0.2',
			'0.3'     => '0.3',
			'0.4'     => '0.4',
			'0.5'     => '0.5',
			'0.6'     => '0.6',
			'0.7'     => '0.7',
			'0.8'     => '0.8',
			'0.9'     => '0.9',
		);
	}

	/**
	 * Kicks off localisation of the public part of the plugin.
	 *
	 * @since    0.1.0
	 * @access   public
	 * @param    string $plugin_name
	 * @param    string $plugin_domain
	 * @param    string $plugin_version
	 * @param    string $meta_key
	 */
	public function __construct( $plugin_name, $plugin_domain, $plugin_version, $meta_key ) {

		$this->plugin_name = $plugin_name;
		$this->plugin_domain = $plugin_domain;
		$this->plugin_version = $plugin_version;
		$this->meta_key = $meta_key;
		$this->post_meta = [ ];

		$this->set_default_values();
		$this->set_allowed_options();
		$this->init();
	}

	/**
	 * Register all necessary hooks for this part of the plugin to work with WordPress.
	 *
	 * @since    0.1.0
	 * @access   public
	 * @return   void
	 */
	public function init() {

		add_action( 'template_redirect', array( &$this, 'get_post_meta' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'localize_public_area' ), 1000 );
	}

	/**
	 * Retrieves the post meta data.
	 *
	 * @hooked_action
	 *
	 * @since    0.1.0
	 * @access   public
	 * @return   void
	 */
	public function get_post_meta(){

		global $post;
		// Get the background color.
		$this->post_meta['backgroundColor'] = trim( get_post_meta( $post->ID, '_cb_static_color', true ), '#' );

		// Get the background image attachment ID.
		$attachment_id = get_post_meta( $post->ID, '_cb_static_image_id', true );

		// If an attachment ID was found, get the image source.
		if ( ! empty( $attachment_id ) ) {
			$image = wp_get_attachment_image_src( absint( $attachment_id ), 'image/*post-thumbnail*/' );
		}

		// Get the image URL.
		$this->post_meta['imageSrc'] = ! empty( $image ) && isset( $image[0] ) ? $image[0] : '';
		$this->post_meta['imageWidth'] = ! empty( $image ) && isset( $image[1] ) ? $image[1] : '';
		$this->post_meta['imageHeight'] = ! empty( $image ) && isset( $image[2] ) ? $image[2] : '';

		// Get the background image settings.
		$backgroundRepeat     = get_post_meta( $post->ID, '_cb_static_repeat', true );
		$positionX = get_post_meta( $post->ID, '_cb_static_position_x', true );
		$positionY = get_post_meta( $post->ID, '_cb_static_position_y', true );
		$bakgroundAttachment = get_post_meta( $post->ID, '_cb_static_attachment', true );

		// Get theme mods.
		$mod_repeat     = get_theme_mod( 'background_repeat', 'repeat' );
		$mod_position_x = get_theme_mod( 'background_position_x', 'left' );
		$mod_position_y = get_theme_mod( 'background_position_y', 'top' );
		$mod_attachment = get_theme_mod( 'background_attachment', 'scroll' );

		/**
		 * Make sure values are set for the image options.  This should always be set so that we can
		 * be sure that the user's background image overwrites the default/WP custom background settings.
		 * With one theme, this doesn't matter, but we need to make sure that the background stays
		 * consistent between different themes and different WP custom background settings.  The data
		 * will only be stored if the user selects a background image.
		 */
		$this->post_meta['backgroundRepeat']     = ! empty( $backgroundRepeat ) ? $backgroundRepeat : $mod_repeat;
		$this->post_meta['positionX'] = ! empty( $positionX ) ? $positionX : $mod_position_x;
		$this->post_meta['positionY'] = ! empty( $positionY ) ? $positionY : $mod_position_y;
		$this->post_meta['bakgroundAttachment'] = ! empty( $bakgroundAttachment ) ? $bakgroundAttachment : $mod_attachment;
	}

	/**
	 * Localizes the public part of the plugin.
	 *
	 * @hooked_action
	 * @since    0.1.0
	 * @access   public
	 * @return   void
	 */
	public function localize_public_area() {

		global $post;

		// Here we need to check if $post is an object. If not, we're not on a singular thus we bail.
		if( ( !is_object( $post ) || NULL === $post ) && false == get_option( 'page_for_posts' ) ) {
			return;
		}

		// Passes the parameters to the script.
		wp_localize_script(
			$this->plugin_name . '-public-js',
			'cbStatic',
			array_merge(
				$this->post_meta,
				$this->get_path_to_overlay_images()
			)
		);
	}

	/**
	 * Retrieves the path to the folder containing the overlay images.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @return   array
	 */
	private function get_path_to_overlay_images() {

		$path = site_url() . '/wp-content/plugins/cb-parallax/public/images/overlays/';

		return array( 'overlayPath' => $path );
	}
}
