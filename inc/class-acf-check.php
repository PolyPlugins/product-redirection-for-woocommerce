<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('ACF_CHECK_PRFW')) {

  class ACF_CHECK_PRFW
  {

    public function init() {
      if (!class_exists('acf')) {
        // Hide Admin Settings
        add_filter('acf/settings/show_admin', '__return_false');
        // Include ACF since it's not found
        require_once(PRFW_PLUGIN_DIR . '/acf/acf.php');
        // Save current ACF fields
        add_filter('acf/settings/save_json', array($this, 'acf_json_save'));
        // Load plugin ACF fields as this is faster than storing in database and writing additional code for installation
        add_filter('acf/settings/load_json', array($this, 'acf_json_load'));
        add_filter('site_transient_update_plugins', array($this, 'disable_acf_update_notifications'), 11);
        PRODUCT_REDIRECTION_FOR_WOOCOMMMERCE_PP::admin_notice('To enhance securirty, Product Redirection for WooCommerce will REQUIRE <a href="plugin-install.php?s=Advanced%20Custom%20Fields&tab=search&type=term">Advanced Custom Fields</a> in the next update! Please install Advanced Custom Fields in order to continue using our plugin.');
      } else {
        // ACF found, load fields
        add_filter('acf/settings/load_json', array($this, 'acf_json_load'));
      }
    }

    // Assign settings path
    public function acf_settings_path($path)
    {
      $path = PRFW_PLUGIN_DIR . '/acf/';
      return $path;
    }

    // Assign settings directory
    public function acf_settings_dir($path)
    {
      $dir = PRFW_PLUGIN_DIR . '/acf/';
      return $dir;
    }

    // Create a local json save
    public function acf_json_save($path)
    {
      $path = PRFW_PLUGIN_DIR . '/acf/json/save/';
      return $path;
    }

    // Prevent default Wordpress Update Notices
    public function disable_acf_update_notifications($value)
    {
      unset($value->response[PRFW_PLUGIN_DIR . '/acf/acf.php']);
      return $value;
    }

    // Load local json
    public function acf_json_load($paths)
    {
      $paths[] = PRFW_PLUGIN_DIR . '/acf/json/load/';
      return $paths;
    }

  }

  $acf_check = new ACF_CHECK_PRFW;
  $acf_check->init();

} else {
  PRODUCT_REDIRECTION_FOR_WOOCOMMMERCE_PP::admin_notice("Product Redirection for WooCommerce ACF handling is not running because the 'ACF_CHECK_PRFW' class already exists. A conflict may be happening with another plugin. <a href='https://wordpress.org/support/plugin/product-redirection-for-woocommerce/' target='_blank'>Get Support</a>");
}