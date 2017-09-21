<?php
	$check = download_file('https://zmp3-photo-td.zadn.vn/banner/e/3/e3333817da03ef38e30bc7c615ebcfbd_1505960609.png', 'download/xyz.jpg');
	if(empty($check))
	{
		echo "download thanh cong";
	}
	else
	{
		echo "download that bai";
	}
	function download_file($url, $path)
	{
		$f = fopen($path, 'w'); // phải set full quyền mới được thực thi write
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_FILE, $f);
		curl_setopt($ch, CURLOPT_TIMEOUT, 28800);
		curl_exec($ch);
		$e = curl_error($ch); // tra ve '' la success, tra ve != '' thi fail
		curl_close($ch);
		fclose($f);
		return $e;
	}