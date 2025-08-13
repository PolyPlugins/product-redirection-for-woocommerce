<?php

/**
 * Plugin Name: Product Redirection for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/product-redirection-for-woocommerce/
 * Description: Instead of deleting products which is bad for SEO, redirect them to their parent category or a custom url.
 * Version: 1.1.6
 * Author: Poly Plugins
 * Author URI: https://www.polyplugins.com
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

if (!defined('ABSPATH')) exit;

/* To-Do
 * Add section to pull pro classes
 * Add to messages a way to get to support based on error
 */

register_activation_hook(__FILE__, array('PRODUCT_REDIRECTION_FOR_WOOCOMMMERCE_PP', 'activation'));

if (!class_exists('PRODUCT_REDIRECTION_FOR_WOOCOMMMERCE_PP')) {

  class PRODUCT_REDIRECTION_FOR_WOOCOMMMERCE_PP
  {

    public static function activation()
    {
      $oos_notice = __('This product is out of stock, you can find similar products in our', 'product-redirection-for-woocommerce');
      add_option('trash_warning_prfw', 1);
      add_option('trash_disable_prfw', 1);
      add_option('stock_notice_prfw', $oos_notice);
    }

    public function load()
    {
      if (!is_multisite()) {
        // Check if WooCommerce is active
        if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
          // Flag if constants are in use
          defined('PRFW_VERSION') && $defined = true;
          defined('PRFW_PLUGIN') && $defined = true;
          defined('PRFW_PLUGIN_BASENAME') && $defined = true;
          defined('PRFW_PLUGIN_NAME') && $defined = true;
          defined('PRFW_PLUGIN_DIR') && $defined = true;
          if (empty($defined)) {
            // Define
            define('PRFW_VERSION', '1.1.6');
            define('PRFW_PLUGIN', __FILE__);
            define('PRFW_PLUGIN_BASENAME', plugin_basename(PRFW_PLUGIN));
            define('PRFW_PLUGIN_NAME', trim(dirname(PRFW_PLUGIN_BASENAME), '/'));
            define('PRFW_PLUGIN_DIR', untrailingslashit(dirname(PRFW_PLUGIN)));
            // Get classes
            require_once(PRFW_PLUGIN_DIR . '/inc/class-acf-check.php');
            require_once(PRFW_PLUGIN_DIR . '/inc/class-enqueue.php');
            require_once(PRFW_PLUGIN_DIR . '/inc/class-trash.php');
            require_once(PRFW_PLUGIN_DIR . '/inc/class-redirect.php');
            require_once(PRFW_PLUGIN_DIR . '/inc/class-admin.php');
          } else {
            $this->admin_notice('Product Redirection for WooCommerce is not running because it has detected a conflict, we believe one of your installed plugins may be using a constant we have defined. <a href="https://wordpress.org/support/plugin/product-redirection-for-woocommerce/" target="_blank">Get Support</a>');
          }
        } else {
          // WooCommerce not found
          $this->admin_notice('Product Redirection for WooCommerce is not running, because WooCommerce is not activated.');
        }
      } else {
        $this->admin_notice('Product Redirection for WooCommerce is not running, because multisite is not supported. This is planned is on our <a href="https://trello.com/b/yCyf2WYs/free-product-redirection-for-woocommerce" target="_blank">Roadmap</a>.');
      }
    }

    public static function admin_notice($message, $type = 'error') {
      if (is_admin()) {
        $type = ($type == 'error') ? 'notice-error' : 'notice-success';
        $class = 'notice ' . $type;
        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), __($message, 'product-redirection-for-woocommerce'));
      }
    }

  }

  $product_redirection_for_woocommerce = new PRODUCT_REDIRECTION_FOR_WOOCOMMMERCE_PP();
  $product_redirection_for_woocommerce->load();

} else {

  add_action('admin_notices', function() {
    $class = 'notice notice-error';
    $message = __("Product Redirection for WooCommerce is not running because the 'PRODUCT_REDIRECTION_FOR_WOOCOMMMERCE_PP' class already exists. A conflict may be happening with another plugin. <a href='https://wordpress.org/support/plugin/product-redirection-for-woocommerce/' target='_blank'>Get Support</a>", 'product-redirection-for-woocommerce');

    printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
  });

}

