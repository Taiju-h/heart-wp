<?php
$my_query = new WP_Query( array(
    'post_type'      => 'post',
    'posts_per_page' => 5,
) );

if ( $my_query->have_posts() ) :
    while ( $my_query->have_posts() ) : $my_query->the_post();
        $count  = $my_query->current_post + 1; // 1始まり
        $suffix = ( $count % 2 === 1 ) ? 1 : 2; // 奇数→1、偶数→2
?>
<?php if (get_field('cambg')): ?>
<div class="bg_gray">
<?php endif; ?>
<div class="informd_bg<?php echo $suffix; ?>">
  <a href="<?php echo get_field('camurl'); ?>" <?php if (get_field('camlink')): ?>target="_blank"<?php endif; ?>>
    <img src="<?php echo get_field('camimg'); ?>" style="max-width:100%;">
  </a>
</div>

<section class="inform_topic<?php echo $suffix; ?>">
  <h4 style="text-align:center;"><?php the_title(); ?></h4>
  <?php the_content(); ?>
</section><div class="clearfloat"></div>
<?php if (get_field('cambg')): ?>
</div>
<?php endif; ?>

<?php
    endwhile;
    wp_reset_postdata(); // メインクエリへの影響を防ぐため必須
endif;
?>