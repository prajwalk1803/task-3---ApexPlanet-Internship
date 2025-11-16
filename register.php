<?php
require 'db.php';
require 'helpers.php';
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if($name === '' || $email === '' || $password === ''){
        $errors[] = 'All fields are required.';
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = 'Invalid email.';
    } else {
        // check email exists
        $stmt = $mysqli->prepare('SELECT id FROM users WHERE email=?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0){
            $errors[] = 'Email already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user';
            $stmt = $mysqli->prepare('INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)');
            $stmt->bind_param('ssss', $name, $email, $hash, $role);
            if($stmt->execute()){
                header('Location: login.php?registered=1');
                exit;
            } else {
                $errors[] = 'Registration failed.';
            }
        }
        $stmt->close();
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Register</title></head><body>
<h2>Register</h2>
<?php foreach($errors as $e) echo '<p style="color:red">'.sanitize($e).'</p>'; ?>
<form method="post" novalidate>
  <label>Name:<br><input name="name" required></label><br>
  <label>Email:<br><input name="email" type="email" required></label><br>
  <label>Password:<br><input name="password" type="password" required></label><br>
  <button type="submit">Register</button>
</form>
<p>Already have account? <a href="login.php">Login</a></p>
</body></html>