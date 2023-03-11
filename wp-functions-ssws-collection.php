<?php

// SSWS WordPress functions collection 

/********************************************************/
// Add Google Fonts
/********************************************************/
add_action( 'wp_enqueue_scripts', 'ssws_google_font' );
function ssws_google_font() {
	wp_enqueue_style( $handle = 'ssws-google-font', $src = 'http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic', $deps = array(), $ver = null, $media = null );
}
//<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>

/********************************************************/
// Add Google Analytics script
/********************************************************/
add_action('wp_footer', 'ssws_Add_GoogleAnalytics');
function ssws_Add_GoogleAnalytics() {
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

function gtag() {
    dataLayer.push(arguments);
}
gtag('js', new Date());

gtag('config', 'UA-11xxxxxx-1');
</script>
<?php
     }
  }
}
// this needs to be implemented with a custom input field via customizr to keep the key separated from the theme


/********************************************************/
// Replaces the excerpt "more" text or [...] with a custom link
/********************************************************/
    function ssws_excerpt_more($more) {
           global $post;
      // return '<a class="moretag" href="'. get_permalink($post->ID) . '"&gt; READ MORE</a>';
           return ' [... <a class="moretag" href="'. get_permalink($post->ID) . '">read more &gt;</a>]';
    }

    add_filter('excerpt_more', 'ssws_excerpt_more');
    /*end of MORE*/

/******************************************************/
// Replaces the excerpt [...] with ... text by a link
/******************************************************/
    function ssws_excerpt_read_more_link($more)
    {
        global $post;
        return '<a class="moretag" href="' . get_permalink($post->ID) . '">...</a>';
    }
    add_filter('excerpt_more', 'ssws_excerpt_read_more_link');

    
/********************************************************/
// Change WordPress Excerpt length
/********************************************************/
function ssws_custom_excerpt_length( $length ) {
  return 20;
}
add_filter( 'excerpt_length', 'ssws_custom_excerpt_length', 999 );


//Two Different Excerpt Length's

