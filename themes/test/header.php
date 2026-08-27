<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="<?php echo get_field('keyword'); ?>" />
    <meta name="description" content="<?php echo get_field('description'); ?>" />
		<link rel="canonical" href="<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; ?>" />
  <title>神田･浅草･上野･御徒町･自由が丘･横浜･新宿で当たる占い館【ハートフル】 | <?php the_title(); ?></title>
    <link rel="stylesheet" href="<?php echo esc_url( get_theme_file_uri() ); ?>/skin/bootstrap/css/bootstrap.min.css" />
		<link rel="stylesheet" href="<?php echo esc_url( get_theme_file_uri() ); ?>/skin/hokukenstyle/print.css" media="print">
<link rel="shortcut icon" href="favicon.ico"  type="image/x-icon" />
  <link rel="stylesheet" href="<?php echo esc_url( get_theme_file_uri() ); ?>/commons/reset.css">
    <link rel="stylesheet" href="<?php echo esc_url( get_theme_file_uri() ); ?>/commons/style.css?v=7">
  <link rel="stylesheet" type="text/css" href="<?php echo esc_url( get_theme_file_uri() ); ?>/commons/slick.css?v=4" />
  <link rel="stylesheet" href="<?php echo esc_url( get_theme_file_uri() ); ?>/js/dist/zoomslider.css">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo esc_url( get_theme_file_uri() ); ?>/js/jquery.js"></script>
		<script type="text/javascript" src="<?php echo esc_url( get_theme_file_uri() ); ?>/skin/bootstrap/js/bootstrap.min.js"></script>
	<link rel="author" href="https://plus.google.com/103178923754360096075" />
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-LX121NPD7K"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-LX121NPD7K');
</script>
		<script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js"></script>
<script defer src="https://use.fontawesome.com/releases/v5.1.0/js/v4-shims.js"></script>
<script src="js/qhm.min.js"></script>
<script type="text/javascript">
$(function(){
  $(".list-group > .list-group-item").find(".list-group-item").removeClass("list-group-item");
  $("#menubar .list-group .list-group-item a").each(function(){
    var url = $(this).attr("href");
    if (url == "https://uranai.heartf.com/index.php\?FrontPage") {
      $(this).parent().addClass("active");
    }
  });
});
</script>

