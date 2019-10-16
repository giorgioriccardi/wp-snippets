<!-- ACF SNIPPETS -->


<!-- this snippet outputs the ACF taxonomy field
it outputs a link to a specific category list of posts -->

<!-- NB! with CPT it only works if the field type is "taxonomy" and the taxonomy is "category" and the type is "checkbox" -->

<?php
$term = get_field('acf_field_name');

//var_dump($term);

if (!empty($term)):
    $category_number = $term[0]; // get_field returns an array, [0] is the 1st element (any others are ignored)
    // now use $category_number
    ?>
					        <h3>
					            Category name is:
					            <a href="<?php echo get_category_link($category_number); ?>" title="<?php echo get_cat_name($category_number) ?>" target="_blank">
					        <!-- or -->
					            <!-- <a href="<?php //echo get_cat_name( $category_number );  ?>" title="<?php //echo get_cat_name( $category_number ) ?>" target="_blank"> -->
					                <?php echo get_cat_name($category_number) ?> <!-- output the category name -->
					            </a>
					        </h3>

					<?php endif;?>
<!-- credits to Scott [https://github.com/sblessley] for the link function call! -->


<!-- this snippet outputs the ACF relational field
it outputs a link to a specific related post -->
<?php
// Get related posts from Advanced Custom Fields
$related_posts = get_field('acf_field_name', get_queried_object_id());
// Check if we have any related posts.
if (!empty($related_posts)):
?>
<?php
// If yes, loop through them.
foreach ($related_posts as $related_post):
?>
    <div class="project-related">
        <a href="<?php echo get_permalink($related_post->ID); ?>" target="_blank" title="<?php echo get_the_title($related_post) ?>">
        <!-- here we can choose how to output the link text: -->
            <?php echo 'See related post '; ?> <!-- output a predifined link name -->
        <!-- or -->
            <?php echo $related_post->post_title; ?> <!-- get the post title -->
        </a>
    </div>
<?php endforeach;?>
<?php endif;?>


<!-- this snippet outputs the ACF file field
when it sets to return value = url  -->
<?php
/*
 *  Show selected file if value exists
 *  Return value = URL
 */
if (get_field('upload_publications')):
?>
    <div class="row-fluid tc-single-post-thumbnail-wrapper __before_content">
        <a href="<?php the_field('upload_publications');?>" >
            <span class="download-icon">Download this publication</span>
        </a>
    </div>
<?php
endif;
?>


<!-- from kickstart.ca single-artists.php -->
<!-- to avoid printing the fields when they are not filled: -->
<div class="artsit-info author-index shorter">
    <?php if (get_field('website')): ?>
        <?php
echo '<p><h3>WEBSITE: </h3>';
the_field('website');
echo '</p>';
?>
    <?php endif;?>

    <?php if (get_field('email')): ?>
        <?php
echo '<p><h3>EMAIL: </h3><a href="mailto:' . get_field('email') . '">';
the_field('email');
echo '</a></p>';
?>
    <?php endif;?>

    <?php if (get_field('phone')): ?>
        <?php
echo '<p><h3>PHONE: </h3><a href="tel:' . get_field('phone') . '">';
the_field('phone');
echo '</a></p>';
?>
    <?php endif;?>
</div>
<!-- the link/website field is created thanks to an add-ons plugin -->
<!-- acf-website-field-master -->


<?php

/********************************************************/
// Hook acf/save_post applied to all custom fields
/********************************************************/
add_action('acf/save_post', 'save_post', 20);

function save_post($post_id)
{
    // check if is autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        // verify nonce
        if (isset($_POST['acf_nonce'], $_POST['fields']) && wp_verify_nonce($_POST['acf_nonce'], 'input')) {
            // update the post (may even be a revision / autosave preview)
            do_action('acf/save_post', $post_id);
        }
    }

    // Remove the hook to avoid infinite loop. Please make sure that it has
    // the same priority (20)
    remove_action('acf/save_post', 'save_post', 20);
    // Update the post
    wp_update_post($new_post);
    // Add the hook back
    add_action('acf/save_post', 'save_post', 20);
}
// https://support.advancedcustomfields.com/forums/topic/hook-acfsave_post-not-applied-for-all/#post-45195

/********************************************************/
// Hook acf/save_post applied to all custom fields (basic version)
/********************************************************/
add_action('save_post', 'my_autosave_acf');

function my_autosave_acf($post_id)
{
    // check if is autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        // verify nonce
        if (isset($_POST['acf_nonce'], $_POST['fields']) && wp_verify_nonce($_POST['acf_nonce'], 'input')) {
            // update the post (may even be a revision / autosave preview)
            do_action('acf/save_post', $post_id);
        }
    }
}
// https://github.com/elliotcondon/acf/issues/585
?>

/********************************************************/
// ACF Phone number for BIM Search site 10/2019
/********************************************************/
<p>business_phone:
    <a
        class=""
        href="tel:<?php the_field('business_phone');?>"
        target="_blank"
        title="<?php the_field('business_phone');?>">
        <?php the_field('business_phone');?>
    </a>
</p>

/********************************************************/
// ACF Email and Url links for BIM Search site 10/2019
/********************************************************/
<p>email_address: <?php // the_field('email_address');?>
    <a
        class=""
        href="mailto:<?php the_field('email_address');?>"
        target="_blank"
        title="<?php the_field('email_address');?>">
        <?php the_field('email_address');?>
    </a>
</p>
<p>website_address: <?php // the_field('website_address');?>
    <a
        class=""
        href="<?php echo esc_url(get_field('website_address')); ?>"
        target="_blank"
        title="<?php echo esc_html(get_field('website_address')); ?>">
        <?php echo esc_html(get_field('website_address')); ?>
    </a>
</p>

/********************************************************/
// Get only the Address and not the coordinates from a GMAP field
/********************************************************/
Address:
<?php // the_field('business_address');
$map_location = get_field('business_address');
echo $map_location['address'];
?>