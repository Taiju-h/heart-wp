#!/usr/bin/env bash
# Install only the network-root blog list; never changes DB, Config or theme files.
set -euo pipefail
umask 077
wplist_root="${HEARTFUL_WPLIST_ROOT:-/home/heartf/heartf.com/public_html}"
wplist_php="${HEARTFUL_WPLIST_PHP_BIN:-/usr/bin/php8.4}"
wplist_backups="${HEARTFUL_WPLIST_BACKUP_DIR:-/home/heartf/wplist-backups}"
wplist_target="$wplist_root/wplist.php"
if [[ ! -f "$wplist_root/wp-load.php" || ! -f "$wplist_target" || -L "$wplist_target" ]]; then
    printf '%s\n' 'Expected a regular wplist.php beside wp-load.php; nothing changed.' >&2
    exit 1
fi
if [[ ! -x "$wplist_php" ]]; then
    printf '%s\n' 'PHP 8.4 CLI not found; nothing changed.' >&2
    exit 1
fi
wplist_stage=$(mktemp "$wplist_root/.wplist-stage.XXXXXXXX.php")
trap 'rm -f -- "$wplist_stage"' EXIT
cat > "$wplist_stage" <<'HEARTFUL_WPLIST_SOURCE_20261025'
<?php
/** Heartful public blog updates. Place beside the network's wp-load.php. */
require_once __DIR__ . '/wp-load.php';

function heartful_wplist_collect($now = null)
{
    $now = null === $now ? time() : (int) $now;
    $cutoff = $now - 30 * DAY_IN_SECONDS;
    $new_cutoff = $now - 5 * DAY_IN_SECONDS;
    $network_id = get_current_network_id();
    $main_site_id = (int) get_main_site_id($network_id);

    // number=0 removes the default 100-site limit. Skip the main site by ID.
    $sites = get_sites(array(
        'network_id' => $network_id,
        'number' => 0,
        'public' => 1,
        'archived' => 0,
        'spam' => 0,
        'deleted' => 0,
        'orderby' => 'id',
        'order' => 'ASC',
    ));

    $updates = array();
    foreach ($sites as $site) {
        $blog_id = (int) $site->blog_id;
        if ($blog_id === $main_site_id || substr($site->path, 1, 1) === 's') {
            continue;
        }

        switch_to_blog($blog_id);
        try {
            $posts = get_posts(array(
                'posts_per_page' => 5,
                'post_type' => 'post',
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC',
            ));

            $items = array();
            foreach ($posts as $blog_post) {
                // A true Unix timestamp keeps the cutoff independent of site timezone.
                $timestamp = get_post_timestamp($blog_post);
                if (false === $timestamp || $timestamp < $cutoff) {
                    continue;
                }
                $items[] = array(
                    'timestamp' => $timestamp,
                    'title' => get_the_title($blog_post),
                    'url' => get_permalink($blog_post),
                    'date' => get_the_date('Y/m/d/', $blog_post),
                    'is_new' => $timestamp > $new_cutoff,
                );
            }

            if (!empty($items)) {
                // Append records so equal publication times never overwrite another blog.
                $updates[] = array(
                    'blog_id' => $blog_id,
                    'title' => get_bloginfo('name'),
                    'latest' => $items[0]['timestamp'],
                    'items' => $items,
                );
            }
        } finally {
            // Restore even for empty blogs or an exception while reading a post.
            restore_current_blog();
        }
    }

    usort($updates, function ($left, $right) {
        if ($left['latest'] !== $right['latest']) {
            return $left['latest'] > $right['latest'] ? -1 : 1;
        }
        return $left['blog_id'] < $right['blog_id'] ? -1 :
            ($left['blog_id'] > $right['blog_id'] ? 1 : 0);
    });

    return $updates;
}

$heartful_updates = heartful_wplist_collect();
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<style type="text/css">
body {
    margin: 20px;
    padding: 0;
    color: #FFF;
    font-family: "ヒラギノ角ゴ Pro W3", "Hiragino Kaku Gothic Pro", "メイリオ", Meiryo, Osaka, "ＭＳ Ｐゴシック", "MS P Gothic", sans-serif;
    font-size: 14px;
    line-height: 26px;
    overflow-x: hidden;
}
a:link, a:active, a:visited {
    color: #FFFFCC;
}
</style>
</head>
<body>
<!-- Heartful blog updates: 2026-10-25 -->
<?php if (empty($heartful_updates)) : ?>
<p>現在、30日以内のブログ更新はありません。</p>
<?php endif; ?>
<?php foreach ($heartful_updates as $heartful_update) : ?>
<p>
<?php echo esc_html($heartful_update['title']); ?><br>
<?php foreach ($heartful_update['items'] as $heartful_item) : ?>
<a href="<?php echo esc_url($heartful_item['url']); ?>" target="_blank" rel="noopener noreferrer">【<?php echo esc_html($heartful_item['title'] . $heartful_item['date']); ?>】</a>
<?php if ($heartful_item['is_new']) : ?>
<img src="https://uranai.heartf.com/swfu/d/new012_07.gif" alt="新着" title="5日以内に更新">
<?php endif; ?>
<br>
<?php endforeach; ?>
</p>
<?php endforeach; ?>
</body>
</html>
HEARTFUL_WPLIST_SOURCE_20261025
wplist_hash=$(sha256sum "$wplist_stage")
wplist_hash=${wplist_hash%% *}
if [[ "$wplist_hash" != "79cb85740203ae420ff44597d9951298b4d2d74624fefb2b961ef2f59ad73ca4" ]]; then
    printf '%s\n' 'PHP payload checksum mismatch; nothing changed.' >&2
    exit 1
fi
"$wplist_php" -l "$wplist_stage"
if cmp -s -- "$wplist_target" "$wplist_stage"; then
    printf '%s\n' 'wplist.php is already current; nothing changed.'
    exit 0
fi
mkdir -p -- "$wplist_backups"
wplist_backup=$(mktemp "$wplist_backups/wplist.before.XXXXXXXX.php")
cp -p -- "$wplist_target" "$wplist_backup"
chmod --reference="$wplist_target" "$wplist_stage"
mv -f -- "$wplist_stage" "$wplist_target"
trap - EXIT
printf 'Installed: %s\nBackup: %s\n' "$wplist_target" "$wplist_backup"
printf '%s\n' 'Check https://heartf.com/wplist.php and the Heartful homepage.'
