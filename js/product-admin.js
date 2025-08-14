const { __, _x, _n, _nx } = wp.i18n;

jQuery(document).ready(function ($) {
  var trashDisabled = true;
  // Product admin deletion handling
  $(".type-product .trash .submitdelete").on("click", function (e) {
    var url = $(this).attr("href");
    if (trashDisabled) {
      e.preventDefault();
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger",
        },
        buttonsStyling: true,
      });

      swalWithBootstrapButtons.fire({
        title: __("This is bad for SEO!", 'product-redirection-for-woocommerce'),
        text: __("You should redirect the product instead.", 'product-redirection-for-woocommerce'),
        icon: "warning",
        showCancelButton: prfw_object.trashdisable,
        confirmButtonText: __("SHOW ME", 'product-redirection-for-woocommerce'),
        cancelButtonText: __("DELETE PRODUCT", 'product-redirection-for-woocommerce'),
        reverseButtons: true,
      })
      .then((result) => {
        if (result.value) {
          var siteurl = prfw_object.siteurl;
          var postid = getParameterByName("post", url);
          var urlMerge =
            siteurl + "/wp-admin/post.php?post=" + postid + "&action=edit";
          window.location = urlMerge;
        } else if (result.dismiss === Swal.DismissReason.cancel) {
          trashDisabled = false;
          window.location = url;
        }
      });
    }
  });
  // Edit product deletion handling
  $("#delete-action .submitdelete.deletion").on("click", function (e) {
    var url = $(this).attr("href");
    if (trashDisabled) {
      e.preventDefault();
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger",
        },
        buttonsStyling: true,
      });

      swalWithBootstrapButtons.fire({
        title: __("ERROR", 'product-redirection-for-woocommerce'),
        text:
          __("Deleting products is not allowed, please see the Redirection section on this page.", 'product-redirection-for-woocommerce'),
        icon: "error",
        showCancelButton: false,
        confirmButtonText: "OK",
      });
    }
  });
  // Let's trigger a different popup for those who have the trash disabled. While rare that someone purposely disabled this functionality, some users may not even know it exists and may be missing out on awesome functionality. Just trying to be informative without intruding too much.
  $(".type-product .delete .submitdelete").on("click", function (e) {
    var url = $(this).attr("href");
    if (trashDisabled) {
      e.preventDefault();
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger",
        },
        buttonsStyling: true,
      });
      if (prfw_object.poststatus != "trash") {
        swalWithBootstrapButtons.fire({
          title: __("This is bad for SEO!", 'product-redirection-for-woocommerce'),
          text: __("You should redirect the product instead.", 'product-redirection-for-woocommerce'),
          footer:
            "<font color='red'>" + __('Did you know you have the', 'product-redirection-for-woocommerce') + "&nbsp;" +
            "<a href='https://codex.wordpress.org/Trash_status#EMPTY_TRASH_DAYS_option' target='_blank'>" + __('WordPress Trash Can', 'product-redirection-for-woocommerce') + "</a>" +
            "&nbsp;" + __('disabled?', 'product-redirection-for-woocommerce') + "</font>",
          icon: "warning",
          showCancelButton: prfw_object.trashdisable,
          confirmButtonText: __("SHOW ME", 'product-redirection-for-woocommerce'),
          cancelButtonText: __("DELETE PRODUCT", 'product-redirection-for-woocommerce'),
          reverseButtons: true,
        }).then((result) => {
          if (result.value) {
            var siteurl = prfw_object.siteurl;
            var postid = getParameterByName("post", url);
            var urlMerge =
              siteurl + "/wp-admin/post.php?post=" + postid + "&action=edit";
            window.location = urlMerge;
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            trashDisabled = false;
            window.location = url;
          }
        });
      } else {
        swalWithBootstrapButtons.fire({
          title: __("This is bad for SEO!", 'product-redirection-for-woocommerce'),
          text: __("You should redirect the product instead.", 'product-redirection-for-woocommerce'),
          icon: "warning",
          showCancelButton: prfw_object.trashdisable,
          confirmButtonText: __("SHOW ME", 'product-redirection-for-woocommerce'),
          cancelButtonText: __("DELETE PRODUCT", 'product-redirection-for-woocommerce'),
          reverseButtons: true,
        })
        .then((result) => {
          if (result.value) {
            var siteurl = prfw_object.siteurl;
            var postid = getParameterByName("post", url);
            var urlMerge =
              siteurl + "/wp-admin/post.php?post=" + postid + "&action=edit";
            window.location = urlMerge;
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            trashDisabled = false;
            window.location = url;
          }
        });
      }
    }
  });
  // Edit product deletion handling
  $("#delete-action .submitdelete.deletion").on("click", function (e) {
    var url = $(this).attr("href");
    var trashCheck = getParameterByName("action", url);
    if (trashDisabled) {
      e.preventDefault();
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger",
        },
        buttonsStyling: true,
      });
      if (trashCheck != "trash") {
        swalWithBootstrapButtons.fire({
          title: __("ERROR", 'product-redirection-for-woocommerce'),
          text:
            __("Deleting products is not allowed, please see the Redirection section on this page.", 'product-redirection-for-woocommerce'),
          footer:
            "<font color='red'>" + __('Did you know you have the', 'product-redirection-for-woocommerce') + "&nbsp;" +
            "<a href='https://codex.wordpress.org/Trash_status#EMPTY_TRASH_DAYS_option' target='_blank'>" + __('WordPress Trash Can', 'product-redirection-for-woocommerce') + "</a>" +
            "&nbsp;" + __('disabled?', 'product-redirection-for-woocommerce') + "</font>",
          icon: "error",
          showCancelButton: false,
          confirmButtonText: "OK",
        });
      } else {
        swalWithBootstrapButtons.fire({
          title: __("ERROR", 'product-redirection-for-woocommerce'),
          text:
            __("Deleting products is not allowed, please see the Redirection section on this page.", 'product-redirection-for-woocommerce'),
          icon: "error",
          showCancelButton: false,
          confirmButtonText: "OK",
        });
      }
    }
  });
});

function getParameterByName(name, url) {
  if (!url) url = window.location.href;
  name = name.replace(/[\[\]]/g, "\\$&");
  var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
    results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return "";
  return decodeURIComponent(results[2].replace(/\+/g, " "));
}
