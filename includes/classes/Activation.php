<?php

namespace PolyPlugins\Product_Redirection_For_WooCommerce;

if (!defined('ABSPATH')) exit;

class Activation {
  
  /**
   * init
   *
   * @return void
   */
  public static function init() {
    self::maybe_activate();
  }
  
  /**
   * Maybe activate
   *
   * @return void
   */
  private static function maybe_activate() {
    if (Utils::activation_check()) {
      $oos_notice = __('This product is out of stock, you can find similar products in our', 'product-redirection-for-woocommerce');
      
      add_option('trash_warning_prfw', 1);
      add_option('trash_disable_prfw', 1);
      add_option('stock_notice_prfw', wp_kses_post($oos_notice));
    } else {
      deactivate_plugins(plugin_basename( __FILE__ ));

      $notice = __('Product Redirection for WooCommerce failed to activate, because multisite is not currently supported. This is on our Roadmap.', 'product-redirection-for-woocommerce' );
      wp_die(wp_kses_post($notice));
    }
  }

}