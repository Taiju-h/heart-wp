(function () {
  'use strict';

  var config = window.heartfulVoice;
  var button = document.getElementById('heartful-voice-more');
  var list = document.getElementById('heartful-voice-list');
  var status = document.getElementById('heartful-voice-status');

  if (!config || !button || !list || !status) {
    return;
  }

  button.addEventListener('click', function () {
    var formData = new FormData();
    formData.append('action', 'heartful_voice_load_more');
    formData.append('nonce', config.nonce);
    formData.append('cursor', JSON.stringify(config.cursor));

    button.disabled = true;
    button.textContent = '読み込み中…';
    status.textContent = '';

    fetch(config.ajaxUrl, {
      method: 'POST',
      credentials: 'same-origin',
      body: formData
    })
      .then(function (response) {
        if (!response.ok) {
          throw new Error('Request failed');
        }
        return response.json();
      })
      .then(function (response) {
        if (!response.success) {
          throw new Error('Response failed');
        }

        list.insertAdjacentHTML('beforeend', response.data.html);
        config.cursor = response.data.next_cursor;

        if (!response.data.has_more) {
          button.hidden = true;
          status.textContent = 'すべてのお客様の声を表示しました。';
          return;
        }

        button.disabled = false;
        button.textContent = 'もっと見る';
        status.textContent = '20件追加しました。';
      })
      .catch(function () {
        button.disabled = false;
        button.textContent = 'もっと見る';
        status.textContent = '読み込みに失敗しました。もう一度お試しください。';
      });
  });
}());
