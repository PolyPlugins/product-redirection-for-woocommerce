<?php

namespace PolyPlugins\Product_Redirection_For_WooCommerce\Backend;

class Edit {

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
    add_filter('manage_edit-product_columns', array($this, 'add_columns'));
    add_action('manage_product_posts_custom_column', array($this, 'render_columns'), 10, 2);
    add_action('quick_edit_custom_box', array($this, 'add_quick_edit_fields'), 10, 2);
    add_action('bulk_edit_custom_box', array($this, 'add_bulk_edit_fields'), 10, 2);
    add_action('save_post_product', array($this, 'save_quick_edit'), 10, 2);
    add_action('save_post_product', array($this, 'save_bulk_edit'), 10, 2);
  }
  
  /**
   * Add columns
   *
   * @param  mixed $columns
   * @return void
   */
  public function add_columns($columns) {
    $columns['enable_prfw']        = 'PRFW';
    $columns['redirect_type_prfw'] = 'Redirect';
    $columns['redirect_to_prfw']   = 'Redirect To';
    $columns['redirect_url_prfw']  = 'Redirect URL';

    return $columns;
  }
  
  /**
   * Render columns
   *
   * @param  mixed $column
   * @param  mixed $post_id
   * @return void
   */
  public function render_columns($column, $post_id) {
    $enable_prfw        = get_field('enable_prfw', $post_id);
    $redirect_type_prfw = get_field('redirect_type_prfw', $post_id);
    $redirect_to_prfw   = get_field('redirect_to_prfw', $post_id);
    $redirect_url_prfw  = get_field('redirect_url_prfw', $post_id);

    switch ($column) {
      case 'enable_prfw':
        $enable_prfw = $enable_prfw ?: 'Disabled';
        echo esc_html($enable_prfw);
        break;

      case 'redirect_type_prfw':
        $redirect_type_prfw = $redirect_type_prfw ?: '301';
        echo esc_html($redirect_type_prfw);
        break;

      case 'redirect_to_prfw':
        $redirect_to_prfw = $redirect_to_prfw ?: "This Product's Category";
        echo esc_html($redirect_to_prfw);
        break;

      case 'redirect_url_prfw':
        $redirect_url_prfw = $redirect_url_prfw ?: '';
        echo esc_html($redirect_url_prfw);
        break;
    }
  }
  
  /**
   * Quick edit fields
   *
   * @param  mixed $column_name
   * @param  mixed $post_type
   * @return void
   */
  public function add_quick_edit_fields($column_name, $post_type) {
    switch( $column_name ) {
      case 'enable_prfw': {
        ?>
        <fieldset class="inline-edit-col-left">
          <div class="inline-edit-col">
            <label>
              <span class="title"><?php esc_html_e('PRFW Type', 'product-redirection-for-woocommerce'); ?></span>
              <span class="input-text-wrap">
                <select name="enable_prfw">
                  <option value="Disabled"><?php esc_html_e('Disabled', 'product-redirection-for-woocommerce'); ?></option>
                  <option value="Redirect"><?php esc_html_e('Redirect', 'product-redirection-for-woocommerce'); ?></option>
                </select>
              </span>
            </label>
          </div>
        <?php
        break;
      }

      case 'redirect_type_prfw': {
        ?>
        <div class="inline-edit-col">
          <label>
            <span class="title"><?php esc_html_e('Redirect Type', 'product-redirection-for-woocommerce'); ?></span>
            <span class="input-text-wrap">
              <select name="redirect_type_prfw">
                <option value="301"><?php esc_html_e('Permanent', 'product-redirection-for-woocommerce'); ?></option>
                <option value="302"><?php esc_html_e('Temporary', 'product-redirection-for-woocommerce'); ?></option>
              </select>
            </span>
          </label>
        </div>
        <?php
        break;
      }
      
      case 'redirect_to_prfw': {
        ?>
        <div class="inline-edit-col">
          <label>
            <span class="title"><?php esc_html_e('Redirect To', 'product-redirection-for-woocommerce'); ?></span>
            <span class="input-text-wrap">
              <select name="redirect_to_prfw">
                <option value="This Product's Category"><?php esc_html_e("This Product's Category", 'product-redirection-for-woocommerce'); ?></option>
                <option value="Custom"><?php esc_html_e('Custom', 'product-redirection-for-woocommerce'); ?></option>
            </select>
            </span>
          </label>
        </div>
        <?php
        break;
      }
      
      case 'redirect_url_prfw': {
        ?>
            <div class="inline-edit-col">
              <label>
                <span class="title"><?php esc_html_e('Redirect URL', 'product-redirection-for-woocommerce'); ?></span>
                <span class="input-text-wrap">
                  <input type="url" name="redirect_url_prfw" value="">
                </span>
              </label>
            </div>
          </fieldset>
        <?php
        break;
      }
    }
  }
  
  /**
   * Bulk edit fields
   *
   * @param  mixed $column_name
   * @param  mixed $post_type
   * @return void
   */
  public function add_bulk_edit_fields($column_name, $post_type) {
    switch( $column_name ) {
      case 'enable_prfw': {
        ?>
        <fieldset class="inline-edit-col-left">
          <div class="inline-edit-col">
            <label>
              <span class="title"><?php esc_html_e('PRFW Type', 'product-redirection-for-woocommerce'); ?></span>
              <span class="input-text-wrap">
                <select name="enable_prfw">
                  <option value="">— No Change —</option>
                  <option value="Disabled"><?php esc_html_e('Disabled', 'product-redirection-for-woocommerce'); ?></option>
                  <option value="Redirect"><?php esc_html_e('Redirect', 'product-redirection-for-woocommerce'); ?></option>
                </select>
              </span>
            </label>
          </div>
        <?php
        break;
      }

      case 'redirect_type_prfw': {
        ?>
        <div class="inline-edit-col">
          <label>
            <span class="title"><?php esc_html_e('Redirect Type', 'product-redirection-for-woocommerce'); ?></span>
            <span class="input-text-wrap">
              <select name="redirect_type_prfw">
                <option value="">— No Change —</option>
                <option value="301"><?php esc_html_e('Permanent', 'product-redirection-for-woocommerce'); ?></option>
                <option value="302"><?php esc_html_e('Temporary', 'product-redirection-for-woocommerce'); ?></option>
              </select>
            </span>
          </label>
        </div>
        <?php
        break;
      }
      
      case 'redirect_to_prfw': {
        ?>
        <div class="inline-edit-col">
          <label>
            <span class="title"><?php esc_html_e('Redirect To', 'product-redirection-for-woocommerce'); ?></span>
            <span class="input-text-wrap">
              <select name="redirect_to_prfw">
                <option value="">— No Change —</option>
                <option value="This Product's Category"><?php esc_html_e("This Product's Category", 'product-redirection-for-woocommerce'); ?></option>
                <option value="Custom"><?php esc_html_e('Custom', 'product-redirection-for-woocommerce'); ?></option>
            </select>
            </span>
          </label>
        </div>
        <?php
        break;
      }
      
      case 'redirect_url_prfw': {
        ?>
            <div class="inline-edit-col">
              <label>
                <span class="title"><?php esc_html_e('Redirect URL', 'product-redirection-for-woocommerce'); ?></span>
                <span class="input-text-wrap">
                  <input type="url" name="redirect_url_prfw" value="">
                </span>
              </label>
            </div>
          </fieldset>
        <?php
        break;
      }
    }
  }
  
  /**
   * Save quick edit
   *
   * @param  mixed $post_id
   * @param  mixed $post
   * @return void
   */
  public function save_quick_edit($post_id, $post) {
    $nonce = isset($_POST['_inline_edit']) ? sanitize_text_field(wp_unslash($_POST['_inline_edit'])) : '';

    if (!wp_verify_nonce($nonce, 'inlineeditnonce')) {
		  return;
	  }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
    }

    if (!current_user_can('edit_product', $post_id)) {
      return;
    }

    if (isset($_POST['enable_prfw'])) {
      update_field('enable_prfw', sanitize_text_field(wp_unslash($_POST['enable_prfw'])), $post_id);
    }

    if (isset($_POST['redirect_type_prfw'])) {
      update_field('redirect_type_prfw', sanitize_text_field(wp_unslash($_POST['redirect_type_prfw'])), $post_id);
    }

    if (isset($_POST['redirect_to_prfw'])) {
      update_field('redirect_to_prfw', sanitize_text_field(wp_unslash($_POST['redirect_to_prfw'])), $post_id);
    }
    
    if (isset($_POST['redirect_url_prfw'])) {
      update_field('redirect_url_prfw', sanitize_url(wp_unslash($_POST['redirect_url_prfw'])), $post_id);
    }
  }
  
  /**
   * Save bulk edit
   *
   * @param  mixed $post_id
   * @param  mixed $post
   * @return void
   */
  public function save_bulk_edit($post_id, $post) {
    $nonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])) : '';

    if (!wp_verify_nonce($nonce, 'bulk-posts')) {
		  return;
	  }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
    }

    if (!current_user_can('edit_product', $post_id)) {
      return;
    }

    $enable_prfw        = isset($_REQUEST['enable_prfw']) ? sanitize_text_field(wp_unslash($_REQUEST['enable_prfw'])) : '';
    $redirect_type_prfw = isset($_REQUEST['redirect_type_prfw']) ? sanitize_text_field(wp_unslash($_REQUEST['redirect_type_prfw'])) : '';
    $redirect_to_prfw   = isset($_REQUEST['redirect_to_prfw']) ? sanitize_text_field(wp_unslash($_REQUEST['redirect_to_prfw'])) : '';
    $redirect_url_prfw  = isset($_REQUEST['redirect_url_prfw']) ? sanitize_text_field(wp_unslash($_REQUEST['redirect_url_prfw'])) : '';

    if ($enable_prfw) {
      update_field('enable_prfw', $enable_prfw, $post_id);
    }

    if ($redirect_type_prfw) {
      update_field('redirect_type_prfw', $redirect_type_prfw, $post_id);
    }

    if ($redirect_to_prfw) {
      update_field('redirect_to_prfw', $redirect_to_prfw, $post_id);
    }
    
    if ($redirect_url_prfw) {
      update_field('redirect_url_prfw', $redirect_url_prfw, $post_id);
    }
  }

}