<?php
/**
 * Homepage customer voice slider items.
 *
 * The surrounding list and Slick initialization live in the WordPress front
 * page content. Items use the same merged WordPress + legacy database source
 * as the full customer voice archive.
 */

$heartful_home_voice_batch = heartful_voice_get_batch(null, 10);

if (! is_wp_error($heartful_home_voice_batch)) {
	echo heartful_voice_render_items($heartful_home_voice_batch['rows'], 80); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
