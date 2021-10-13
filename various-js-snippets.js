// Edit the client/browser view
javascript: document.body.contentEditable = true;
void 0;
// credit: https://nickjanetakis.com/blog/temporarily-edit-text-on-any-website

// CF7 focus form labels effect
// Add a .focused class to style with an animation class
jQuery(document).ready(function ($) {
  // contact form focus
  (function () {
    var inputs = $(".wpcf7-form label");

    inputs.each(function (index, el) {
      var input = $(this).find("input, textarea");
      var label = $(this).find(".label");

      input.on("focus", function (event) {
        // event.preventDefault();

        label.addClass("focused");
      });

      input.on("blur", function (event) {
        // event.preventDefault();
        var val = $(this).val();

        if (val == "") {
          label.removeClass("focused");
        }
      });
    });
  })();
  // end CF7 focus effect
}); // end document.ready
// credit: https://mikelaroy.ca/wp_site/wp-content/themes/mikelaroy-2016/dist/assets/js/app.js
