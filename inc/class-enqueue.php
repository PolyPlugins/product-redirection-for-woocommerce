<?php

if (!defined('ABSPATH')) exit;

class ENQUEUE_PRFW
{
  // Enqueue scripts and styles
  public function product_admin_enqueue()
  {
    $screen = get_current_screen();
    if ($screen->post_type == 'product' && is_admin()) {
      $status = get_query_var('post_status');
      $trash_disable_prfw = (get_option('trash_disable_prfw')) ? false : true;
      wp_enqueue_script('product-admin-prfw', plugins_url('/js/product-admin.js', PRFW_PLUGIN), array('jquery'), filemtime(PRFW_PLUGIN_DIR . '/js/product-admin.js'), true);
      wp_localize_script('product-admin-prfw', 'LOCALIZED_PRFW', array('siteurl' => get_option('siteurl'), 'trashdisable' => $trash_disable_prfw, 'poststatus' => $status));
      wp_enqueue_style('product-admin-prfw', plugins_url('/css/product-admin.css', PRFW_PLUGIN), array(), filemtime(PRFW_PLUGIN_DIR . '/css/product-admin.css'), false);
      wp_enqueue_script('sweet-alert-2', plugins_url('/js/sweetalert2.min.js', PRFW_PLUGIN), array('jquery'), filemtime(PRFW_PLUGIN_DIR . '/js/sweetalert2.min.js'), false);
      wp_enqueue_style('sweet-alert-2', plugins_url('/css/sweetalert2.min.css', PRFW_PLUGIN), array(), filemtime(PRFW_PLUGIN_DIR . '/css/sweetalert2.min.css'), false);
    }
  }
}
