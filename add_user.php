<?php
require 'db.php';
require 'helpers.php';
require_login();
if(!is_admin()){ die('Access denied'); }
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] === 'admin' ? 'admin' : 'user';
    if($name==''||$email==''||$password=='') $errors[]='All fields required.';
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[]='Invalid email.';
    if(empty($errors)){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare('INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)');
        $stmt->bind_param('ssss', $name,$email,$hash,$role);
        if($stmt->execute()) header('Location: users.php');
        else $errors[]='Insert failed.';
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Add User</title></head><body>
<h2>Add User</h2>
<?php foreach($errors as $e) echo '<p style="color:red">'.sanitize($e).'</p>'; ?>
<form method="post">
  <label>Name:<br><input name="name" required></label><br>
  <label>Email:<br><input name="email" type="email" required></label><br>
  <label>Password:<br><input name="password" type="password" required></label><br>
  <label>Role:<br><select name="role"><option value="user">User</option><option value="admin">Admin</option></select></label><br>
  <button type="submit">Add</button>
</form>
<p><a href="users.php">Back</a></p>
</body></html>