<script type="text/javascript">
<!--
$(document).ready(function(){
	$("ul.accordion > li > a.acctitle")
		.click(
			function() {
				if (!$(this).attr("href").match(/^#/)) {
					location.href = $(this).attr("href");
					return false;
				}
				$(this).parent().parent().siblings("ul.accordion").find("a.acctitle").removeClass("focustitle");
				$(">ul:not(:animated)",$(this).parent()).toggle();

				if ($(this).next("ul.subbox").is(":visible")) {
					$(this).addClass("focustitle");
				}
				else {
					$(this).removeClass("focustitle");
				}
				return false;
			}
		);
});
//-->
</script>

<style type="text/css">
ul.accordion{
list-style:none;
margin:0 !important;
padding:0;
}
ul.dropn1 li a.acctitle{
display:block;
background:transparent url("<?php echo esc_url( get_theme_file_uri() ); ?>/image/accordion_title_bg.png") repeat-x 0 0;background-size: 1px 100%;line-height:30px;color:#666;
}
ul.dropn1 li a.acctitle:hover,
ul.dropn1 li a.focustitle {
background:transparent url("<?php echo esc_url( get_theme_file_uri() ); ?>/image/accordion_title_hoverbg.png") repeat-x 0 0;background-size: 1px 100%;text-decoration:none;color:#fff;
}
ul.subbox{
list-style:none;
margin:0 0!important;
display:none;
padding:0;
}
ul.dropn1 div.accbox{
padding:5px;
}
span.accexpand{
margin:auto 5px;
}
</style>
<style>
.kuchikomi_area table.schedule td{
    width: 30%;
}
.kuchikomi_area table.schedule td:nth-child(1) {
    width: 32%;

}
.tbn {
  display: flex;
  justify-content: space-evenly;
  max-width: 600px;
  margin: 0 auto;
}

.tbn img {
max-width:100%;
  padding: 10px;
  height: auto;
}

.screen-reader-response ul{
				display:none;
				}
/* =====================
   CF7 確認モーダル
===================== */
/* モーダル本体（非表示時） */
#cf7-confirm-modal {
  display: none;
  position: fixed;
  inset: 0;
  z-index: 9999;
}
/* .is-visible クラスが付いたとき表示 */
#cf7-confirm-modal.is-visible {
  display: block;
}
/* 背景オーバーレイ（半透明の黒） */
#cf7-confirm-overlay {
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
}
/* モーダルボックス本体 */
#cf7-confirm-box {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: #fff;
  border-radius: 8px;
  padding: 32px 28px;
  width: 90%;
  max-width: 560px;
  max-height: 80vh;        /* 画面高さの80%を超えたらスクロール */
  overflow-y: auto;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.18);
}
/* タイトル */
#cf7-confirm-box h2 {
  font-size: 18px;
  margin: 0 0 8px;
  color: #333;
}
/* リード文 */
.cf7-confirm-lead {
  font-size: 14px;
  color: #666;
  margin: 0 0 20px;
  line-height: 1.7;
}
/* 入力内容テーブル（dl要素） */
#cf7-confirm-table {
  margin: 0 0 24px;
  padding: 0;
}
/* 1行分（項目名 + 入力値） */
.cf7-confirm-row {
  display: grid;
  grid-template-columns: 140px 1fr;  /* 左：項目名 / 右：入力値 */
  gap: 8px 16px;
  padding: 12px 0;
  border-bottom: 1px solid #eee;
  font-size: 14px;
  line-height: 1.6;
}
.cf7-confirm-row:first-child {
  border-top: 1px solid #eee;
}
/* 項目名 */
.cf7-confirm-row dt {
  font-weight: bold;
  color: #555;
  word-break: break-all;
}
/* 入力値 */
.cf7-confirm-row dd {
  margin: 0;
  color: #333;
  word-break: break-all;
}
/* ボタンエリア */
.cf7-confirm-buttons {
  display: flex;
  gap: 12px;
  justify-content: center;
  flex-wrap: wrap;
}
/* 「戻って修正する」ボタン */
#cf7-back-btn {
  padding: 10px 24px;
  background: #f0f0f0;
  color: #555;
  border: none;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
  transition: background 0.2s;
}
#cf7-back-btn:hover {
  background: #ddd;
}
/* 「送信する」ボタン */
#cf7-send-btn {
  padding: 10px 24px;
  background: #4a90e2;
  color: #fff;
  border: none;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
  transition: background 0.2s;
}
#cf7-send-btn:hover {
  background: #357abd;
}
/* モーダル表示中はページ本体のスクロールを禁止 */
body.cf7-modal-open {
  overflow: hidden;
}
/* ===============
   スマホ対応
=============== */
@media (max-width: 480px) {
  #cf7-confirm-box {
    padding: 24px 16px;
  }
  /* スマホでは項目名と入力値を縦並びに */
  .cf7-confirm-row {
    grid-template-columns: 1fr;
    gap: 4px;
  }
}
.wpcf7-not-valid-tip{
				display:block;
				color:#a94442;
				}
.col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
    padding-left: 0; */
}
.smonly{
				display:none;
				}
			@media (max-width: 480px) {
.smonly{
				display:inherit;
				}
				}
.screen-reader-response{
				display:none;
				}
