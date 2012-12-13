<?php
define('SMARTY_DIR',str_replace("\\","/",getcwd()).'/../Smarty/libs/');
require_once ('DbTools.class.php');
require_once (SMARTY_DIR.'Smarty.class.php');
require_once ('11rank.php');

session_start();
if ($_SESSION['uid'] > 0){
	$uid = $_SESSION['uid'];
	$conn = DbTools::getDbConnect('dota');
	updateAllUser();
	$res = $conn->query("SELECT ulist FROM user WHERE uid = $uid");
	$ulist = explode(',', $res[0]['ulist']);
	$user_infos = array();
	foreach ($ulist as $k => $v) {
		$user_id = intval($v);
		if ($user_id > 0){
			$user_info = getUserInfoFromDb($user_id);
			$user_info['rank'] = $user_info['rank'] == 0 ? '-' : $user_info['rank'];
			$user_info['win_rate'] = number_format(100.0 * $user_info['win'] / ($user_info['win'] + $user_info['lose']), 2);
			$user_infos[] = $user_info;
			//var_dump($user_info);
		}
	}

	$smarty = new Smarty();
	$smarty->assign('user_infos', $user_infos);
	$smarty->display('userlist.tpl');

}
else{
	echo "Please <a href=\"login\">Sign in</a>.";
}

?>