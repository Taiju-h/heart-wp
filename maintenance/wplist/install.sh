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
                    'date' => get_the_date('Y/m/d', $blog_post),
                    'datetime' => get_the_date('Y-m-d', $blog_post),
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
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="color-scheme" content="light">
<title>占い師のブログ｜占いハートフル</title>
<style>
* {
    box-sizing: border-box;
}
html {
    color-scheme: light;
    background: #fff;
}
body {
    margin: 0;
    padding: 20px;
    background: #fff;
    color: #302735;
    font-family: "ヒラギノ角ゴ Pro W3", "Hiragino Kaku Gothic Pro", "メイリオ", Meiryo, Osaka, "ＭＳ Ｐゴシック", "MS P Gothic", sans-serif;
    font-size: 16px;
    line-height: 1.7;
}
.blog-updates {
    max-width: 960px;
    margin: 0 auto;
}
.blog-updates h1 {
    margin: 0 0 4px;
    font-size: 24px;
    line-height: 1.5;
}
.blog-updates-intro {
    margin: 0 0 24px;
    color: #665d6b;
    font-size: 14px;
}
.blog-group {
    margin: 0 0 24px;
    border: 1px solid #ded7e3;
}
.blog-group h2 {
    margin: 0;
    padding: 12px 16px;
    background: #f5f1f7;
    color: #493056;
    font-size: 18px;
    line-height: 1.6;
    overflow-wrap: anywhere;
}
.blog-posts {
    margin: 0;
    padding: 0;
    list-style: none;
}
.blog-posts li + li {
    border-top: 1px solid #eee9f0;
}
.blog-post,
.blog-post:visited {
    display: grid;
    grid-template-columns: 6.2rem minmax(0, 1fr);
    gap: 12px;
    min-height: 48px;
    padding: 12px 16px;
    color: #493056;
    text-decoration: none;
    overflow-wrap: anywhere;
}
.blog-post:hover {
    background: #faf7fc;
}
.blog-post:focus-visible {
    outline: 3px solid #71448a;
    outline-offset: -3px;
    background: #faf7fc;
}
.blog-post-date {
    padding-top: 2px;
    color: #665d6b;
    font-size: 14px;
    font-variant-numeric: tabular-nums;
}
.blog-post-content {
    min-width: 0;
}
.blog-post-title {
    text-decoration: underline;
    text-underline-offset: 3px;
}
.blog-post-new {
    display: inline-block;
    margin-left: 8px;
    padding: 1px 6px;
    vertical-align: 1px;
    background: #f9e5da;
    color: #8a351b;
    font-size: 11px;
    font-weight: bold;
    line-height: 1.6;
}
.blog-updates-empty {
    padding: 20px 16px;
    border: 1px solid #ded7e3;
}
@media (max-width: 480px) {
    body {
        padding: 12px;
    }
    .blog-updates h1 {
        font-size: 22px;
    }
    .blog-group h2 {
        padding: 12px;
        font-size: 16px;
    }
    .blog-post {
        grid-template-columns: minmax(0, 1fr);
        gap: 2px;
        padding: 10px 12px 12px;
    }
}
</style>
</head>
<body>
<!-- Heartful blog updates: 2026-10-25 readable-v2 -->
<main class="blog-updates">
<h1>占い師のブログ</h1>
<p class="blog-updates-intro">30日以内の更新をご紹介します。</p>
<?php if (empty($heartful_updates)) : ?>
<p class="blog-updates-empty">現在、30日以内のブログ更新はありません。</p>
<?php endif; ?>
<?php foreach ($heartful_updates as $heartful_update) : ?>
<section class="blog-group">
<h2><?php echo esc_html($heartful_update['title']); ?></h2>
<ul class="blog-posts">
<?php foreach ($heartful_update['items'] as $heartful_item) : ?>
<li>
<a class="blog-post" href="<?php echo esc_url($heartful_item['url']); ?>" target="_blank" rel="noopener noreferrer">
<time class="blog-post-date" datetime="<?php echo esc_attr($heartful_item['datetime']); ?>"><?php echo esc_html($heartful_item['date']); ?></time>
<span class="blog-post-content">
<span class="blog-post-title"><?php echo esc_html($heartful_item['title']); ?></span>
<?php if ($heartful_item['is_new']) : ?>
<span class="blog-post-new" aria-label="5日以内に更新">NEW</span>
<?php endif; ?>
</span>
</a>
</li>
<?php endforeach; ?>
</ul>
</section>
<?php endforeach; ?>
</main>
</body>
</html>
HEARTFUL_WPLIST_SOURCE_20261025
wplist_hash=$(sha256sum "$wplist_stage")
wplist_hash=${wplist_hash%% *}
if [[ "$wplist_hash" != "573b42ee113b94348e9f9ca00cc45b3d3346058367c0e1f8de20cf39eabc566f" ]]; then
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
