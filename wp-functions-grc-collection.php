<?php

//SSWS functions collection 

/********************************************************/
// Adding a Google Fonts
/********************************************************/
add_action( 'wp_enqueue_scripts', 'ssws_google_font' );
function ssws_google_font() {
	wp_enqueue_style( $handle = 'my-google-font', $src = 'http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic', $deps = array(), $ver = null, $media = null );
}
//<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>


/********************************************************/
// Install Google Analytics in WordPress
/********************************************************/
add_action('wp_footer', 'add_googleanalytics');
function add_googleanalytics() {
  // wrap the GA code in an if condition to match only live site url
  // if ($_SERVER['HTTP_HOST']==="staging.your-site.com" || $_SERVER['HTTP_HOST']==="www.staging.your-site.com") { // local
  if ($_SERVER['HTTP_HOST']==="your-site.com" || $_SERVER['HTTP_HOST']==="www.your-site.com") { // production
     if (@$_COOKIE["COOKIENAME"] !== "COOKIEVALUE") {
        // Insert Analytics Code Here
        ?>
          <!-- Global site tag (gtag.js) - Google Analytics -->
          <script async src="https://www.googletagmanager.com/gtag/js?id=UA-11xxxxxx-1"></script>
          <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-11xxxxxx-1');
          </script>
        <?php
     }
  }
}


/********************************************************/
  // Replaces the excerpt "more" text or [...] with a custom link
/********************************************************/
    function new_excerpt_more($more) {
           global $post;
      // return '<a class="moretag" href="'. get_permalink($post->ID) . '"&gt; READ MORE</a>';
           return ' [... <a class="moretag" href="'. get_permalink($post->ID) . '">read more &gt;</a>]';
    }

    add_filter('excerpt_more', 'new_excerpt_more');
    /*end of MORE*/


/********************************************************/
//Change WordPress Excerpt length
/********************************************************/
function custom_excerpt_length( $length ) {
  return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );


//Two Different Excerpt Length's

// SSWS working:
function custom_excerpt($new_length = 20) {
  
  add_filter('excerpt_length', function () use ($new_length) {
    return $new_length;
  }, 999);

  $output = get_the_excerpt();
  $output = apply_filters('wptexturize', $output);
  $output = apply_filters('convert_chars', $output);
  $output = '<p>' . $output . '</p>';
  
  echo $output;
}

// requires this snippet in the page template:
//<?php custom_excerpt(55, '<a class="moretag" href="' . get_permalink( get_the_ID() ) . '"&gt; READ MORE</a>') ? >
//ssws 02/2015:
//<?php custom_excerpt(55) ? > // works as well
//started from http://wordpress.org/support/topic/two-different-excerpt-lengths
//implemented by Scott and SSWS


/********************************************************/
  // Add menus to Customizr
/********************************************************/

  add_action( 'init', 'register_secondary_menu' ); // this registers your menu with WP
  function register_secondary_menu() {
      if ( function_exists( 'register_nav_menu' ) ) {
          register_nav_menu( 'secondary-menu', 'Secondary Menu' );
      }
  }
  // Select the add_action you need, depending on where you want to add the menu and disable the rest:
  add_action('__before_header', 'display_secondary_menu');     // use this line to add above header
  //add_action('__header', 'display_secondary_menu', 1000, 0);     // use this to add after header
  //add_action('__before_footer', 'display_secondary_menu');     // use this line to add above footer
  //add_action('wp_footer', 'display_secondary_menu', 1000, 0);     // use this to add before credits

//http://themesandco.com/snippet/adding-extra-menus-customizr/
  
/*SSWS 
  the secondary menu it is displayed manually in the footer hooks file*/
  
  // display function for your menu:
  function display_secondary_menu() {
      echo ( has_nav_menu( 'secondary-menu' ) ? wp_nav_menu (
          array (
              'theme_location' => 'secondary-menu',
              'container_id'    => 'secondary-menu',
              'container_class'    => 'secondary-menu'
          )
      ).'<div class="clearall"></div>' : '' );
  }
/*end of SSWS extra menus*/


/*SSWS 
  Credits 
  NON FUNZIONA CON CUSTOMIZR 3.2.x
*/
    add_filter('tc_credits_display', 'ssws_custom_credits');
    function ssws_custom_credits(){
      $credits = '';
      $newline_credits = '';
      
/*SSWS

        /*<div class="span4 credits">*/

      return '
        <div class="span4 credits">
            <p> &middot; &copy; '.esc_attr( date( 'Y' ) ).' 
                <a href="'.esc_url( home_url() ).'" title="'.esc_attr(get_bloginfo()).'" rel="bookmark">'.esc_attr(get_bloginfo()).'</a>
                 &middot; '.($credits ? $credits : 'Designed by <a href="http://www.griccardi.com/" target="_blank">SSWS Ltd.</a>').' 
                 &middot;'.($newline_credits ? '<br />&middot; '.$newline_credits.' &middot;' : '').'</p>
        </div>';

    //or with union logo:

      // return '
      //   <div class="span4 credits">
      //       <p> &middot; &copy; '.esc_attr( date( 'Y' ) ).' 
      //           <a href="'.esc_url( home_url() ).'" title="'.esc_attr(get_bloginfo()).'" rel="bookmark">'.esc_attr(get_bloginfo()).'</a>
      //            &middot; '.($credits ? $credits : 'Designed by <a href="http://www.griccardi.com/" target="_blank">SSWS Ltd.</a>').' 
      //            &middot;'.($newline_credits ? '<br />&middot; '.$newline_credits.' &middot;' : '').'
      //           <img src="/wordpress/wp-content/themes/customizr-child/images/unifor_label_2000-38_web.png" alt="MediaUnion-mini-logo" width="30px" height="27px" />            
      //       </p>
      //   </div>';

  }
/*end of SSWS credits*/


/********************************************************/
//  Limit archives widget to display only 6 months
/********************************************************/

function ssws_limit_archives($args){
  echo "<li class='extra-link'><a href='/archive/'>Older Posts</a></li>";
  // it requires a page named archive with a custom template with this loop:
  // https://codex.wordpress.org/Creating_an_Archive_Index
    $args['limit'] = 6;

    // $args = array(
    //   'type'            => 'monthly',
    //   'limit'           => '12',
    //   'format'          => 'html', 
    //   'before'          => '',
    //   'after'           => '',
    //   'show_post_count' => false,
    //   'echo'            => 1,
    //   'order'           => 'ASC',
    //   'post_type'       => 'post'
    // );
    
    // http://codex.wordpress.org/Template_Tags/wp_get_archives
  
    return $args;
}
add_filter( 'widget_archives_args', 'ssws_limit_archives', 10, 1 );
add_filter( 'widget_archives_dropdown_args', 'ssws_limit_archives', 10, 1 );


/********************************************************/
//  Limit archives widget to display only 3 years
/********************************************************/
function ssws_limit_archives($args){
    //echo "<li class='extra-link'><a href='/archive-samples/'>All previous Posts</a></li>";
    //$args['limit'] = 6; // default is monthly
    $args = array(
    'type'            => 'yearly',
    'limit'           => '3',
    'show_post_count' => true
    );
    return $args;
}
add_filter( 'widget_archives_args', 'ssws_limit_archives' );
//http://codex.wordpress.org/Function_Reference/wp_get_archives


/********************************************************/
//  Excludes certain categories
/********************************************************/
function widget_categories_args_filter( $cat_args ) {
    $exclude_arr = array( 843, 838 ); //here the id of the categories to exclude
    
    if( isset( $cat_args['exclude'] ) && !empty( $cat_args['exclude'] ) )
        $exclude_arr = array_unique( array_merge( explode( ',', $cat_args['exclude'] ), $exclude_arr ) );
    $cat_args['exclude'] = implode( ',', $exclude_arr );
    return $cat_args;
}

add_filter( 'widget_categories_args', 'widget_categories_args_filter', 10, 1 );


