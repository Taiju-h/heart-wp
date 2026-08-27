<?php
$args = array(
     'post_type' => 'csvoice', 
     'posts_per_page' => 10, 
); ?>
<?php query_posts( $args ); ?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<li><div class='profile_container'>
<img src='<?php the_post_thumbnail_url('full'); ?>' alt='<?php the_title(); ?>'>
<div class="profile_detail"><span><?php the_time('Y-m-d'); ?></span>
<p class='Mincho'><?php the_title(); ?></p>
</div>
<span class='hosi'>★★★★★</span>
<?php echo wp_trim_words( get_the_content(), 80, '…' ); ?></li>
<?php endwhile; // end of the loop. ?>
<?php wp_reset_query(); ?>