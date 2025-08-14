<?php

namespace PolyPlugins\Product_Redirection_For_WooCommerce\Backend;

use PolyPlugins\Product_Redirection_For_WooCommerce\Utils;

class Notices {

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
  
  /**
   * Init
   *
   * @return void
   */
  public function init() {
    add_action('admin_notices', array($this, 'maybe_show_notice'));
    add_action('wp_ajax_prfw_dismiss_notice_nonce', array($this, 'dismiss_notice'));
  }
  
  /**
   * Maybe show notice
   *
   * @return void
   */
  public function maybe_show_notice() {
    $is_dismissed = get_option('prfw_notice_dismissed_polyplugins');

    if ($is_dismissed) {
      return;
    }
    
    $screen = get_current_screen();

    if ($this->version == '1.2.0') {
      $this->notice_120();
    }
  }
  
  /**
   * Notice for v1.2.0
   *
   * @return void
   */
  public function notice_120() {
    ?>
    <div class="notice notice-success is-dismissible product-redirection-for-woocommerce">
      <p><?php echo esc_html__("Product Redirection for WooCommerce has been updated. We've added the ability to quick and bulk edit products per request. We've also updated translations and migrated to PSR-4 standards that we use across a majority of our plugins.", 'product-redirection-for-woocommerce'); ?></p>
      <p><?php echo esc_html__("Speaking of plugins, we have a new free plugin if you're interested called ", 'product-redirection-for-woocommerce'); ?><a href="https://wordpress.org/plugins/maintenance-mode-made-easy/" target="_blank"><?php echo esc_html__("Maintenance Mode Made Easy", 'product-redirection-for-woocommerce'); ?></a>. <?php echo esc_html__("We've discovered a lot of the free maintenance plugins don't handle stopping WooCommerce orders during downtime and many fail to alert search engines that you're temporarily unavailable, leaving your site vulnerable to SEO issues.", 'product-redirection-for-woocommerce'); ?></p>
      <p><?php echo esc_html__("We know it's been a minute since the last Product Redirection for WooCommerce update, so we'd love to hear what you think.", 'product-redirection-for-woocommerce'); ?></p>
      <p><a href="https://wordpress.org/support/plugin/product-redirection-for-woocommerce/" target="_blank"><?php echo esc_html__('Feature Request', 'product-redirection-for-woocommerce'); ?></a> | <a href="https://wordpress.org/plugins/product-redirection-for-woocommerce/" target="_blank"><?php echo esc_html__('Leave a Review', 'product-redirection-for-woocommerce'); ?></a></p>
    </div>
    <?php
  }
  
  /**
   * Dismiss notice
   *
   * @return void
   */
  public function dismiss_notice() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'prfw_dismiss_notice_nonce')) {
      Utils::send_error('Invalid session', 403);
    }

    if (!current_user_can('manage_options')) {
      Utils::send_error('Unauthorized', 401);
    }

    update_option('prfw_notice_dismissed_polyplugins', true);
  }

}
