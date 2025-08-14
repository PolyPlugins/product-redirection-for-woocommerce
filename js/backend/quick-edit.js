jQuery(function ($) {
  // Save the original inline edit function
  const wp_inline_edit_function = inlineEditPost.edit;

  // Override the edit function
  inlineEditPost.edit = function (post_id) {
    // Call the original function first
    wp_inline_edit_function.apply(this, arguments);

    // Make sure post_id is numeric
    if (typeof post_id === "object") {
      post_id = parseInt(this.getId(post_id));
    }

    // Get the edit row and post row
    const edit_row = $("#edit-" + post_id);
    const post_row = $("#post-" + post_id);

    if (!edit_row.length || !post_row.length) return;

    // Read column data
    const productEnablePRFW = $(".column-enable_prfw", post_row).text().trim();
    const productRedirectTypePRFW = $(".column-redirect_type_prfw", post_row).text().trim();
    const productRedirectToPRFW = $(".column-redirect_to_prfw", post_row).text().trim();
    const productRedirectURLPRFW = $(".column-redirect_url_prfw", post_row).text().trim();

    // Populate Quick Edit fields
    $('select[name="enable_prfw"]', edit_row).val(productEnablePRFW);
    $('select[name="redirect_type_prfw"]', edit_row).val(productRedirectTypePRFW);
    $('select[name="redirect_to_prfw"]', edit_row).val(productRedirectToPRFW);
    $('input[name="redirect_url_prfw"]', edit_row).val(productRedirectURLPRFW);
  };
});
