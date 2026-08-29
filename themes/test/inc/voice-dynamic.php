<?php
/**
 * Dynamic customer voice archive.
 *
 * The archive combines recent WordPress `csvoice` posts with approved reviews
 * from the legacy Heartful database and serves them in small batches.
 */

if (! defined('ABSPATH')) {
	exit;
}

const HEARTFUL_VOICE_PAGE_SIZE = 20;

/**
 * Return WordPress customer voice posts older than the supplied cursor.
 */
function heartful_voice_get_wp_rows($cursor, $limit)
{
	global $wpdb;

	$params = array('csvoice', 'publish');
	$where  = '';

	if (is_array($cursor)) {
		if ('wp' === $cursor['source']) {
			$where = ' AND (post_date < %s OR (post_date = %s AND ID < %d))';
			$params[] = $cursor['created'];
			$params[] = $cursor['created'];
			$params[] = $cursor['id'];
		} else {
			// WordPress rows sort before legacy rows when timestamps are equal.
			$where = ' AND post_date < %s';
			$params[] = $cursor['created'];
		}
	}

	$params[] = $limit;
	$sql = "SELECT ID, post_date, post_title, post_content
		FROM {$wpdb->posts}
		WHERE post_type = %s AND post_status = %s{$where}
		ORDER BY post_date DESC, ID DESC
		LIMIT %d";

	$posts = $wpdb->get_results($wpdb->prepare($sql, $params));
	$rows  = array();

	foreach ($posts as $post) {
		$image  = get_the_post_thumbnail_url((int) $post->ID, 'full');
		$rows[] = array(
			'id'      => (int) $post->ID,
			'source'  => 'wp',
			'created' => $post->post_date,
			'title'   => $post->post_title,
			'content' => $post->post_content,
			'image'   => $image ? $image : '',
			'alt'     => $post->post_title,
			'rating'  => '★★★★★',
		);
	}

	return $rows;
}

/**
 * Return approved legacy reviews older than the supplied cursor.
 */
