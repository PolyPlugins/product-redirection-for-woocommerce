<?php

namespace PolyPlugins\Product_Redirection_For_WooCommerce;

class Dependency_Loader {

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
    $this->maybe_load();
  }

  public function maybe_load() {
    $is_compatible = Utils::check_compatibility();

    if ($is_compatible) {
      $this->load_frontend();
      $this->load_backend();
      $this->load_updater();
    } else {
      add_action('admin_notices', array($this, 'load_incompatible_notice'));
    }
  }
  
  /**
   * Load Frontend
   *
   * @return void
   */
  public function load_frontend() {
    $frontend_loader = new Frontend_Loader($this->plugin, $this->version, $this->plugin_dir);
    $frontend_loader->init();
  }
  
  /**
   * Load Backend
   *
   * @return void
   */
  public function load_backend() {
    $backend_loader = new Backend_Loader($this->plugin, $this->version, $this->plugin_dir);
    $backend_loader->init();
  }
  
  public function load_incompatible_notice() {
    if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
      ?>
      <div class="notice notice-error">
        <p>
          <?php esc_html_e('Product Redirection for WooCommerce is not running, because ', 'product-redirection-for-woocommerce'); ?>
          <a href="plugin-install.php?s=WooCommerce&tab=search&type=term"><?php esc_html_e('WooCommerce', 'product-redirection-for-woocommerce'); ?></a>
          <?php esc_html_e(' is not installed or activated.', 'product-redirection-for-woocommerce' ); ?>
        </p>
      </div>
      <?php
    }

    if (!class_exists('acf')) {
      ?>
      <div class="notice notice-error">
        <p>
          <?php esc_html_e('Product Redirection for WooCommerce requires ', 'product-redirection-for-woocommerce'); ?>
          <a href="plugin-install.php?s=Advanced%20Custom%20Fields&tab=search&type=term"><?php esc_html_e('Advanced Custom Fields', 'product-redirection-for-woocommerce'); ?></a>
          <?php esc_html_e(' to run! Please install Advanced Custom Fields in order to continue using our plugin.', 'product-redirection-for-woocommerce' ); ?>
        </p>
      </div>
      <?php
    }
    
    if (is_multisite()) {
      ?>
      <div class="notice notice-error">
        <p>
          <?php esc_html_e('Product Redirection for WooCommerce is not running, because multisite is not supported. This is planned is on our ', 'product-redirection-for-woocommerce'); ?>
          <a href="https://github.com/users/PolyPlugins/projects/2/" target="_blank"><?php esc_html__('Roadmap', 'product-redirection-for-woocommerce'); ?></a>
        </p>
      </div>
      <?php
    }
  }
  
  
  /**
   * Load Updater
   *
   * @return void
   */
  public function load_updater() {
    $backend_loader = new Updater($this->plugin, $this->version, $this->plugin_dir);
    $backend_loader->init();
  }

}