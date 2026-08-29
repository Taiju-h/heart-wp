<?php
/**
 * Customer voice archive template.
 */

$heartful_voice_batch = heartful_voice_get_batch();
get_header();
?>
<main id="container" class="main_content">
	<div class="content heartful-voice-archive">
		<h2 class="Mincho title">お客様の声<span>customer's voice</span></h2>
		<?php if (is_wp_error($heartful_voice_batch)) : ?>
			<p class="heartful-voice-error">お客様の声を読み込めませんでした。時間をおいて、もう一度お試しください。</p>
		<?php else : ?>
			<ul id="heartful-voice-list" class="voice" aria-live="polite">
				<?php echo heartful_voice_render_items($heartful_voice_batch['rows']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</ul>
			<?php if ($heartful_voice_batch['has_more']) : ?>
				<div class="heartful-voice-actions">
					<button id="heartful-voice-more" class="heartful-voice-more" type="button" aria-controls="heartful-voice-list">もっと見る</button>
					<p id="heartful-voice-status" class="heartful-voice-status" role="status" aria-live="polite"></p>
				</div>
			<?php endif; ?>

			<?php echo heartful_voice_render_teacher_dialog(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

			<script>
			window.heartfulVoice = <?php echo wp_json_encode(array(
				'ajaxUrl' => admin_url('admin-ajax.php'),
				'nonce'   => wp_create_nonce('heartful_voice_load_more'),
				'cursor'  => $heartful_voice_batch['next_cursor'],
			)); ?>;
			</script>
			<script defer src="<?php echo esc_url(get_theme_file_uri('/js/voice-dynamic.js')); ?>?v=1.2.0"></script>
		<?php endif; ?>
	</div>
</main>
<?php get_footer(); ?>
