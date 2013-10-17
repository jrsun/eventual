<?php

session_start();
session_destroy();
setcookie('login_cookie','', time()-3600);
header("Location: main.php");
?>