/********************************************************/
//  Includes certain categories
/********************************************************/
function widget_categories_args_filter( $cat_args ) {
    $include_arr = array( 843, 838 ); //here the id of the categories to include
    
    if( isset( $cat_args['include'] ) && !empty( $cat_args['include'] ) )
        $include_arr = array_unique( array_merge( explode( ',', $cat_args['include'] ), $include_arr ) );
    $cat_args['include'] = implode( ',', $include_arr );
    return $cat_args;
}

add_filter( 'widget_categories_args', 'widget_categories_args_filter', 10, 1 );
//https://codex.wordpress.org/Plugin_API/Filter_Reference/widget_categories_args


/********************************************************/
// Exclude Categories from Category Widget
/********************************************************/
function ssws_custom_category_widget($args) {
    $exclude = "212"; // FeATURED Category IDs to be excluded
    $args["exclude"] = $exclude;
    return $args;
}
add_filter("widget_categories_dropdown_args","ssws_custom_category_widget");


/********************************************************/
// ADDING A FOURTH FOOTER WIDGET AREA
/********************************************************/

// Adds a widget area. It gets registered automatically as part of the array
add_filter( 'tc_footer_widgets', 'ssws_footer_widgets');
function ssws_footer_widgets( $default_widgets_area ) {
    $default_widgets_area['footer_four'] = array(
          'name'                 => __( 'Footer Widget Area Four' , 'customizr' ),
          'description'          => __( 'Just use it as you want !' , 'customizr' )
    );
    return $default_widgets_area;
}

// Style all the footer widgets so they take up the right space
add_filter( 'footer_one_widget_class', 'ssws_footer_widget_class');
add_filter( 'footer_two_widget_class', 'ssws_footer_widget_class');
add_filter( 'footer_three_widget_class', 'ssws_footer_widget_class');
add_filter( 'footer_four_widget_class', 'ssws_footer_widget_class');
function ssws_footer_widget_class() {
    return 'span3';
}


/********************************************************/
//Add Search Form in your Post with a WordPress Search Shortcode
/********************************************************/
function wpbsearchform( $form ) {

    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
    <div><label class="screen-reader-text" for="s">' . __('Search for:') . '</label>
    <input type="text" value="' . get_search_query() . '" name="s" id="s" />
    <input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
    </div>
    </form>';

    return $form;
}
add_shortcode('wpbsearch', 'wpbsearchform');

//http://www.wpbeginner.com/wp-tutorials/how-to-add-search-form-in-your-post-with-a-wordpress-search-shortcode/

// requires a shortcode in the header file
/*<?php echo do_shortcode('[wpbsearch]'); ?>*/

    //search form in the header can be easly replaced with this:


/********************************************************/
//ADDING AN HTML5 SEARCH FORM IN YOUR WORDPRESS MENU
/********************************************************/
// As of 3.1.10, Customizr doesn't output an html5 form.
add_theme_support( 'html5', array( 'search-form' ) );

add_filter('wp_nav_menu_items', 'add_search_form_to_menu', 10, 2);
function add_search_form_to_menu($items, $args) {

  // If this isn't the main navbar menu, do nothing
  if( !($args->theme_location == 'main') ) // or set to 'secondary' if the option in Customizr is enabled
    return $items;

  // On main menu: put styling around search and append it to the menu items
  return $items . '<li class="my-nav-menu-search">' . get_search_form(false) . '</li>';
}
/*need in the css:
    font-family: FontAwesome;
    content: '\f002';*/
//http://www.themesandco.com/snippet/adding-an-html5-search-form-in-your-wordpress-menu/


/********************************************************/
// Remove 3-bars from menu button
/********************************************************/
add_filter('tc_menu_display', 'rdc_menu_display');
function rdc_menu_display($output) {
    return preg_replace('|<span class="icon-bar"></span>|', null, $output);
}
// requires css content: "Menu";
// http://themesandco.com/snippet/add-menu-text-3-bar-menu-button/


/********************************************************/
// <!-- http://wordpress.stackexchange.com/questions/16070/how-to-highlight-search-terms-without-plugin -->
// <!-- HIGHLIGHT THE SEARCH TERMS IN RESULTS -->
/********************************************************/
function search_excerpt_highlight() {
    $excerpt = get_the_excerpt();
    $keys = implode('|', explode(' ', get_search_query()));
    $excerpt = preg_replace('/(' . $keys .')/iu', '<strong class="search-highlight">\0</strong>', $excerpt);

    //echo '<p>' . $excerpt . '</p>';
    //echo $excerpt;
}

function search_title_highlight() {
    $title = get_the_title();
    $keys = implode('|', explode(' ', get_search_query()));
    $title = preg_replace('/(' . $keys .')/iu', '<strong class="search-highlight">\0</strong>', $title);

    //echo $title;
}

function search_content_highlight() {
    $content = get_the_content();
    $keys = implode('|', explode(' ', get_search_query()));
    $content = preg_replace('/(' . $keys .')/iu', '<strong class="search-highlight">\0</strong>', $content);

    //echo '<p>' . $content . '</p>';
}

//requires some changes in the search.php:

      //<!-- <h2><a href="<?php the_permalink() ? >" rel="bookmark"><?php the_title(); ? ></a></h2> -->
      //<h2><a href="<?php the_permalink() ? >" rel="bookmark"><?php search_title_highlight(); ? ></a></h2>

      //<?php //the_excerpt(); ? >

      //<!-- this shows only the excerpt -->
      //<?php search_excerpt_highlight(); ? >

      //<!-- this shows highlighted results in all the content -->
      //<?php search_content_highlight(); ? >

//<!-- http://wordpress.stackexchange.com/questions/16070/how-to-highlight-search-terms-without-plugin -->


//different version of the higlighted search function, it does not requires snippets:

//http://bradsknutson.com/blog/highlight-search-terms-wordpress/
function highlight_search_term($text){
    if(is_search()){
    $keys = implode('|', explode(' ', get_search_query()));
    $text = preg_replace('/(' . $keys .')/iu', '<span class="search-term">\0</span>', $text);
  }
    return $text;
}
add_filter('the_excerpt', 'highlight_search_term');
add_filter('the_title', 'highlight_search_term');
add_filter('the_content', 'highlight_search_term');

//<!-- end of HIGHLIGHT THE SEARCH TERMS IN RESULTS -->


/********************************************************/
// remove span9 from navbar-wrapper:
/********************************************************/
add_filter('tc_navbar_display', 'remove_span9_navbar_display');
function remove_span9_navbar_display($output) {
  return preg_replace('/navbar-wrapper clearfix span9/', 'navbar-wrapper clearfix', $output);
}
//vedi Change logo/navbar classes
//https://gist.github.com/eri-trabiccolo/bc447c364dd27236b105


/********************************************************/
//SSWS function to override the menu wrapper's class 
/********************************************************/
function ssws_custom_navbar_wrapper_class() {
    echo apply_filters( 'ssws_custom_navbar_wrapper_class', 'navbar-wrapper clearfix span12' );
}
add_filter('tc_navbar_wrapper_class', 'ssws_custom_navbar_wrapper_class' );


// prevent the output of tc_social_in_header:
add_filter('tc_social_in_header', 'prevent_social_in_header');
function prevent_social_in_header($output) {
  return;
}

// prevent the output of tc_social_in_footer:
add_filter('tc_social_in_footer', 'prevent_social_in_footer');
function prevent_social_in_footer($output) {
  return;
}


// prevent output of tc_tagline_display:
add_filter('tc_tagline_display', 'prevent_tagline_display');
function prevent_tagline_display($output) {
  return;
}


//SSWS function to override the logo class 
add_filter('tc_logo_text_display', 'ssws_logo_display');
add_filter('tc_logo_img_display', 'ssws_logo_display');
function ssws_logo_display($output) {
  return preg_replace('/brand span3/', 'brand span12', $output, -1);
}

//questo e' un estratto di quello sotto ed anche una evoluzione di quello sopra
add_filter('tc_logo_class', 'ssws_logo_class', 15);
function ssws_logo_class($classes){
    return str_replace('span3', 'span12', $classes);
}

