<!-- ACF SNIPPETS -->


<!-- this snippet outputs the ACF taxonomy field
it outputs a link to a specific category list of posts -->

<!-- NB! with CPT it only works if the field type is "taxonomy" and the taxonomy is "category" and the type is "checkbox" -->

<?php
    $term = get_field('acf_field_name');

//var_dump($term);

    if ( !empty( $term ) ):
        $category_number = $term[0]; // get_field returns an array, [0] is the 1st element (any others are ignored)
        // now use $category_number
?>
    <h3>
        Category name is:
        <a href="<?php echo get_category_link( $category_number );  ?>" title="<?php echo get_cat_name( $category_number ) ?>" target="_blank">
    <!-- or -->    
        <!-- <a href="<?php //echo get_cat_name( $category_number );  ?>" title="<?php //echo get_cat_name( $category_number ) ?>" target="_blank"> -->
          <?php echo get_cat_name( $category_number ) ?> <!-- output the category name -->
        </a>
    </h3>

<?php endif; ?>
<!-- credits to Scott [https://github.com/sblessley] for the link function call! -->


<!-- this snippet outputs the ACF relational field
it outputs a link to a specific related post -->
<?php
  // Get related posts from Advanced Custom Fields
  $related_posts = get_field( 'acf_field_name', get_queried_object_id() );
  // Check if we have any related posts. 
  if ( !empty( $related_posts ) ) :  
?>
<?php 
  // If yes, loop through them.
  foreach( $related_posts as $related_post ) : 
?>
    <div class="project-related">
        <a href="<?php echo get_permalink( $related_post->ID ); ?>" target="_blank" title="<?php echo get_the_title( $related_post ) ?>">
        <!-- here we can choose how to output the link text: -->
            <?php echo 'See related post '; ?> <!-- output a predifined link name -->
        <!-- or -->
            <?php echo $related_post->post_title; ?> <!-- get the post title -->
        </a>
    </div>
<?php endforeach; ?>
<?php endif; ?>



<!-- this snippet outputs the ACF file field
when it sets to return value = url  -->
<?php
/*
*  Show selected file if value exists
*  Return value = URL
*/
if( get_field('upload_publications') ):
?>
    <div class="row-fluid tc-single-post-thumbnail-wrapper __before_content">
        <a href="<?php the_field('upload_publications'); ?>" >
            <span class="download-icon">Download this publication</span>
        </a>
    </div>
<?php
endif;
?>                            


<!-- from kickstart.ca single-artists.php -->
<!-- to avoid printing the fields when they are not filled: -->
<div class="artsit-info author-index shorter">
    <?php if( get_field('website') ): ?>
        <?php 
            echo '<p><h3>WEBSITE: </h3>';
            the_field('website');
            echo '</p>';  
        ?>
    <?php endif; ?>

    <?php if( get_field('email') ): ?>
        <?php 
            echo '<p><h3>EMAIL: </h3><a href="mailto:' . get_field('email') . '">';
            the_field('email');
            echo '</a></p>';
        ?>
    <?php endif; ?>

    <?php if( get_field('phone') ): ?>
        <?php 
            echo '<p><h3>PHONE: </h3><a href="tel:' . get_field('phone') . '">';
            the_field('phone');
            echo '</a></p>';
        ?>
    <?php endif; ?>
</div>
<!-- the link/website field is created thanks to an add-ons plugin -->
<!-- acf-website-field-master -->