</style>
<script type="text/javascript">
<!--
$(document).ready(function(){
	$("ul.dropn1 > li > a.acctitle").each(function(){
		if (!$(this).children("span.accexpand").is(":visible")) {
			$(this).prepend('<span class="accexpand"><img src="<?php echo esc_url( get_theme_file_uri() ); ?>/image/accordion_close.png" /></span>');
		}
	});
	$("ul.dropn1 > li > a.acctitle").click(
			function() {
				if ($(this).next("ul.subbox").is(":visible")) {
					$("span.accexpand",this).html('<img src="<?php echo esc_url( get_theme_file_uri() ); ?>/image/accordion_open.png" />');
				}
				else {
					$("span.accexpand",this).html('<img src="<?php echo esc_url( get_theme_file_uri() ); ?>/image/accordion_close.png" />');
				}
				return false;
	});
});
//-->
</script>
<script type="text/javascript">
var name = "width";			// クッキーの名前
var value = window.innerWidth;		// クッキーの値
var path = "/";
document.cookie = name + "=" + escape(value) + "; path =" + path;
var name = "awidth";			// クッキーの名前
var value = screen.availWidth;		// クッキーの値
var path = "/";
document.cookie = name + "=" + escape(value) + "; path =" + path;

</script >
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-DN9TZ3Q2LG"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-DN9TZ3Q2LG');
</script>
<script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "ohypceto3a");
</script>
<style>
.flowimage{
      float: left;
      width: 400px;
padding:0 0 1rem;
margin-right: 20px;
    }
.flowimage img{
margin: 0 auto;
}
.flow_container{
overflow:hidden;
}
    @media screen and (max-width: 550px) {
      .flowimage{
      float: none;
      width: 340px;
      margin: 0 auto;
    }
    }
.wpcf7{
				overflow:hidden;
	}
	@media screen and (max-width: 700px) {
	.smonly{
		display:inherit !important;
	}
	}
</style>
<link rel="stylesheet" href="plugin/section/section.css" />
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'G-LX121NPD7K', 'auto');
  ga('send', 'pageview');

</script>

  <script type="text/javascript" src="<?php echo esc_url( get_theme_file_uri() ); ?>/js/modernizr-custom.js"></script>
  <script src="<?php echo esc_url( get_theme_file_uri() ); ?>/js/lazyload.min.js"></script>
    <script>
      (function(){
      var _UA = navigator.userAgent;
      if (_UA.indexOf('iPhone') > 0) {
      document.write('<script type="text/javascript" src="<?php echo esc_url( get_theme_file_uri() ); ?>/jquery.browser.js"><\/script>');
      }else{
      document.write('<script type="text/javascript" src="<?php echo esc_url( get_theme_file_uri() ); ?>/jquery.browser.js"><\/script><script type="text/javascript" src="<?php echo esc_url( get_theme_file_uri() ); ?>/jquery-iframe-auto-height.js"><\/script>');
      }
      })();
    </script>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-90931713-27', {'name': 'newTracker'});
      ga('send', 'pageview');

    </script>
    <script>
      $(function() {

      $.simpleTicker($("#fade"),{'effectType':'fade'});

      $.simpleTicker($("#roll"),{'effectType':'roll'});

      $.simpleTicker($("#slide"),{'effectType':'slide'});

      });
    </script>
</head>
<body>
    <header class="main_header">
      <div id="header-bar">
				<h1><?php echo get_field('headcopy'); ?></h1>
      </div>
    <section class=" area_header">
    <p id="logo"><a href="<?php echo esc_url( home_url() ); ?>/"><img src="<?php echo esc_url( get_theme_file_uri() ); ?>/images/logo.png" alt=""></a></p>
    <div class="accordion-content">
      <nav id="local" class="accordion-nav">
        <ul>
             <li class="Mincho"><a href="<?php echo esc_url( home_url() ); ?>/about/">会社概要</a></li>
            <li class="Mincho"><a href="<?php echo esc_url( home_url() ); ?>/privacy/">個人情報の取り扱いについて</a></li>
			<li class="Mincho"><a href="<?php echo esc_url( home_url() ); ?>/unspecifiedLaw/"> 特定商取引</a></li>
			<li class="Mincho"><a href="<?php echo esc_url( home_url() ); ?>/storeguide/">店舗一覧</a></li>
       </ul>
      </nav>
      <div id="login_container">
          <object data="https://uranai.heartf.com/Public/users/login2" type="text/html" width="100%" id="myObject" <iframe="" scrolling="no" frameborder="0" allowtransparency="true" src="https://uranai.heartf.com/Public/users/login2" style="height: 89px;">
 </object>
          <script type="text/javascript">
