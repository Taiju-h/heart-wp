<?php 
// テーマをショートコード
add_shortcode('theme', 'shortcode_theme');
function shortcode_theme() {
  return get_template_directory_uri();
}
remove_filter ('the_content', 'wpautop'); 
function my_nl2br_content( $content ) {
    // <pre>, <style>, <script> を一時退避
    $parts = preg_split(
        '/(<pre[^>]*>.*?<\/pre>|<style[^>]*>.*?<\/style>|<script[^>]*>.*?<\/script>)/is',
        $content,
        -1,
        PREG_SPLIT_DELIM_CAPTURE
    );

    foreach ( $parts as $i => $part ) {
        if ( $i % 2 === 0 ) {
            // 改行コードを統一
            $part = preg_replace( "/\r\n|\r/", "\n", $part );

            // タグの直後・直前の改行にはbrを入れない
            $part = preg_replace( '/(?<!>)\n(?!\s*<)/', "<br>\n", $part );

            $parts[ $i ] = $part;
        }
    }

    return implode( '', $parts );
}
add_filter( 'the_content', 'my_nl2br_content' );

// functions.php など
require_once get_stylesheet_directory() . '/inc/db_select.inc.php';

function my_db_select_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'pref'      => '',
        'mtenpo_id' => null,
        'sub'       => null,
        'sub2'      => null,
        'sub3'      => null,
        'sub4'      => null,
    ), $atts, 'db_select' );

    return plugin_db_select_convert(
        $atts['pref'],
        $atts['mtenpo_id'],
        $atts['sub'],
        $atts['sub2'],
        $atts['sub3'],
        $atts['sub4']
    );
}
add_shortcode( 'db_select', 'my_db_select_shortcode' );

function Include_my_php($params = array()) {
    extract(shortcode_atts(array(
        'file' => 'default'
    ), $params));
    ob_start();
    include(get_theme_root() . '/' . get_template() . "/$file.php");
    return ob_get_clean();
}
 
add_shortcode('myphp', 'Include_my_php');

add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_script(
        'cf7-confirm-modal',                                          // ハンドル名（任意）
        get_stylesheet_directory_uri() . '/js/cfm.js', // ファイルパス
        array( 'jquery' ),                                            // jQueryに依存
        '1.0.0',                                                      // バージョン
        true                                                          // フッターで読み込む
    );
} );

function sc_homeurl() {
	return esc_url( home_url() );
}
add_shortcode( 'home', 'sc_homeurl' );

?>