<?php

// SSWS WooCommerce functions collection

/********************************************************/
// Hide Product Category Count
/********************************************************/
add_filter('woocommerce_subcategory_count_html', 'ssws_hide_subcategory_count');
function ssws_hide_subcategory_count()
{
  /* empty - no count */
}

/********************************************************/
// Loop Services on Homepage
/********************************************************/
add_action('storefront_homepage_after_featured_products', 'custom_storefront_home_loop');

function custom_storefront_home_loop()
{ ?>


  <div class="services-posts woocommerce columns-4">
    <!-- Check if the post has a Post Thumbnail assigned to it. -->
    <ul class="products">
      <?php $query = new WP_Query(
        array(
          'category_name' => 'services',
          'posts_per_page' => 4,
          'post_status' => 'publish',
        )
      ); ?>
      <?php if (is_front_page() && $query->have_posts()) : while ($query->have_posts()) : $query->the_post(); ?>

          <li class="product type-product">
            <a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link" title="<?php the_title_attribute(); ?>">
              <?php echo get_the_post_thumbnail($post->ID, array(300, 300)); ?>
              <!-- Display the Title as a link to the Post's permalink. -->
              <h2>
                <a href="<?php the_permalink(); ?>" class="woocommerce-loop-product__title" rel="bookmark" title="Services: <?php the_title_attribute(); ?>">
                  <?php the_title(); ?>
                </a>
              </h2>
            </a>
            <?php // echo apply_filters( 'the_content', $post->post_content ); 
            ?>
            <a rel="nofollow" href="/category/services/" class="button add_to_cart_button ajax_add_to_cart" title="all Services">more Services</a>
          </li>

        <?php endwhile;
        wp_reset_postdata();
      else : ?>
        <p><?php esc_html_e('Sorry, no Services matched your criteria.'); ?></p>
      <?php endif; ?>

    </ul>
  </div><!-- end services-posts -->

<?php }

/********************************************************/
// Adds prefix and/or suffix to WooCommerce Prices
/********************************************************/
/**
 * @snippet       Adds prefix and/or suffix to WooCommerce Prices
 * @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055
 * @source        https://businessbloomer.com/?p=472
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 2.4.7
 */

function ssws_wc_custom_get_price_html($price, $product)
{
  if ($product->get_price() > 0) {
    $price = '<span class="woocommerce-Price-amount amount">CAD </span>' . $price;
    return apply_filters('woocommerce_get_price', $price);
  }
  return $price;
}

add_filter('woocommerce_get_price_html', 'ssws_wc_custom_get_price_html', 10, 2);
// https://github.com/woocommerce/woocommerce/issues/13658#issuecomment-291952437

/********************************************************/
// rename the Product Description or Reviews tabs
/********************************************************/
// add_filter( 'woocommerce_product_tabs', 'woo_rename_tab', 98);

// function woo_rename_tab($tabs) {
//   $tabs['description']['title'] = 'More info';
//   return $tabs;
// }
// http://kb.oboxthemes.com/articles/woocommerce-how-do-i-rename-the-product-description-or-reviews-tabs/

/********************************************************/
// Change the description tab title to product name
/********************************************************/
add_filter('woocommerce_product_tabs', 'wc_change_product_description_tab_title', 10, 1);
function wc_change_product_description_tab_title($tabs)
{
  global $post;
  if (isset($tabs['description']['title']))
  // $tabs['description']['title'] = $post->post_title; // return the title
  // $tabs['description']['title'] = 'More info'; // return the custom text
  {
    return $tabs;
  }
}
// Change the description tab heading to product name
add_filter('woocommerce_product_description_heading', 'wc_change_product_description_tab_heading', 10, 1);
function wc_change_product_description_tab_heading($title)
{
  global $post;
  return $post->post_title;
}
// https://github.com/woocommerce/woocommerce/issues/13658#issuecomment-291952437

/********************************************************/
/**
 * Remove product meta
 */
/********************************************************/
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);


/********************************************************/
/**
 * Remove product additional info heading
 */
/********************************************************/
add_filter('woocommerce_product_additional_information_heading', '__return_null');


/********************************************************/
/**
 * Rename product data tabs
 */
/********************************************************/
add_filter('woocommerce_product_tabs', 'woo_rename_tabs', 98);
function woo_rename_tabs($tabs)
{

  $tabs['description']['title'] = __('');  // Rename the description tab
  // $tabs['reviews']['title'] = __( 'Ratings' );   // Rename the reviews tab
  $tabs['additional_information']['title'] = __('');    // Rename the additional information tab

  return $tabs;
}