//Change logo/navbar classes
add_filter('tc_navbar_wrapper_class', 'ssws_header_elements_class');
add_filter('tc_logo_class', 'ssws_header_elements_class', 15);
function ssws_header_elements_class($classes){
    // remember replacements sum must be == 12 to have them side by side
    // the default proportion is: logo 3 - navbar 9
    $src_rep_classes = array(
        //filter => array( search => replacement)
        'tc_logo_class' => array(
            'span3' => 'span5'
        ),
        'tc_navbar_wrapper_class' => array(
            'span9' => 'span7'
        )
    );
    // new classes to add
    $add_classes = array(
        //filter => array of classes
        'tc_navbar_wrapper_class' => array(
            'new-class'
        )
    );
    $current_filter = current_filter();
    $new_classes = str_replace(array_keys($src_rep_classes[ $current_filter] ),
                        array_values($src_rep_classes[ $current_filter] ),
                        $classes);

    return ( isset($add_classes[$current_filter]) ) ? array_merge( $new_classes, $add_classes[$current_filter] ) : $new_classes ;
}
//https://gist.github.com/eri-trabiccolo/bc447c364dd27236b105


/********************************************************/
// Exclude images from search results - Customizr
/********************************************************/
add_action('init', 'exclude_images_from_search_results');
function exclude_images_from_search_results(){
    if ( is_admin() )
        return;
    remove_filter( 'pre_get_posts', array(TC_post_list::$instance,'tc_include_attachments_in_search') );
}
//images not to be confused with featured images within the posts [thumbnails]

//http://themesandco.com/snippet/exclude-images-attachments-search-results/


/********************************************************/
// Change the post title tag to h2 / h3
/********************************************************/
add_filter('tc_content_title_tag' , 'ssws_title_tag');
function ssws_title_tag() {
    return 'h3';
}


/********************************************************/
//Linking the whole slide’s picture to a page/post in Customizr
/********************************************************/
add_filter('tc_slide_background' , 'ssws_slide_link', 10, 2);
function ssws_slide_link( $slide_image , $slide_link) {
    return sprintf('<a href="%1$s">%2$s</a>',
        $slide_link,
        $slide_image
    );
}


/********************************************************/
//Adding a link in the WordPress tagline
/********************************************************/
add_filter( 'tc_tagline_display' , 'ssws_link_in_tagline');
function ssws_link_in_tagline() {
    global $wp_current_filter;
    ?>
        <?php if ( !in_array( '__navbar' , $wp_current_filter ) )  :?>
            <div class="container outside">
                <h2 class="site-description">
                    <?php //bloginfo( 'description' ); ?>
                    <a href="http://#/" title="###">###</a>
                </h2> 
            </div>
        <?php else : //when hooked on __navbar ?>
            <h2 class="span7 inside site-description">
                <?php //bloginfo( 'description' ); ?>
                <a href="http://#/" title="###">###</a>
            </h2>
        <?php endif; ?>
    <?php
}


/********************************************************/
/* Custom Login Page
/********************************************************/
function ssws_custom_login() {
  echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('stylesheet_directory') . '/login/custom-login-styles.css" />';
}
add_action('login_head', 'ssws_custom_login');
// https://premium.wpmudev.org/blog/customize-login-page/


/********************************************************/
// Change the WordPress Login Logo old version 1.0
/********************************************************/
function custom_login_logo() {
echo '
<style type="text/css">
      h1 a { background-image:url(/images/logo.jpg) !important; }
  </style>
';
}
add_action('login_head', 'custom_login_logo');

//Changing the default WordPress login URL

function ssws_login_logo_url() {
    return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'ssws_login_logo_url' );

function ssws_login_logo_url_title() {
    return 'Your Site Name and Info';
}
add_filter( 'login_headertitle', 'ssws_login_logo_url_title' );
//Here, the get_bloginfo(‘url’) is the URL of your blog, you can change that with whatever URL you may like, but don’t forget to add it between quotes.



/********************************************************/
// Change Login Logo ver. 2.0
/********************************************************/
function ssws_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/logo.png);
            padding-bottom: 30px;
            background-size: 436px 123px;
            width: 436px;
            height: 123px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'ssws_login_logo' );

// Change the Login Logo URL
function ssws_login_logo_url() {
  return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'ssws_login_logo_url' );

function ssws_login_logo_url_title() {
  return 'SSWS - Home';
}
add_filter( 'login_headertitle', 'ssws_login_logo_url_title' );

// Change the Redirect URL
function admin_login_redirect( $redirect_to, $request, $user ) {
  global $user;
  if( isset( $user->roles ) && is_array( $user->roles ) ) {
    if( in_array( "administrator", $user->roles ) ) {
      return $redirect_to;
    } else {
      return home_url();
    }
  }
  else
  {
    return $redirect_to;
  }
}
add_filter("login_redirect", "admin_login_redirect", 10, 3);

// Set “Remember Me” To Checked
function login_checked_remember_me() {
  add_filter( 'login_footer', 'rememberme_checked' );
}
add_action( 'init', 'login_checked_remember_me' );

function rememberme_checked() {
  echo "<script>document.getElementById('rememberme').checked = true;</script>";
}
// https://premium.wpmudev.org/blog/customize-login-page/


/********************************************************/
//Make Archives.php Include Custom Post Types
/********************************************************/

// this snippet can be paste within the custom plugin created to output the CPT,
// assuming we are creating a plugin instead of embedding the CPT into functions.php
function namespace_add_custom_types( $query ) {
  if( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
    $query->set( 'post_type', array(
     'post', 'nav_menu_item', 'your-custom-post-type-here'
    ));
    return $query;
  }
}
add_filter( 'pre_get_posts', 'namespace_add_custom_types' );

//http://css-tricks.com/snippets/wordpress/make-archives-php-include-custom-post-types/


/********************************************************/
/**
 * Add "first" and "last" CSS classes to dynamic sidebar widgets. 
 * Also adds numeric index class for each widget (widget-1, widget-2, etc.)
 */
/********************************************************/
function widget_first_last_classes($params) {

  global $ssws_widget_num; // Global a counter array
  $this_id = $params[0]['id']; // Get the id for the current sidebar we're processing
  $arr_registered_widgets = wp_get_sidebars_widgets(); // Get an array of ALL registered widgets  

  if(!$ssws_widget_num) {// If the counter array doesn't exist, create it
    $ssws_widget_num = array();
  }

  if(!isset($arr_registered_widgets[$this_id]) || !is_array($arr_registered_widgets[$this_id])) { // Check if the current sidebar has no widgets
    return $params; // No widgets in this sidebar... bail early.
  }

  if(isset($ssws_widget_num[$this_id])) { // See if the counter array has an entry for this sidebar
    $ssws_widget_num[$this_id] ++;
  } else { // If not, create it starting with 1
    $ssws_widget_num[$this_id] = 1;
  }

  $class = 'class="widget-' . $ssws_widget_num[$this_id] . ' '; // Add a widget number class for additional styling options

  if($ssws_widget_num[$this_id] == 1) { // If this is the first widget
    $class .= 'widget-first ';
  } elseif($ssws_widget_num[$this_id] == count($arr_registered_widgets[$this_id])) { // If this is the last widget
    $class .= 'widget-last ';
  }

  //$params[0]['before_widget'] = str_replace('class="', $class, $params[0]['before_widget']); // Insert our new classes into "before widget"
  $params[0]['before_widget'] = preg_replace('/class=\"/', "$class", $params[0]['before_widget'], 1);

  return $params;

}
add_filter('dynamic_sidebar_params','widget_first_last_classes');
//https://wordpress.org/support/topic/how-to-first-and-last-css-classes-for-sidebar-widgets?replies=9


/********************************************************/
// ADDING AN UPDATE STATUS NEXT TO POST TITLES IN WORDPRESS, 3 options:
/********************************************************/
// Displaying the last post update's date in the post metas 
// This snippet replaces the default published date by the last post update's date in your post metas

