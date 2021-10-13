// Edit the client/browser view
javascript: document.body.contentEditable = true;
void 0;
// credits: https://nickjanetakis.com/blog/temporarily-edit-text-on-any-website

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
// credits: https://mikelaroy.ca/wp_site/wp-content/themes/mikelaroy-2016/dist/assets/js/app.js

// Add Smooth Scroll effect via js animate()
jQuery(document).ready(function ($) {
  // Smooth Scroll
  $(function () {
    $("a[href*=#]:not([href=#])").click(function () {
      if (
        location.pathname.replace(/^\//, "") ==
          this.pathname.replace(/^\//, "") &&
        location.hostname == this.hostname
      ) {
        var target = $(this.hash);
        target = target.length
          ? target
          : $("[name=" + this.hash.slice(1) + "]");
        if (target.length) {
          $("html,body").animate(
            {
              // 205 is a fixed header offset
              scrollTop: target.offset().top - 205,
            },
            1000
          );
          return false;
        }
      }
    });
  });
  // end Smooth Scroll
}); // end document.ready
// source: https://nvision.co/development/smooth-scroll-simple-javascript/
// credits: https://www.learningjquery.com/2007/10/improved-animated-scrolling-script-for-same-page-links
