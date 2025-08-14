<?php

namespace PolyPlugins\Product_Redirection_For_WooCommerce\Backend;

class ACF {

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
    add_filter('acf/settings/load_json', array($this, 'acf_json_load'));
  }
  
  /**
   * Load local json
   *
   * @param  mixed $paths
   * @return void
   */
  public function acf_json_load($paths) {
    $paths[] = $this->plugin_dir . '/acf/json/load/';
    
    return $paths;
  }

}