/********************************************************/
/**
 * @snippet       Move product tabs beside the product image - WooCommerce
 * @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055
 * @sourcecode    https://businessbloomer.com/?p=393
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 3.5.2
 */
/********************************************************/
// remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
// add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 60 ); // after cart button
// add_action( 'woocommerce_before_add_to_cart_button', 'woocommerce_output_product_data_tabs', 60 ); // before cart button


/********************************************************/
/**
 * Remove product data tabs
 * https://docs.woocommerce.com/document/editing-product-data-tabs/
 */
/********************************************************/
add_filter('woocommerce_product_tabs', 'woo_remove_product_tabs', 98);

function woo_remove_product_tabs($tabs)
{

  // unset( $tabs['description'] );      // Remove the description tab
  unset($tabs['reviews']);     // Remove the reviews tab
  // unset( $tabs['additional_information'] );  	// Remove the additional information tab

  return $tabs;
}


/********************************************************/
/**
 * @snippet       Remove Product Tabs & Echo Long Description
 * @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055
 * @sourcecode    https://businessbloomer.com/?p=19940
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 3.5.3
 */
/********************************************************/
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
add_action('woocommerce_after_single_product_summary', 'bbloomer_wc_output_long_description', 10);

function bbloomer_wc_output_long_description()
{
?>
  <div class="woocommerce-tabs">
    <?php $heading = esc_html(apply_filters('woocommerce_product_description_heading', __('Description', 'woocommerce'))); ?>

    <?php if ($heading) : ?>
      <h2><?php echo $heading; ?></h2>
    <?php endif; ?>

    <?php the_content(); ?>
  </div>
<?php
}


/********************************************************/
// Override WooCommerce Gallery Thumbnail 
/********************************************************/
add_filter('woocommerce_get_image_size_gallery_thumbnail', function ($size) {
  return array(
    'width'  => 200,
    'height' => 300,
    'crop'   => 0,
  );
});
// https://gist.github.com/mikeyarce/b0bb7fa0d815c85c723638842fb1f6cc


/********************************************************/
// Add WooCommerce variation stock status to single product variations
/********************************************************/
// Function that will check the stock status and display the corresponding additional text
function get_stock_status_text($product, $name, $term_slug)
{
  foreach ($product->get_available_variations() as $variation) {
    if ($variation['attributes'][$name] == $term_slug) $stock = $variation['is_in_stock'];
  }
  return $stock == 1 ? ' - (In Stock)' : ' - (Out of Stock)';
}

// The hooked function that will add the stock status to the dropdown options elements.
add_filter('woocommerce_dropdown_variation_attribute_options_html', 'show_stock_status_in_dropdown', 10, 2);

function show_stock_status_in_dropdown($html, $args)
{
  // Only if there is a unique variation attribute (one dropdown)
  if (sizeof($args['product']->get_variation_attributes()) == 1) :
    $options = $args['options'];
    $product = $args['product'];
    $attribute = $args['attribute']; // The product attribute taxonomy
    $name = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title($attribute);
    $id = $args['id'] ? $args['id'] : sanitize_title($attribute);
    $class = $args['class'];
    $show_option_none = $args['show_option_none'] ? true : false;
    $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __('Choose an option', 'woocommerce');
    if (empty($options) && !empty($product) && !empty($attribute)) {
      $attributes = $product->get_variation_attributes();
      $options = $attributes[$attribute];
    }
    $html = '<select id="' . esc_attr($id) . '" class="' . esc_attr($class) . '" name="' . esc_attr($name) . '" data-attribute_name="attribute_' . esc_attr(sanitize_title($attribute)) . '" data-show_option_none="' . ($show_option_none ? 'yes' : 'no') . '">';
    $html .= '<option value="">' . esc_html($show_option_none_text) . '</option>';
    if (!empty($options)) {
      if ($product && taxonomy_exists($attribute)) {
        $terms = wc_get_product_terms($product->get_id(), $attribute, array(
          'fields' => 'all'
        ));
        foreach ($terms as $term) {
          if (in_array($term->slug, $options)) {
            // HERE Added the function to get the text status
            $stock_status = get_stock_status_text($product, $name, $term->slug);
            $html .= '<option value="' . esc_attr($term->slug) . '" ' . selected(sanitize_title($args['selected']), $term->slug, false) . '>' . esc_html(apply_filters('woocommerce_variation_option_name', $term->name) . $stock_status) . '</option>';
          }
        }
      } else {
        foreach ($options as $option) {
          $selected = sanitize_title($args['selected']) === $args['selected'] ? selected($args['selected'], sanitize_title($option), false) : selected($args['selected'], $option, false);
          // HERE Added the function to get the text status
          $stock_status = get_the_stock_status($product, $name, $option);
          $html .= '<option value="' . esc_attr($option) . '" ' . $selected . '>' . esc_html(apply_filters('woocommerce_variation_option_name', $option) . $stock_status) . '</option>';
        }
      }
    }
    $html .= '</select>';
  endif;
  return $html;
}
// https://stackoverflow.com/questions/47180058/how-to-add-variation-stock-status-to-woocommerce-product-variation-dropdown/#answer-47189725


