<?php
/** WordPress roster shortcode renderer; DB configuration stays in the legacy include. */
function heartful_mcast_top($chokuei = null, $mtenpo_id = null)
{
	$link = mysqli_connect(
		SEVER,
		USER_ID,
		USER_PASS,
		USER_DB
	);

	if (!$link) {
		error_log(
			'mcast_top DB接続エラー: ' .
				mysqli_connect_error()
		);

		return '<!-- mcast_top: DB接続エラー -->';
	}

	if (!mysqli_set_charset($link, USER_CHATSET)) {
		error_log(
			'mcast_top 文字コード設定エラー: ' .
				mysqli_error($link)
		);
	}

	/*
	 * WordPressの日本時間を使用。
	 * 午前0時～4時台は前日の営業日。
	 */
	$now = current_datetime();
	$currentHour = (int)$now->format('H');

	if ($currentHour > 4) {
		$today = $now->format('Y-m-d');
	} else {
		$today = $now
			->modify('-1 day')
			->format('Y-m-d');
	}

	$defaultMode = (
		$chokuei === null ||
		$chokuei === ''
	);

	/*
	 * 今日の実店舗勤務。
	 */
	$sqlTenpo = "
		SELECT
			Mkanteishi.name,
			Mkanteishi.yomigana,

			Mtenpo.domain,
			Mtenpo.tenponame,
			Mtenpo.weboder,
			Mtenpo.weblistcnt,

			Eworkdays.mtenpo_id,
			Eworkdays.mtime_id,
			Eworkdays.mtime2_id,
			Eworkdays.taikin AS taikin_flg,
			Eworkdays.id AS EworkdaysID,

			Mcast.mtenpo_id AS cast_tenpo_id,
			Mcast.mshutuen_id,
			Mcast.mkanteishi_id,

			Mshutuen.img,
			Mshutuen.name AS imgname

		FROM eworkdays AS Eworkdays

		INNER JOIN mkanteishis AS Mkanteishi
			ON Mkanteishi.id = Eworkdays.mkanteishi_id

		INNER JOIN mtenpos AS Mtenpo
			ON Mtenpo.id = Eworkdays.mtenpo_id

		INNER JOIN mcast AS Mcast
			ON (
				Mcast.mkanteishi_id = Eworkdays.mkanteishi_id
				AND Mcast.mdivision_id = 1
			)

		LEFT JOIN mshutuens AS Mshutuen
			ON Mshutuen.id = Mcast.mshutuen_id

		WHERE
			Eworkdays.workdate = '{$today}'
			AND Mkanteishi.delflg = 0
			AND Eworkdays.holiday_flg = 0
			AND Eworkdays.id > 0
			AND Eworkdays.mtenpo_id <> 51
			AND Mcast.mkanteishi_id <> 5
	";

	if ($defaultMode) {
		$sqlTenpo .= "
			AND Mtenpo.chokuei = 1
		";
	} else {
		$sqlTenpo .= "
			AND Mtenpo.id IN (2, 71)
		";
	}

	if (
		$mtenpo_id !== null &&
		$mtenpo_id !== ''
	) {
		$targetTenpoId = (int)$mtenpo_id;

		$sqlTenpo .= "
			AND Mtenpo.id = {$targetTenpoId}
		";
	}

	$sqlTenpo .= "
		ORDER BY
			Mtenpo.weboder,

			CASE
				WHEN Mcast.mtenpo_id = Eworkdays.mtenpo_id
					THEN 0
				ELSE 1
			END,

			Eworkdays.mtime_id,
			Mshutuen.oder,
			Mkanteishi.mpost_id,
			Mkanteishi.mtankataimen_id DESC,
			Mkanteishi.id
	";

	/*
	 * リモート。
	 *
	 * 通常：今日のリモート勤務
	 * マイナスID：Mcast店舗51かつステータス1
	 */
	$sqlRemote = "
		SELECT
			Mkanteishi.name,
			Mkanteishi.yomigana,

			Mtenpo.domain,
			Mtenpo.tenponame,
			Mtenpo.weboder,
			Mtenpo.weblistcnt,

			Eworkdays.mtenpo_id,
			Eworkdays.mtime_id,
			Eworkdays.mtime2_id,
			Eworkdays.id AS EworkdaysID,

			Mcast.mtenpo_id AS cast_tenpo_id,
			Mcast.mshutuen_id,
			Mcast.mkanteishi_id,

			Mshutuen.img,
			Mshutuen.name AS imgname

		FROM eworkdays AS Eworkdays

		INNER JOIN mkanteishis AS Mkanteishi
			ON Mkanteishi.id = Eworkdays.mkanteishi_id

		INNER JOIN mtenpos AS Mtenpo
			ON Mtenpo.id = Eworkdays.mtenpo_id

		INNER JOIN mcast AS Mcast
			ON (
				Mcast.mkanteishi_id = Eworkdays.mkanteishi_id
				AND Mcast.mdivision_id = 1
			)

		LEFT JOIN mshutuens AS Mshutuen
			ON Mshutuen.id = Mcast.mshutuen_id

		WHERE
			Mkanteishi.delflg = 0
			AND Eworkdays.mtenpo_id = 51

			AND (
				(
					Eworkdays.workdate = '{$today}'
					AND Eworkdays.holiday_flg = 0
					AND Eworkdays.id > 0
				)

				OR

				(
					Eworkdays.id < 0
					AND Mcast.mtenpo_id = 51
					AND Mcast.mshutuen_id = 1
				)
			)

			AND Mtenpo.chokuei = 1
			AND Mcast.mkanteishi_id <> 5
	";

	$sqlRemote .= "
		ORDER BY
			CASE
				WHEN Eworkdays.id > 0
					THEN 0
				ELSE 1
			END,

			Eworkdays.mtime_id,
			Mshutuen.oder,
			Mkanteishi.mpost_id,
			Mkanteishi.mtankataimen_id DESC,
			Mkanteishi.id
	";

	/*
	 * カードHTML作成。
	 */
	$createCard = static function (array $mcast, bool $offToday = false): string {
		$mkanteishiId = (int)$mcast['mkanteishi_id'];

		$name = htmlspecialchars(
			(string)$mcast['name'],
			ENT_QUOTES,
			'UTF-8'
		);

		$tenponame = htmlspecialchars(
			(string)$mcast['tenponame'],
			ENT_QUOTES,
			'UTF-8'
		);

		$imgname = htmlspecialchars(
			(string)$mcast['imgname'],
			ENT_QUOTES,
			'UTF-8'
		);

		$profileImage =
			sprintf('%04d', $mkanteishiId) .
			'.jpg';

		$profileUrl =
			USER_IMG3 .
			$profileImage;

		$detailUrl =
			USER_URL .
			'Mkanteishis/view/' .
			$mkanteishiId .
			'/' .
			TENPO_ID .
			'/';

		$statusImage =
			USER_IMG2 .
			$mcast['img'];

		$html = '<li>';

		$html .=
			'<a href="' .
			htmlspecialchars(
				$detailUrl,
				ENT_QUOTES,
				'UTF-8'
			) .
			'" target="_kanteishi">';

		$html .=
			'<img src="' .
			htmlspecialchars(
				$profileUrl,
				ENT_QUOTES,
				'UTF-8'
			) .
			'?v=' .
			time() .
			'"' . ($offToday ? ' loading="lazy"' : '') . ' alt="' .
			$name .
			'">';

		$html .=
			'<h4 class="Mincho">' .
			$name .
			'先生<span> ' .
			$tenponame .
			'</span></h4>';

		$mtimeId =
			isset($mcast['mtime_id'])
			? (int)$mcast['mtime_id']
			: null;

		$mtime2Id =
			isset($mcast['mtime2_id'])
			? (int)$mcast['mtime2_id']
			: null;

		if (
			$mtimeId !== null &&
			$mtime2Id !== null &&
			$mtimeId > 0 &&
			$mtime2Id > 0
		) {
			$strtime = heartftime(
				$mtimeId,
				$mtime2Id
			);

			$html .=
				'<h4 class="Mincho">' .
				htmlspecialchars(
					$strtime,
					ENT_QUOTES,
					'UTF-8'
				) .
				'</h4>';
		}

		if ($offToday) {
			$html .= '<p class="other-soothsayers-status">本日の出演はありません</p>';
		} else {
		$html .=
			'<img src="' .
			htmlspecialchars(
				$statusImage,
				ENT_QUOTES,
				'UTF-8'
			) .
			'" width="150" alt="' .
			$imgname .
			'">';
		}

		$html .= '</a>';
		$html .= '</li>';

		return $html;
	};

	/*
	 * 実店舗SQL実行。
	 */
	$tenpoData = mysqli_query(
		$link,
		$sqlTenpo
	);

	if ($tenpoData === false) {
		$sqlError = mysqli_error($link);

		error_log(
			'mcast_top 実店舗SQLエラー: ' .
				$sqlError .
				PHP_EOL .
				$sqlTenpo
		);

		mysqli_close($link);

		return
			'<!-- mcast_top 実店舗SQLエラー: ' .
			htmlspecialchars(
				$sqlError,
				ENT_QUOTES,
				'UTF-8'
			) .
			' -->';
	}

	$output  = '<div class="bg_gradation">';
	$output .= '<div class="soothsayer">';
	$output .= '<section class="content">';
	$output .= '<h2>本日の占い師</h2>';
	$output .= '<ul class="soothsayer_list">';

	$displayed = [];

	/*
	 * 実店舗を先に表示。
	 */
	while ($mcast = mysqli_fetch_assoc($tenpoData)) {
		$mkanteishiId = (int)$mcast['mkanteishi_id'];

		if (isset($displayed[$mkanteishiId])) {
			continue;
		}

		/*
		 * 実店舗勤務が退勤済みなら表示しない。
		 * displayedに登録しないため、
		 * リモート条件を満たせばリモート側で表示される。
		 */
		if (
			isset($mcast['taikin_flg']) &&
			(int)$mcast['taikin_flg'] === 1
		) {
			continue;
		}

		$displayed[$mkanteishiId] = true;
		$output .= $createCard($mcast);
	}

	mysqli_free_result($tenpoData);

	/*
	 * リモートを後から表示。
	 */
	$showRemote =
		$defaultMode &&
		(
			$mtenpo_id === null ||
			$mtenpo_id === '' ||
			(int)$mtenpo_id === 51
		);

	if ($showRemote) {
		$remoteData = mysqli_query(
			$link,
			$sqlRemote
		);

		if ($remoteData === false) {
			error_log(
				'mcast_top リモートSQLエラー: ' .
					mysqli_error($link) .
					PHP_EOL .
					$sqlRemote
			);

			$output .=
				'<!-- mcast_top: リモートSQLエラー -->';
		} else {
			while ($mcast = mysqli_fetch_assoc($remoteData)) {
				$mkanteishiId =
					(int)$mcast['mkanteishi_id'];

				if (isset($displayed[$mkanteishiId])) {
					continue;
				}

				$eworkdaysId =
					isset($mcast['EworkdaysID'])
					? (int)$mcast['EworkdaysID']
					: null;

				$mshutuenId =
					isset($mcast['mshutuen_id'])
					? (int)$mcast['mshutuen_id']
					: null;

				$castTenpoId =
					isset($mcast['cast_tenpo_id'])
					? (int)$mcast['cast_tenpo_id']
					: null;

				/*
				 * マイナスIDは
				 * Mcast店舗51かつステータス1だけ。
				 */
				if (
					$eworkdaysId !== null &&
					$eworkdaysId < 0 &&
					(
						$castTenpoId !== 51 ||
						$mshutuenId !== 1
					)
				) {
					continue;
				}
				$displayed[$mkanteishiId] = true;
				$output .= $createCard($mcast);
			}

			mysqli_free_result($remoteData);
		}
	}

	$output .= '</ul>';
	// 全店トップのみ。店舗別・別モードの一覧には混在させない。
	if ($defaultMode && ($mtenpo_id === null || $mtenpo_id === '')) {
		require_once __DIR__ . '/other-soothsayers.php';
		$output .= heartful_other_soothsayers($link, $today, $displayed, $createCard);
	}
	$output .= '</section>';
	$output .= '</div>';
	$output .= '</div>';

	mysqli_close($link);

	return $output;
}



