<?php
	function plugin_public_read_inline($filename = null) {
	$wk = '/home/heartf/heartf.com/public_html/uranai/' . $filename . ".php";

	$output = file_get_contents($wk);
	return $output;
}
?>
