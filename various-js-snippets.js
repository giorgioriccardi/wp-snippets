/********************************************************/
// Edit the client/browser view
/********************************************************/
javascript: document.body.contentEditable = true;
void 0;
// credits: https://nickjanetakis.com/blog/temporarily-edit-text-on-any-website

/********************************************************/
// CF7 focus form labels effect
/********************************************************/
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
}); // end CF7 focus effect
// credits: https://mikelaroy.ca/wp_site/wp-content/themes/mikelaroy-2016/dist/assets/js/app.js
// css: CF7 labels animation
/*
label .label {
    display: inline-block;
    position: relative;
    transform: translateY(140%) translateX(12%);
    transition-delay: 0s;
    transition-duration: 0.25s;
    transition-property: transform;
    transition-timing-function: ease;
    z-index: 50;
}

label .label.focused {
    color: var(--dark-blue-03-color);
    transform: translateY(0px) translateX(0px);
}
*/

/********************************************************/
// Add Smooth Scroll effect via js animate()
/********************************************************/
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
}); // end Smooth Scroll
// source: https://nvision.co/development/smooth-scroll-simple-javascript/
// credits: https://www.learningjquery.com/2007/10/improved-animated-scrolling-script-for-same-page-links

/********************************************************/
// Open External Links In New Tab
/********************************************************/\
// jQuey version
jQuery(document).ready(function ($) {
  $("a").each(function () {
    var a = new RegExp("/" + window.location.host + "/");
    if (!a.test(this.href)) {
      $(this).click(function (event) {
        event.preventDefault();
        event.stopPropagation();
        window.open(this.href, "_blank");
        console.log("external-link");
      });
    }
  });
}); // end jQuey version

// vanilla JavaScript version
var links = document.links;

for (var i = 0, linksLength = links.length; i < linksLength; i++) {
  if (links[i].hostname != window.location.hostname) {
    links[i].target = '_blank';
    links[i].rel = 'noopener';
    links[i].classList.add('debug', 'external-link');
    console.log('external-link');
}
} // end vanilla JavaScript version

// ADDING EXTERNAL ICON via CSS
// https://developer.wordpress.org/resource/dashicons/#external
  // .external-link::after {
  // 	font-family: 'Dashicons';
  // 	content: "\f504";
//  position: absolute;
//  right: 0px;
// }
// end Open External Links In New Tab
