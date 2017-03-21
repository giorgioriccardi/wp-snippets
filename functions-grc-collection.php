<?php

//GRC functions collection 

/********************************************************/
// Adding a Google Fonts
/********************************************************/
add_action( 'wp_enqueue_scripts', 'my_google_font' );
function my_google_font() {
	wp_enqueue_style( $handle = 'my-google-font', $src = 'http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic', $deps = array(), $ver = null, $media = null );
}
//<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>


/********************************************************/
// Install Google Analytics in WordPress
/********************************************************/
add_action('wp_footer', 'add_googleanalytics');
function add_googleanalytics() { 
  ?>
    <!-- Paste your Google Analytics code here -->
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-xxxxxxx-x', 'auto');
      ga('send', 'pageview');

    </script>
  <?php 
}
