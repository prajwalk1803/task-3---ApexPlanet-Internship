<?php
require 'helpers.php';
if(is_logged_in()){
    header('Location: users.php');
    exit;
} else {
    header('Location: login.php');
    exit;
}
?>