<?php
		require_once('tenpo.php');
		define("SEVER", '210.131.223.248');
	define("USER_DB", 'heartf');
	define("USER_ID", 'root');
	define("USER_PASS", 'Sora13%2lom');
	
		define("USER_CHATSET", 'utf8');

	function plugin_public_campaign_inline($filename = null) {
		$wk = '/home/heartf/heartf.com/public_html/uranai/wiki/43616D706169676E32.txt';

		$output = file_get_contents($wk);
		
	if(is_numeric(TENPO_ID)) {		

	
			$link = mysqli_connect(SEVER, USER_ID, USER_PASS, USER_DB);
		
			$sql = 'SELECT tenponame FROM mtenpos WHERE id= ' . TENPO_ID;

			$data = mysqli_query($link, $sql);

			$tenponame = mysqli_fetch_assoc($data);


		$output .= '<section class="content"><h2 id="content_1_0">';
		$output .= $tenponame["tenponame"];
		$output .= "限定のキャンペーン</h2>キャンペーン内容は都合により変更する場合がござます。こちらが最新状態になります。</p>";
	} 
	return $output;
}
?>
