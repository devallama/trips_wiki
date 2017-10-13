<?php
require('./php/lib/auth.php');

logoutUser();
$_SESSION['msg'] = "Sucessfully logged out.";
header('Location: ./index.php');
exit();
