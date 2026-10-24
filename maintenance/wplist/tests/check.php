<?php
require_once '/app/wp-load.php';
$now = time();
for ($id = 1; $id <= 125; $id++) {
    $GLOBALS['fixture_sites'][] = (object) array('blog_id' => $id, 'path' => '/h' . $id . '/',
        'public' => 1, 'archived' => 0, 'spam' => 0, 'deleted' => 0);
    $GLOBALS['fixture_titles'][$id] = 'Fixture blog ' . $id;
}
$GLOBALS['fixture_sites'][] = (object) array('blog_id' => 594, 'path' => '/h0594/',
    'public' => 1, 'archived' => 0, 'spam' => 0, 'deleted' => 0);
$GLOBALS['fixture_titles'][594] = '占い師SUBROSAのBlog';
$GLOBALS['fixture_posts'][594] = array(fixture_post($now - 6 * DAY_IN_SECONDS, '明るい未来の確信犯✨️'));
$GLOBALS['fixture_posts'][1] = array(fixture_post($now, 'main-site-excluded'));
$GLOBALS['fixture_posts'][2] = array(fixture_post($now - DAY_IN_SECONDS, 'same-time-A'));
$GLOBALS['fixture_posts'][3] = array(fixture_post($now - DAY_IN_SECONDS, 'same-time-B'));
$GLOBALS['fixture_posts'][4] = array(fixture_post($now - 31 * DAY_IN_SECONDS, 'too-old'));
$GLOBALS['fixture_sites'][6]->path = '/s0007/';
$GLOBALS['fixture_posts'][7] = array(fixture_post($now, 'school-excluded'));
foreach (array(8 => 'public', 9 => 'archived', 10 => 'spam', 11 => 'deleted') as $id => $flag) {
    $GLOBALS['fixture_sites'][$id - 1]->$flag = $flag === 'public' ? 0 : 1;
    $GLOBALS['fixture_posts'][$id] = array(fixture_post($now, 'excluded-' . $id));
}
for ($n = 0; $n < 8; $n++) $GLOBALS['fixture_posts'][12][] = fixture_post($now - $n * 60, 'item-' . $n);
$GLOBALS['fixture_posts'][12][] = fixture_post($now + 60, 'draft-excluded', 'draft');
$GLOBALS['fixture_titles'][12] = '占い師穏樹のBlog';
$GLOBALS['fixture_titles'][2] = '占い師ベルのBlog';
$GLOBALS['fixture_titles'][3] = '占い師 紫水愛のblog';
$GLOBALS['fixture_posts'][12][0]->title = '海外で頑張っているのに、なぜか報われないと感じたら';
$GLOBALS['fixture_posts'][12][1]->title = '年末の大掃除は少しずつ！お部屋と心を整えて、新しい年を迎える準備を始めましょう';
$GLOBALS['fixture_posts'][12][2]->title = '「働く」ということの、もうひとつの形';
$GLOBALS['fixture_posts'][12][3]->title = '牡牛座満月🌕 本当の豊かさは、すでにあなたの中にある';
$GLOBALS['fixture_posts'][12][4]->title = 'レイキと霊視は違うんです';
$GLOBALS['fixture_posts'][2][0]->title = '執着の整理';
$GLOBALS['fixture_posts'][3][0]->title = '【風水術・方位術でつむぐ再出発】離婚問題・人間関係のトラブルから運気を引揚げる開運処方箋🍀親しい方との離別…全て解明していきます✨✨';
$GLOBALS['fixture_posts'][24] = array(fixture_post($now - 25 * DAY_IN_SECONDS, str_repeat('LongTitle', 50)));
$GLOBALS['fixture_posts'][13] = array(fixture_post($now - 30 * DAY_IN_SECONDS, 'boundary-30-days'));
$GLOBALS['fixture_posts'][14] = array(fixture_post($now - 30 * DAY_IN_SECONDS - 1, 'outside-30-days'));
$GLOBALS['fixture_posts'][20] = array(fixture_post($now - 5 * DAY_IN_SECONDS, 'new-boundary'));
$GLOBALS['fixture_posts'][21] = array(fixture_post($now - 5 * DAY_IN_SECONDS + 1, 'new-inside'));
$GLOBALS['fixture_posts'][22] = array(fixture_post(false, 'invalid-date'));
$GLOBALS['fixture_titles'][23] = '<script>blog</script>';
$GLOBALS['fixture_posts'][23] = array(fixture_post($now - 2 * DAY_IN_SECONDS, '<script>alert(1)</script>'));
$post = (object) array('sentinel' => 'keep-global-post');
$original_post = $post;
if ($CASE === 'empty') $GLOBALS['fixture_posts'] = array();
if ($CASE === 'exception') $GLOBALS['fixture_throw'] = 3;

ob_start();
try {
    require '/app/wplist.php';
    $html = ob_get_clean();
} catch (RuntimeException $error) {
    ob_end_clean();
    if ($CASE !== 'exception') throw $error;
    fixture_expect($GLOBALS['fixture_current_blog'] === 1, 'Exception did not restore original blog');
    fixture_expect(empty($GLOBALS['fixture_stack']), 'Exception leaked a switch');
    echo 'PASS: exception path restores blog context';
    return;
}
fixture_expect($CASE !== 'exception', 'Exception fixture did not execute');
fixture_expect($GLOBALS['fixture_current_blog'] === 1 && empty($GLOBALS['fixture_stack']), 'Blog context leaked');
fixture_expect($post === $original_post, 'Global post changed');
if ($CASE === 'empty') {
    fixture_expect(strpos($html, '30日以内のブログ更新はありません') !== false, 'Empty-state text missing');
    echo 'PASS: empty state and blog context';
    return;
}
$updates = heartful_wplist_collect($now);
$by_id = array_column($updates, null, 'blog_id');
fixture_expect($GLOBALS['fixture_sites_args']['number'] === 0, 'Site retrieval is capped');
fixture_expect(isset($by_id[594]) && strpos($html, 'SUBROSA') !== false, 'Blog after the first 100 is missing');
fixture_expect(isset($by_id[2], $by_id[3]), 'Equal timestamps overwrote a blog');
fixture_expect($by_id[2]['latest'] === $by_id[3]['latest'], 'Same-time fixture invalid');
fixture_expect(count($by_id[12]['items']) === 5, 'Per-blog limit changed');
fixture_expect(isset($by_id[13]) && !isset($by_id[14]), '30-day cutoff boundary changed');
fixture_expect(!$by_id[20]['items'][0]['is_new'] && $by_id[21]['items'][0]['is_new'], '5-day NEW boundary changed');
foreach (array(1, 4, 7, 8, 9, 10, 11, 22) as $excluded) fixture_expect(!isset($by_id[$excluded]), 'Excluded blog leaked: ' . $excluded);
foreach (array(1, 7, 8, 9, 10, 11) as $excluded) fixture_expect(!in_array($excluded, $GLOBALS['fixture_queried'], true), 'Excluded blog was queried');
fixture_expect(strpos($html, '<script>') === false && strpos($html, '&lt;script&gt;') !== false, 'Output is not escaped');
file_put_contents('/app/rendered.html', $html);
for ($i = 1; $i < count($updates); $i++) fixture_expect($updates[$i - 1]['latest'] >= $updates[$i]['latest'], 'Blogs are not newest first');
fixture_expect(empty($GLOBALS['fixture_stack']) && $GLOBALS['fixture_current_blog'] === 1, 'Repeated collection leaked context');
echo 'PASS: 126 sites, beyond-100 SUBROSA, tied times, 5 posts, 30/5-day boundaries, exclusions, escaping, order, global post and context';
