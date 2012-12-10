<html>
<head>
</head>
<body>
	<table>
		<tr class = "user_add">
			<form action="useradd" method="post">
			<td colspan=4>
				<input type="text" name="new_user" />
			</td>
			<td>
				<input type="submit" name="submit" />
			</td>
			</form>
		</tr>
		<tr class = "rank_head">
			<td>Rank</td>
			<td>Nickname</td>
			<td>Score</td>
			<td>Win</td>
			<td>Lose</td>
			<td>Win Rate</td>
		</tr>
		{foreach $user_infos as $user_info}
			<tr>
				<td>{$user_info['rank']}</td>
				<td>{$user_info['uname']}</td>
				<td>{$user_info['score']}</td>
				<td>{$user_info['win']}</td>
				<td>{$user_info['lose']}</td>
				<td>{$user_info['win_rate']}%</td>
			</tr>
		{/foreach}
	</table>
</body>
</html>