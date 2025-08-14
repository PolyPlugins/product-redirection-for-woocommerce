<?php

namespace PolyPlugins\Product_Redirection_For_WooCommerce;

class Updater {

  /**
	 * Full path and filename of plugin.
	 *
	 * @var string $version Full path and filename of plugin.
	 */
  private $plugin;

	/**
	 * The version of this plugin.
	 *
	 * @var   string $version The current version of this plugin.
	 */
	private $version;

  /**
   * The plugin directory.
   *
   * @var string $plugin_dir The plugin directory.
   */
	private $plugin_dir;
  
  /**
   * __construct
   *
   * @return void
   */
  public function __construct($plugin, $version, $plugin_dir) {
    $this->plugin     = $plugin;
    $this->version    = $version;
    $this->plugin_dir = $plugin_dir;
  }

  public function init() {
    add_action('wp', array($this, 'maybe_update'));
  }

  public function maybe_update() {
    $stored_version = get_option('prfw_version_polyplugins');

    if (!$stored_version) {
      $stored_version = $this->version;

      update_option('prfw_version_polyplugins', $this->version);

      return;
    }

    if (version_compare($stored_version, '1.2.0', '<')) {
      $stored_version = '1.2.0';

      $this->update_to_120();

      update_option('prfw_version_polyplugins', $stored_version);
    }
  }

  private function update_to_120() {
    update_option('prfw_notice_dismissed_polyplugins', false);
  }

}