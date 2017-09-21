<?php
	include "libs/Curl/CaseInsensitiveArray.php";
	include "libs/Curl/Curl.php";
	include "libs/Curl/MultiCurl.php";

	use \Curl\Curl;
	$curl = new Curl();
	$curl->setOpt(CURLOPT_ENCODING, '');
	$html = $curl->get('https://batgioistudio.com/');
	var_dump(htmlspecialchars($html));		
?>