add_filter( 'tc_date_meta' , 'display_the_update_date'); //hook only for Customizr
function display_the_update_date() {
    return sprintf( '<a href="%1$s" title="updated %2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>' ,
        esc_url( get_day_link( get_the_time( 'Y' ), get_the_time( 'm' ), get_the_time( 'd' ) ) ),
        esc_attr( get_the_modified_date('F j, Y') ),
        esc_attr( get_the_modified_date('c') ),
          get_the_modified_date('F j, Y')
    );
}

// This simple snippet adds an update status after your post title in WordPress.
// It checks if the post has been updated since its creation and if this update is less than n days old. If the condition is verified, it adds an update status text after the title. In this example, the update interval is set to 90 days.

add_filter('the_title' , 'add_update_status');
//add_filter('tc_content_headings' , 'add_update_status'); //hook only for Customizr

function add_update_status($html) {
    //First checks if we are in the loop and we are not displaying a page
    if ( ! in_the_loop() || is_page() )
        return $html;
 
    //Instantiates the different date objects
    $created = new DateTime( get_the_date('Y-m-d g:i:s') );
    $updated = new DateTime( get_the_modified_date('Y-m-d g:i:s') );
    $current = new DateTime( date('Y-m-d g:i:s') );
 
    //Creates the date_diff objects from dates
    $created_to_updated = date_diff($created , $updated);
    $updated_to_today = date_diff($updated, $current);
 
    //Checks if the post has been updated since its creation
    $has_been_updated = ( $created_to_updated -> s > 0 || $created_to_updated -> i > 0 ) ? true : false;
 
    //Checks if the last update is less than n days old. (replace n by your own value)
    $has_recent_update = ( $has_been_updated && $updated_to_today -> days < 90 ) ? true : false;
 
    //Adds HTML after the title
    $recent_update = $has_recent_update ? '<span class="label label-warning">Recently updated</span>' : '';
 
    //Returns the modified title
    return $html.'&nbsp;'.$recent_update;
}


// for new posts I have modified my functions.php 
//in the child theme and sort all posts by last update

add_filter('the_title' , 'add_created_status');
//add_filter('tc_content_headings' , 'add_created_status'); //hook only for Customizr
function add_created_status($html) {
//First checks if we are in the loop and we are not displaying a page
if ( ! in_the_loop() || is_page() )
return $html;

//Instantiates the different date objects
$created = new DateTime( get_the_date('Y-m-d g:i:s') );
$current = new DateTime( date('Y-m-d g:i:s') );

//Creates the date_diff objects from dates
$created_to_today = date_diff($created, $current);

//Checks if the post is “fresh”
$has_been_created = ( $created_to_today -> s > 0 || $created_to_today -> i > 0 ) ? true : false;

//Checks if the post ist n days old to remove NEW-status (replace n by your own value)
$is_new_post = ( $has_been_created && $created_to_today -> days < 7 ) ? true : false;

//Adds HTML after the title
$recent_post = $is_new_post ? '<span class="label label-success">NEW</span>' : '';

//Returns the modified title
return $html.' '.$recent_post;
}

// End of ADDING AN UPDATE STATUS NEXT TO POST TITLES IN WORDPRESS, 3 options


/********************************************************/
//Post list thumbnails reordering in small viewports
/********************************************************/
add_action('wp_footer', 'postlist_smallwidth_disable_alternate_layout');
function postlist_smallwidth_disable_alternate_layout(){
$tb_position = "after"; /* "before" article-content or "after" article-content */
?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            ! function ($) {

                var $thumbnails = $('article section[class*="tc-thumbnail"]'),
                    $contents = $('article section[class*="tc-content"]').not(".span12"),
                    reordered = false;

                //reordering function
                function reordering(reorder){
                    var position = '<?php echo $tb_position; ?>',
                    iterator = ( (position == "before" && reorder) || ( position != "before" && ! reorder ) ) ? $thumbnails : $contents;
      
                    reordered = reorder;
      
                    iterator.each(
                        function(){
                            if ( $(this).next().is('section') || (!reorder && ! $(this).parent().hasClass('reordered')) )
                                return;

                            $(this).prependTo($(this).parent());
                            $(this).parent().toggleClass('reordered');
                        }
                    );
                }

                function reorder_or_revert(){
                    if ( $thumbnails.width() == $thumbnails.parent().width() && ! reordered )
                        reordering(true);
                    else if ( $thumbnails.width() != $thumbnails.parent().width() && reordered )
                        reordering(false);
                }

                reorder_or_revert();

                $(window).resize(function () {
                    //call the function with a timeout of 500 ms when resing window.
                    setTimeout(reorder_or_revert, 500);
                }); 
             }(window.jQuery);
         });
    </script>
<?php
}
//http://themesandco.com/snippet/post-list-thumbnails-reordering/


/********************************************************/
//Changing the default prefix : “Category Archives :”
/********************************************************/
add_filter('tc_category_archive_title' , 'ssws_cat_title');
function ssws_cat_title($title) {
return '';
}
//http://themesandco.com/snippet/changing-the-title-of-the-categories-archive-pages/


/********************************************************/
// Exclude post category from displaying on the blog post page
/********************************************************/
function exclude_category($query) {
if ( $query->is_home() ) {
$query->set('cat', '-51');
}
return $query;
}
add_filter('pre_get_posts', 'exclude_category');
//NOTE: this snippet works fine, but in case of ACF relational fields
// it is best to embed into the index.php this snippet:
// <?php
//      if ( is_home() ) {
//        query_posts( 'cat=-1,-2,-3' ); //works only with exclusions!!
//      }
// ? >
//http://codex.wordpress.org/Function_Reference/query_posts


/********************************************************/
//Inheriting parent styles in WordPress child themes
/********************************************************/
add_action( 'wp_enqueue_scripts', 'ssws_theme_enqueue_styles' );
function ssws_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
// https://codex.wordpress.org/Child_Themes

// from mor10
add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_styles', PHP_INT_MAX);
function enqueue_child_theme_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_uri(), array('parent-theme-name-style') );
    //wp_enqueue_style( 'child-style', get_stylesheet_uri(), array('simone-style') );
}

// in alternative for some themes:
function get_parent_theme_css() {
  wp_enqueue_style( 'your-child-theme-name', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'get_parent_theme_css' );
//http://mor10.com/challenges-new-method-inheriting-parent-styles-wordpress-child-themes/


/********************************************************/
//Allow SVG through WordPress Media Uploader
/********************************************************/
function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');


/********************************************************/
//Reordering the featured page elements : title, image, text, button
/********************************************************/

add_action('wp_footer' , 'set_fp_item_order');
function set_fp_item_order() {
    $ssws_item_order = array(
        'title', 
        'image', 
        'text',
        'button',
    );
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            ! function ($) {
                //prevents js conflicts
                "use strict";
                var ssws_item_order   = [<?php echo '"'.implode('","', $ssws_item_order).'"' ?>],
                  $Wrapper    = '';

                if ( 0 != $('.widget-front' , '#main-wrapper .marketing' ).length ) {
                  $Wrapper = $('.widget-front' , '#main-wrapper .marketing' );
                } else if ( 0 != $('.fpc-widget-front' , '#main-wrapper .fpc-marketing' ).length ) {
                  //for FPU users
                  $Wrapper = $('.fpc-widget-front' , '#main-wrapper .fpc-marketing' );
                } else {
                  return;
                }

                $Wrapper.each( function() {
                    var o            = [];
                    o['title']   = $(this).find('h2');
                    o['image']   = $(this).find('.thumb-wrapper');
                    o['text']    = $(this).find('p');
                    o['button']  = $(this).find('a.btn');
                    for (var i = 0; i < ssws_item_order.length - 1; i++) {
                       o[ssws_item_order[i]].after(o[ssws_item_order[i+1]]);
                    };
                });
            }(window.jQuery)
        });
    </script>
    <?php
}
//http://themesandco.com/snippet/reordering-featured-page-elements-title-image-text-button/


/********************************************************/
// DISABLE WordPress notifications
/********************************************************/

$func = function ($a) {
    global $wp_version;
    return (object) array(
        'last_checked' => time(),
        'version_checked' => $wp_version,
    );
};
//add_filter('pre_site_transient_update_core', $func);
//add_filter('pre_site_transient_update_plugins', $func);
add_filter('pre_site_transient_update_themes', $func);
//http://stackoverflow.com/questions/11821419/wordpress-plugin-notifications/14935077#14935077

