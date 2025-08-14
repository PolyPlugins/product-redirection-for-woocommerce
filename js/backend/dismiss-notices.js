jQuery(document).ready(function($) {

  let ajax_url = prfw_object.ajax_url;
  let nonce    = prfw_object.nonce;

  $('body').on('click', '.product-redirection-for-woocommerce .notice-dismiss', function() {
    $.post(ajax_url, {
      action: 'prfw_dismiss_notice_nonce',
      nonce: nonce
    });
  });

});
