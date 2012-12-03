<?php
require_once ("DbTools.class.php");

//getCookie();
//$user_info = getUserInfoFrom11('还我K神');
//var_dump($user_info);
//updateUserInfo($user_info);
//updateAllUser();

// user_info
// id, uname, score, rank, win, lose, update_time

// update all user 11 score and info
function updateAllUser(){
	$conn = DbTools::getDBConnect('dota');
	$time = time();
	do{
		$res = $conn->query("SELECT uname FROM dota.user_info WHERE update_time < $time ".
			"LIMIT 100");
		if (false === $res){
			return false;
		}

		if (empty($res)){
			break;
		}
		foreach ($res as $user){
			$user_info = getUserInfoFrom11($user['uname']);
			updateUserInfo($user_info);
		}
		break;
	}while(1);
	
	return true;
}

// update A user info with 11 rank (throght getUserInfoFrom11)
function updateUserInfo($user_info){
	$conn = DbTools::getDBConnect('dota');
	$res = $conn->query("SELECT id,score,rank FROM dota.user_info ".
		"WHERE uname LIKE '".$conn->escape_string($user_info['user'])."'");
	if (false === $res){

	}
	extract($user_info);
	$rank = $rank == "-" ? 0 : $rank;
	$time = time();
	// update
	if (!empty($res)){
		$conn->query("UPDATE dota.user_info SET score = $score, rank = $rank,".
			"win = $win, lose = $lose, update_time = $time WHERE id = ". $res[0]['id']);
	}
	// insert
	else{
		$sql = "INSERT INTO dota.user_info SET uname = '".$conn->escape_string($user)."', score = $score,".
			"rank = $rank, win = $win, lose = $lose, update_time = $time";
		//var_dump($sql);
		$conn->query($sql);
	}
	return true;
}

// get 11 rank from db with local uid
function getUserInfoFromDb($id){
	$conn = DbTools::getDBConnect('dota');
	$res = $conn->query("SELECT id,uname,score,rank,win,lose FROM dota.user_info ".
		"WHERE id = $id");
	if (false === $res){

	}
	return $res[0];
}

// get 11 rank with A user name
function getUserInfoFrom11($user = 'morefreeze'){
	$url = "http://i.5211game.com/rank/search?t=10001&n=$user&login=1";
	//$url = "http://www.baidu.com/s?wd=fdsa";
	$rank_url = parse_url($url);
	//var_dump($rank_url);
	$fp = fsockopen($rank_url['host'], 80, $errno, $errstr, 10);  
	if (!$fp) {  
		echo "$errstr ($errno)\n";
	} else {  
		$html = "GET ".$rank_url['path']."?".$rank_url['query']." HTTP/1.1\r\n";  
		$html .= "Host: ".$rank_url['host']."\r\n";  
		$html .= "Connection: keep-alive\r\n";
		$html .= "User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.4 (KHTML, like Gecko) Chrome/22.0.1229.94 Safari/537.4\r\n";  
		$html .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n";  
		$html .= "Accept-Encoding: gzip,deflate,sdch\r\n"; 
		$html .= "Accept-Language: en-US,en;q=0.8\r\n";  
		$html .= "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.3\r\n"; 
		//$html .= "Cookie: __utma=1.1935785162.1354122597.1354122597.1354122597.1; __utmc=1; __utmz=1.1354122597.1.1.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided); ASP.NET_SessionId=ws0iqjlmhqeexua5fzpw5fee; AsCommunity=D4E99848E3A8B121061280C1C94E9ADCA39D3EF38585703E25C66BB933AC7719A15735415DF3DA58E6F45768F119093528CE9343BF200C2FCD44E3648DFF5DAE99A5F18C75CD36CF09736E7758FF5BFE1C8C3B9BF00972306AA2261EA1D743BC14816FA78A622D3748530BCD4B943F436DEF79427E4DFA3FFC7AE62308366795DC76CC32517A0E5022A424A348BA3607387EA3BD527811DF4227390611BB47E3; lastid=3574433; oldid=3563005; Hm_lvt_f42223235f19a6d82fd2f36f5d7daaab=1354214627,1354299566,1354299621,1354299920; Hm_lpvt_f42223235f19a6d82fd2f36f5d7daaab=1354327754\r\n";
		$html .= "\r\n";
		
		fwrite($fp, $html);  
		
		$i = 0;
		$res = "";
		while (!feof($fp)) {
			$res .= fgets($fp, 1024);
			++$i;
			if ($i > 10) break;
		}
		
		preg_match("/HTTP\/1.\d (\d{3,3})/", $res, $out);
		if ($out[1] != '200'){
			var_dump($out[1]);
			exit;
		}
		while (!feof($fp)) {
			$res .= fgets($fp, 1024);
			++$i;
			if ($i > 270) break;
		}
		fclose($fp);
		//var_dump($res);
		preg_match("/td\s+class=\"con1\">\s*<strong[^>]*>([\-0-9])[^<]*<\/\s*strong/s", $res, $out);
		//preg_match("/div\s+class=\"user\">\s*<a.*>(\w+)\s*<\/a>/", $res, $out);
		$ret['rank'] = $out[1];
		preg_match("/div\s+class=\"user\">.+?>(.+?)\s+<\/a>/s", $res, $out);
		$ret['user'] = $out[1];
		preg_match("/span\s+class=\"red\"[^>]*>(\d+)/", $res, $out);
		$ret['score'] = $out[1];
		preg_match("/span\s+class=\"orange\"[^>]*>(\d+)/", $res, $out);
		$ret['win'] = $out[1];
		preg_match("/span\s+class=\"gray\"[^>]>(\d+)/", $res, $out);
		$ret['lose'] = $out[1];
		preg_match("/td\s*class=\"con6\">([0-9.%]+)/", $res, $out);
		$ret['win_rate'] = $out[1];
		//var_dump($ret);
		return $ret;
	}
	return false;
}