function resizeObject(obj) {
  var vheight= obj.contentWindow.document.body.scrollHeight;
    if(vheight>80){
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
    }else{
        obj.style.height ="80px";
    }

}
var myObject = document.getElementById('myObject');
myObject.onload = function() { resizeObject(this); };
</script>


     </div>
      <nav id="global" class="accordion-nav">
        <ul>
						<?php $loop = new WP_Query( array( 'post_type' => 'global', 'posts_per_page' => 15 ) ); ?>
<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
						<li class="Mincho<?php if (get_field('smonly')): ?> smonly<?php endif; ?>">
<p><a href="<?php echo get_field('linkurl'); ?>"><?php the_title(); ?></a></p></li>
<?php endwhile; ?>
        </ul>
      </nav>
    </div>
    <button class="c-hamburger-btn" aria-expanded="false" aria-controls="drawer-nav">
      <div class="c-hamburger-btn__bars">
        <div class="c-hamburger-btn__bar"></div>
        <div class="c-hamburger-btn__bar"></div>
        <div class="c-hamburger-btn__bar"></div>
      </div>

      <div class="c-hamburger-btn__label">
        MENU </div>
    </button>
    <nav id="smnavigation">
      <div id="global_wrapper">
        <ul>
        <?php $loop = new WP_Query( array( 'post_type' => 'global', 'posts_per_page' => 15 ) ); ?>
<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
						<li class="Mincho<?php if (get_field('smonly')): ?> smonly<?php endif; ?>">
<p><a href="<?php echo get_field('linkurl'); ?>"><?php the_title(); ?></a></p></li>
<?php endwhile; ?>
        </ul>
      </div>
    </nav>
     <ul class="sm_content">
        <li class="shop_tel"><a href="#"><img src="<?php echo esc_url( get_theme_file_uri() ); ?>/images/sm_btn1.png" alt="店舗へのお問い合わせ"></a></li>
        <li class="login_wrapper"><a href="#"><img src="<?php echo esc_url( get_theme_file_uri() ); ?>/images/sm_btn2.png" alt="会員ログイン"></a></li>
      </ul>
      <ul class="sm_content shop_tel_inner" style="display:none;">
      	<li><a href="tel:03-6231-6637">
            <h5>浅草すしや通り店</h5>
            <p>03-6231-6637</p>
          </a></li>

        <li><a href="tel:03-6284-4168">
            <h5>上野広小路店</h5>
            <p>03-6284-4168</p>
          </a></li>
        <li><a href="tel:03-6231-6780">
            <h5>上野店</h5>
            <p>03-6231-6780</p>
          </a></li>
        <li><a href="tel:03-6803-2567">
            <h5>御徒町店</h5>
            <p>03-6803-2567</p>
          </a></li>
        <li><a href="tel:03-6260-9026">
            <h5>神田店</h5>
            <p>03-6260-9026</p>
          </a></li>
        <li><a href="tel:03-6231-7199">
            <h5>浅草駅前店</h5>
            <p>03-6231-7199</p>
          </a></li>
        <li><a href="tel:03-5830-3083">
            <h5>浅草店</h5>
            <p>03-5830-3083</p>
          </a></li>
        <li><a href="tel:03-6279-1858">
            <h5>大久保店</h5>
            <p>03-6279-1858</p>
          </a></li>
        <li><a href="tel:03-5726-9499">
            <h5>自由が丘店</h5>
            <p>03-5726-9499</p>
          </a></li>
        <li><a href="tel:03-6421-4943">
            <h5>自由が丘南口店</h5>
            <p>03-6421-4943</p>
          </a></li>
        <li><a href="tel:045-334-7141">
            <h5>伊勢佐木町店</h5>
            <p>045-334-7141</p>
          </a></li>
      </ul>
    </section>
</header>
