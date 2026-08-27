objectFitImages('img.object-fit-img');

$(function () {
  $('.c-hamburger-btn').click(function () {
    // [#acdn-target]に[slideToggle()]を実行する
    $('#smnavigation').slideToggle();
  });
  $('.shop_tel').click(function () {
    // [#acdn-target]に[slideToggle()]を実行する
    $('.shop_tel_inner').slideToggle();
  });
  $('.login_wrapper').click(function () {
    // [#acdn-target]に[slideToggle()]を実行する
    $('#login_container').slideToggle();
  });
		$('.h-yoyaku').click(function () {
				// [#acdn-target]に[slideToggle()]を実行する
				$('.shop_tel_inner').slideToggle();
		});
		$('.h-login').click(function () {
				// [#acdn-target]に[slideToggle()]を実行する
				$('#login_container').slideToggle();
		});
});

$(function() {
	//クリックしたときのファンクションをまとめて指定
	$('.store_list-tab li').click(function() {

		//.index()を使いクリックされたタブが何番目かを調べ、
		//indexという変数に代入します。
		var index = $('.store_list-tab li').index(this);

		//コンテンツを一度すべて非表示にし、
		$('.u-list dt').css('display','none');

		//クリックされたタブと同じ順番のコンテンツを表示します。
		$('.u-list dt').eq(index).fadeIn().css('display','block');

		//一度タブについているクラスselectを消し、
		$('.store_list-tab li').removeClass('select');

		//クリックされたタブのみにクラスselectをつけます。
		$(this).addClass('select')
	});
});


$(function() {
		var $window = $(window),
		$clone = $('#smunder_yoyaku'),
		threshold = 100;

		$window.on('scroll',function(){
				if($window.scrollTop() > threshold) {
					$clone.fadeIn();
				}else{
					$clone.fadeOut();
				}
		});
});

$(function() {
  var $win = $(window),
      $header = $('.sub_header'),
      animationClass = 'is-animation';

  $win.on('load scroll', function() {
    var value = $(this).scrollTop();
    if ( value > 100 ) {
      $header.addClass(animationClass);
    } else {
      $header.removeClass(animationClass);
    }
  });
});

var moreNum = 3;
$('.list li:nth-child(n + ' + (moreNum + 1) + ')').addClass('is-hidden');
$('.more').on('click', function() {
  $('.list li.is-hidden').slice(0, moreNum).removeClass('is-hidden');
  if ($('.list li.is-hidden').length == 0) {
    $('.more').fadeOut();
  }
});

    $('.slick1').slick({ //{}を入れる
      autoplay: true, //「オプション名: 値」の形式で書く
      arrows: true,
      dots: false, //複数書く場合は「,」でつなぐ
      centerMode: true,
      centerPadding: '12.5%',
      slidesToShow: 3,
      slidesToScroll: 1,
      focusOnSelect: true,
      responsive: [{
        breakpoint: 550,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          centerPadding: '0'
        }
      }]
    });
    $(window).on('resize orientationchange', function () {
      $('.slick1').slick('resize');
    });

$('.slick2').slick({ //{}を入れる
    autoplay: true, //「オプション名: 値」の形式で書く
    arrows: true,
    dots: false, //複数書く場合は「,」でつなぐ
    slidesToShow: 3,
    slidesToScroll: 1,
  focusOnSelect: true,
         centerMode: false,
    responsive: [{
      breakpoint: 550,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        centerPadding: '0'
      }
    }]
});

    lazyload();
