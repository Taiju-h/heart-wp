(function () {
  'use strict';

  var config = window.heartfulVoice;
  var button = document.getElementById('heartful-voice-more');
  var list = document.getElementById('heartful-voice-list');
  var status = document.getElementById('heartful-voice-status');
	var dialog = document.getElementById('heartful-voice-teacher-dialog');
	var dialogPanel = dialog ? dialog.querySelector('.heartful-voice-dialog-panel') : null;
	var teacherName = document.getElementById('heartful-voice-dialog-teacher-name');
	var profileLink = document.getElementById('heartful-voice-profile-link');
	var reservationLink = document.getElementById('heartful-voice-reservation-link');
	var lastTrigger = null;

	if (!config || !list) {
		return;
	}

	function findTeacherTrigger(element) {
		while (element && element !== list) {
			if (element.classList && element.classList.contains('heartful-voice-teacher-trigger')) {
				return element;
			}
			element = element.parentNode;
		}
		return null;
	}

	function closeTeacherDialog() {
		if (!dialog || dialog.hidden) {
			return;
		}

		dialog.hidden = true;
		document.body.classList.remove('heartful-voice-dialog-open');

		if (lastTrigger) {
			lastTrigger.focus();
		}
	}

	if (dialog && dialogPanel && teacherName && profileLink && reservationLink) {
		list.addEventListener('click', function (event) {
			var trigger = findTeacherTrigger(event.target);
			if (!trigger) {
				return;
			}

			lastTrigger = trigger;
			teacherName.textContent = trigger.getAttribute('data-teacher-name') || '';
			profileLink.href = trigger.getAttribute('data-profile-url') || '#';
			reservationLink.href = trigger.getAttribute('data-reservation-url') || '#';
			dialog.hidden = false;
			document.body.classList.add('heartful-voice-dialog-open');
			dialogPanel.focus();
		});

		dialog.addEventListener('click', function (event) {
			if (event.target.hasAttribute('data-heartful-voice-close')) {
				closeTeacherDialog();
			}
		});

		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape') {
				closeTeacherDialog();
			}
		});
	}

	if (!button || !status) {
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
