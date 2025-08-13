<?php

namespace PolyPlugins;

if (!defined('ABSPATH')) exit;

class ENQUEUE extends PRODUCT_REDIRECTION_FOR_WOOCOMMMERCE
{

  public function __construct() {
		parent::__construct();
	}

  public function init() {
    if (get_option('trash_warning_prfw')) {
      add_action('admin_enqueue_scripts', array($this, 'product_admin_enqueue'));
    }
  }

  // Enqueue scripts and styles
  public function product_admin_enqueue()
  {
    $screen = get_current_screen();
    if ($screen->post_type == 'product' && is_admin()) {
      $status = get_query_var('post_status');
      $trash_disable_prfw = (get_option('trash_disable_prfw')) ? false : true;
      wp_enqueue_script('product-admin-prfw', plugins_url('/js/product-admin.js', $this->plugin), array('jquery'), filemtime($this->plugin_dir . '/js/product-admin.js'), true);
      wp_localize_script('product-admin-prfw', 'LOCALIZED_PRFW', array('siteurl' => get_option('siteurl'), 'trashdisable' => $trash_disable_prfw, 'poststatus' => $status));
      wp_enqueue_style('product-admin-prfw', plugins_url('/css/product-admin.css', $this->plugin), array(), filemtime($this->plugin_dir . '/css/product-admin.css'), false);
      wp_enqueue_script('sweet-alert-2', plugins_url('/js/sweetalert2.min.js', $this->plugin), array('jquery'), filemtime($this->plugin_dir . '/js/sweetalert2.min.js'), false);
      wp_enqueue_style('sweet-alert-2', plugins_url('/css/sweetalert2.min.css', $this->plugin), array(), filemtime($this->plugin_dir . '/css/sweetalert2.min.css'), false);
    }
  }

}

$enqueue = new ENQUEUE;
$enqueue->init();
