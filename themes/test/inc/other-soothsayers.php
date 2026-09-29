<?php
/** Active public teachers without a shift on the same business day as mcast_top. */
function heartful_other_soothsayers($link, $today, array $displayed, callable $createCard)
{
    $day = mysqli_real_escape_string($link, $today);
    $sql = "SELECT Mkanteishi.id AS mkanteishi_id, Mkanteishi.name,
                   '' AS tenponame, '' AS img, '' AS imgname
            FROM mkanteishis AS Mkanteishi
            WHERE Mkanteishi.delflg = 0 AND Mkanteishi.id <> 5
              AND EXISTS (
                  SELECT 1 FROM mcast AS Mcast
                  WHERE Mcast.mkanteishi_id = Mkanteishi.id
                    AND Mcast.mdivision_id = 1
              )
              AND NOT EXISTS (
                  SELECT 1 FROM eworkdays AS Eworkdays
                  WHERE Eworkdays.mkanteishi_id = Mkanteishi.id
                    AND Eworkdays.workdate = '{$day}'
                    AND Eworkdays.holiday_flg = 0 AND Eworkdays.id > 0
              )
            ORDER BY Mkanteishi.yomigana, Mkanteishi.id";

    $result = mysqli_query($link, $sql);
    if ($result === false) {
        error_log('heartful_other_soothsayers: query failed');
        return '<p class="other-soothsayers-status">それ以外の占い師を取得できませんでした。時間をおいて再度お試しください。</p>';
    }

    $cards = '';
    while ($teacher = mysqli_fetch_assoc($result)) {
        // Also exclude remote teachers shown through the negative-ID standby route.
        $id = (int) $teacher['mkanteishi_id'];
        if (isset($displayed[$id])) {
            continue;
        }
        $displayed[$id] = true;
        $cards .= $createCard($teacher, true);
    }
    mysqli_free_result($result);

    // Native disclosure supports touch, keyboard, and browsers without JavaScript.
    $html = '<details class="other-soothsayers">';
    $html .= '<summary>それ以外の占い師</summary>';
    $html .= '<div class="other-soothsayers-content">';
    $html .= $cards === ''
        ? '<p class="other-soothsayers-status">該当する占い師はいません。</p>'
        : '<ul class="soothsayer_list">' . $cards . '</ul>';
    return $html . '</div></details>';
}
