<?php
//define('SMARTY_DIR',str_replace("\\","/",getcwd()).'/../Smarty-3.1.12/libs/');
require_once ('DbTools.class.php');
require_once ('Smarty.class.php');
require_once ('11rank.php');

$conn = DbTools::getDbConnect('dota');
$res = $conn->query("SELECT ulist FROM user WHERE uid = 1");
$ulist = explode(',', $res[0]['ulist']);
foreach ($ulist as $k => $v) {
	$user_id = intval($v);
	if ($user_id > 0){
		$user_info = getUserInfoFromDb($user_id);
		var_dump($user_info);
	}
}

$smarty = new Smarty();

?>
<html>
<head>
</head>
<body>
	<table>
		<tr class = "rank_head">
			<td>Rank</td>
			<td>Nickname</td>
			<td>Score</td>
			<td>Win</td>
			<td>Lose</td>
			<td>Win Rate</td>
		</tr>
	</table>
</body>
</html>