//this is the custom ssws version
//on some versions of customizr the above did not work

function disable_wp_updates($a) {
    global $wp_version;
    return (object) array(
        'last_checked' => time(),
        'version_checked' => $wp_version,
    );
};
//add_filter('pre_site_transient_update_core', 'disable_wp_updates');
//add_filter('pre_site_transient_update_plugins', 'disable_wp_updates');
add_filter('pre_site_transient_update_themes', 'disable_wp_updates');
//http://stackoverflow.com/questions/11821419/wordpress-plugin-notifications/14935077#14935077

//end of WordPress notifications


/********************************************************/
//Customizing the post layout (content, thumbnail) in post lists [SSWS version]
/********************************************************/

add_filter('tc_post_list_layout' , 'ssws_post_list_layout_options');
function ssws_post_list_layout_options($layout) {

  //For custom post types
  if ( 'publications' == get_post_type() ) {
    //extracts the array as variables
    extract($layout , EXTR_OVERWRITE );
     
    //set the custom layout parameters
    $content          = 'span10';
    $thumb            = 'span2'; //<= the sum of content and thumb span suffixes (span[*]) must equal 12 otherwise the content and thumbnail blocks will no fit correctly in any viewport
    $show_thumb_first = true;
    $alternate        = wp_is_mobile() ? false : true;//<= this will desactivate the alternate thumbnail / content in mobile viewports
    
    //returns the recreated $layout array containing your custom variables values
    echo "uno";
    return compact("content" , "thumb" , "show_thumb_first" , "alternate" );
  }
  //For the blog posts page
  elseif ( is_home() ) {
    //extracts the array as variables
    extract($layout , EXTR_OVERWRITE );
     
    //set the custom layout parameters
    $content          = 'span9';
    $thumb            = 'span3'; //<= the sum of content and thumb span suffixes (span[*]) must equal 12 otherwise the content and thumbnail blocks will no fit correctly in any viewport
    $show_thumb_first = true;
    $alternate        = wp_is_mobile() ? false : true;//<= this will desactivate the alternate thumbnail / content in mobile viewports
    
    //returns the recreated $layout array containing your custom variables values
    echo "due";
    return compact("content" , "thumb" , "show_thumb_first" , "alternate" );
  }
  //For all the other conditions return the default span8+span4
  else {
    echo "tre";
    return $layout;
  }
}
//http://themesandco.com/snippet/customizing-post-layout-content-thumbnail-archive-post-lists/


// pre_get_posts

// we can change our main query before it runs
// this function can be parsed from functions.php and intercept the main loop

// [example 1] --> for posts
function ssws_pre_get_posts( $query ) {
    // Check if the main query and home and not admin
    if ( $query->is_main_query() && is_home() && !is_admin() ) {
    // Display only posts that belong to a certain Category
    $query->set( 'category_name', 'fatuity' );
    // Display only 3 posts per page
    $query->set( 'posts_per_page', '3' );
    // others parameters here: http://codex.wordpress.org/Class_Reference/WP_Query#Parameters
    return;
  }
}
// Add our function to the pre_get_posts hook
add_action( 'pre_get_posts', 'ssws_pre_get_posts' );

// [example 2] --> for CPT
function ssws_pre_get_posts( $query ) {
  // Check if the main query and movie CPT archive and not admin
  if($query->is_main_query() && is_post_type_archive('movie') && !is_admin()){
    // Display only posts from a certain taxonomies
    $query->set( 'tax_query', array(
    array( 'taxonomy' => 'genre',
    'field' => 'slug',
    'terms' => array ( 'fantasy', 'sci-fi' )
    )
    ) );
    return;
  }
}
// Add our function to the pre_get_posts hook
add_action( 'pre_get_posts', 'ssws_pre_get_posts' );
// http://www.slideshare.net/anthonyhortin/wordpress-queries-the-right-way


