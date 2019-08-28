# Index

- Add Google Fonts
- Add Google Analytics script
- Replaces the excerpt "more" text or [...] with a custom link
- ...
- to be continued...

# WordPress Snippets

This document is designed to be a one-stop quick reference of useful WordPress code snippets.

This repository is licensed under the GNU GENERAL PUBLIC LICENSE.

The focus is to reference the common, though perhaps not-so-obvious hooks and functions used in everyday theme development.

Reference links will be provided where possible. Much content comes straight from the [WordPress Codex](http://codex.wordpress.org/), as well as [Customizr Hooks & API](http://presscustomizr.com/code-snippets/).

##### What this is not

This reference guide is not intended to provide in-depth tutorials or walk-throughs, or be an exhaustive development guide. However, it will seek to add explanations and provide commented code where it may be appropriate.

### How this guide works

Throughout this guide, many functions will be written like:

```
function yourtheme_function(){
    // insert magic here
}
add_action('action', 'yourtheme_function');
```

In this example, `yourtheme_function` is a standard way of naming your functions. Unless you are name-spacing your functions, is it common to prefix them with the name of your theme. The second part of the name will hopefully be descriptive of what the function will seek to accomplish.

Unless otherwise specified, functions are to be placed within your **functions.php** file in your theme.

---

## Basic Page Template

This template provides the basic generic building blocks of a WordPress page. Assuming you already have a **header.php** and a **footer.php** file with appropriate code in each.

```
<?php
get_header();

// the WordPress loop
while ( have_posts() ) : the_post();

    the_content();

endwhile;

get_footer();
```

## Scripts

To add scripts to your theme. The `array()` parameter will be an array of dependencies, to tell WordPress what needs to be loaded first (e.g. jQuery). Adding the "true" parameter will force the script to be loaded in the footer, rather than in the `<head>`.

```
function yourtheme_scripts() {

    wp_enqueue_script( 'griccardi-bootstrap4', get_template_directory_uri() . '/assets/js/app.js', array(), true );
}
add_action( 'wp_enqueue_scripts', 'your_theme_scripts' );
```

## Widgets

To create a place for widgets (for example, to appear in a sidebar). The `register_sidebar()` function will enable your new widget area called 'Sidebar' in the widgets section of the WordPress admin:

```
function yourtheme_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'yourtheme' ),
        'id'            => 'sidebar-1',
        'description'   => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h1 class="widget-title">',
        'after_title'   => '</h1>',
    ) );
}
add_action( 'widgets_init', 'yourtheme_widgets_init' );
```

In your theme template files, add this code where you wish your widget area to appear, checking if it is active, and calling it by its ID:

```
if ( is_active_sidebar( 'sidebar-1' ) ) :
    dynamic_sidebar( 'sidebar-1' );
endif;
```

[http://codex.wordpress.org/Function_Reference/register_sidebar](http://codex.wordpress.org/Function_Reference/register_sidebar)

## Custom Login Page

```
// replace the WordPress logo
function my_login_logo() { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/img/logo.png);
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

// replace the link to WordPress
function my_login_logo_url() {
    return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

// change the login page title
function my_login_logo_url_title() {
    return 'mydomain.com | Joe Smith - WordPress Developer';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );

```

## WP Query

Reference the WP_Query class to query for any post type, for example. This type of function can be used in your theme templates, or placed within a function inside **functions.php**

```
$args = array(
    'post_type'       => 'custom_post_type',
    'post_status'     => 'publish',
    'posts_per_page'  => -1,  // -1 will show all posts
);

// The Query
$the_query = new WP_Query( $args );

    // The Loop
if ( $the_query->have_posts() ) {
    while ( $the_query->have_posts() ) {
        $the_query->the_post();
        // do stuff
        ...
    }
}

// Restore original Post Data
wp_reset_postdata();

```

## Advanced Custom Fields

If you are using ACF pro, you can enable an options page:

```
if( function_exists('acf_add_options_page') ) {

    $page = acf_add_options_page(array(
        'page_title'    => 'Theme Global settings',
        'menu_title'    => 'Global Settings',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'  => false
    ));

}
```

In your template files, retrieve your options page fields:

```
$field = get_field('custom_field', 'options');
```

## Admin Columns

Show the Featured Image in the admin pages:

```
// add a new column to your admin pages list of posts
function add_posts_columns($columns){
    $columns['post_thumbs'] = __('Featured Image');
    return $defaults;
}
add_filter('manage_posts_columns', 'add_posts_columns', 5);

// for a custom post type of 'work':
add_filter('manage_work_posts_columns', 'add_posts_columns', 5);

// output the Featured Image
function show_featured_image($column_name, $id){
    if($column_name === 'post_thumbs'){
        echo the_post_thumbnail( 'admin-thumb' );
    }
}
add_action('manage_posts_custom_column', 'show_featured_image', 5, 2);

// for a custom post type of 'work':
add_action('manage_work_custom_column', 'show_featured_image', 5, 2);
```

[https://codex.wordpress.org/Plugin_API/Action_Reference/manage_posts_custom_column](https://codex.wordpress.org/Plugin_API/Action_Reference/manage_posts_custom_column)

## Custom Mail Sender

To use an email address different from your site administrator's email address, use this function:

```
function mail_from ($email ){
  return 'address@domain.com'; // new email address from sender.
}
add_filter( 'wp_mail_from', 'mail_from' );
```

## Add Attachments (images) to Search

```
function add_attachment_to_search( $query ) {
  if ( $query->is_search ) {
    // add attachment to the post types allowed in search results
    $query->set( 'post_type', array( 'post', 'attachment' , 'region', 'project', 'watershed') );
    $query->set( 'post_status', array( 'publish', 'inherit' ) );
  }
  return $query;
}
add_filter( 'pre_get_posts', 'add_attachment_to_search' );
```

## Limit Attachment Search Results

To have search exclude images, but include other document types, you need to add a filter.

[http://wordpress.stackexchange.com/questions/209712/how-do-i-exclude-all-images-from-a-wp-query/209714](http://wordpress.stackexchange.com/questions/209712/how-do-i-exclude-all-images-from-a-wp-query/209714)

```
function remove_images($where) {
    global $wpdb;
    $where.=' AND '.$wpdb->posts.'.post_mime_type NOT LIKE \'image/%\'';
    return $where;
}
add_filter( 'posts_where' , 'remove_images' );
```
