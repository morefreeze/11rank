<?php
require_once ("DbTools.class.php");
require_once ("usertools.php");
if(isset($_POST['username'], $_POST['password1'])){
	if (check_valid($_POST['username'])){
		$conn = DbTools::getDbConnect('dota');
		$time = time();
		//$conn->query("INSERT INTO dota.user SET uname = '".$conn->escape_string($_POST['username']).
		//	"', password = '".md5($_POST['password1'])."', create_time = $time, update_time = $time");
		$conn->query("INSERT INTO dota.user (uname, password, create_time, update_time) ".
			"VALUES('".$conn->escape_string($_POST['username'])."', '".md5($_POST['password1'])."', $time, $time)");
		$uid = $conn->insert_id;
		session_start();
		$_SESSION['uid'] = $uid;
		$_SESSION['uname'] = $_POST['username'];
		header("Location: userlist");
	}
}

?>
<!doctype html public "-//w3c//dtd xhtml 1.0 transitional//en" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.111cn.net/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" type="text/css教程" href="css/reg_log.css" />

</head>

<body>
<form id="myform" name="myform" method="post" action="register">
<div id="top">Sign up <a href="login">Sign in</a></div>
<div id="content">
 <div class="c1">请填写以下必填信息完成注册</div>
    <div class="c2"><span>带红色*的都是必填项目，若填写不全将无法注册</span></div>
    <div class="cinput"><dl>
     <dt>用户名<font color="red">*</font></dt>
        <dd><input id="username" name="username" type="text" maxlength="12" /></dd>
        <dd id="user_prompt" class="prompt">大小写英文字母、汉字、数字、下划线组成的长度 3-12 个字节以内</dd>
    </dl></div>
    <div class="cinput"><dl>
     <dt>密&nbsp;&nbsp;码<font color="red">*</font></dt>
        <dd><input id="password1" name="password1" type="password" maxlength="16" /></dd>
        <dd id="pwd1_prompt" class="prompt">最小长度:6 最大长度:16，仅限字母、数字及_,字母区分大小写</dd>
    </dl></div>         
    <!--检测密码强度-->
 <div class="cinput" style="padding-bottom:0px;"><!--ie6 hack-->
     <div class="chkpwd">
         <span id="cp1_prompt" class="cp1"></span><span id="cp2_prompt" class="cp2"></span>
            <span id="cp3_prompt" class="cp3"></span>
     </div>
    </div>
    <div class="cinput" style="padding-top:0px;"><dl>
     <dt>确认密码<font color="red">*</font></dt>
        <dd><input id="password2" name="password2" type="password" maxlength="16" /></dd>
        <dd id="pwd2_prompt" class="prompt">请再输入一遍您上面填写的密码</dd>
    </dl></div>
    <!--
    <div class="cinput"><dl>
     <dt>email<font color="red">*</font></dt>
        <dd><input id="email" name="email" type="text" /></dd>
        <dd id="email_prompt" class="prompt">请填写真实并且最常用的邮箱</dd>
    </dl></div>
-->
    <div style="clear:both"></div>
</div>
<div id="bottom">
 <input class="btn1" type="submit" name="register" value="Register" id="checkall" />
 <input class="btn2" type="reset" name="reset" value="Reset" onclick="return sub(this.form)" />
</div>
</form>
</body>
</html>