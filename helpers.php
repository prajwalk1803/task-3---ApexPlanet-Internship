<?php
// helpers.php - small helpers for auth & validation
session_start();
function is_logged_in(){
    return isset($_SESSION['user_id']);
}
function is_admin(){
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
function require_login(){
    if(!is_logged_in()){
        header('Location: login.php');
        exit;
    }
}
function sanitize($s){
    return htmlspecialchars(trim($s), ENT_QUOTES, 'UTF-8');
}
?>