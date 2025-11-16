<?php
require 'db.php';
require 'helpers.php';
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if($email === '' || $password === ''){
        $errors[] = 'Enter email and password.';
    } else {
        $stmt = $mysqli->prepare('SELECT id, name, password, role FROM users WHERE email=?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if($row = $res->fetch_assoc()){
            if(password_verify($password, $row['password'])){
                // login success
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['role'] = $row['role'];
                header('Location: users.php');
                exit;
            } else {
                $errors[] = 'Invalid credentials.';
            }
        } else {
            $errors[] = 'Invalid credentials.';
        }
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Login</title></head><body>
<h2>Login</h2>
<?php if(isset($_GET['registered'])) echo '<p style="color:green">Registration successful. Please login.</p>'; ?>
<?php foreach($errors as $e) echo '<p style="color:red">'.sanitize($e).'</p>'; ?>
<form method="post" novalidate>
  <label>Email:<br><input name="email" type="email" required></label><br>
  <label>Password:<br><input name="password" type="password" required></label><br>
  <button type="submit">Login</button>
</form>
<p>New? <a href="register.php">Register</a></p>
</body></html>