/********************************************************/
// Add Font Awesome CDN
/********************************************************/
add_action( 'wp_enqueue_scripts', 'prefix_enqueue_awesome' );
/**
* Register and load font awesome CSS files using a CDN.
*
* @link http://www.bootstrapcdn.com/#fontawesome
* @author FAT Media
*/
function prefix_enqueue_awesome() {
wp_enqueue_style( 'prefix-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css', array(), '4.0.3' );
// http://ozzyrodriguez.com/tutorials/font-awesome-wordpress-cdn/


/********************************************************/
// GPS track files allowed
/********************************************************/
function cc_mime_types($mimes) {
  $mimes['gpx'] = 'text/gpx+xml';
  $mimes['gdb'] = 'text/gdb+xml';
  $mimes['kml'] = 'text/kml+xml';
  $mimes['kmz'] = 'text/kmz+xml';
  $mimes['tcx'] = 'text/tcx+xml';
  $mimes['hst'] = 'text/hst+xml';
  $mimes['gtm'] = 'text/gtm+xml';
  $mimes['gpi'] = 'text/gpi+xml';
  $mimes['wpt'] = 'text/wpt+xml';
  $mimes['wpo'] = 'text/wpo+xml';
  $mimes['trk'] = 'text/trk+xml';
  $mimes['gpb'] = 'text/gpb+xml';
  $mimes['dfx'] = 'text/dfx+xml';
  $mimes['dat'] = 'text/dat+xml';
  $mimes['mps'] = 'text/mps+xml';
  $mimes['tk'] = 'text/tk+xml';
  $mimes['wp'] = 'text/wp+xml';
  $mimes['swf'] = 'text/swf+xml'; //Flash

  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');
// http://blog.chrismeller.com/modifying-allowed-upload-types-in-wordpress


/********************************************************/
/* Load a Script from a Child Theme without Dependencies
/********************************************************/
// http://codex.wordpress.org/Function_Reference/wp_enqueue_script
  
//questo e' per caricare un .js di tuo gusto:
//in questo caso jquery.lettering-0.6.1.min.js per testare quello che mi hai indicato per l'ultima lettera colorata
add_action( 'wp_enqueue_scripts', 'child_add_scripts' );

/**
 * Register and enqueue a script that does not depend on a JavaScript library.
 */
function child_add_scripts() {
    wp_register_script(
        'jquery-lettering',
        get_stylesheet_directory_uri() . '/js/jquery.lettering-0.6.1.min.js',
        false,
        '1.0',
        true
    );

    wp_enqueue_script( 'jquery-lettering' );
}
// it requires jQuery(document) instead of $(document) or better:
// wrap your js into this function 
// jQuery(document).ready(function($) {
//    //some js code within here
// });
// http://codex.wordpress.org/Function_Reference/wp_enqueue_script#jQuery_noConflict_Wrappers


/********************************************************/
// Redirect To Post If Search Results Return One Post
/********************************************************/

// http://www.wpthemedetector.com/useful-code-snippets-wordpress/
add_action('template_redirect', 'redirect_single_post');
function redirect_single_post() {
    if (is_search()) {
        global $wp_query;
        if ($wp_query->post_count == 1 && $wp_query->max_num_pages == 1) {
            wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );
            exit;
        }
    }
}
//source: http://www.paulund.co.uk/redirect-search-results-return-one-post


/********************************************************/
// Add a widget in WordPress Dashboard
/********************************************************/
function wpc_dashboard_widget_function() {
  // Entering the text between the quotes
  echo "<ul>
    <li>Launch Date: February 2015</li>
    <li>Author: Ignition Strategic Creative</li>
    <li>Hosting Provider: Media Temple</li>
  </ul>";
}
function wpc_add_dashboard_widgets() {
  wp_add_dashboard_widget('wp_dashboard_widget', 'Technical Information', 'wpc_dashboard_widget_function');
}
add_action('wp_dashboard_setup', 'wpc_add_dashboard_widgets' );


/********************************************************/ 
/* Use a Local or External Copy of Font Awesome
/********************************************************/  
//enqueues our external font awesome stylesheet
function enqueue_our_required_stylesheets(){
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
}
add_action('wp_enqueue_scripts','enqueue_our_required_stylesheets');
// http://www.sitepoint.com/using-font-awesome-with-wordpress/


/********************************************************/ 
/* Redirect a category or any CPT or taxonomy
/********************************************************/  
add_action( 'template_redirect' , function() {
  if ( is_category( 'news' ) ){
    wp_redirect( 'http://xzy.com/news/', 301 ); exit;
  }
});


/********************************************************/
//Show Featured Image in the Post page
/********************************************************/

add_filter( 'tc_content_headings_separator', 'ssws_thumbnail_post_image' ); //output the thumbnail after the title and the entry-meta
//add_filter( '__after_content', 'ssws_thumbnail_post_image' );  //output the thumbnail after the content and before the comments
//add_filter( '__before_content', 'ssws_thumbnail_post_image' ); //output the thumbnail before the title and after the breadcrumb
function ssws_thumbnail_post_image() {
  if ( is_single() && has_post_thumbnail() ) { //check if we are in the single post page && if the post has a featured image assigned to it.

      //echo '<hr class="featurette-divider">';
      echo '<div class="debug post-thumbnail-container">'; //let's wrap the thumbnail in a div that can be styled
      echo the_post_thumbnail();
      echo '</div>';
      //echo '<hr class="featurette-divider">';
  }
}
//https://wordpress.org/support/topic/featured-images-to-show-in-post


// ============================================================ //
// Add thumbnail (where available) before title on pages and    //
// and single posts. Change span2 and span10 (in two places)    //
// to change layout (spans must add up to 12)                   //
// ============================================================ //
// https://wordpress.org/support/topic/display-post-featured-image-below-title

// Add the thumbnail before the content title
add_action( '__before_content_title' , 'ssws_extra_thumbnail_on_posts_and_pages', 5);
function ssws_extra_thumbnail_on_posts_and_pages() {
  if ( is_single()  && has_post_thumbnail() ) {

    ob_start();
      echo '<div class="span3 my-extra-thumbnail" style="display: inline-block; vertical-align: top;">';

          the_post_thumbnail('medium'); //can be small, medium, large

      echo '</div>';

    $image = ob_get_contents();

    ob_end_clean();

    echo $image;
  }

}

// Add row-fluid class to the page header, in order to add bootstrap span classes to thumbnail and heading/meta
add_filter( 'tc_content_header_class', 'ssws_content_header_class' );
function ssws_content_header_class($content_header_class) {
  if ( is_single()  && has_post_thumbnail() ) {

    $content_header_class = $content_header_class . ' row-fluid';

  }

  return $content_header_class;

}
// Add a bootstrap span class to the page title when on a single blog entry or a page -- bit of a hack, as it latches on to the code to add the format-icon class
add_filter( 'tc_content_title_icon', 'ssws_content_title_icon' );
function ssws_content_title_icon($icon_class) {
  if ( is_single()  && has_post_thumbnail() ) {

    $icon_class = $icon_class . ' span9';

  }

  return $icon_class;

}

// add a bootstrap span class to post metas when on a single blog entry or a page
add_filter( 'tc_post_metas', 'ssws_post_metas' );
function ssws_post_metas($html) {
  if ( is_single()  && has_post_thumbnail() ) {

    $html = str_replace('<div class="entry-meta">', '<div class="entry-meta span9">', $html);

  }

  return $html;

}
// ============================================================ //
// End of post/page thumbnail code                              //
// ============================================================ //
// ssws note:
// the original snippet from Rocco used is_singular() I changed to is_single() to affect only posts
// is_single() returns true if any single post is being displayed 
// is_singular() returns true when any page, attachment, or single post is being displayed.
// https://wordpress.org/support/topic/display-post-featured-image-below-title



/********************************************************/
// Changing the default image sizes in Customizr-Pro
/********************************************************/
// old snippet [2014]:
// still works with CTZ Pro 2.1.3
// http://presscustomizr.com/snippet/changing-default-image-sizes-customizr/

// for fp thumbnails:

add_filter( 'fpc_size', 'ssws_thumb_size');
function ssws_thumb_size() {
    $sizeinfo = array( 'width' => 370 , 'height' => 200, 'crop' => false );
    return $sizeinfo;
}
// fpc_size vs. tc_thumb_size [customizr-free]

// it requires some custom styles dependings on the theme version, the size and on the layout settings:

// .home .fpc-widget-front .round-div, {
//     border-top-color: rgba(0,0,0,0) !important;
//     border-right-color: rgba(0,0,0,0) !important;
//     border-bottom-color: rgba(0,0,0,0) !important;
//     border-left-color: rgba(0,0,0,0) !important;
// }

// .fp-thumb-wrapper .czr-link-mask::before {
//   display: none !important;
// }

// .featured-page .fp-thumb-wrapper {
//     max-width: 60%;
// }

// .fpc-widget-front .thumb-wrapper {
//     width: 100%;
//     height: auto !important;
// }

// for sliders:

add_filter( 'tc_slider_size', 'ssws_boxed_slider_size'); // boxed slider
function ssws_boxed_slider_size() {
    $sizeinfo = array( 'width' => 1170 , 'height' => 800, 'crop' => true );
    return $sizeinfo;
}

add_filter( 'tc_slider_full_size', 'ssws_fullWidth_slider_size'); // full-width slider
function ssws_fullWidth_slider_size() {
    $sizeinfo = array( 'width' => 99999 , 'height' => 800, 'crop' => true );
    return $sizeinfo;
}

// http://docs.presscustomizr.com/article/36-image-sizes-in-the-customizr-theme
// used on the 2017 Unifor Media One website


/********************************************************/
// Adding shortcode to a slider text [Customizr]
/********************************************************/
add_filter('tc_slide_text_length', 'ssws_tc_slide_text_length');
function ssws_tc_slide_text_length(){
    return 500; /*change this value to suit your needs*/
}

add_filter('tc_slider_display', 'do_ssws_shortcode');
function do_ssws_shortcode($html){
    return do_shortcode(html_entity_decode($html));
}

add_shortcode('htmlify', 'do_html');
function do_html($attr, $content){
    return str_replace(array('{','}'), array('<','>'), $content);
}
// Then in the slide's description box put something like this:
// [htmlify]{/p}{ul class="ssws_class"}{li}first{/li}{li}second{/li}{/ul}{p}[/htmlify]
// or [htmlify]{/p}{p class="ssws_class lead"}SOMETHING BEFORE{span class="red-link"} >[/htmlify]
// or [htmlify]{/p}{h1 class="ssws_class lead"}This text for more information{span class="red-link"} >{/span}{/h1}[/htmlify]

// https://wordpress.org/support/topic/adding-shortcode-to-a-sliders-text


/********************************************************/
// Restrict the post navigation to the same category [Customizr]
/********************************************************/
add_filter('tc_previous_single_post_link_args', 'navigate_in_same_taxonomy');
add_filter('tc_next_single_post_link_args', 'navigate_in_same_taxonomy');
function navigate_in_same_taxonomy( $args ){
  $args['in_same_term'] = true;
  return $args;
}
//http://presscustomizr.com/snippet/restrict-post-navigation-category/


/****************************************************************/ 
/* Change Breadcrumb trail 'Home' to 'Custom Word' or Font Awesome icon
/****************************************************************/
add_filter( 'tc_breadcrumb_trail_args', 'ssws_breadcrumb_home_word' );
function ssws_breadcrumb_home_word($args) {
    $new_args = array(
             'container'  => 'div' ,        // div, nav, p, etc. [without this parameter it won't work]
             'separator'  => '→' ,          // default is » choose any special character you want: |, >, ⇒, ->, ~, etc. 

             // 'show_home'  => __( 'Some Name ' , 'customizr' )  //this option is for outputting a text
             'show_home'  => '<span class="fa fa-home"></span>'  // this option is for font awesome http://fortawesome.github.io/Font-Awesome/icon/home/
         );
    return array_merge($args, $new_args);
}
// http://presscustomizr.com/snippet/change-word-home-breadcrumb-trail/


/********************************************************/
// Adding Dashicons in WordPress Front-end
/********************************************************/
add_action( 'wp_enqueue_scripts', 'load_dashicons_front_end' );
function load_dashicons_front_end() {
  wp_enqueue_style( 'dashicons' );
}

/********************************************************/ 
/* Insert Extra Info in the Footer
/********************************************************/ 
function insert_koine_info() {

   $output = '<div class="koine-info">' 

      . '<address>'
        . '<i class="fa fa-map-marker"></i> '
        . '<a href="https://www.google.com/maps/place/Corso+Regina+Margherita,+153,+10122+Torino,+Italy/@45.0798722,7.6733892,17z/data=!3m1!4b1!4m2!3m1!1s0x47886da0b2738c7b:0xaf3557cb1f60b6fa" target="_blank"> Corso Regina Margherita, 153 - 10122 TORINO - ITALY</a>' 
        . '<span class="sep">  |  </span>'

        . ' <i class="fa fa-envelope"></i> '
        . '<a href="mailto:koine@koinesistemi.it"> koine@koinesistemi.it</a>'
        . '<span class="sep">  |  </span>'

        . ' <i class="fa fa-phone"></i> '
        . '<a href="tel:0039-011-521-2496"> 011.521.24.96</a>' //Tel. 011.521.24.96 - Fax 011.436.87.15
        . '<span class="sep">  |  </span>'

        . ' <i class="fa fa-fax"></i> '
        . '<a href="tel:0039-011-436-8715"> 011.436.87.15</a>' //Tel. 011.521.24.96 - Fax 011.436.87.15
        . '<span class="sep">  |  </span>'

        . ' <i class="fa fa-tag"></i>'
        . ' P.IVA e C.F. 07499390016'
      . '</addres>'

   . '</div><!-- .koine-info -->'
   . '<hr>';

   echo do_shortcode( $output );
}

add_action( 'wp_footer', 'insert_koine_info', 100);
// add_action( 'simone_credits', 'insert_koine_info', 20 ); //in case we have a hook!

//https://codex.wordpress.org/Plugin_API/Action_Reference/wp_footer


/************************************/
/* Change Search Button Text
/************************************/

// Add to your child-theme functions.php
add_filter('get_search_form', 'ssws_search_form_text');
 
function ssws_search_form_text($text) {
     $text = str_replace('value="Search"', 'value="Click me"', $text); //set as value the text you want
     return $text;
}

// the 'value="Search"' needs to be replaced accordingly to the default WP language installation
// 'value="Cerca"' for Italian
// 'value="Suche"' for German and so on...


/********************************************************/
/* Add a custom Phone Number icon to the Social Icons
/********************************************************/

// Header section
add_filter ( 'tc_social_in_header' , 'custom_icon_phone_number' );
function custom_icon_phone_number() {
  //class
  $class =  apply_filters( 'tc_social_header_block_class', 'span5' );
  ob_start();
?>
  <div class="social-block <?php echo $class ?>">
    <?php if ( 0 != tc__f( '__get_option', 'tc_social_in_header') ) : ?>
      <?php echo tc__f( '__get_socials' ) ?>
        <a class="social-icon" href="tel:+1 123-456-7890" title="Call us" target="_self"><span class="fa fa-phone"></span></a>
      <?php endif; ?>
  </div><!--.social-block-->
<?php
  $html = ob_get_contents();
  ob_end_clean();
  return $html;
}

// Footer section
add_filter ( 'tc_colophon_left_block' , 'custom_icon_phone_number_footer' );
function custom_icon_phone_number_footer() {
  $class =  apply_filters( 'tc_colophon_left_block_class', 'span3' );
  ob_start();
?>
  <div class="social-block <?php echo $class ?>">
    <?php if ( 0 != tc__f( '__get_option', 'tc_social_in_footer') ) : ?>
      <?php echo tc__f( '__get_socials' ) ?>
        <a class="social-icon" href="tel:+1 123-456-7890" title="Call us" target="_self"><span class="fa fa-phone"></span></a>
      <?php endif; ?>
  </div><!--.social-block-->
<?php
  $html = ob_get_contents();
  ob_end_clean();
  return $html;
}

// Sidebar section
add_filter ( 'tc_social_in_sidebar' , 'custom_icon_phone_number_sidebar' );
function custom_icon_phone_number_sidebar() {
  $class =  apply_filters( 'tc_sidebar_block_social_class', 'widget_social' );
  ob_start();
?>
  <div class="social-block <?php echo $class ?>">
    <?php if ( 0 != tc__f( '__get_option', 'tc_social_in_left-sidebar') ) : ?>
      <?php echo tc__f( '__get_socials' ) ?>
        <a class="social-icon" href="tel:+1 123-456-7890" title="Call us" target="_self"><span class="fa fa-phone"></span></a>
      <?php endif; ?>
  </div><!--.social-block-->
<?php
  $html = ob_get_contents();
  ob_end_clean();
  return $html;
}

// css
/*new social icons - phone number*/
// .icon-phone_number:before {
//     content: "\f50c"; /* we can use any genericon */
// }
// http://presscustomizr.com/snippet/adding-custom-social-profile-link-icon-header/


/********************************************************/
// disable WordPress Heartbeat API completely
/********************************************************/
add_action( 'init', 'stop_heartbeat', 1 );
function stop_heartbeat() {
 wp_deregister_script('heartbeat');
}
// http://www.inspire2rise.com/how-to-disable-wordpress-heartbeat-api.html


/***********************************************************/
// Change the Number of Posts in the Category landing pages
/***********************************************************/
add_filter('pre_get_posts', 'posts_in_category');

function posts_in_category($query){
  if ($query->is_category) {
    $query->set('posts_per_archive_page', 9);
  }
}
// https://www.webhostinghero.com/change-the-number-of-posts-category-wordpress/


/********************************************************/
// * Enables the Excerpt meta box in Page edit screen.
/********************************************************/
function wpcodex_add_excerpt_support_for_pages() {
  add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'wpcodex_add_excerpt_support_for_pages' );



/********************************************************/
// Load a Script to disable links on pages with no landing content
/********************************************************/
add_action('wp_footer', 'add_disableLinks');
function add_disableLinks() { 
  ?>
    <script>
      jQuery(document).ready(function($) {
        $("#menu-item-274, #menu-item-315").children("a").attr('href', "javascript:void(0)");

        // disable links with a class .no-landing
        $(".no-landing").children("a").attr('href', "javascript:void(0)");

        // disable targeted links e.g. breadcrumb trail
        $("a[href='http://www.cbc.com'], a[href='http://www.cnn.com']").attr('href', "javascript:void(0)");
      });
    </script>
  <?php 
}


/********************************************************/
// Mark parent navigation active when on custom post type single page and landing page
/********************************************************/
// used on koine (Perth Pro) 2016
// highlight active custom post page in nav
add_action('nav_menu_css_class', 'add_current_nav_class', 10, 2 );
  
function add_current_nav_class($classes, $item) {
  
  // Getting the current post details
  global $post;
  
  // Getting the post type of the current post
  $current_post_type = get_post_type_object(get_post_type($post->ID));
  $current_post_type_slug = $current_post_type->rewrite['slug'];
    
  // Getting the URL of the menu item
  $menu_slug = strtolower(trim($item->url));
  
  // If the menu item URL contains the current post types slug add the current-menu-item class
  if (strpos($menu_slug,$current_post_type_slug) !== false) {
  
     $classes[] = 'current-menu-item ssws-test';
  
  }
  
  // Return the corrected set of classes to be added to the menu item
  return $classes;

}
// https://gist.github.com/gerbenvandijk/5253921


/********************************************************/
// Open External Links In New Window
/********************************************************/
add_action('wp_footer', 'add_openExternalLinksNewTab');
function add_openExternalLinksNewTab() {
  ?>
    <script>
      jQuery(document).ready(function($) {
        $('a').each(function() {
           var a = new RegExp('/' + window.location.host + '/');
           if(!a.test(this.href)) {
               $(this).click(function(event) {
                   event.preventDefault();
                   event.stopPropagation();
                   window.open(this.href, '_blank');
               });
           }
        });
      });
    </script>
  <?php
}
// https://css-tricks.com/snippets/jquery/open-external-links-in-new-window/


/********************************************************/
//  Allowing Hyperlinks in Your WordPress Excerpts
//  www.lewayotte.com/2010/09/22/allowing-hyperlinks-in-your-wordpress-excerpts
/********************************************************/
function new_wp_trim_excerpt($text) {
  $raw_excerpt = $text;
  if ( '' == $text ) {

// SSWS
//  this change the lenght from words to characters 

    $text = get_the_content('');            // original snippet
    //$text = substr( get_the_content('') , 0, 775);  //ssws snippet from Scott code

    $text = strip_shortcodes( $text );

    $text = apply_filters('the_content', $text);
    $text = str_replace(']]>', ']]>', $text);
    $text = strip_tags($text, '<a>');
    

    $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');

    $excerpt_length = apply_filters('excerpt_length', 55);
    $words = preg_split('/(<a.*?a>)|\n|\r|\t|\s/', $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE );
    if ( count($words) > $excerpt_length ) {
      array_pop($words);
      $text = implode(' ', $words);
      $text = $text . $excerpt_more;
    } else {
      $text = implode(' ', $words);
    }
  }
  return apply_filters('new_wp_trim_excerpt', $text, $raw_excerpt);

}
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'new_wp_trim_excerpt');
//  www.lewayotte.com/2010/09/22/allowing-hyperlinks-in-your-wordpress-excerpts


