/**
 * CF7 確認画面モーダル
 * 使い方：子テーマの functions.php で wp_enqueue_scripts にてこのJSを読み込む
 * 依存：jQuery（WPデフォルトで入ってるのでOK）
 */

(function ($) {

  const FORM_SELECTOR   = '.wpcf7-form';
  const SUBMIT_SELECTOR = '.wpcf7-submit';

  // ============================================================
  // 設定：フォームIDごとにラベルマップを定義
  //
  // キーはCF7のフォームID（数字）。
  // フォームのdiv.wpcf7にある data-wpcf7-id 属性か、
  // クラス名 wpcf7-f{ID}-... の数字部分で確認できます。
  //
  // ・IDが一致するフォーム → マップの項目を使用
  // ・IDが見つからない場合 → フォーム内のフィールドを自動収集（フォールバック）
  //
  // 同意チェックボックス（acceptanceタイプ）はどの場合も自動除外されます。
  // ============================================================
  const FIELD_LABELS_MAP = {
    // フォームIDをキーにして、項目名とラベルを定義する
    // 設定方法はSTEP 3を参照

    // フォームID: 「フォームA」の設定
    '154': {
      'your-name':    'お名前',
      'your-email':   'メールアドレス',
      'your-tel':   'お電話番号',
      'your-message': 'お問い合わせ内容',
'your-select': 'どこで知ったか',
    },
    // ↑ フォームIDと項目を案件に合わせて追加・変更してください
  };


  // ============================================================
  // デフォルトラベル：FIELD_LABELS_MAP にフォームIDが登録されて
  // いない場合のフォールバック時にも、CF7でよく使われる項目名は
  // 自動で日本語に変換されるようにする
  // ============================================================
  const DEFAULT_FIELD_LABELS = {
    'your-name':     'お名前',
    'your-furi': 'ふりがな',
    'your-company':  '会社名・法人名',
    'your-email':    'メールアドレス',
    'your-tel':      'お電話番号',
    'your-subject':  '件名',
    'your-message':  'お問い合わせ内容',
    'your-select':   'どこで知ったか',
    'your-url':      'URL',
    'your-day':     '希望日',
			'your-tenpo':     '希望店舗',
			'checkbox-526':     '媒体',
    'your-project': '企画内容',
    'your-irai':     'ご依頼内容',
    'your-bangumi':    '番組名（雑誌名）',
    'your-uranai':    '占い師名',
    'your-zip':      '郵便番号',
    'select-city': '都道府県',
    'your-senju': '占術の種類',
    'your-sera': 'セラピーの種類',
    'your-tokui': '得意な相談内容',
    'your-apeal': 'お客様へのアピール',
    'your-keireki': '占い経歴',
    'your-honmyo': '本名',
    'your-kana': 'フリガナ',
    'your-seinen': '生年月日',
    'your-siku': '市区町村',
    'your-banti': '番地建物',
    'your-keitai':  '携帯番号',
  };

  // ============================================================
  // ユーティリティ
  // ============================================================

  function getCf7FormId($form) {
    const $wrapper = $form.closest('.wpcf7');
    if (!$wrapper.length) return null;
    const dataId = $wrapper.attr('data-wpcf7-id');
    if (dataId) return dataId;
    const classes = $wrapper.attr('class') || '';
    const match = classes.match(/wpcf7-f(\d+)/);
    return match ? match[1] : null;
  }

  function isAcceptance($form, name) {
    return $form.find('[name="' + name + '"]').closest('.wpcf7-acceptance').length > 0;
  }

  function getFieldLabels($form, formId) {
    if (formId && FIELD_LABELS_MAP[formId]) {
      const labels = {};
      $.each(FIELD_LABELS_MAP[formId], function (name, label) {
        const baseName = name.replace(/\[\]$/, '');
        if (!isAcceptance($form, baseName)) {
          labels[name] = label;
        }
      });
      return labels;
    }
    // フォールバック：フォーム内のフィールドを自動収集
    const autoLabels = {};
    const seen = {};
    $form.find('[name]').each(function () {
      const name     = $(this).attr('name');
      const baseName = name ? name.replace(/\[\]$/, '') : '';
      const isAcc    = $(this).closest('.wpcf7-acceptance').length > 0;
      if (name && !name.startsWith('_') && !isAcc && !seen[baseName]) {
        seen[baseName]   = true;
        autoLabels[name] = DEFAULT_FIELD_LABELS[baseName] || baseName;
      }
    });
    return autoLabels;
  }

  // ============================================================
  // モーダルHTML生成
  // ============================================================
  function buildModal() {
    if ($('#cf7-confirm-modal').length) return;
    const html = [
      '<div id="cf7-confirm-modal" role="dialog" aria-modal="true" aria-labelledby="cf7-modal-title">',
        '<div id="cf7-confirm-overlay"></div>',
        '<div id="cf7-confirm-box">',
          '<h2 id="cf7-modal-title">入力内容のご確認</h2>',
          '<p class="cf7-confirm-lead">以下の内容で送信します。よろしければ「送信する」を押してください。</p>',
          '<dl id="cf7-confirm-table"></dl>',
          '<div class="cf7-confirm-buttons">',
            '<button type="button" id="cf7-back-btn">戻って修正する</button>',
            '<button type="button" id="cf7-send-btn">送信する</button>',
          '</div>',
        '</div>',
      '</div>',
    ].join('');
    $('body').append(html);
  }

  // ============================================================
  // フォームの入力値をモーダルに反映
  // ============================================================
  function populateModal($form) {
    const $table     = $('#cf7-confirm-table').empty();
    const formId     = getCf7FormId($form);
    const fieldLabels = getFieldLabels($form, formId);

    $.each(fieldLabels, function (name, label) {
      const $field = $form.find('[name="' + name + '"]');
      if (!$field.length) return;
      // Conditional Fieldsで非表示になっているフィールドはスキップ
      if ($field.closest('.wpcf7cf-hidden').length) return;

      let value = '';
      if ($field.is('select')) {
        value = $field.find('option:selected').text();
      } else if ($field.is(':checkbox')) {
        const checked = [];
        $form.find('[name="' + name + '"]:checked').each(function () {
          checked.push($(this).val());
        });
        value = checked.join('、') || '（未選択）';
      } else if ($field.is(':radio')) {
        value = $form.find('[name="' + name + '"]:checked').val() || '（未選択）';
      } else {
        value = $field.val() || '（未入力）';
      }

      // 改行をbrに変換（テキストエリア対応）
      value = $('<div>').text(value).html().replace(/\n/g, '<br>');

      $table.append(
        '<div class="cf7-confirm-row">' +
          '<dt>' + label + '</dt>' +
          '<dd>' + value + '</dd>' +
        '</div>'
      );
    });
  }

  // ============================================================
  // モーダル表示・非表示
  // ============================================================
  function showModal() {
    $('#cf7-confirm-modal').addClass('is-visible');
    $('body').addClass('cf7-modal-open');
    $('#cf7-confirm-box').scrollTop(0);
    $('#cf7-modal-title').focus();
  }

  function hideModal() {
    $('#cf7-confirm-modal').removeClass('is-visible');
    $('body').removeClass('cf7-modal-open');
    $(SUBMIT_SELECTOR).focus();
  }

  // ============================================================
  // 実際の送信
  // ============================================================
  function submitForm($form) {
    $form.data('cf7-confirmed', true);
    hideModal();
    $form.find(SUBMIT_SELECTOR).trigger('click');
  }

  // ============================================================
  // 初期化
  // ============================================================
  $(document).ready(function () {
    buildModal();

    function bindSubmitHandler($form) {
      $form.find(SUBMIT_SELECTOR).off('click.cf7modal').on('click.cf7modal', function (e) {
        if ($form.data('cf7-confirmed')) {
          $form.data('cf7-confirmed', false);
          return;
        }
        e.preventDefault();
        populateModal($form);
        showModal();
        $('#cf7-send-btn').data('form', $form);
      });
    }

    $(FORM_SELECTOR).each(function () {
      bindSubmitHandler($(this));
    });

    $(document).on('click', '#cf7-send-btn', function () {
      submitForm($(this).data('form'));
    });

    $(document).on('click', '#cf7-back-btn, #cf7-confirm-overlay', function () {
      hideModal();
    });

    $(document).on('keydown', function (e) {
      if (e.key === 'Escape' && $('#cf7-confirm-modal').hasClass('is-visible')) {
        hideModal();
      }
    });
  });

})(jQuery);
