<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="#{$keywords}" />
    <meta name="description" content="#{$description}" />
    <link rel="alternate" type="application/rss+xml" title="RSS" href="#{$rss_link}" />
  <title>神田･浅草･上野･御徒町･自由が丘･横浜･新宿で当たる占い館【ハートフル】 | #{$this_page_title}</title>
    #{$default_css}
  <link rel="stylesheet" href="commons/reset.css">
    <link rel="stylesheet" href="commons/style.css?v=7">
  <link rel="stylesheet" type="text/css" href="commons/slick.css?v=4" />
  <link rel="stylesheet" href="js/dist/zoomslider.css">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    #{$jquery_script}#{$jquery_cookie_script}
    #{$custom_meta}#{$noindex}#{$external_link}#{$clickpad_js}
    #{$head_tag}
    #{$beforescript}
  <script type="text/javascript" src="js/modernizr-custom.js"></script>
  <script src="js/lazyload.min.js"></script>
    <script>
      (function(){
      var _UA = navigator.userAgent;
      if (_UA.indexOf('iPhone') > 0) {
      document.write('<script type="text/javascript" src="/jquery.browser.js"><\/script>');
      }else{
      document.write('<script type="text/javascript" src="/jquery.browser.js"><\/script><script type="text/javascript" src="/jquery-iframe-auto-height.js"><\/script>');
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
    #{$toolkit_upper}
    <header class="main_header">
      <div id="header-bar">
        <!-- ◆ Head copy ◆ =====================================================  -->
        #{$head_copy_tag}
      </div>
    <section class=" area_header">
    <p id="logo"><a href="/"><img src="images/logo.png" alt=""></a></p>
    <div class="accordion-content">
      <nav id="local" class="accordion-nav">
        <ul>
             <li class="Mincho"><a href="index.php?About">会社概要</a></li>
            <li class="Mincho"><a href="index.php?PrivacyPolicy">個人情報の取り扱いについて</a></li>
			<li class="Mincho"><a href="https://uranai.heartf.com/index.php?UnspecifiedLaw"> 特定商取引</a></li>
			<li class="Mincho"><a href="https://uranai.heartf.com/index.php?StoreGuide">店舗一覧</a></li>
       </ul>
      </nav>
      <div id="login_container">
          <object data="https://uranai.heartf.com/Public/users/login2" type="text/html" width="100%" id="myObject" 
          <iframe scrolling="no" frameborder="0" allowtransparency="true" src="https://uranai.heartf.com/Public/users/login2" width="100%"  ></iframe>
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
              #{$site_navigator}
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
              #{$site_navigator2}
        </ul>
      </div>
    </nav>
     <ul class="sm_content">
        <li class="shop_tel"><a href="#"><img src="images/sm_btn1.png" alt="店舗へのお問い合わせ"></a></li>
        <li class="login_wrapper"><a href="#"><img src="images/sm_btn2.png" alt="会員ログイン"></a></li>
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
    </section>    </header>
    <main id="container" class="main_content">
#{$body}
        #{$menubar_tag}
    </main>
    <footer>
      <!-- <div class="pagetop"><a href="#top"><img src="commons/images/btn-pagetop.png" width="80" height="80" alt="ページトップへ" /></a></div> -->
      <div class="footer_inner">
        <div class="logo_area"><a href="/"><img src="images/logo.png" alt="占いハートフルロゴ"></a></div>
        <nav>
          <ul>
                <li><a href="https://asakusa3.uranai.heartf.com/" target="_blank">浅草すしや通り店</a></li>
		            <li><a href="https://ueno2.uranai.heartf.com/" target="_blank">上野広小路店</a></li>
                <li><a href="https://ueno.uranai.heartf.com/" target="_blank">上野店</a></li>
                <li><a href="https://okachimachi.uranai.heartf.com/" target="_blank">御徒町店</a></li>
                <li><a href="https://kanda.uranai.heartf.com/" target="_blank">神田店</a></li>
                <li><a href="https://asakusaekimae.uranai.heartf.com/" target="_blank">浅草駅前店</a></li>
                <li><a href="https://asakusa.uranai.heartf.com/" target="_blank">浅草店</a></li>
                <li><a href="https://okubo.uranai.heartf.com/" target="_blank">新宿大久保店</a></li>
                <li><a href="https://jiyugaoka.uranai.heartf.com/" target="_blank">自由が丘店</a></li>
               <li><a href="https://jiyugaoka2.uranai.heartf.com/" target="_blank">自由が丘南口店</a></li>
               <li><a href="https://yokohama.uranai.heartf.com/" target="_blank">伊勢佐木町店</a></li>
 		<li><a href="https://tel.uranai.heartf.com/" target="_blank">リモート占い館</a></li>
         </ul>
        </nav>
      </div>
         

	<div class="footer_area">Copyright © #{$today_year} <a href="#{$modifierlink}">#{$modifier}</a> All Rights Reserved.
     <div class="copylicense">#{$licence_tag}</div></div>
    </footer>
          
          <script type="text/javascript" src="js/dist/jquery.zoomslider.min.js"></script>
          <script type="text/javascript" src="js/slick.min.js"></script>
          <script type="text/javascript" src="js/ofi.min.js"></script>
          <script src="js/script.js"></script>
          <script type="text/javascript" src="js/jquery-iframe-auto-height.js"></script>
          <script type="text/javascript" src="js/jquery.simpleTicker.js"></script>
          <link rel="stylesheet" type="text/css" href="js/jquery.simpleTicker.css">
          <script type="text/javascript">
            function adjust_frame_css(F){
              if(document.getElementById(F)) {
                var myF = document.getElementById(F);
                var myC = myF.contentWindow.document.documentElement;
                var myH = 100;
                if(document.all) {
                  myH  = myC.scrollHeight;
                } else {
                  myH = myC.offsetHeight;
                }
                myF.style.height = myH+"px";
              }
            }
          </script>
	<script>
	document.addEventListener("DOMContentLoaded",() => {
	const title = document.querySelectorAll('.js-accordion-title');

	for (let i = 0; i < title.length; i++){
	let titleEach = title[i];
	let content = titleEach.nextElementSibling;
	titleEach.addEventListener('click', () => {
	titleEach.classList.toggle('is-active');
	content.classList.toggle('is-open');
	});
	}

	});
	</script>
        </body>
      </html>