// SSWS working:
function ssws_custom_excerpt($new_length = 20) {
  
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
//<?php ssws_custom_excerpt(55, '<a class="moretag" href="' . get_permalink( get_the_ID() ) . '"&gt; READ MORE</a>') ? >
//ssws 02/2015:
//<?php ssws_custom_excerpt(55) ? > // works as well
//started from http://wordpress.org/support/topic/two-different-excerpt-lengths
//implemented by Scott and SSWS


/********************************************************/
  // Add menus to Customizr
/********************************************************/

  add_action( 'init', 'ssws_register_secondary_menu' ); // this registers your menu with WP
  function ssws_register_secondary_menu() {
      if ( function_exists( 'register_nav_menu' ) ) {
          register_nav_menu( 'secondary-menu', 'Secondary Menu' );
      }
  }
  // Select the add_action you need, depending on where you want to add the menu and disable the rest:
  add_action('__before_header', 'ssws_display_secondary_menu');     // use this line to add above header
  //add_action('__header', 'ssws_display_secondary_menu', 1000, 0);     // use this to add after header
  //add_action('__before_footer', 'ssws_display_secondary_menu');     // use this line to add above footer
  //add_action('wp_footer', 'ssws_display_secondary_menu', 1000, 0);     // use this to add before credits

//http://themesandco.com/snippet/adding-extra-menus-customizr/
  
/*SSWS 
  the secondary menu it is displayed manually in the footer hooks file*/
  
  // display function for your menu:
  function ssws_display_secondary_menu() {
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
//  Excludes certain categories from category widget
/********************************************************/
function ssws_exclude_widget_categories_args_filter( $cat_args ) {
    $exclude_arr = array( 843, 838 ); //here the id of the categories to exclude
    
    if( isset( $cat_args['exclude'] ) && !empty( $cat_args['exclude'] ) )
        $exclude_arr = array_unique( array_merge( explode( ',', $cat_args['exclude'] ), $exclude_arr ) );
    $cat_args['exclude'] = implode( ',', $exclude_arr );
    return $cat_args;
}

add_filter( 'widget_categories_args', 'ssws_exclude_widget_categories_args_filter', 10, 1 );

// Semplified version
/** Remove category from widget list */
function ssws_remove_widget_categories($args) {
  $exclude = '36, 44, 1';
  $args['exclude'] = $exclude;
  return $args;
}
add_filter('widget_categories_args','ssws_remove_widget_categories');


/********************************************************/
//  Includes certain categories
/********************************************************/
function ssws_widget_includes_categories_args_filter( $cat_args ) {
    $include_arr = array( 843, 838 ); //here the id of the categories to include
    
    if( isset( $cat_args['include'] ) && !empty( $cat_args['include'] ) )
        $include_arr = array_unique( array_merge( explode( ',', $cat_args['include'] ), $include_arr ) );
    $cat_args['include'] = implode( ',', $include_arr );
    return $cat_args;
}

add_filter( 'widget_categories_args', 'ssws_widget_includes_categories_args_filter', 10, 1 );
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
function ssws_search_form( $form ) {

    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
    <div><label class="screen-reader-text" for="s">' . __('Search for:') . '</label>
    <input type="text" value="' . get_search_query() . '" name="s" id="s" />
    <input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
    </div>
    </form>';

    return $form;
}
add_shortcode('ssws-search', 'ssws_search_form');

//http://www.wpbeginner.com/wp-tutorials/how-to-add-search-form-in-your-post-with-a-wordpress-search-shortcode/

// requires a shortcode in the header file
/*<?php echo do_shortcode('[ssws-search]'); ?>*/

//search form in the header can be easly replaced with this:


/********************************************************/
//ADDING AN HTML5 SEARCH FORM IN YOUR WORDPRESS MENU
/********************************************************/
// As of 3.1.10, Customizr doesn't output an html5 form.
add_theme_support( 'html5', array( 'search-form' ) );

add_filter('wp_nav_menu_items', 'ssws_add_search_form_to_menu', 10, 2);
function ssws_add_search_form_to_menu($items, $args) {

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
add_filter('tc_menu_display', 'ssws_menu_display');
function ssws_menu_display($output) {
return preg_replace('|<span class="icon-bar"></span>|', null, $output);
}
// requires css content: "Menu";
// http://themesandco.com/snippet/add-menu-text-3-bar-menu-button/


/********************************************************/
//
<!-- http://wordpress.stackexchange.com/questions/16070/how-to-highlight-search-terms-without-plugin -->
//
<!-- HIGHLIGHT THE SEARCH TERMS IN RESULTS -->
/********************************************************/
function ssws_search_excerpt_highlight() {
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

//
<!-- <h2><a href="<?php the_permalink() ? >" rel="bookmark"><?php the_title(); ? ></a></h2> -->
//<h2><a href="<?php the_permalink() ? >" rel="bookmark"><?php search_title_highlight(); ? ></a></h2>

      //<?php //the_excerpt(); ? >

      //<!-- this shows only the excerpt -->
      //<?php ssws_search_excerpt_highlight(); ? >

      //<!-- this shows highlighted results in all the content -->
      //<?php search_content_highlight(); ? >

//<!-- http://wordpress.stackexchange.com/questions/16070/how-to-highlight-search-terms-without-plugin -->


//different version of the higlighted search function, it does not requires snippets:

//http://bradsknutson.com/blog/highlight-search-terms-wordpress/
function ssws_highlight_search_term($text){
    if(is_search()){
    $keys = implode('|', explode(' ', get_search_query()));
    $text = preg_replace('/(' . $keys .')/iu', '<span class="search-term">\0</span>', $text);
  }
    return $text;
}
add_filter('the_excerpt', 'ssws_highlight_search_term');
add_filter('the_title', 'ssws_highlight_search_term');
add_filter('the_content', 'ssws_highlight_search_term');

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
            <div class=" container outside">
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
function ssws_custom_login_logo() {
echo '
<style type="text/css">
      h1 a { background-image:url(/images/logo.jpg) !important; }
  </style>
';
}
add_action('login_head', 'ssws_custom_login_logo');

//Changing the default WordPress login URL

function ssws_login_logo_url() {
    return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'ssws_login_logo_url' );

function ssws_login_logo_url_title() {
    return 'Your Site Name and Info';
}
add_filter( 'login_headertext', 'ssws_login_logo_url_title' );
//Here, the get_bloginfo(‘url’) is the URL of your blog, you can change that with whatever URL you may like, but don’t forget to add it between quotes.



/********************************************************/
// Change Login Logo ver. 2.0
/********************************************************/
function ssws_login_logo() { ?>
        <style type="text/css">
        #login h1 a,
        .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri();
            ?>/images/logo.png);
            padding-bottom: 2em;
            background-size: contain;
            width: auto;
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
  return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'ssws_login_logo_url_title' );

// Change the Redirect URL (ver. Standard WP)
function ssws_admin_login_redirect( $redirect_to, $request, $user ) {
  global $user;
  if( isset( $user->roles ) && is_array( $user->roles ) ) {
    if( in_array( "administrator", $user->roles ) ) {
      return $redirect_to;
    } else {
      // standard WP
      return home_url();

      // WP Multisite
      // return network_home_url();
    }
  }
  else
  {
    return $redirect_to;
  }
}
add_filter("login_redirect", "ssws_admin_login_redirect", 10, 3);

// Change the Redirect URL (ver. Multisite WP)
function ssws_multisite_login_redirect($redirect_to, $request_redirect_to, $user)
{
  global $user;
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		$user_info = get_userdata($user->ID);
		if ($user_info->primary_blog) {
			$primary_url = get_blogaddress_by_id($user_info->primary_blog);
			if ($primary_url) {
				wp_redirect($primary_url);
				die();
			}
		}
	}
	return $redirect_to;
}
add_filter('login_redirect', 'ssws_multisite_login_redirect', 100, 3);

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
// Customize Login Screen ver. 3.0
/********************************************************/
add_filter('login_headerurl', 'SSWSHeaderUrl');

function SSWSHeaderUrl()
{
    return esc_url(site_url('/'));
}

add_action('login_enqueue_scripts', 'SSWSLoginCSS');

function SSWSLoginTitle()
{
    return get_bloginfo('name');
}

function SSWSLoginCSS()
{
    wp_enqueue_style('ssws_main_styles', get_stylesheet_uri());
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
}

add_filter('login_headertext', 'SSWSLoginTitle');


/********************************************************/
// Make Archives.php Include Custom Post Types
/********************************************************/

// this snippet can be pasted within the custom plugin created to output the CPT,
// assuming we are creating a plugin instead of embedding the CPT into functions.php
function ssws_add_custom_types( $query ) {
  if( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
    $query->set( 'post_type', array(
     'post', 'nav_menu_item', 'your-custom-post-type-here'
    ));
    return $query;
  }
}
add_filter( 'pre_get_posts', 'ssws_add_custom_types' );
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
        jQuery(document).ready(function() {
            ! function($) {

                var $thumbnails = $('article section[class*="tc-thumbnail"]'),
                    $contents = $('article section[class*="tc-content"]').not(".span12"),
                    reordered = false;

                //reordering function
                function reordering(reorder) {
                    var position = '<?php echo $tb_position; ?>',
                        iterator = ((position == "before" && reorder) || (position != "before" && !reorder)) ?
                        $thumbnails : $contents;

                    reordered = reorder;

                    iterator.each(
                        function() {
                            if ($(this).next().is('section') || (!reorder && !$(this).parent().hasClass(
                                    'reordered')))
                                return;

                            $(this).prependTo($(this).parent());
                            $(this).parent().toggleClass('reordered');
                        }
                    );
                }

                function reorder_or_revert() {
                    if ($thumbnails.width() == $thumbnails.parent().width() && !reordered)
                        reordering(true);
                    else if ($thumbnails.width() != $thumbnails.parent().width() && reordered)
                        reordering(false);
                }

                reorder_or_revert();

                $(window).resize(function() {
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
// 01
function ssws_exclude_home_category($query) {
  if ( $query->is_home() ) {
      $query->set('cat', '-36'); 
  }
  return $query;
}
add_filter('pre_get_posts', 'ssws_exclude_home_category');

// 02
/** Features cat #36 only in slider, exclude everywhere else, but widget */
function ssws_exclude_category( $query ) {
  if ( $query->is_home() || $query->is_main_query() ) {
      $query->set( 'cat', '-36' );
  }
}
add_action( 'pre_get_posts', 'ssws_exclude_category' );

// 03
/** Exclude Specific Categories From The WordPress Loop */
function ssws_exclude_specific_categories( $wp_query ) { 
  if( !is_admin() && is_main_query() && is_home() ) {
      $wp_query->set( 'cat', '-36, 15, 27, -1' );
  }
}
add_action( 'pre_get_posts', 'ssws_exclude_specific_categories' );
//NOTE: this snippet works fine, but in case of ACF relational fields
// it is best to embed into the index.php this snippet:
// <?php
//      if ( is_home() ) {
//        query_posts( 'cat=-1,-2,-3' ); //works only with exclusions!!
//      }
// ? >
//http://codex.wordpress.org/Function_Reference/query_posts

/********************************************************/
/** Hide 1st (or x) Post in Loop */
/********************************************************/
function ssws_offset_loop( $query ) {
  if ( $query->is_home() && $query->is_main_query() ) {
      $query->set( 'offset', '1' );
  }
}
add_action( 'pre_get_posts', 'ssws_offset_loop' );

/********************************************************/
// Inheriting parent styles in WordPress child themes
/********************************************************/
// ver 2.0
add_action( 'wp_enqueue_scripts', 'ssws_theme_enqueue_styles' );
function ssws_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
// https://codex.wordpress.org/Child_Themes

// from mor10
// ver 1.0
add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_styles', PHP_INT_MAX);
function enqueue_child_theme_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_uri(), array('parent-theme-name-style') );
    //wp_enqueue_style( 'child-style', get_stylesheet_uri(), array('simone-style') );
}

// in alternative for some themes:
// ver 3.0
function get_parent_theme_css() {
  wp_enqueue_style( 'your-child-theme-name', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'get_parent_theme_css' );
//http://mor10.com/challenges-new-method-inheriting-parent-styles-wordpress-child-themes/

/********************************************************/
// Enqueue the parent theme stylesheet, then the child theme stylesheet.
// Used in place of @import rule in child theme style.css
/********************************************************/
// ver 4.0
add_action('wp_enqueue_scripts', 'ssws_enqueue_styles');
function ssws_enqueue_styles()
{
    // store style version
    $version = wp_get_theme()->get('Version');

    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css', array(), $version);
}
// https://developer.wordpress.org/themes/advanced-topics/child-themes/

/********************************************************/
// Enqueue parent and child-theme styles ver 2.0 (2023)
/********************************************************/
/*
Use a unique handle for the parent theme's style ["parent-blocksy-style"] instead of the default "parent-style". This will avoid conflicts with other themes or plugins that may also enqueue styles with the same handle.

Use the "get_stylesheet_directory_uri()" function instead of "get_template_directory_uri()" to enqueue the child theme's style. This ensures that the correct path to the child theme's style is used.

Use a version number for the "ssws-child-styles" and "ssws-custom-js" scripts to enable browser caching.
*/

add_action('wp_enqueue_scripts', 'ssws_child_styles_scripts_02');

function ssws_child_styles_scripts_02() {
	// Parent theme style
	wp_enqueue_style(
		'parent-blocksy-style',
		get_template_directory_uri() . '/style.css'
	);

	// Child theme styles
	wp_enqueue_style(
		'ssws-child-styles',
		get_stylesheet_directory_uri() . '/style.css',
		array('parent-blocksy-style'),
		wp_get_theme()->get('Version')
	);

	// Custom JS script
	wp_enqueue_script(
		'ssws-custom-js',
		get_stylesheet_directory_uri() . '/assets/js/app.js',
		array('jquery'),
		wp_get_theme()->get('Version'),
		true
	);
}

/********************************************************/
// Allow SVG through WordPress Media Uploader
/********************************************************/
function ssws_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'ssws_mime_types');


/********************************************************/
// Reordering the featured page elements : title, image, text, button
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
        jQuery(document).ready(function() {
            ! function($) {
                //prevents js conflicts
                "use strict";
                var ssws_item_order = [ < ? php echo '"'.implode('","', $ssws_item_order).
                        '"' ? >
                    ],
                    $Wrapper = '';

                if (0 != $('.widget-front', '#main-wrapper .marketing').length) {
                    $Wrapper = $('.widget-front', '#main-wrapper .marketing');
                } else if (0 != $('.fpc-widget-front', '#main-wrapper .fpc-marketing').length) {
                    //for FPU users
                    $Wrapper = $('.fpc-widget-front', '#main-wrapper .fpc-marketing');
                } else {
                    return;
                }

                $Wrapper.each(function() {
                    var o = [];
                    o['title'] = $(this).find('h2');
                    o['image'] = $(this).find('.thumb-wrapper');
                    o['text'] = $(this).find('p');
                    o['button'] = $(this).find('a.btn');
                    for (var i = 0; i < ssws_item_order.length - 1; i++) {
                        o[ssws_item_order[i]].after(o[ssws_item_order[i + 1]]);
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
// Customizing the post layout (content, thumbnail) in post lists [SSWS version]
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
// http://themesandco.com/snippet/customizing-post-layout-content-thumbnail-archive-post-lists/


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
add_action( 'wp_enqueue_scripts', 'ssws_prefix_enqueue_awesome' );
/**
* Register and load font awesome CSS files using a CDN.
*
* @link http://www.bootstrapcdn.com/#fontawesome
* @author FAT Media
*/
function ssws_prefix_enqueue_awesome() {
wp_enqueue_style( 'prefix-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css', array(), '4.0.3' );
// http://ozzyrodriguez.com/tutorials/font-awesome-wordpress-cdn/


/********************************************************/
// GPS track files allowed
/********************************************************/
function ssws_mime_types($mimes) {
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
add_filter('upload_mimes', 'ssws_mime_types');
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
add_filter ( 'tc_social_in_header' , 'ssws_custom_icon_phone_number' );
function ssws_custom_icon_phone_number() {
  //class
  $class =  apply_filters( 'tc_social_header_block_class', 'span5' );
  ob_start();
?>
        <div class="social-block <?php echo $class ?>">
            <?php if ( 0 != tc__f( '__get_option', 'tc_social_in_header') ) : ?>
            <?php echo tc__f( '__get_socials' ) ?>
            <a class="social-icon" href="tel:+1 123-456-7890" title="Call us" target="_self"><span
                    class="fa fa-phone"></span></a>
            <?php endif; ?>
        </div>
        <!--.social-block-->
        <?php
  $html = ob_get_contents();
  ob_end_clean();
  return $html;
}

// Footer section
add_filter ( 'tc_colophon_left_block' , 'ssws_custom_icon_phone_number_footer' );
function ssws_custom_icon_phone_number_footer() {
  $class =  apply_filters( 'tc_colophon_left_block_class', 'span3' );
  ob_start();
?>
        <div class="social-block <?php echo $class ?>">
            <?php if ( 0 != tc__f( '__get_option', 'tc_social_in_footer') ) : ?>
            <?php echo tc__f( '__get_socials' ) ?>
            <a class="social-icon" href="tel:+1 123-456-7890" title="Call us" target="_self"><span
                    class="fa fa-phone"></span></a>
            <?php endif; ?>
        </div>
        <!--.social-block-->
        <?php
  $html = ob_get_contents();
  ob_end_clean();
  return $html;
}

// Sidebar section
add_filter ( 'tc_social_in_sidebar' , 'ssws_custom_icon_phone_number_sidebar' );
function ssws_custom_icon_phone_number_sidebar() {
  $class =  apply_filters( 'tc_sidebar_block_social_class', 'widget_social' );
  ob_start();
?>
        <div class="social-block <?php echo $class ?>">
            <?php if ( 0 != tc__f( '__get_option', 'tc_social_in_left-sidebar') ) : ?>
            <?php echo tc__f( '__get_socials' ) ?>
            <a class="social-icon" href="tel:+1 123-456-7890" title="Call us" target="_self"><span
                    class="fa fa-phone"></span></a>
            <?php endif; ?>
        </div>
        <!--.social-block-->
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
// Open External Links In New Tab jquery
/********************************************************/
add_action('wp_footer', 'add_openExternalLinksNewTab');
function add_openExternalLinksNewTab() {
  ?>
        <script>
        jQuery(document).ready(function($) {
            $('a').each(function() {
                var a = new RegExp('/' + window.location.host + '/');
                if (!a.test(this.href)) {
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
// Open External Links In New Tab vanilla js
/********************************************************/
add_action('wp_footer', 'openExternalLinksNewTab');
function openExternalLinksNewTab() {
  ?>
        <script>
        // vanilla JavaScript
        var links = document.links;

        for (var i = 0, linksLength = links.length; i < linksLength; i++) {
            if (
              links[i].hostname != window.location.hostname &&
              links[i].firstChild &&
              links[i].firstChild.nodeName != "IMG" &&
              !links[i].href.startsWith("tel:") &&
              !links[i].href.startsWith("mailto:")
          ) {
                links[i].target = '_blank';
                links[i].rel = 'noopener';
                // console.log('ext-link');
            }
        }
        </script>
        <?php
}


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
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window,
            document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1234567890987654321'); // Insert your pixel ID here.
        fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id=1234567890987654321&ev=PageView&noscript=1" />
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
add_action('admin_head', 'ssws_custom_remove_error_message');

function ssws_custom_remove_error_message() {
  echo '<style>
    .mce-widget.mce-notification.mce-notification-error.mce-has-close {
    display: none;
  }
  </style>';
}
// https://css-tricks.com/snippets/wordpress/apply-custom-css-to-admin-area/
/* https://wordpress.org/support/topic/error-failed-to-load-content-css/page/2/#post-9371633 */


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


/********************************************************/
// Enqueue GMAPS API Key and store into variable
/********************************************************/
function ssws_enqueue_files()
{
    wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=YOUR-GMAPS-API-KEY', null, '1.0', true);
}
add_action('wp_enqueue_scripts', 'ssws_enqueue_files');

function sswsMapKey($api)
{
    $api['key'] = 'YOUR-GMAPS-API-KEY';
    return $api;
}
add_filter('acf/fields/google_map/api', 'sswsMapKey');
// https://www.advancedcustomfields.com/resources/google-map/
// this needs to be implemented with a custom input field via customizr to keep the key separated from the theme


/********************************************************/
// Automatically set the image Title, Alt-Text, Caption & Description upon upload
/********************************************************/
add_action( 'add_attachment', 'ssws_set_image_meta_upon_image_upload' );
function ssws_set_image_meta_upon_image_upload( $post_ID ) {

	// Check if uploaded file is an image, else do nothing

	if ( wp_attachment_is_image( $post_ID ) ) {

		$ssws_image_title = get_post( $post_ID )->post_title;

		// Sanitize the title:  remove hyphens, underscores & extra spaces:
		$ssws_image_title = preg_replace( '%\s*[-_\s]+\s*%', ' ',  $ssws_image_title );

		// Sanitize the title:  capitalize first letter of every word (other letters lower case):
		$ssws_image_title = ucwords( strtolower( $ssws_image_title ) );

		// Create an array with the image meta (Title, Caption, Description) to be updated
		// Note:  comment out the Excerpt/Caption or Content/Description lines if not needed
		$ssws_image_meta = array(
			'ID'		    => $post_ID,			// Specify the image (ID) to be updated
			'post_title'	=> $ssws_image_title,		// Set image Title to sanitized title
			// 'post_excerpt'	=> $ssws_image_title,		// Set image Caption (Excerpt) to sanitized title
			// 'post_content'	=> $ssws_image_title,		// Set image Description (Content) to sanitized title
		);

		// Set the image Alt-Text
		update_post_meta( $post_ID, '_wp_attachment_image_alt', $ssws_image_title );

		// Set the image meta (e.g. Title, Excerpt, Content)
		wp_update_post( $ssws_image_meta );

	} 
}
// http://brutalbusiness.com/automatically-set-the-wordpress-image-title-alt-text-other-meta/

/********************************************************/
// Set all posts status to published
/********************************************************/
add_action('init', 'ssws_update_draft_posts_to_publish');
function ssws_update_draft_posts_to_publish()
{
    $args = array('post_type' => 'post',
        'post_status' => 'draft',
        'posts_per_page' => -1,
    );
    $published_posts = get_posts($args);

    foreach ($published_posts as $post_to_draft) {
        $query = array(
            'ID' => $post_to_draft->ID,
            'post_status' => 'publish',
        );
        wp_update_post($query, true);
    }
}
// note that this function will not publish custom fields 
// unless the double click publish button is disabled in the Gutenberg options
// by default Gutenberg will ask to re-click the publish button to make sure you checked everything twice (rather annoying!)

/********************************************************/
/*
Plugin Name: Export API Data to JSON
Author: SSWS
Author URI: https://stackoverflow.com/users/2517011/giorgio25b
Description: Every time you save,update or delete a post, all the published post are getting saved in a JSON file in the uploads directory. Have in mind that by default it only exports "title - excerpt - author" , but you can add whatever else you want.
 */
/********************************************************/
function export_posts_in_json()
{
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    );
    $query = new WP_Query($args);
    $posts = array();
    while ($query->have_posts()): $query->the_post();
        $posts[] = array(
            'title' => get_the_title(),
            'excerpt' => get_the_excerpt(),
            'author' => get_the_author(),
        );
    endwhile;
    wp_reset_query();
    $data = json_encode($posts);
    $upload_dir = wp_get_upload_dir(); // set to save in the /wp-content/uploads folder
    $file_name = date('Y-m-d') . '.json';
    $save_path = $upload_dir['basedir'] . '/' . $file_name;

    $f = fopen($save_path, "w"); //if json file doesn't gets saved, comment this and uncomment the one below
    //$f = @fopen( $save_path , "w" ) or die(print_r(error_get_last(),true)); //if json file doesn't gets saved, uncomment this to check for errors
    fwrite($f, $data);
    fclose($f);

}
add_action('save_post', 'export_posts_in_json');
// https://wordpress.stackexchange.com/questions/232708/export-all-post-from-database-to-json-only-when-the-database-gets-updated
// my answer here: 
// https://stackoverflow.com/questions/43787499/wordpress-rest-api-write-to-json-file


/********************************************************/
// Export API Data to JSON, another method
/********************************************************/
// Export API Data to JSON (BIM version)
add_action('publish_post', 'export_wp_rest_api_data_to_json', 10, 2);
function export_wp_rest_api_data_to_json($ID, $post)
{
    $wp_uri = get_site_url();
    $bimEndpoint = '/?rest_route=/bim-businesses/v1/posts';
    $url = $wp_uri . $bimEndpoint; // http://bim-business-search.local/?rest_route=/bim-businesses/v1/posts
    // $url = 'http://bim-business-search.local/?rest_route=/bim-businesses/v1/posts'; // use this full path variable in case you want to use an absolute path
    $response = wp_remote_get($url);
    $responseData = json_encode($response); // saved under the wp root installation
    file_put_contents('bim_business_data_backup.json', $responseData);
}

// Export API Data to JSON (Stackoverflow/Generic version)
add_action('publish_post', 'export_wp_rest_api_data_to_json', 10, 2);

function export_wp_rest_api_data_to_json($ID, $post) 
{
    $wp_uri = get_site_url();
    $customApiEndpoint = '/wp-json/wp/v2/posts'; // or your custom endpoint

    $url = $wp_uri . $customApiEndpoint; // outputs https://your-site.com/wp-json/wp/v2/posts
    // $url = 'https://your-site.com/wp-json/wp/v2/posts'; // use this full path variable in case you want to use an absolute path

    $response = wp_remote_get($url);
    $responseData = json_encode($response); // saved under the wp root installation, can be customized to any folder

    file_put_contents('your_api_data_backup.json', $responseData);
}
// https://stackoverflow.com/questions/46082213/wordpress-save-api-json-after-publish-a-post

/********************************************************/
// Export Users and Posts in json from the DataBase
/********************************************************/
function export_users_to_json()
{
    global $wpdb;

    $query = "SELECT * FROM wp_users";
    $users = $wpdb->get_results($query, ARRAY_A);
    $json = json_encode($users);
    file_put_contents('users_export.json', $json);
}
add_action('wp_loaded', 'export_users_to_json');

function export_posts_to_json()
{
    global $wpdb;

    $query = "SELECT * FROM wp_posts";
    $posts = $wpdb->get_results($query, ARRAY_A); // gets all the posts, trash, draft, publish...
    $json = json_encode($posts);
    file_put_contents('posts_export.json', $json);
}
add_action('wp_loaded', 'export_posts_to_json');

/********************************************************/
// Change dashboard Posts label to News
/********************************************************/
add_action( 'init', 'ssws_change_post_object' );
function ssws_change_post_object() {
    $get_post_type = get_post_type_object('post');
    $labels = $get_post_type->labels;
        $labels->name = 'News';
        $labels->singular_name = 'News';
        $labels->add_new = 'Add News';
        $labels->add_new_item = 'Add News';
        $labels->edit_item = 'Edit News';
        $labels->new_item = 'News';
        $labels->view_item = 'View News';
        $labels->search_items = 'Search News';
        $labels->not_found = 'No News found';
        $labels->not_found_in_trash = 'No News found in Trash';
        $labels->all_items = 'All News';
        $labels->menu_name = 'News';
        $labels->name_admin_bar = 'News';
}

/********************************************************/
// Change dashboard admin icons
/********************************************************/
function replace_admin_menu_icons_css()
{
    ?>
        <style>
        .dashicons-admin-post::before {
            content: "";
            background-image: url('/wp-content/themes/minimal-lite-child/assets/images/menu-icon@2x.png');
            background-size: contain;
            background-repeat: no-repeat;
        }
        }
        </style>
        <?php
}
add_action('admin_head', 'replace_admin_menu_icons_css');

/********************************************************/
// Change “Add title” help text for custom post types or posts
/********************************************************/
add_filter('gettext','ssws_custom_add_title');

function ssws_custom_add_title( $input ) {

    global $post_type;

    if( is_admin() && 'Add title' == $input && 'ssws-cpt' == $post_type )
        return 'Add CPT Title';

    return $input;
}
// https://wordpress.stackexchange.com/questions/6818/change-enter-title-here-help-text-on-a-custom-post-type/#answer-6820

/********************************************************/
// SSWS Remove inline style for <figure>
/********************************************************/
// To remove the inline width in a clean PHP-way could be done with a filter, as described in the source code: https://core.trac.wordpress.org/browser/trunk/src/wp-includes/media.php#L1587
add_filter('img_caption_shortcode_width', '__return_false');
// https://wordpress.stackexchange.com/questions/89221/removing-inline-styles-from-wp-caption-div

/********************************************************/
// SSWS Remove html tags from excerpt and content
/********************************************************/
// $content = get_the_content();
// echo wp_filter_nohtml_kses( $content ); //or strip_tags()
// $content = apply_filters('the_content', $content);
function ssws_remove_html_tags_from_text($content)
{
    $content = wp_filter_nohtml_kses($content);
    return $content;
}
add_filter('the_content', 'ssws_remove_html_tags_from_text');
// this solution removes also the <html> tag, which is a problem in FE

/********************************************************/
// Filter Gutenberg Blocks
/********************************************************/

function ssws_allowed_block_types($allowed_block_types, $post)
{
    if ($post->post_type !== 'post') {
        return $allowed_block_types;
    }
    return array(
        // List here: https://gist.github.com/giorgioriccardi/71f97eeb1646314386f14043cf0e8124
        'core/paragraph',
        'core/image',
        'core/list',
        'core/video',
        'core/quote',
    );
}

add_filter('allowed_block_types', 'ssws_allowed_block_types', 10, 2);
// https://developer.wordpress.org/block-editor/developers/filters/block-filters/
// https://developer.wordpress.org/block-editor/

/********************************************************/
// Change & Add Image Sizes, child-theme
/********************************************************/

// Enable function to set new image sizes
// add_theme_support('post-thumbnails'); // not necessary for child-theme

// Add/Remove Image Sizes
function ssws_add_image_sizes()
{
    // Remove minimal-lite default sizes
    remove_image_size('medium_large');
    
    // Add SSWS custom sizes
    add_image_size('mobile-all-375x280', '375', '280', ["center", "center"]);
    add_image_size('hero-1919x610', '1919', '610', ["center", "center"]);
    add_image_size('profile-image-102x102', '102', '102', ["center", "center"]);
}
add_action('after_setup_theme', 'ssws_add_image_sizes', 11);

// Enable New Sizes to WP
function ssws_custom_image_sizes($size_names)
{
    $ssws_sizes = array(
        'hero-1919x610' => __('Hero Slider'),
        'mobile-all-375x280' => __('Mobile (all)'),
        'profile-image-102x102' => __('Profile Image'),
    );
    return array_merge($size_names, $ssws_sizes);
}
add_filter('image_size_names_choose', 'ssws_custom_image_sizes');
// https://developer.wordpress.org/reference/hooks/image_size_names_choose/

// End Change & Add Image Sizes, child-theme

/********************************************************/
// Get next and previous post links, chronologically 
// or alphabetically by title, across post types
/********************************************************/

add_filter('get_next_post_sort', 'filter_next_and_prev_post_sort');
add_filter('get_previous_post_sort', 'filter_next_and_prev_post_sort');
function filter_next_and_prev_post_sort($sort)
{
    $op = ('get_previous_post_sort' == current_filter()) ? 'DESC' : 'ASC';
    // $sort = "ORDER BY p.post_title " . $op . " LIMIT 1";
    $sort = "ORDER BY p.post_date " . $op . " LIMIT 1";
    return $sort;

}

add_filter('get_next_post_join', 'navigate_in_same_taxonomy_join', 20);
add_filter('get_previous_post_join', 'navigate_in_same_taxonomy_join', 20);
function navigate_in_same_taxonomy_join()
{
    global $wpdb;
    return " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";
}

add_filter('get_next_post_where', 'filter_next_and_prev_post_where');
add_filter('get_previous_post_where', 'filter_next_and_prev_post_where');
function filter_next_and_prev_post_where($original)
{
    global $wpdb, $post;
    $where = '';
    $taxonomy = 'category';
    $op = ('get_previous_post_where' == current_filter()) ? '<' : '>';

    if (!is_object_in_taxonomy($post->post_type, $taxonomy)) {
        return $original;
    }

    $term_array = wp_get_object_terms($post->ID, $taxonomy, array('fields' => 'ids'));

    $term_array = array_map('intval', $term_array);

    if (!$term_array || is_wp_error($term_array)) {
        return $original;
    }
    $where = " AND tt.term_id IN (" . implode(',', $term_array) . ")";
    // return $wpdb->prepare("WHERE p.post_title $op %s AND p.post_type = %s AND p.post_status = 'publish' $where", $post->post_title, $post->post_type);
    return $wpdb->prepare("WHERE p.post_date $op %s AND p.post_type = %s AND p.post_status = 'publish' $where", $post->post_date, $post->post_type);
}
// https://wordpress.stackexchange.com/questions/204265/next-previous-posts-links-alphabetically-and-from-same-category

/********************************************************/
  // Add CF7 to bottom content section (for Blocksy Framework Hooks)
/********************************************************/
function ssws_cf7_embed() {
	//   echo '<pre>Test</pre>';
	$output = '<div class="wp-block-ugb-container ugb-container>
				<div class="ugb-container__content-wrapper">
					[contact-form-7 id="5547" title="Contact form 1"]
				</div>
			</div>';

	echo do_shortcode( $output );
	//   echo do_shortcode( '[contact-form-7 id="5547" title="Contact form 1"]' );
}
//   add_action('blocksy:single:content:bottom', 'ssws_cf7_embed');
//   add_action('blocksy:single:bottom', 'ssws_cf7_embed');
add_action('blocksy:content:bottom', 'ssws_cf7_embed');

/********************************************************/
/* Hide Selected Plugins from The Plugin Page */
/********************************************************/
add_filter( 'all_plugins', 'ssws_hide_plugins');
function ssws_hide_plugins($plugins)
{
      // Hide Your Plugin One
  if( !current_user_can('administrator') && is_plugin_active('blocksy-companion-pro/blocksy-companion.php')) {
    unset( $plugins['blocksy-companion-pro/blocksy-companion.php'] );
  }
  
   		// Hide Your Plugin Two
	if( !current_user_can('administrator') && is_plugin_active('user-role-editor/user-role-editor.php')) {
		unset( $plugins['user-role-editor/user-role-editor.php'] );
	}
  
   		// Hide Your Plugin Three
	if( !current_user_can('administrator') && is_plugin_active('plugin-three-directory-name/plugin-three-directory-name.php')) {
		unset( $plugins['plugin-three-directory-name/plugin-three-directory-name.php'] );
	}

	return $plugins;
}
// https://syncwin.com/tutorial/wordpress-dashboard-plugin-hiding/


/********************************************************/
// Display/Render Blocks Content on Blog Pages
/********************************************************/
$posts_page = get_post( get_option( 'page_for_posts' ) );
  echo apply_filters( 'the_content', $posts_page->post_content );
// https://dev-notes.eu/2016/05/wordpress-content-on-posts-for-pages/


/********************************************************/
/* Hide Selected Plugins from The Plugins Page */
/********************************************************/
add_filter( 'all_plugins', 'ssws_hide_plugins');
function ssws_hide_plugins($plugins)
{
  		// Hide Your Plugin One
	if( !current_user_can('administrator') && is_plugin_active('blocksy-companion-pro/blocksy-companion.php')) {
		unset( $plugins['blocksy-companion-pro/blocksy-companion.php'] );
	}
  
   		// Hide Your Plugin Two
	if( !current_user_can('administrator') && is_plugin_active('user-role-editor/user-role-editor.php')) {
		unset( $plugins['user-role-editor/user-role-editor.php'] );
	}
  
   		// Hide Your Plugin Three
	if( !current_user_can('administrator') && is_plugin_active('plugin-three-directory-name/plugin-three-directory-name.php')) {
		unset( $plugins['plugin-three-directory-name/plugin-three-directory-name.php'] );
	}

	return $plugins;
}
// https://syncwin.com/tutorial/wordpress-dashboard-plugin-hiding/


/********************************************************/
// Hide blocksy dashboard admin icons
/********************************************************/
if ( ! current_user_can( 'administrator' ) ) {
	function ssws_hide_admin_menu_icons_css()
	{
		?>
			<style>
				/* blocksy icon */
				.toplevel_page_ct-dashboard {
					display: none;
				}
			</style>
		<?php
	}
	add_action('admin_head', 'ssws_hide_admin_menu_icons_css'); 
}

/********************************************************/
// Allow upload custom file formats
/********************************************************/
add_filter( 'wp_check_filetype_and_ext', 'ssws_and_ext_webp', 10, 4 );
function ssws_and_ext_webp( $types, $file, $filename, $mimes ) {
    if ( false !== strpos( $filename, '.webp' ) ) {
        $types['ext']  = 'webp';
        $types['type'] = 'image/webp';
    }
    if ( false !== strpos( $filename, '.ogg' ) ) {
        $types['ext']  = 'ogg';
        $types['type'] = 'audio/ogg';
    }
    if ( false !== strpos( $filename, '.woff' ) ) {
        $types['ext']  = 'woff';
        $types['type'] = 'font/woff|application/font-woff|application/x-font-woff|application/octet-stream';
    }
    if ( false !== strpos( $filename, '.woff2' ) ) {
        $types['ext']  = 'woff2';
        $types['type'] = 'font/woff2|application/octet-stream|font/x-woff2';
    }

    return $types;
}

function ssws_mime_types($mimes) {
  $mimes['webp']  = 'image/webp';
  $mimes['ogg']   = 'audio/ogg';
  $mimes['woff']  = 'font/woff|application/font-woff|application/x-font-woff|application/octet-stream';
  $mimes['woff2'] = 'font/woff2|application/octet-stream|font/x-woff2';
  
  return $mimes;
}

add_filter( 'upload_mimes', 'ssws_mime_types' );

/********************************************************/
// Add Search results widget
/********************************************************/
function ssws_add_search_results_widget($query) {
	if ( !is_admin() && $query->is_main_query() ) {
		if ($query->is_search) {
			// Add Search results widget area
			function ssws_add_search_results_widget_area() {
				// create a widget area here
				// https://github.com/giorgioriccardi/wp-snippets#widgets
			}
			add_filter('blocksy:hero:description:before', 'ssws_add_search_results_widget_area');
		}
	}
}
  
add_action('pre_get_posts','ssws_add_search_results_widget');