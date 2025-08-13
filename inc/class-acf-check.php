<?php

use PolyPlugins\PRODUCT_REDIRECTION_FOR_WOOCOMMMERCE;

if (!defined('ABSPATH')) exit;

class ACF_CHECK extends PRODUCT_REDIRECTION_FOR_WOOCOMMMERCE
{

  public function __construct() {
		parent::__construct();
	}

  public function init() {
    add_filter('acf/settings/load_json', array($this, 'acf_json_load'));
  }

  // Load local json
  public function acf_json_load($paths)
  {
    $paths[] = $this->plugin_dir . '/acf/json/load/';
    return $paths;
  }

}

$acf_check = new ACF_CHECK;
$acf_check->init();
