<?php

namespace PolyPlugins\Product_Redirection_For_WooCommerce\Backend;

class Admin {

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
    add_action('admin_init', array($this, 'register_fields'));
    add_action('admin_menu', array($this, 'register_settings_page'));
    // Display cta links on plugin page
    add_action('plugin_action_links_' . plugin_basename($this->plugin), array($this, 'plugin_action_links_prfw'));
    add_action('plugin_row_meta', array($this, 'plugin_meta_links_prfw'), 10, 4);
  }
    
  /**
   * Register settings page
   *
   * @return void
   */
  public function register_settings_page() {
    add_submenu_page('woocommerce', __('Product Redirection for WooCommerce Settings', 'product-redirection-for-woocommerce'), __('Redirection', 'product-redirection-for-woocommerce'), 'administrator', 'product-redirection-for-woocommerce', array($this, 'settings_page'));
  }
  
  /**
   * Settings page
   *
   * @return void
   */
  public function settings_page() {
    // Set tab
    $tab = isset($_GET["tab"]) ? sanitize_key($_GET["tab"]) : 'general'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    ?>
    <div class="wrap">
      <h2><?php esc_html_e('Product Redirection for WooCommerce Settings', 'product-redirection-for-woocommerce'); ?></h2>
      <?php settings_errors(); ?>
      <h2 class="nav-tab-wrapper">
        <a href="?page=product-redirection-for-woocommerce&tab=general" class="nav-tab<?php echo ($tab == 'general') ? ' nav-tab-active' : ''; ?>"><?php esc_html_e('General', 'product-redirection-for-woocommerce'); ?></a>
        <a href="?page=product-redirection-for-woocommerce&tab=out-of-stock" class="nav-tab<?php echo ($tab == 'out-of-stock') ? ' nav-tab-active' : '' ?>"><?php esc_html_e('Out of Stock', 'product-redirection-for-woocommerce'); ?></a>
        <a href="https://wordpress.org/support/plugin/product-redirection-for-woocommerce/" class="nav-tab" target="_blank"><?php esc_html_e('Support', 'product-redirection-for-woocommerce'); ?></a>
        <a href="https://www.polyplugins.com/product/product-redirection-for-woocommerce/" class="nav-tab" target="_blank"><?php esc_html_e('Go Pro', 'product-redirection-for-woocommerce'); ?></a>
      </h2>
      <form method="post" action="<?php echo esc_url(add_query_arg('tab', $tab, admin_url('options.php'))); ?>">
        <?php
        if ($tab == "general") {
          // General Settings
          settings_fields('prfw-general-settings');
          do_settings_sections('product-redirection-for-woocommerce');
          submit_button();
        } elseif ($tab == "out-of-stock") {
          // Out of Stock Settings
          settings_fields('prfw-oos-settings');
          do_settings_sections('product-redirection-for-woocommerce');
          ?>
          <a href="https://www.polyplugins.com/product/product-redirection-for-woocommerce/" class="button button-primary" target="_blank">
            <?php esc_html_e("Go Pro", "product-redirection-for-woocommerce"); ?>
          </a>
          <?php
        } else {
          esc_html_e('This is not a valid tab.', 'product-redirection-for-woocommerce');
        }
        ?>
      </form>
    </div>
  <?php
  }
  
  /**
   * Register fields
   *
   * @return void
   */
  public function register_fields() {
    // Set tab
    $tab = isset($_GET["tab"]) ? sanitize_key($_GET["tab"]) : 'general'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    // Define args for saving the settings, using this for sanitize callback.
    $checked = array(
      'type' => 'boolean',
      'sanitize_callback' => 'sanitize_text_field',
      'default' => 1,
    );

    $unchecked = array(
      'type' => 'boolean',
      'sanitize_callback' => 'sanitize_text_field',
      'default' => 0,
    );

    $oos_notice = array(
      'type' => 'string',
      'sanitize_callback' => 'sanitize_text_field',
      'default' => __('This product is out of stock, you can find similar products in our', 'product-redirection-for-woocommerce'),
    );
    // Handle tabs
    if ($tab == "general") {
      // Add Section
      add_settings_section('prfw-general-settings', __('General Settings', 'product-redirection-for-woocommerce'), null, 'product-redirection-for-woocommerce');
      // Display General Settings
      add_settings_field('trash_warning_prfw', __('Popup', 'product-redirection-for-woocommerce'), array($this, 'trash_warning_setting'), 'product-redirection-for-woocommerce', 'prfw-general-settings');
      add_settings_field('trash_disable_prfw', __('Disable Trash / Deletion', 'product-redirection-for-woocommerce'), array($this, 'trash_disable_setting'), 'product-redirection-for-woocommerce', 'prfw-general-settings');
      // Save General Settings
      register_setting('prfw-general-settings', 'trash_warning_prfw', $checked);
      register_setting('prfw-general-settings', 'trash_disable_prfw', $checked);
    } elseif ($tab == "out-of-stock") {
      // Add Section
      add_settings_section('prfw-oos-settings', __('Out of Stock Settings', 'product-redirection-for-woocommerce'), null, 'product-redirection-for-woocommerce');
      // Display Out of Stock Settings
      add_settings_field('stock_notice_toggle_prfw', __('Out of Stock', 'product-redirection-for-woocommerce'), array($this, 'stock_toggle_setting'), 'product-redirection-for-woocommerce', 'prfw-oos-settings');
      add_settings_field('stock_notice_prfw', __('Out of Stock Notice', 'product-redirection-for-woocommerce'), array($this, 'stock_notice_setting'), 'product-redirection-for-woocommerce', 'prfw-oos-settings');
      add_settings_field('stock_recommendations_toggle_prfw', __('Out of Stock Recommendations', 'product-redirection-for-woocommerce'), array($this, 'stock_recommendations_toggle_setting'), 'product-redirection-for-woocommerce', 'prfw-oos-settings');
      // Save Out of Stock Settings
      register_setting('prfw-oos-settings', 'stock_notice_toggle_prfw', $checked);
      register_setting('prfw-oos-settings', 'stock_notice_prfw', $oos_notice);
      register_setting('prfw-oos-settings', 'stock_recommendations_toggle_prfw', $unchecked);
    }
  }
  
  /**
   * Trash warning setting
   *
   * @return void
   */
  public function trash_warning_setting(){
    ?>
    <input type="checkbox" name="trash_warning_prfw" id="trash_warning_prfw" value="1" <?php checked(1, get_option('trash_warning_prfw'), true); ?> />
    <?php esc_html_e('Enable the popup that displays when you click trash or delete on a product?', 'product-redirection-for-woocommerce'); ?>
    <?php
  }
  
  /**
   * Trash disable setting
   *
   * @return void
   */
  public function trash_disable_setting() {
    ?>
    <input type="checkbox" name="trash_disable_prfw" id="trash_disable_prfw" value="1" <?php checked(1, get_option('trash_disable_prfw'), true); ?> />
    <?php esc_html_e('Disable deleting products?', 'product-redirection-for-woocommerce'); ?>
    <?php
  }
  
  /**
   * Stock toggle setting
   *
   * @return void
   */
  public function stock_toggle_setting() {
    ?>
    <input type="checkbox" name="stock_notice_toggle_prfw" id="stock_notice_toggle_prfw" value="1" <?php checked(1, get_option('stock_notice_toggle_prfw'), true); ?> disabled />
    <?php esc_html_e('This will enable handling for out of stock products.', 'product-redirection-for-woocommerce'); ?>
    <?php
  }
  
  /**
   * Stock recommendations toggle setting
   *
   * @return void
   */
  public function stock_recommendations_toggle_setting() {
    ?>
    <input type="checkbox" name="stock_recommendations_toggle_prfw" id="stock_recommendations_toggle_prfw" value="1" <?php checked(1, get_option('stock_recommendations_toggle_prfw'), true); ?> disabled />
    <?php esc_html_e('This uses WooCommerce Product Shortcode to display products from the parent category for out of stock products.', 'product-redirection-for-woocommerce'); ?>
    <?php
  }
  
  /**
   * Stock notice setting
   *
   * @return void
   */
  public function stock_notice_setting() {
    $option = get_option('stock_notice_prfw');
    ?>
    <textarea name="stock_notice_prfw" id="stock_notice_prfw" rows="7" cols="50" disabled><?php esc_html($option); ?></textarea>
    <p><?php esc_html_e('This notice will display for out of stock products. The end is completed by PRFW.', 'product-redirection-for-woocommerce'); ?></p>
    <p><?php esc_html_e('Example: This product is out of stock, you can find similar products in our Glasses category', 'product-redirection-for-woocommerce'); ?>.</p>
    <?php
  }
  
  /**
   * Plugin action links
   *
   * @param  mixed $links
   * @return void
   */
  public function plugin_action_links_prfw($links) {
    $settings_cta = '<a href="' . admin_url('/admin.php?page=product-redirection-for-woocommerce') . '" style="color: orange; font-weight: 700;">' . __("Settings", "product-redirection-for-woocommerce") . '</a>';
    $pro_cta = '<a href="https://www.polyplugins.com/product/product-redirection-for-woocommerce/" style="color: green; font-weight: 700;" target="_blank">' . __("Go Pro", "product-redirection-for-woocommerce") . '</a>';
    array_unshift($links, $settings_cta, $pro_cta);
    return $links;
  }
  
  /**
   * Plugin meta links
   *
   * @param  mixed $links
   * @param  mixed $plugin_base_name
   * @return void
   */
  public function plugin_meta_links_prfw($links, $plugin_base_name) {
    if ($plugin_base_name === plugin_basename($this->plugin)) {
      $links[] = '<a href="https://github.com/users/PolyPlugins/projects/2/" style="color: purple; font-weight: 700;" target="_blank">' . __("Roadmap", "product-redirection-for-woocommerce") . '</a>';
      $links[] = '<a href="https://wordpress.org/support/plugin/product-redirection-for-woocommerce/" style="font-weight: 700;" target="_blank">' . __("Support", "product-redirection-for-woocommerce") . '</a>';
    }
    return $links;
  }

}