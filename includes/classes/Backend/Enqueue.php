<?php

namespace PolyPlugins\Product_Redirection_For_WooCommerce\Backend;

class Enqueue {

  /**
	 * Full path and filename of plugin.
	 *
	 * @var string $version Full path and filename of plugin.
	 */
  private $plugin;

	/**
	 * The version of this plugin.
	 *
	 * @var   string $version The current version of this plugin.
	 */
	private $version;

  /**
   * The plugin directory.
   *
   * @var string $plugin_dir The plugin directory.
   */
	private $plugin_dir;
  
  /**
   * __construct
   *
   * @return void
   */
  public function __construct($plugin, $version, $plugin_dir) {
    $this->plugin     = $plugin;
    $this->version    = $version;
    $this->plugin_dir = $plugin_dir;
  }
  
  /**
   * Init
   *
   * @return void
   */
  public function init() {
    add_action('admin_enqueue_scripts', array($this, 'enqueue'));
  }
  
  /**
   * Enqueue scripts and styles
   *
   * @return void
   */
  public function enqueue($hook_suffix) {
    $screen = get_current_screen();
    
    if ($screen->post_type == 'product' && is_admin()) {
      $this->enqueue_styles();
      $this->enqueue_scripts();
    }
  }
  
  /**
   * Enqueue styles
   *
   * @return void
   */
  private function enqueue_styles() {
    wp_enqueue_style('product-admin-prfw', plugins_url('/css/product-admin.css', $this->plugin), array(), $this->version, false);
    wp_enqueue_style('sweet-alert-2', plugins_url('/css/sweetalert2.min.css', $this->plugin), array(), $this->version, false);
  }
  
  /**
   * Enqueue scripts
   *
   * @return void
   */
  private function enqueue_scripts() {
    $status             = get_query_var('post_status');
    $trash_disable_prfw = get_option('trash_disable_prfw') ? false : true;

    wp_enqueue_script('product-admin-prfw', plugins_url('/js/product-admin.js', $this->plugin), array('jquery'), $this->version, true);
    wp_localize_script('product-admin-prfw', 'prfw_object',
      array(
        'siteurl'      => get_option('siteurl'),
        'trashdisable' => $trash_disable_prfw,
        'poststatus'   => $status
      )
    );
    wp_set_script_translations('product-admin-prfw', 'product-redirection-for-woocommerce', plugin_dir_path($this->plugin) . '/languages/');

    wp_enqueue_script('sweet-alert-2', plugins_url('/js/sweetalert2.min.js', $this->plugin), array('jquery'), $this->version, false);
  }

}