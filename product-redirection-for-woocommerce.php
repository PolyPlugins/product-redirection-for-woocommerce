<?php

/**
 * Plugin Name: Product Redirection for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/product-redirection-for-woocommerce/
 * Description: Instead of deleting products which is bad for SEO, redirect them to their parent category or a custom url.
 * Version: 1.1.4
 * Author: Poly Plugins
 * Author URI: https://www.polyplugins.com
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

if (!defined('ABSPATH')) exit;

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
          // Constants
          define('PRFW_VERSION', '1.1.4');
          define('PRFW_PLUGIN', __FILE__);
          define('PRFW_PLUGIN_BASENAME', plugin_basename(PRFW_PLUGIN));
          define('PRFW_PLUGIN_NAME', trim(dirname(PRFW_PLUGIN_BASENAME), '/'));
          define('PRFW_PLUGIN_DIR', untrailingslashit(dirname(PRFW_PLUGIN)));
          // Include acf check class
          if (!class_exists('ACF_CHECK_PRFW')) {
            require_once(PRFW_PLUGIN_DIR . '/inc/class-acf-check.php');
          }
          // Include enqueue class
          if (!class_exists('ENQUEUE_PRFW')) {
            require_once(PRFW_PLUGIN_DIR . '/inc/class-enqueue.php');
          }
          // Include trash handling class
          if (!class_exists('TRASH_PRFW')) {
            require_once(PRFW_PLUGIN_DIR . '/inc/class-trash.php');
          }
          // Include redirect class
          if (!class_exists('REDIRECT_PRFW')) {
            require_once(PRFW_PLUGIN_DIR . '/inc/class-redirect.php');
          }
          // Include admin class
          if (!class_exists('ADMIN_PRFW')) {
            require_once(PRFW_PLUGIN_DIR . '/inc/class-admin.php');
          }

          // Check if Advanced Custom Fields is active
          if (!class_exists('acf')) {
            // Hide Admin Settings
            add_filter('acf/settings/show_admin', '__return_false');
            // Include ACF since it's not found
            require_once(PRFW_PLUGIN_DIR . '/acf/acf.php');
            // Save current ACF fields
            add_filter('acf/settings/save_json', array('ACF_CHECK_PRFW', 'acf_json_save'));
            // Load plugin ACF fields as this is faster than storing in database and writing additional code for installation
            add_filter('acf/settings/load_json', array('ACF_CHECK_PRFW', 'acf_json_load'));
            add_filter('site_transient_update_plugins', array('ACF_CHECK_PRFW', 'disable_acf_update_notifications'), 11);
            add_action('admin_notices', array($this, 'acf_now_required'));
          } else {
            // ACF found, load fields
            add_filter('acf/settings/load_json', array('ACF_CHECK_PRFW', 'acf_json_load'));
          }

          // Init admin
          add_action('admin_init', array('ADMIN_PRFW', 'register_fields'));
          add_action('admin_menu', array('ADMIN_PRFW', 'register_settings_page'));
          // Display cta links on plugin page
          add_action('plugin_action_links_' . PRFW_PLUGIN_BASENAME, array($this, 'plugin_action_links_prfw'));
          add_action('plugin_row_meta', array($this, 'plugin_meta_links_prfw'), 10, 4);
          // Enqueue Scripts and styles
          if (get_option('trash_warning_prfw')) {
            add_action('admin_enqueue_scripts', array('ENQUEUE_PRFW', 'product_admin_enqueue'));
            if (get_option('trash_disable_prfw')) {
              add_action('wp_trash_post', array('TRASH_PRFW', 'trash_check'), 1);
            }
          }
          // Handle Redirects
          add_action('template_redirect', array('REDIRECT_PRFW', 'redirect'));
        } else {
          // WooCommerce not found
          add_action('admin_notices', array($this, 'woocommerce_not_active_notice'));
        }
      } else {
        add_action('admin_notices', array($this, 'multisite_not_supported_notice'));
      }
    }

    public static function plugin_action_links_prfw($links)
    {
      $settings_cta = '<a href="' . admin_url('/admin.php?page=product-redirection-for-woocommerce') . '" style="color: orange; font-weight: 700;">Settings</a>';
      $pro_cta = '<a href="https://www.polyplugins.com/product/product-redirection-for-woocommerce/" style="color: green; font-weight: 700;" target="_blank">Go Pro</a>';
      array_unshift($links, $settings_cta, $pro_cta);
      return $links;
    }

    public static function plugin_meta_links_prfw($links, $plugin_base_name)
    {
      if ($plugin_base_name === PRFW_PLUGIN_BASENAME) {
        $links[] = '<a href="https://trello.com/b/yCyf2WYs/free-product-redirection-for-woocommerce" style="color: purple; font-weight: 700;" target="_blank">Roadmap</a>';
        $links[] = '<a href="https://wordpress.org/support/plugin/product-redirection-for-woocommerce/" style="font-weight: 700;" target="_blank">Support</a>';
      }
      return $links;
    }

    public function acf_now_required()
    {
      $class = 'error notice';
      $message = 'To enhance securirty, Product Redirection for WooCommerce will REQUIRE Advanced Custom Fields in the next update! Please install Advanced Custom Fields in order to continue using our plugin.';
      printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);
    }

    public function multisite_not_supported_notice()
    {
      $class = 'error notice';
      $message = 'Product Redirection for WooCommerce is not running, because multisite is not supported. This is planned for our next release and is on our <a href="https://trello.com/b/yCyf2WYs/free-product-redirection-for-woocommerce" target="_blank">Roadmap</a>.';
      printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);
    }

    public function woocommerce_not_active_notice()
    {
      $class = 'error notice';
      $message = __('Product Redirection for WooCommerce is not running, because WooCommerce is not activated.', 'product-redirection-for-woocommerce');
      printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
    }
  }
}

$product_redirection_for_woocommerce = new PRODUCT_REDIRECTION_FOR_WOOCOMMMERCE_PP();
$product_redirection_for_woocommerce->load();
