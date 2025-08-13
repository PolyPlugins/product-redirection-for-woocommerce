<?php

/**
 * Plugin Name: Product Redirection for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/product-redirection-for-woocommerce/
 * Description: Instead of deleting products which is bad for SEO, redirect them to their parent category or a custom url.
 * Version: 1.1.8
 * Author: Poly Plugins
 * Author URI: https://www.polyplugins.com
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace PolyPlugins;

if (!defined('ABSPATH')) exit;

/* To-Do
 * Add section to pull pro classes
 * Add to messages a way to get to support based on error
 */

register_activation_hook(__FILE__, array(__NAMESPACE__ . '\PRODUCT_REDIRECTION_FOR_WOOCOMMMERCE', 'activation'));

class PRODUCT_REDIRECTION_FOR_WOOCOMMMERCE
{

  protected $plugin;
  protected $plugin_basename;
  protected $plugin_name;
  protected $plugin_dir;
  protected $plugin_slug;
  protected $support;

  public function __construct() {
    // Define Properties
    $this->plugin = __FILE__;
    $this->plugin_basename = plugin_basename($this->plugin);
    $this->plugin_name = trim(dirname($this->plugin_basename), '/');
    $this->plugin_dir = untrailingslashit(dirname($this->plugin));
    $this->plugin_slug = dirname(plugin_basename($this->plugin));
    $this->support = " <a href='https://wordpress.org/support/plugin/" . $this->plugin_slug . "/' target='_blank'>Get Support</a>";
  }

  public static function activation()
  {
    if (self::activation_check()) {
      $oos_notice = __('This product is out of stock, you can find similar products in our', 'product-redirection-for-woocommerce');
      add_option('trash_warning_prfw', 1);
      add_option('trash_disable_prfw', 1);
      add_option('stock_notice_prfw', $oos_notice);
    } else {
      deactivate_plugins(plugin_basename( __FILE__ ));
      wp_die( __('Product Redirection for WooCommerce failed to activate, because multisite is not currently supported. This is planned in on our <a href="https://trello.com/b/yCyf2WYs/free-product-redirection-for-woocommerce" target="_blank">Roadmap</a>.', 'product-redirection-for-woocommerce' ));
    }
  }

  public function load()
  {
    // Display notice if incompatible
    add_action( 'admin_init', array( $this, 'check_compatibility' ) );
    // Don't run if incompatible
    if (!self::compatibility()) {
      return;
    }
    require($this->plugin_dir . '/inc/class-acf-check.php');
    require($this->plugin_dir . '/inc/class-enqueue.php');
    require($this->plugin_dir . '/inc/class-trash.php');
    require($this->plugin_dir . '/inc/class-redirect.php');
    require($this->plugin_dir . '/inc/class-admin.php');
  }

  public static function activation_check() {
    if (is_multisite()) {
      return false;
    } else {
      return true;
    }
  }

  public function check_compatibility() {
    if ( ! self::compatibility() ) {
      if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
        add_action( 'admin_notices', array( $this, 'incompatible' ) );
      }
    }
  }

  public static function compatibility() {
    if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
      return false;
    } else if (!class_exists('acf')) {
      return false;
    } else if (is_multisite()) {
      return false;
    } else {
      return true;
    }
  }

  public function incompatible() {
    $class = 'notice notice-error';

    if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
      $message = __('Product Redirection for WooCommerce is not running, because <a href="plugin-install.php?s=WooCommerce&tab=search&type=term">WooCommerce</a> is not installed or activated.', 'product-redirection-for-woocommerce' );

      printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), __($message, 'product-redirection-for-woocommerce'));
    }

    if (!class_exists('acf')) {
      $message = __('Product Redirection for WooCommerce requires <a href="plugin-install.php?s=Advanced%20Custom%20Fields&tab=search&type=term">Advanced Custom Fields</a> to run! Please install Advanced Custom Fields in order to continue using our plugin.', 'product-redirection-for-woocommerce' );

      printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), __($message, 'product-redirection-for-woocommerce'));
    }
    
    if (is_multisite()) {
      $message = __('Product Redirection for WooCommerce is not running, because multisite is not supported. This is planned is on our <a href="https://trello.com/b/yCyf2WYs/free-product-redirection-for-woocommerce" target="_blank">Roadmap</a>.', 'product-redirection-for-woocommerce' );

      printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), __($message, 'product-redirection-for-woocommerce'));
    }
  }

}

$product_redirection_for_woocommerce = new PRODUCT_REDIRECTION_FOR_WOOCOMMMERCE();
$product_redirection_for_woocommerce->load();
