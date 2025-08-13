<?php

if (!defined('ABSPATH')) exit;

class REDIRECT_PRFW
{
  // Handle redirects
  public function redirect()
  {
    if (is_product()) {
      $setting = get_field("enable_prfw");
      if ($setting == "Redirect") {
        $redirect_type = get_field("redirect_type_prfw");
        $redirect_to = get_field("redirect_to_prfw");
        if ($redirect_to == "This Product's Category") {
          $cat_exists = false;
          $get_categories = get_the_terms(get_the_ID(), 'product_cat');
          // Grab the parent category
          foreach ($get_categories as $category) {
            if ($category->parent == 0 && !$cat_exists) {
              $cat = $category->term_id;
              $cat_exists = true;
            }
          }
          // Parent category isn't selected, let's choose the first in the stack
          if (!$cat_exists) {
            $cat = $get_categories[0]->term_id;
          }

          $cat_url = get_term_link($cat, 'product_cat');
          wp_redirect($cat_url, (int) $redirect_type, PRFW_PLUGIN_NAME);
          exit;
        } else {
          $redirect_url = get_field("redirect_url_prfw");
          wp_redirect($redirect_url, (int) $redirect_type, PRFW_PLUGIN_NAME);
          exit;
        }
      }
    }
  }
}