/********************************************************/
// ADDING AN UPDATE STATUS NEXT TO POST TITLES IN WORDPRESS, 2 options:
/********************************************************/
function add_update_status($html) {
    //First checks if we are in the loop and we are not displaying a page
    if ( ! in_the_loop() || is_page() )
        return $html;
 
    //Instantiates the different date objects
    $created = new DateTime( get_the_date('Y-m-d g:i:s') );
    $updated = new DateTime( get_the_modified_date('Y-m-d g:i:s') );
    $current = new DateTime( date('Y-m-d g:i:s') );
 
    //Creates the date_diff objects from dates
    $created_to_updated = date_diff($created , $updated);
    $updated_to_today = date_diff($updated, $current);
 
    //Checks if the post has been updated since its creation
    $has_been_updated = ( $created_to_updated -> s > 0 || $created_to_updated -> i > 0 ) ? true : false;
 
    //Checks if the last update is less than n days old. (replace n by your own value)
    $has_recent_update = ( $has_been_updated && $updated_to_today -> days < 90 ) ? true : false;
 
    //Adds HTML after the title
    $recent_update = $has_recent_update ? '<span class="label label-warning">Recently updated</span>' : '';
 
    //Returns the modified title
    return $html.'&nbsp;'.$recent_update;
}


// for new posts I have modified my functions.php 
//in the child theme and sort all posts by last update

