<?php
	error_reporting(E_ERROR);
	set_time_limit(0);
	$proxy = trim(file_get_contents("proxy.txt"));
	$url = "http://mp3.zing.vn/";
	echo get_data($url, $proxy);
	function get_data($link, $proxy = null, $proxy_type = null)
	{
		//set_time_limit(0); // set timeout cho file, nếu = 0 thì sẽ chạy đến khi code chạy hết
		$useragent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.79 Safari/537.36";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $link);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, $useragent);// +referer = 90%
		curl_setopt($ch, CURLOPT_REFERER, "https://www.google.com.vn/");// từ trang nào refer đến, một số trang web kiểm tra cái này
		curl_setopt($ch, CURLOPT_ENCODING, ''); //mp3.zing.vn, set all encoding => chấp nhận tất cả encoding, không thì khai báo encoding của mình
		curl_setopt($ch, CURLOPT_TIMEOUT, 10); //nếu quá 10s mà k xử lý được thì fail
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); //nếu quá 10s mà k connect được thì fail
		$headers = array();
		$headers[] = 'Host:mp3.zing.vn';
		$headers[] = 'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.79 Safari/537.36';
		$headers[] = 'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8';
		$headers[] = 'Accept-Language:vi,en-US;q=0.8,en;q=0.6';
		$headers[] = 'Accept-Encoding:gzip, deflate';
		//$headers[] = 'Cookie:SRVID=s670_13110; __mp3sessid=BF8864A9BAFC; _ga=GA1.2.1109810026.1504897970; _gid=GA1.2.1103738387.1505933810; _znu=1; atmpv=1; __zi=2000.e6145ab3e48c0cd2559d.1504897969882.37f79bf6; adtimaUserId=2000.e6145ab3e48c0cd2559d.1504897969882.37f79bf6; fuid=c5375561ea1fa46b1902ac8e688da93e';// tùy trường hợp nên truyền cookie
		$headers[] = 'Cache-Control:max-age=0';
		$headers[] = 'Connection:keep-alive';
		$headers[] = 'Upgrade-Insecure-Requests:1'; //???? 
		//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // một số website khác có thể check header
		//curl_setopt($ch, CURLOPT_HEADER, true); // mặc định là false, khi trả dữ liệu về kèm theo header đã gửi, dùng trong debug
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // chấp nhận việc website chuyển qua trang khác rồi mới trả dữ liệu về
		curl_setopt($ch, CURLOPT_MAXREDIRS, 5); // nếu chuyển qua tối qua 5 website mà k có dữ liệu thì bỏ qua

		if(check_proxy_live($proxy) && isset($proxy))
		{
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
			if(isset($proxy_type))
			{
				curl_setopt($ch, CURLOPT_PROXYTYPE, $proxy_type);
			}
		}
		$result = curl_exec($ch);
		curl_close($ch);
		//echo htmlspecialchars($result);
		return $result;
	}
	function check_proxy_live($proxy)
	{
		$waitTimeoutInSeconds = 1;
		$proxy_split = explode(':', $proxy);
		$ip = $proxy_split[0];
		$port = $proxy_split[1];
		$result = false;
		if($fp = fsockopen($ip, $port, $errCode, $errStr, $waitTimeoutInSeconds))
		{
			$result = true;
			fclose($fp);
		}
		return $result;
	}