function heartful_voice_get_legacy_rows($cursor, $limit)
{
	$link = mysqli_init();
	if (! $link) {
		return new WP_Error('voice_db_init', 'Database connection could not be initialized.');
	}

	mysqli_options($link, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
	if (! @mysqli_real_connect($link, SEVER, USER_ID, USER_PASS, USER_DB)) {
		return new WP_Error('voice_db_connect', 'Database connection failed.');
	}
	mysqli_set_charset($link, USER_CHATSET);

	$select = 'SELECT DISTINCT Tfeedback.id, Mkanteishi.name, Tfeedback.nicname,
		Tfeedback.feedback, Mnendai.name AS Mname, Msex.KBN,
		Tfeedback.mkanteishi_id, Tfeedback.created, Mevaluate.name AS evaname
		FROM tfeedback AS Tfeedback
		INNER JOIN mkanteishis AS Mkanteishi ON Tfeedback.mkanteishi_id = Mkanteishi.id
		INNER JOIN msex AS Msex ON Tfeedback.msex_id = Msex.id
		INNER JOIN mnendais AS Mnendai ON Tfeedback.mnendai_id = Mnendai.id
		INNER JOIN mevaluates AS Mevaluate ON Tfeedback.mevaluate_id = Mevaluate.id
		WHERE Mkanteishi.delflg = 0 AND Tfeedback.approval_kbn = 1';

	if (! is_array($cursor)) {
		$sql  = $select . ' ORDER BY Tfeedback.created DESC, Tfeedback.id DESC LIMIT ?';
		$stmt = mysqli_prepare($link, $sql);
		if ($stmt) {
			mysqli_stmt_bind_param($stmt, 'i', $limit);
		}
	} elseif ('legacy' === $cursor['source']) {
		$sql  = $select . ' AND (Tfeedback.created < ? OR (Tfeedback.created = ? AND Tfeedback.id < ?))'
			. ' ORDER BY Tfeedback.created DESC, Tfeedback.id DESC LIMIT ?';
		$stmt = mysqli_prepare($link, $sql);
		if ($stmt) {
			mysqli_stmt_bind_param($stmt, 'ssii', $cursor['created'], $cursor['created'], $cursor['id'], $limit);
		}
	} else {
		// Legacy rows sort after WordPress rows when timestamps are equal.
		$sql  = $select . ' AND Tfeedback.created <= ?'
			. ' ORDER BY Tfeedback.created DESC, Tfeedback.id DESC LIMIT ?';
		$stmt = mysqli_prepare($link, $sql);
		if ($stmt) {
			mysqli_stmt_bind_param($stmt, 'si', $cursor['created'], $limit);
		}
	}

	if (! $stmt || ! mysqli_stmt_execute($stmt)) {
		if ($stmt) {
			mysqli_stmt_close($stmt);
		}
		mysqli_close($link);
		return new WP_Error('voice_db_query', 'Customer voices could not be loaded.');
	}

	$result = mysqli_stmt_get_result($stmt);
	$rows   = array();

	while ($row = mysqli_fetch_assoc($result)) {
		$rows[] = array(
			'id'      => (int) $row['id'],
			'source'  => 'legacy',
			'created' => $row['created'],
			'title'   => trim($row['nicname']) . '(' . $row['Mname'] . $row['KBN'] . ')',
			'content' => $row['feedback'],
			'image'   => USER_IMG3 . sprintf('%04d', $row['mkanteishi_id']) . '.jpg',
			'alt'     => $row['name'],
			'rating'  => $row['evaname'],
		);
	}

	mysqli_free_result($result);
	mysqli_stmt_close($stmt);
	mysqli_close($link);

	return $rows;
}

/**
 * Combine both review sources into a chronologically ordered batch.
 */
function heartful_voice_get_batch($cursor = null, $page_size = HEARTFUL_VOICE_PAGE_SIZE)
{
	$fetch_size = $page_size + 1;
	$wp_rows    = heartful_voice_get_wp_rows($cursor, $fetch_size);
	$legacy     = heartful_voice_get_legacy_rows($cursor, $fetch_size);

	if (is_wp_error($legacy)) {
		return $legacy;
	}

	$rows = array_merge($wp_rows, $legacy);
	usort($rows, function ($left, $right) {
		$date_order = strcmp($right['created'], $left['created']);
		if (0 !== $date_order) {
			return $date_order;
		}

		if ($left['source'] !== $right['source']) {
			return 'wp' === $left['source'] ? -1 : 1;
		}

		return $right['id'] <=> $left['id'];
	});

	$has_more = count($rows) > $page_size;
	$rows     = array_slice($rows, 0, $page_size);
	$next     = null;

	if ($has_more && $rows) {
		$last = end($rows);
		$next = array(
			'created' => $last['created'],
			'source'  => $last['source'],
			'id'      => $last['id'],
		);
	}

	return array(
		'rows'        => $rows,
		'has_more'    => $has_more,
		'next_cursor' => $next,
	);
}

/**
 * Render review cards shared by the initial page and AJAX responses.
 */
function heartful_voice_render_items($rows)
{
	ob_start();
	foreach ($rows as $row) {
		$date = date_create($row['created']);
		?>
		<li class="heartful-voice-item">
			<div class="profile_container">
				<?php if ($row['image']) : ?>
					<img src="<?php echo esc_url($row['image']); ?>" alt="<?php echo esc_attr($row['alt']); ?>" loading="lazy" decoding="async">
				<?php endif; ?>
				<div class="profile_detail">
					<span><?php echo esc_html($date ? $date->format('Y-m-d') : ''); ?></span>
					<p class="Mincho"><?php echo esc_html($row['title']); ?></p>
				</div>
				<span class="hosi"><?php echo esc_html($row['rating']); ?></span>
				<div class="heartful-voice-text"><?php echo wp_kses_post(wpautop($row['content'])); ?></div>
			</div>
		</li>
		<?php
	}

	return ob_get_clean();
}

/**
 * Validate a cursor received from the browser.
 */
function heartful_voice_parse_cursor($value)
{
	if (! is_array($value)
		|| empty($value['created'])
		|| ! in_array($value['source'] ?? '', array('wp', 'legacy'), true)
		|| ! preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value['created'])) {
		return null;
	}

	return array(
		'created' => $value['created'],
		'source'  => $value['source'],
		'id'      => absint($value['id'] ?? 0),
	);
}

/**
 * Public AJAX endpoint for the "load more" button.
 */
function heartful_voice_ajax_load_more()
{
	check_ajax_referer('heartful_voice_load_more', 'nonce');

	$cursor = isset($_POST['cursor']) ? json_decode(wp_unslash($_POST['cursor']), true) : null;
	$cursor = heartful_voice_parse_cursor($cursor);

	if (! $cursor) {
		wp_send_json_error(array('message' => 'Invalid cursor.'), 400);
	}

	$batch = heartful_voice_get_batch($cursor);
	if (is_wp_error($batch)) {
		wp_send_json_error(array('message' => 'Customer voices could not be loaded.'), 500);
	}

	wp_send_json_success(array(
		'html'        => heartful_voice_render_items($batch['rows']),
		'has_more'    => $batch['has_more'],
		'next_cursor' => $batch['next_cursor'],
	));
}
add_action('wp_ajax_heartful_voice_load_more', 'heartful_voice_ajax_load_more');
add_action('wp_ajax_nopriv_heartful_voice_load_more', 'heartful_voice_ajax_load_more');
