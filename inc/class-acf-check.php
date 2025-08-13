<?php

if (!defined('ABSPATH')) exit;

class ACF_CHECK_PRFW
{
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
