<?php
require_once 'auth.php';
logout();
header("Location: ../pages/login.php");
exit;