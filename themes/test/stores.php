<?php
$args = array(
     'post_type' => 'omise', 
     'posts_per_page' => 20, 
); ?>
<?php query_posts( $args ); ?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<li><div class="tenpo_banner">
<a href="<?php echo get_field('tenpourl'); ?>" target="_blank"><img src="<?php the_post_thumbnail_url('full'); ?>" alt="<?php echo get_field('bntit'); ?>">
<h5><?php echo nl2br(esc_html(get_field('bntit'))); ?></h5>
<span class="tel"><?php echo get_field('tenpotel'); ?></span><br> <?php echo get_field('moyori'); ?></a></div>
</li>
<?php endwhile; // end of the loop. ?>
<?php wp_reset_query(); ?>