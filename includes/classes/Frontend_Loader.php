<?php

namespace PolyPlugins\Product_Redirection_For_WooCommerce;

use PolyPlugins\Product_Redirection_For_WooCommerce\Frontend\Actions\Redirect;
use PolyPlugins\Product_Redirection_For_WooCommerce\Frontend\Enqueue;
use PolyPlugins\Product_Redirection_For_WooCommerce\Frontend\UI;

class Frontend_Loader {

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
    $this->load_ui();
    $this->load_actions();
  }
  
  /**
   * Load Enqueue
   *
   * @return void
   */
  public function load_enqueue() {
    $enqueue = new Enqueue($this->plugin, $this->version, $this->plugin_dir);
    $enqueue->init();
  }
  
  /**
   * Load UI
   *
   * @return void
   */
  public function load_ui() {
    $ui = new UI($this->plugin, $this->version, $this->plugin_dir);
    $ui->init();
  }
  
  /**
   * Load actions
   *
   * @return void
   */
  public function load_actions() {
    $this->load_redirect();
  }
  
  /**
   * Load redirect rule
   *
   * @return void
   */
  private function load_redirect() {
    $redirect = new Redirect($this->plugin, $this->version, $this->plugin_dir);
    $redirect->init();
  }
  
}