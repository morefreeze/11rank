<?php
require_once ("DbTools.class.php");
require_once ("usertools.php");

if (isset($_POST['username'], $_POST['password'])){
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $conn = DbTools::getDBConnect('dota');
    $res = $conn->query("SELECT uid,uname FROM dota.user WHERE uname = '".$conn->escape_string($username)."' ".
        "AND password = '$password' LIMIT 1");
    if (false === $res){

    }
    if (empty($res)){
        echo "Username or Password is incorrect.<br />";
    }
    else{
        session_start();
        $_SESSION['uid'] = $res[0]['uid'];
        $_SESSION['uname'] = $res[0]['uname'];
        if (headers_sent()){
            echo "hehe";
        }
        else{
            header("Location: userlist");    
        }
        
    }
}
?>
<!doctype html public "-//w3c//dtd xhtml 1.0 transitional//en" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.111cn.net/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title></title>
</head>

<body>
<form id="myform" name="myform" method="post" action="login">
<div id="content">
    <div class="cinput"><dl>
     <dt>Username: <font color="red">*</font></dt>
        <dd><input id="username" name="username" type="text" maxlength="12" /></dd>
        <dd id="user_prompt" class="prompt">Username</dd>
    </dl></div>
    <div class="cinput"><dl>
     <dt>Password: <font color="red">*</font></dt>
        <dd><input id="password" name="password" type="password" maxlength="16" /></dd>
        <dd id="pwd1_prompt" class="prompt">Password</dd>
    </dl></div>         
    <div style="clear:both"></div>
</div>
<div id="bottom">
 <input class="btn1" type="submit" name="login" value="Login" id="checkall" />
</div>
</form>
</body>
</html>