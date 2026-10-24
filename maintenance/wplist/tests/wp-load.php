<?php
// Isolated fixtures; no real WordPress, database, HTTP requests or mutations.
define('DAY_IN_SECONDS', 86400);
$GLOBALS['fixture_current_blog'] = 1;
$GLOBALS['fixture_stack'] = array();
$GLOBALS['fixture_sites'] = array();
$GLOBALS['fixture_posts'] = array();
$GLOBALS['fixture_titles'] = array();
$GLOBALS['fixture_throw'] = null;
$GLOBALS['fixture_sites_args'] = array();
$GLOBALS['fixture_queried'] = array();

function get_current_network_id() { return 1; }
function get_main_site_id($network_id) { return 1; }
function get_sites($args) {
    $GLOBALS['fixture_sites_args'] = $args;
    $sites = array_values(array_filter($GLOBALS['fixture_sites'], function ($site) use ($args) {
        foreach (array('public', 'archived', 'spam', 'deleted') as $key) {
            if (isset($args[$key]) && $site->$key != $args[$key]) return false;
        }
        return true;
    }));
    $number = isset($args['number']) ? $args['number'] : 100;
    return $number === 0 ? $sites : array_slice($sites, 0, $number);
}
function switch_to_blog($id) {
    $GLOBALS['fixture_stack'][] = $GLOBALS['fixture_current_blog'];
    $GLOBALS['fixture_current_blog'] = $id;
}
function restore_current_blog() {
    if (!$GLOBALS['fixture_stack']) throw new RuntimeException('Unbalanced restore');
    $GLOBALS['fixture_current_blog'] = array_pop($GLOBALS['fixture_stack']);
}
function get_posts($args) {
    $id = $GLOBALS['fixture_current_blog'];
    $GLOBALS['fixture_queried'][] = $id;
    if ($GLOBALS['fixture_throw'] === $id) throw new RuntimeException('fixture read failure');
    if ($args['post_status'] !== 'publish' || $args['post_type'] !== 'post') {
        throw new RuntimeException('Only public posts may be queried');
    }
    $posts = isset($GLOBALS['fixture_posts'][$id]) ? $GLOBALS['fixture_posts'][$id] : array();
    $posts = array_values(array_filter($posts, function ($post) { return $post->status === 'publish'; }));
    usort($posts, function ($a, $b) { return $b->timestamp <=> $a->timestamp; });
    return array_slice($posts, 0, $args['posts_per_page']);
}
function get_post_timestamp($post) { return $post->timestamp; }
function get_the_title($post) { return $post->title; }
function get_permalink($post) { return $post->url; }
function get_the_date($format, $post) { return gmdate($format, $post->timestamp); }
function get_bloginfo($show) { return $GLOBALS['fixture_titles'][$GLOBALS['fixture_current_blog']]; }
function esc_html($value) { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
function esc_attr($value) { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
function esc_url($value) { return preg_match('~^https?://~i', $value) ? esc_html($value) : ''; }

function fixture_post($timestamp, $title = '公開記事', $status = 'publish') {
    return (object) array('timestamp' => $timestamp, 'title' => $title, 'status' => $status,
        'url' => 'https://fixture.test/article/' . rawurlencode($title));
}
function fixture_expect($condition, $message) {
    if (!$condition) throw new RuntimeException($message);
}
