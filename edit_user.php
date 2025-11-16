<?php
require 'db.php';
require 'helpers.php';
require_login();
if(!is_admin()){ die('Access denied'); }
$id = intval($_GET['id'] ?? 0);
if(!$id) die('Invalid id');
$stmt = $mysqli->prepare('SELECT id,name,email,role FROM users WHERE id=?');
$stmt->bind_param('i',$id);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
if(!$user) die('User not found');
$errors = [];
if($_SERVER['REQUEST_METHOD']==='POST'){
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role']==='admin' ? 'admin' : 'user';
    if($name==''||$email=='') $errors[]='Fields required.';
    if(empty($errors)){
        $stmt = $mysqli->prepare('UPDATE users SET name=?, email=?, role=? WHERE id=?');
        $stmt->bind_param('sssi',$name,$email,$role,$id);
        if($stmt->execute()) header('Location: users.php');
        else $errors[]='Update failed.';
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Edit User</title></head><body>
<h2>Edit User</h2>
<?php foreach($errors as $e) echo '<p style="color:red">'.sanitize($e).'</p>'; ?>
<form method="post">
  <label>Name:<br><input name="name" value="<?= sanitize($user['name']) ?>" required></label><br>
  <label>Email:<br><input name="email" type="email" value="<?= sanitize($user['email']) ?>" required></label><br>
  <label>Role:<br><select name="role"><option value="user" <?= $user['role']=='user' ? 'selected' : '' ?>>User</option><option value="admin" <?= $user['role']=='admin' ? 'selected' : '' ?>>Admin</option></select></label><br>
  <button type="submit">Update</button>
</form>
<p><a href="users.php">Back</a></p>
</body></html>