add_filter('the_title' , 'add_created_status'); // hook for all WP themes
//add_filter('tc_content_headings' , 'add_created_status'); //hook only for Customizr
function add_created_status($html) {
//First checks if we are in the loop and we are not displaying a page
if ( ! in_the_loop() || is_page() )
return $html;

//Instantiates the different date objects
$created = new DateTime( get_the_date('Y-m-d g:i:s') );
$current = new DateTime( date('Y-m-d g:i:s') );

//Creates the date_diff objects from dates
$created_to_today = date_diff($created, $current);

//Checks if the post is "fresh"
$has_been_created = ( $created_to_today -> s > 0 || $created_to_today -> i > 0 ) ? true : false;

//Checks if the post ist n days old to remove NEW-status (replace n by your own value)
$is_new_post = ( $has_been_created && $created_to_today -> days < 7 ) ? true : false;

//Adds HTML after the title
$recent_post = $is_new_post ? '<span class="label label-success">NEW</span>' : '';

//Returns the modified title
return $html.' '.$recent_post;
}
// End of ADDING AN UPDATE STATUS NEXT TO POST TITLES IN WORDPRESS, 2 options


/********************************************************/
// CUSTOMIZR UPDATE TC_ to CZR_
/********************************************************/
/*
PHP classes and methods have been renamed and made consistent accross the theme :
    class prefixes have been changed from TC_ to CZR_
    method prefixes have been also changed from tc_ to czr_fn_
One exception : the function tc__f() has been kept unchanged for retro-compatibility.

// http://presscustomizr.com/customizr-pro-v1-2-30-customizr-free-v3-4-30-release-note/
// tc__f
// czr_fn__f
*/


/********************************************************/
// How to add "active" class to wp_nav_menu() current-menu-item (simple way)
/********************************************************/
add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);

function special_nav_class ($classes, $item) {
    if (in_array('current-menu-item', $classes) ){
        $classes[] = 'active ';
    }
    return $classes;
}
// https://codex.wordpress.org/Plugin_API/Filter_Reference/nav_menu_css_class


/********************************************************/
// WooCommerce hide or show prices for users
/********************************************************/
// add_filter('woocommerce_get_price_html','members_only_price');
function members_only_price($price){
if(is_user_logged_in() ){
return $price;
}
else return '<a class="price-login" href="/?p=195">Login</a> o <a class="price-login" href="/?p=195">Registrati</a> per vedere i prezzi';
}
// to show prices
// add filter( 'woocommerce_show_variation_price', '__return_true' );


/********************************************************/
// Install Facebook Pixel Code in WordPress
/********************************************************/
add_action('wp_head', 'add_pixelcode');
function add_pixelcode() { ?>
  <!-- Facebook Pixel Code -->
  <script>
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
    n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
    document,'script','https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1234567890987654321'); // Insert your pixel ID here.
    fbq('track', 'PageView');
  </script>
  <noscript>
    <img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id=1234567890987654321&ev=PageView&noscript=1"
    />
  </noscript>
  <!-- DO NOT MODIFY -->
  <!-- End Facebook Pixel Code -->
<?php 
}


/********************************************************/ 
/* Apply Custom CSS to Admin Area
/********************************************************/ 
/* remove warning in Customizr editor */
/* ".../customizr-pro/addons/wfc/front/assets/css/dyn-style.php?is_customizing=false" */
add_action('admin_head', 'custom_remove_error_message');

function custom_remove_error_message() {
  echo '<style>
    .mce-widget.mce-notification.mce-notification-error.mce-has-close {
    display: none;
  }
  </style>';
}
// https://css-tricks.com/snippets/wordpress/apply-custom-css-to-admin-area/
/* https://wordpress.org/support/topic/error-failed-to-load-content-css/page/2/#post-9371633 */


/********************************************************/
// Remove the “ver” parameter from all enqueued CSS and JS files
/********************************************************/
function ssws_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'ssws_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'ssws_remove_wp_ver_css_js', 9999 );
// https://www.virendrachandak.com/techtalk/how-to-remove-wordpress-version-parameter-from-js-and-css-files/


/****************************************************************/
// Style widgets in WP dashboard
/****************************************************************/
add_action('admin_head', 'ssws_wp_dashboard_custom_styles');

function ssws_wp_dashboard_custom_styles() {
  echo '<style>
    .widgets-php .rpwe-columns-3:first-child,
    #widget-rpwe_widget-2-css {
      display: none;
    }
  </style>';
}


/********************************************************/
// Remove the “ver” parameter from all enqueued CSS and JS files
/********************************************************/
function ssws_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'ssws_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'ssws_remove_wp_ver_css_js', 9999 );
/* https://www.virendrachandak.com/techtalk/how-to-remove-wordpress-version-parameter-from-js-and-css-files/ */
// SSWS: though the versioning is quite important for caching


/********************************************************/
// add custom key/value pair to WP Rest
/********************************************************/
function ssws_custom_rest()
{
    register_rest_field('post', 'authorName', array(
        'get_callback' => function () {return get_the_author();},
    ));
    register_rest_field('trackCPT', 'trackCPTName', array(
        'get_callback' => function () {return get_the_title();},
    ));
}

add_action('rest_api_init', 'ssws_custom_rest');
// requires this line in the CPT registration code
// 'show_in_rest' => true,