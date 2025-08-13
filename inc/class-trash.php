<?php

if (!defined('ABSPATH')) exit;

class TRASH_PRFW
{
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