/********************************************************/
// Change Woocommerce alert text on add to cart action without selected variation
/********************************************************/
// add_filter( 'woocommerce_get_script_data', 'change_alert_text', 10, 2 );
function change_alert_text($params, $handle)
{
  if ($handle === 'wc-add-to-cart-variation')
    $params['i18n_unavailable_text'] = __('Your new alert text', 'woocommerce');

  return $params;
}
// https://stackoverflow.com/questions/50216622/change-the-alert-text-on-add-to-cart-action-without-selected-variation-in-woocom/#answer-51248936


/********************************************************/
// WooCommerce change the out of stock message
/********************************************************/
add_filter('woocommerce_get_availability', 'db_custom_get_availability', 1, 2);
function db_custom_get_availability($availability, $_product)
{

  // Change Out of Stock Text
  if (!$_product->is_in_stock()) {
    $availability['availability'] = __('Sold Out', 'woocommerce');
  }
  return $availability;
}
// https://generatepress.com/forums/topic/woocommerce-change-the-out-of-stock-message/


/********************************************************/
// Change the WooCommerce product placeholder image
/********************************************************/
add_filter('woocommerce_placeholder_img_src', 'custom_woocommerce_placeholder_img_src');

function custom_woocommerce_placeholder_img_src($src)
{
  $upload_dir = wp_upload_dir();
  $uploads = untrailingslashit($upload_dir['baseurl']);
  // replace with path to your image
  $src = $uploads . '/2018/12/brand-pro-cashmere-brand-image-placeholder.jpg';
  // http://live-some-am-site.pantheonsite.io/wp-content/uploads/2018/11/960x640.png
  // http://live-some-am-site.pantheonsite.io/wp-content/uploads/2018/12/brand-pro-cashmere-brand-image-placeholder.jpg

  return $src;
}
// https://docs.woocommerce.com/document/change-the-placeholder-image/


/********************************************************/
// Add WooCommerce prefix and/or suffix to WooCommerce Prices
/********************************************************/
/**
 * @snippet       Adds prefix and/or suffix to WooCommerce Prices
 * @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055
 * @source        https://businessbloomer.com/?p=472
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 2.4.7
 */

function grc_wc_custom_get_price_html($price, $product)
{
  if ($product->get_price() > 0) {
    $price = '<span class="woocommerce-Price-amount amount">USD </span>' . $price;
    return apply_filters('woocommerce_get_price', $price);
  }
  return $price;
}

add_filter('woocommerce_get_price_html', 'grc_wc_custom_get_price_html', 10, 2);
// https://github.com/woocommerce/woocommerce/issues/13658#issuecomment-291952437


/********************************************************/
// Change the description tab title to product name
/********************************************************/
add_filter('woocommerce_product_tabs', 'wc_change_product_description_tab_title', 10, 1);
function wc_change_product_description_tab_title($tabs)
{
  global $post;
  if (isset($tabs['description']['title']))
    // $tabs['description']['title'] = $post->post_title; // return the title
    // $tabs['description']['title'] = 'More info'; // return the custom text
    return $tabs;
}
// Change the description tab heading to product name
add_filter('woocommerce_product_description_heading', 'wc_change_product_description_tab_heading', 10, 1);
function wc_change_product_description_tab_heading($title)
{
  global $post;
  return $post->post_title;
}
// https://github.com/woocommerce/woocommerce/issues/13658#issuecomment-291952437


/********************************************************/
// Hide SKU from WC FE single product
/********************************************************/
function ssws_remove_product_page_sku($enabled)
{

  if (!is_admin() && is_product()) {

    return false;
  }

  return $enabled;
}

add_filter('wc_product_sku_enabled', 'ssws_remove_product_page_sku');
// add_filter('wc_product_sku_enabled', '__return_false');