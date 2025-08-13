<?php

use PolyPlugins\PRODUCT_REDIRECTION_FOR_WOOCOMMMERCE;

if (!defined('ABSPATH')) exit;

class TRASH extends PRODUCT_REDIRECTION_FOR_WOOCOMMMERCE
{

  public function __construct() {
		parent::__construct();
	}

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

$trash = new TRASH;
$trash->init();
