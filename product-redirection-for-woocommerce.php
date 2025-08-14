<?php

/**
 * Plugin Name: Product Redirection for WooCommerce
 * Description: Instead of deleting products which is bad for SEO, redirect them to their parent category or a custom url.
 * Version: 1.2.1
 * Requires at least: 6.5
 * Requires PHP: 5.4
 * Author: Poly Plugins
 * Author URI: https://www.polyplugins.com
 * Plugin URI: https://wordpress.org/plugins/product-redirection-for-woocommerce/
 * Requires Plugins: woocommerce, advanced-custom-fields
 * Text Domain: product-redirection-for-woocommerce
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace PolyPlugins\Product_Redirection_For_WooCommerce;

if (!defined('ABSPATH')) exit;

require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

register_activation_hook(__FILE__, array(__NAMESPACE__ . '\Activation', 'init'));
register_deactivation_hook(__FILE__, array(__NAMESPACE__ . '\Deactivation', 'init'));

class Product_Redirection_For_WooCommerce
{
  
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
  public function __construct() {
    $this->plugin         = __FILE__;
    $this->version        = $this->get_plugin_version();
    $this->plugin_dir     = untrailingslashit(dirname($this->plugin));
  }
  
  /**
   * Init
   *
   * @return void
   */
  public function init() {
    $this->load_dependencies();
  }
  
  /**
   * Load dependencies
   *
   * @return void
   */
  public function load_dependencies() {
    $dependency_loader = new Dependency_Loader($this->plugin, $this->version, $this->plugin_dir);
    $dependency_loader->init();
  }

  /**
   * Get the plugin version
   *
   * @return string $version The plugin version
   */
  private function get_plugin_version() {
    $plugin_data = get_file_data($this->plugin, array('Version' => 'Version'), false);
    $version     = $plugin_data['Version'];

    return $version;
  }

}

$product_redirection_for_woocommerce = new Product_Redirection_For_WooCommerce();
$product_redirection_for_woocommerce->init();
