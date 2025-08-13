<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('TRASH_PRFW')) {

  class TRASH_PRFW
  {

    public function init() {
      if (get_option('trash_warning_prfw') && get_option('trash_disable_prfw')) {
        add_action('wp_trash_post', array($this, 'trash_check'), 1);
      }
    }

    // Trash prevention handling
    public function trash_check($post_id)
    {
      $screen = get_current_screen();
      if ($screen->post_type == 'product') {
        wp_redirect(admin_url('edit.php?post_type=product'));
        exit;
      }
    }

  }

  $trash = new TRASH_PRFW;
  $trash->init();

} else {
  PRODUCT_REDIRECTION_FOR_WOOCOMMMERCE_PP::admin_notice("Product Redirection for WooCommerce Trash handling is not running because the 'TRASH_PRFW' class already exists. A conflict may be happening with another plugin. <a href='https://wordpress.org/support/plugin/product-redirection-for-woocommerce/' target='_blank'>Get Support</a>");
}
