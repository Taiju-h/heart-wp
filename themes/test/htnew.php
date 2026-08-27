<ul>
<?php query_posts('post_type=news&showposts=10');?>
				<?php if(have_posts()): ?>
				<?php while(have_posts()): the_post();?>
<li><b><?php echo get_the_date( 'm-d' ); ?> </b><?php the_content(); ?></li>
<?php endwhile; ?>
<?php endif; ?>
</ul>