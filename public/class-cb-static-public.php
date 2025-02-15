<?php

/**
 * The public facing part of the plugin.
 *
 * @link              https://github.com/demispatti/cb-static/
 * @since             0.1.0
 * @package           cb_static
 * @subpackage        cb_static/public
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class cb_static_public {

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
	 * The loader that's responsible for maintaining
	 * and registering all hooks that power the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      object $loader
	 */
	private $loader;

	/**
	 * The name of the meta key for accessing post meta data.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string $meta_key
	 */
	private $meta_key;

	/**
	 * Kicks off the public part of the plugin.
	 *
	 * @since 0.1.0
	 *
	 * @param string $plugin_name
	 * @param string $plugin_domain
	 * @param string $plugin_version
	 * @param object $loader
	 * @param string $meta_key
	 */
	public function __construct( $plugin_name, $plugin_domain, $plugin_version, $loader, $meta_key ) {

		$this->plugin_name = $plugin_name;
		$this->plugin_domain = $plugin_domain;
		$this->plugin_version = $plugin_version;
		$this->loader = $loader;
		$this->meta_key = $meta_key;

		$this->load_dependencies();
	}

	/**
	 * Loads the class responsible for localizing the script that manages the parallax related stuff on the frontend.
	 *
	 * @since    0.1.0
	 * @access   public
	 * @return   void
	 */
	public function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . "public/includes/class-cb-static-public-localisation.php";
	}

	/**
	 * Registers the stylesheet for the public-facing side of the site.
	 *
	 * @hooked_action
	 *
	 * @since    0.1.0
	 * @access   public
	 * @return   void
	 */
	public function enqueue_styles() {
		
		wp_enqueue_style(
			$this->plugin_name . '-public-css',
			plugin_dir_url( __FILE__ ) . 'css/public.css',
			array(),
			$this->plugin_version,
			'all'
		);
	}

	/**
	 * Registers the scripts for the public-facing side of the site.
	 *
	 * @hooked_action
	 *
	 * @since    0.1.0
	 * @access   public
	 * @return   void
	 */
	public function enqueue_scripts() {

		// Public script.
		wp_enqueue_script(
			$this->plugin_name . '-public-js',
			plugin_dir_url( __FILE__ ) . 'js/public.js',
			array(
				'jquery',
			),
			$this->plugin_version,
			true
		);
	}

	/**
	 * Initiates the localisation for the public part of the plugin.
	 *
	 * @hooked_action
	 *
	 * @since    0.1.0
	 * @access   private
	 * @return   void
	 */
	public function define_public_localisation() {

		$public_localisation = new cb_static_public_localisation( $this->get_plugin_name(), $this->get_plugin_domain(), $this->get_plugin_version(), $this->get_meta_key() );
		$this->loader->add_action( 'template_redirect', $public_localisation, 'get_post_meta' );
		$this->loader->add_action( 'wp_enqueue_scripts', $public_localisation, 'localize_public_area', 1000 );
	}

	/**
	 * Retrieves the name of the plugin.
	 *
	 * @since     0.1.0
	 * @access    public
	 * @return    string The name of the plugin.
	 */
	public function get_plugin_name() {

		return $this->plugin_name;
	}

	/**
	 * Retrieves the domain of the plugin.
	 *
	 * @since     0.1.0
	 * @access    public
	 * @return    string The domain of the plugin.
	 */
	public function get_plugin_domain() {

		return $this->plugin_domain;
	}

	/**
	 * Retrieves the version number of the plugin.
	 *
	 * @since     0.1.0
	 * @access    public
	 * @return    string The version number of the plugin.
	 */
	public function get_plugin_version() {

		return $this->plugin_version;
	}

	/**
	 * Retrieves the meta key.
	 *
	 * @since    0.1.0
	 * @access   public
	 * @return   string $meta_key
	 */
	public function get_meta_key() {

		return $this->meta_key;
	}

}
