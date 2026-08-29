<section class="content">
        <ul class="index_list">
          <li class="list-group-item">
            <a href="<?php echo esc_url( home_url() ); ?>/appraisal/">
              <h3>鑑定をする</h3>
              <p>お好きな時間・場所から<br>
                ご都合に合わせて<br>
                ご利用できます。</p>
              <div class="index_button">詳細ページへ</div>
            </a>
          </li>
          <li class="list-group-item">
            <a href="<?php echo esc_url( home_url() ); ?>/listoffortunefellers/">
              <h3>占い師ご紹介</h3>
              <p>プロの占い師が、あなたの<br>
              お悩み、相談に合わせて<br>
              問題解決のお手伝い。</p>
              <div class="index_button">詳細ページへ</div>
            </a>
          </li>
          <li class="list-group-item">
            <a href="<?php echo esc_url( home_url() ); ?>/schedule/">
              <h3>スケジュールのご案内</h3>
              <p>各店舗ごとに在籍する<br>
            占い師のスケジュールが<br>
            ご覧いただけます。</p>
              <div class="index_button">詳細ページへ</div>
            </a>
          </li>
             </ul>
      </section>
<img src="<?php echo esc_url( home_url() ); ?>/images/border-topic-common.jpg" alt="ボーダー" class="boda">
<div class="bg_gradation">
	<h3 class="Mincho shopinfo_title">店舗のご案内</h3>
<ul class="slick1">
		<?php
$args = array(
     'post_type' => 'omise',
     'posts_per_page' => 50,
); ?>
<?php query_posts( $args ); ?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<li class="shop_card"><a href='<?php echo get_field('tenpourl'); ?>'><img class='object-fit-img' src="<?php the_post_thumbnail_url('full'); ?>" alt='<?php the_title(); ?>'><h4><?php the_title(); ?></h4></a></li>
<?php endwhile; // end of the loop. ?>
<?php wp_reset_query(); ?>
</ul>
</div>

<script type="text/javascript" id="cf7-confirm-modal-js" src="/js/cfm.js?ver=4"></script>
<style>
	.smunder_yoyaku {
		display:none;
		}
	#smunder_yoyaku {
  padding-top: 0px;
  padding-bottom: 5px;
  position: fixed;
  top: 0;
  width: 100%;
  overflow: hidden;
  text-align: center;
  z-index: 100;
  display: none;
}

.smunder_btn {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
}

.smunder_btn {
  font-size: 16px;
  box-sizing: border-box;
  text-align: center;
  font-weight: bold;
  text-align: center;
  background-color: rgb(255, 152, 67);
}

.smunder_btn a img {
  width: 100%;
  height: auto;
}

.smunder_btn a:hover {
  -webkit-transition: 0.1s;
  transition: 0.1s;
  opacity: 1;
}
@media screen and (max-width: 530px) {
  .smunder_yoyaku {
    display: inherit;
    padding-top: 0px;
    font-size: 14px;
    font-weight: bold;
    position: fixed;
    bottom: 0;
    width: 100%;
    overflow: hidden;
    text-align: center;
    background-color: #f0edde;
    z-index: 100;
    color: #000000;
  }
}
.smunder_btn a {
  display: block;
  color: #fff;
  text-decoration: none;
  padding: 7px 3.53% 7px 3.53%;
  background-image: url(https://uranai.heartf.com/remote/assets/css/images/mail-icon.svg);
  background-position: 10% center;
  background-size: 26px auto;
  background-repeat: no-repeat;
}
</style>
<div class="smunder_yoyaku">
    <div class="smunder_btn smtel"><a href="<?php echo esc_url( home_url() ); ?>/index.php#ura" id="scheduleButton" style="width: 100%;text-align: center;" onclick="return gtag_report_conversion('https://uranai.heartf.com/#tdy');">▶︎占い師一覧・ご予約へ</a>
    </div>
    <div class="setu">▲今すぐ占えるプロの占い師が待機中！</div>
  </div>
      <section class="content">
        <ul class="other_information">

          <li class="list-group-item">
            <a href="<?php echo esc_url( home_url() ); ?>/joboffer/">
              <h4 class="Mincho">占い師募集</h4>
            </a>
          </li>
        </ul>
      </section>

     <h3 class="normal">当社運営サイト</h3>
      <div class="bg_gray">
      <section class="content">
        <ul class="banner_area">
          <li class="list-group-item"><a href="https://school.heartf.com/" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url( home_url() ); ?>/images/sitebanner-school.jpg" alt="ハートフルスクールバナー"></a></li>
          <li class="list-group-item"><a href="https://zense.heartf.com/" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url( home_url() ); ?>/images/sitebanner-hps.jpg" alt="前世療法バナー"></a></li>
          <li class="list-group-item"><a href="https://tel.uranai.heartf.com/" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url( home_url() ); ?>/images/sitebanner-remote.jpg" alt="リモート占い館バナー"></a></li>
        </ul>
      </section>
</div>


<!-- MENUBAR CONTENTS END -->

</div>

<footer>
      <!-- <div class="pagetop"><a href="#top"><img src="commons/images/btn-pagetop.png" width="80" height="80" alt="ページトップへ" /></a></div> -->
      <div class="footer_inner">
        <div class="logo_area"><a href="/"><img src="<?php echo esc_url( get_theme_file_uri() ); ?>/images/logo.png" alt="占いハートフルロゴ"></a></div>
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


	<div class="footer_area">Copyright © <script>document.write(new Date().getFullYear());</script> <a href="https://uranai.heartf.com/">占いハートフル</a> All Rights Reserved.
     </div>
    </footer>

          <?php if (is_front_page() || is_home()) : ?>
            <?php echo heartful_voice_render_teacher_dialog(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <script defer src="<?php echo esc_url(get_theme_file_uri('/js/voice-dynamic.js')); ?>?v=1.2.0"></script>
          <?php endif; ?>

          <script type="text/javascript" src="<?php echo esc_url( get_theme_file_uri() ); ?>/js/dist/jquery.zoomslider.min.js" ></script>
          <script type="text/javascript" src="<?php echo esc_url( get_theme_file_uri() ); ?>/js/slick.min.js"></script>
          <script type="text/javascript" src="<?php echo esc_url( get_theme_file_uri() ); ?>/js/ofi.min.js"></script>
          <script src="<?php echo esc_url( get_theme_file_uri() ); ?>/js/script.js"></script>
          <script type="text/javascript" src="<?php echo esc_url( get_theme_file_uri() ); ?>/js/jquery-iframe-auto-height.js"></script>
          <script type="text/javascript" src="<?php echo esc_url( get_theme_file_uri() ); ?>/js/jquery.simpleTicker.js"></script>
          <link rel="stylesheet" type="text/css" href="<?php echo esc_url( get_theme_file_uri() ); ?>/js/jquery.simpleTicker.css">
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
