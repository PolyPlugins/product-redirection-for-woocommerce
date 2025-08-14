<?php

namespace PolyPlugins\Product_Redirection_For_WooCommerce;

use PolyPlugins\Product_Redirection_For_WooCommerce\Backend\ACF;
use PolyPlugins\Product_Redirection_For_WooCommerce\Backend\Admin;
use PolyPlugins\Product_Redirection_For_WooCommerce\Backend\Enqueue;
use PolyPlugins\Product_Redirection_For_WooCommerce\Backend\Trash;

class Backend_Loader {

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
    $this->load_enqueue();
    $this->load_settings();
    $this->load_acf();
    $this->load_trash();
  }
  
  /**
   * Load UI
   *
   * @return void
   */
  public function load_enqueue() {
    $gui = new Enqueue($this->plugin, $this->version, $this->plugin_dir);
    $gui->init();
  }
  
  /**
   * Load Admin
   *
   * @return void
   */
  public function load_settings() {
    $admin = new Admin($this->plugin, $this->version, $this->plugin_dir);
    $admin->init();
  }
  
  /**
   * Load ACF
   *
   * @return void
   */
  public function load_acf() {
    $acf = new ACF($this->plugin, $this->version, $this->plugin_dir);
    $acf->init();
  }
  
  /**
   * Load trash
   *
   * @return void
   */
  private function load_trash() {
    $trash = new Trash($this->plugin, $this->version, $this->plugin_dir);
    $trash->init();
  }

}