<?php
require 'db.php';
require 'helpers.php';
require_login();
if(!is_admin()) die('Access denied');
$id = intval($_GET['id'] ?? 0);
if(!$id) die('Invalid id');
// don't allow admin to delete themselves (basic safeguard)
if($id == $_SESSION['user_id']) die('Cannot delete yourself.');
$stmt = $mysqli->prepare('DELETE FROM users WHERE id=?');
$stmt->bind_param('i',$id);
$stmt->execute();
header('Location: users.php');
exit;
?>