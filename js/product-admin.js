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
        title: "This is bad for SEO!",
        text: "You should redirect the product instead.",
        icon: "warning",
        showCancelButton: LOCALIZED_PRFW.trashdisable,
        confirmButtonText: "SHOW ME",
        cancelButtonText: "DELETE PRODUCT",
        reverseButtons: true,
      })
      .then((result) => {
        if (result.value) {
          var siteurl = LOCALIZED_PRFW.siteurl;
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
        title: "ERROR",
        text:
          "Deleting products is not allowed, please see the Redirection section on this page.",
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
      if (LOCALIZED_PRFW.poststatus != "trash") {
        swalWithBootstrapButtons.fire({
          title: "This is bad for SEO!",
          text: "You should redirect the product instead.",
          footer:
            "<font color='red'>Did you know you have the&nbsp;" +
            "<a href='https://codex.wordpress.org/Trash_status#EMPTY_TRASH_DAYS_option' target='_blank'>WordPress Trash Can</a>" +
            "&nbsp;disabled?</font>",
          icon: "warning",
          showCancelButton: LOCALIZED_PRFW.trashdisable,
          confirmButtonText: "SHOW ME",
          cancelButtonText: "DELETE PRODUCT",
          reverseButtons: true,
        }).then((result) => {
          if (result.value) {
            var siteurl = LOCALIZED_PRFW.siteurl;
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
          title: "This is bad for SEO!",
          text: "You should redirect the product instead.",
          icon: "warning",
          showCancelButton: LOCALIZED_PRFW.trashdisable,
          confirmButtonText: "SHOW ME",
          cancelButtonText: "DELETE PRODUCT",
          reverseButtons: true,
        })
        .then((result) => {
          if (result.value) {
            var siteurl = LOCALIZED_PRFW.siteurl;
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
          title: "ERROR",
          text:
            "Deleting products is not allowed, please see the Redirection section on this page.",
          footer:
            "<font color='red'>Did you know you have the&nbsp;" +
            "<a href='https://codex.wordpress.org/Trash_status#EMPTY_TRASH_DAYS_option' target='_blank'>WordPress Trash Can</a>" +
            "&nbsp;disabled?</font>",
          icon: "error",
          showCancelButton: false,
          confirmButtonText: "OK",
        });
      } else {
        swalWithBootstrapButtons.fire({
          title: "ERROR",
          text:
            "Deleting products is not allowed, please see the Redirection section on this page.",
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
