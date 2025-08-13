<?php

namespace PolyPlugins;

if (!defined('ABSPATH')) exit;

class ADMIN extends PRODUCT_REDIRECTION_FOR_WOOCOMMMERCE
{

  public function __construct() {
		parent::__construct();
	}

  public function init() {
    add_action('admin_init', array($this, 'register_fields'));
    add_action('admin_menu', array($this, 'register_settings_page'));
    // Display cta links on plugin page
    add_action('plugin_action_links_' . $this->plugin_basename, array($this, 'plugin_action_links_prfw'));
    add_action('plugin_row_meta', array($this, 'plugin_meta_links_prfw'), 10, 4);
  }

  // Register settings page
  public function register_settings_page()
  {
    add_submenu_page('woocommerce', 'Product Redirection for WooCommerce Settings', 'Redirection', 'administrator', 'product-redirection-for-woocommerce', array($this, 'settings_page'));
  }

  // Create the settings page using Settings API
  public function settings_page()
  {
    // Set tab
    $tab = (isset($_GET["tab"])) ? sanitize_key($_GET["tab"]) : 'general';
?>
    <div class="wrap">
      <h2>Product Redirection for WooCommerce Settings</h2>
      <?php settings_errors(); ?>
      <h2 class="nav-tab-wrapper">
        <a href="?page=product-redirection-for-woocommerce&tab=general" class="nav-tab<?php echo ($tab == 'general') ? ' nav-tab-active' : ''; ?>">General</a>
        <a href="?page=product-redirection-for-woocommerce&tab=out-of-stock" class="nav-tab<?php echo ($tab == 'out-of-stock') ? ' nav-tab-active' : '' ?>">Out of Stock</a>
        <a href="https://wordpress.org/support/plugin/product-redirection-for-woocommerce/" class="nav-tab" target="_blank">Support</a>
        <a href="https://www.polyplugins.com/product/product-redirection-for-woocommerce/" class="nav-tab" target="_blank">Go Pro</a>
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
          echo '<a href="https://www.polyplugins.com/product/product-redirection-for-woocommerce/" class="button button-primary" target="_blank">Go Pro</a>';
        } else {
          _e('This is not a valid tab.', 'product-redirection-for-woocommerce');
        }
        ?>
      </form>
    </div>
  <?php
  }

  // Register fields
  public function register_fields()
  {
    // Set tab
    $tab = (isset($_GET["tab"])) ? sanitize_key($_GET["tab"]) : 'general';
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
      'default' => 'This product is out of stock, you can find similar products in our',
    );
    // Handle tabs
    if ($tab == "general") {
      // Add Section
      add_settings_section('prfw-general-settings', 'General Settings', null, 'product-redirection-for-woocommerce');
      // Display General Settings
      add_settings_field('trash_warning_prfw', 'Popup', array($this, 'trash_warning_setting'), 'product-redirection-for-woocommerce', 'prfw-general-settings');
      add_settings_field('trash_disable_prfw', 'Disable Trash / Deletion', array($this, 'trash_disable_setting'), 'product-redirection-for-woocommerce', 'prfw-general-settings');
      // Save General Settings
      register_setting('prfw-general-settings', 'trash_warning_prfw', $checked);
      register_setting('prfw-general-settings', 'trash_disable_prfw', $checked);
    } elseif ($tab == "out-of-stock") {
      // Add Section
      add_settings_section('prfw-oos-settings', 'Out of Stock Settings', null, 'product-redirection-for-woocommerce');
      // Display Out of Stock Settings
      add_settings_field('stock_notice_toggle_prfw', 'Out of Stock', array($this, 'stock_toggle_setting'), 'product-redirection-for-woocommerce', 'prfw-oos-settings');
      add_settings_field('stock_notice_prfw', 'Out of Stock Notice', array($this, 'stock_notice_setting'), 'product-redirection-for-woocommerce', 'prfw-oos-settings');
      add_settings_field('stock_recommendations_toggle_prfw', 'Out of Stock Recommendations', array($this, 'stock_recommendations_toggle_setting'), 'product-redirection-for-woocommerce', 'prfw-oos-settings');
      // Save Out of Stock Settings
      register_setting('prfw-oos-settings', 'stock_notice_toggle_prfw', $checked);
      register_setting('prfw-oos-settings', 'stock_notice_prfw', $oos_notice);
      register_setting('prfw-oos-settings', 'stock_recommendations_toggle_prfw', $unchecked);
    }
  }

  // Create options on settings page
  public function trash_warning_setting()
  {
  ?>
    <input type="checkbox" name="trash_warning_prfw" id="trash_warning_prfw" value="1" <?php checked(1, get_option('trash_warning_prfw'), true); ?> />
    Enable the popup that displays when you click trash or delete on a product?
  <?php
  }

  public function trash_disable_setting()
  {
  ?>
    <input type="checkbox" name="trash_disable_prfw" id="trash_disable_prfw" value="1" <?php checked(1, get_option('trash_disable_prfw'), true); ?> />
    Disable deleting products?
  <?php
  }

  public function stock_toggle_setting()
  {
  ?>
    <input type="checkbox" name="stock_notice_toggle_prfw" id="stock_notice_toggle_prfw" value="1" <?php checked(1, get_option('stock_notice_toggle_prfw'), true); ?> disabled />
    This will enable handling for out of stock products.
  <?php
  }

  public function stock_recommendations_toggle_setting()
  {
  ?>
    <input type="checkbox" name="stock_recommendations_toggle_prfw" id="stock_recommendations_toggle_prfw" value="1" <?php checked(1, get_option('stock_recommendations_toggle_prfw'), true); ?> disabled />
    This uses WooCommerce Product Shortcode to display products from the parent category for out of stock products.
  <?php
  }

  public function stock_notice_setting()
  {
  ?>
    <textarea name="stock_notice_prfw" id="stock_notice_prfw" rows="7" cols="50" disabled><?php echo get_option('stock_notice_prfw'); ?></textarea>
    <p>This notice will display for out of stock products. The end is completed by PRFW.</p>
    <p>Example: This product is out of stock, you can find similar products in our Glasses category.</p>
<?php
  }

  public function plugin_action_links_prfw($links)
  {
    $settings_cta = '<a href="' . admin_url('/admin.php?page=product-redirection-for-woocommerce') . '" style="color: orange; font-weight: 700;">Settings</a>';
    $pro_cta = '<a href="https://www.polyplugins.com/product/product-redirection-for-woocommerce/" style="color: green; font-weight: 700;" target="_blank">Go Pro</a>';
    array_unshift($links, $settings_cta, $pro_cta);
    return $links;
  }

  public function plugin_meta_links_prfw($links, $plugin_base_name)
  {
    if ($plugin_base_name === $this->plugin_basename) {
      $links[] = '<a href="https://trello.com/b/yCyf2WYs/free-product-redirection-for-woocommerce" style="color: purple; font-weight: 700;" target="_blank">Roadmap</a>';
      $links[] = '<a href="https://wordpress.org/support/plugin/product-redirection-for-woocommerce/" style="font-weight: 700;" target="_blank">Support</a>';
    }
    return $links;
  }

}

$admin = new ADMIN;
$admin->init();
