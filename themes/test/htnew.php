<ul>
<?php
$heartful_news_query = new WP_Query(
	array(
		'post_type'      => 'news',
		'posts_per_page' => 10,
	)
);
?>
				<?php if ($heartful_news_query->have_posts()): ?>
				<?php while ($heartful_news_query->have_posts()): $heartful_news_query->the_post();?>
<li><b><?php echo get_the_date( 'm-d' ); ?> </b><?php the_content(); ?></li>
<?php endwhile; ?>
<?php endif; ?>
<?php wp_reset_postdata(); ?>
</ul>
