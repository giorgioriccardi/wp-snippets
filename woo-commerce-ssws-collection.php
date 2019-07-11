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
{?>


  <div class="services-posts woocommerce columns-4">
    <!-- Check if the post has a Post Thumbnail assigned to it. -->
    <ul class="products">
        <?php $query = new WP_Query(
    array(
        'category_name' => 'services',
        'posts_per_page' => 4,
        'post_status' => 'publish',
    )
);?>
        <?php if (is_front_page() && $query->have_posts()): while ($query->have_posts()): $query->the_post();?>

		        <li class="product type-product">
		          <a href="<?php the_permalink();?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link" title="<?php the_title_attribute();?>">
		            <?php echo get_the_post_thumbnail($post->ID, array(300, 300)); ?>
		            <!-- Display the Title as a link to the Post's permalink. -->
		            <h2>
		              <a href="<?php the_permalink();?>" class="woocommerce-loop-product__title" rel="bookmark" title="Services: <?php the_title_attribute();?>">
		                <?php the_title();?>
		              </a>
		            </h2>
		          </a>
		          <?php // echo apply_filters( 'the_content', $post->post_content ); ?>
		          <a rel="nofollow" href="/category/services/" class="button add_to_cart_button ajax_add_to_cart" title="all Services">more Services</a>
		        </li>

		        <?php endwhile;
        wp_reset_postdata();
    else: ?>
        <p><?php esc_html_e('Sorry, no Services matched your criteria.');?></p>
        <?php endif;?>

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