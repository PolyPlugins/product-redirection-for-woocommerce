<?php

namespace PolyPlugins\Product_Redirection_For_WooCommerce\Backend;

class Trash {

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
		if (get_option('trash_warning_prfw') && get_option('trash_disable_prfw')) {
      add_action('wp_trash_post', array($this, 'trash_check'), 1);
    }
  }
    
  /**
   * Checks if trashing
   *
   * @param  mixed $post_id
   * @return void
   */
  public function trash_check($post_id)
  {
    $screen = get_current_screen();
    
    if ($screen->post_type == 'product') {
      wp_redirect(admin_url('edit.php?post_type=product'));
      
      exit;
    }
  }

}