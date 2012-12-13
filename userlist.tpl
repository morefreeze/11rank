<html>
<head>
</head>
<body>
	<table>
		<tr>
			<td colspan=6>You can add the 11 username that you want to follow.</td>
		</tr>
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
			<td>Trend</td>
		</tr>
		{foreach $user_infos as $user_info}
			<tr>
				<td>{$user_info['rank']}</td>
				<td textAlign="center" width="40%">{$user_info['uname']}</td>
				<td style="color:gold">{$user_info['score']}</td>
				<td style="color:red">{$user_info['win']}</td>
				<td style="color:green">{$user_info['lose']}</td>
				<td style="color:blue">{$user_info['win_rate']}%</td>
				{if $user_info['trend'] > 0}<td style="color:red">↑</td>
				{elseif $user_info['trend'] < 0}<td style="color:green">↓</td>
				{else}<td style="color:black">-</td>{/if}
			</tr>
		{/foreach}
	</table>
</body>
</html>