function getCookie(){
	$url = "http://passport.5211game.com/t/Login.aspx";
	$login_url = parse_url($url);
	$fp = fsockopen($login_url['host'], 80, $errno, $errstr, 10);
	
	if (!fp){
		echo "$errstr ($errno)\n";
	}else{
		$html = "POST ".$login_url['path']." HTTP/1.1\r\n";
		$html .= "Host: ".$login_url['host']."\r\n";
		$html .= "Proxy-Connection: keep-alive\r\n";
		$html .= "Cache-Control: max-age=0\r\n";
		$html .= "Origin: \r\n";
		$html .= "http://".$login_url['host']."\r\n";
		$html .= "User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.4 (KHTML, like Gecko) Chrome/22.0.1229.94 Safari/537.4\r\n";
		$html .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$html .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n";
		$html .= "Referer: http://passport.5211game.com/t/Login.aspx\r\n";
		$html .= "Accept-Encoding: gzip,deflate,sdch\r\n";
		$html .= "Accept-Language: en-US,en;q=0.8\r\n";
		$html .= "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.3\r\n";
		$html .= "Cookie: __utma=1.1935785162.1354122597.1354122597.1354122597.1; __utmc=1; __utmz=1.1354122597.1.1.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided); ASP.NET_SessionId=jya1z245jcis0ynfwthxifak\r\n";
		$post_arr = array('txtUser' => 'iknow963', 'txtPassWord' => 123456, 'butLogin' => 'µÇÂ¼', 
		'__VIEWSTATE' => '/wEPDwULLTEyMzI3ODI2ODBkGAEFHl9fQ29udHJvbHNSZXF1aXJlUG9zdEJhY2tLZXlfXxYBBQxVc2VyQ2hlY2tCb3ihhGZuvwu91lnQxEfvvglIxoUV3w==',
		'__EVENTVALIDATION' => '/wEWBQLLvu3fCALB2tiHDgK1qbSWCwL07qXZBgLmwdLFDS8b1h9eACycstvQytmO42OInEvi',);
		$post = '';
		foreach ($post_arr as $key => $val){
			if ($post != ''){
				$post .= "&";
			}
			$post .= $key . "=" . $val;
			$length = strlen($post);
		}
		$html .= "Content-Length: $length\r\n";
		$html .= "$post\r\n\r\n";
		
		fwrite($fp, $html);
		$i = 0;
		$res = "";
		while (!feof($fp)) {  
			$res .= fgets($fp, 1024);  
			++$i;
			if ($i > 1) break;
		} 
		fclose($fp);  
	}
}
