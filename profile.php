<?php
require 'db.php';
require 'helpers.php';
require_login();
$user_id = $_SESSION['user_id'];
$stmt = $mysqli->prepare('SELECT id,name,email,role,profile_picture FROM users WHERE id=?');
$stmt->bind_param('i',$user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name'] ?? '');
    if($name == '') $errors[] = 'Name required.';
    // handle upload if present
    if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE){
        $f = $_FILES['profile_picture'];
        if($f['error'] !== UPLOAD_ERR_OK) $errors[] = 'Upload error.';
        else {
            $allowed = ['image/jpeg','image/png','image/gif'];
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($f['tmp_name']);
            if(!in_array($mime, $allowed)) $errors[] = 'Only JPG/PNG/GIF allowed.';
            if($f['size'] > 2 * 1024 * 1024) $errors[] = 'Max 2MB file size.';
            if(empty($errors)){
                $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
                $newname = uniqid('p_', true) . '.' . $ext;
                $target = __DIR__ . '/uploads/' . $newname;
                if(!move_uploaded_file($f['tmp_name'], $target)) $errors[] = 'Could not move uploaded file.';
                else {
                    // delete old picture
                    if($user['profile_picture'] && file_exists(__DIR__ . '/uploads/' . $user['profile_picture'])){
                        @unlink(__DIR__ . '/uploads/' . $user['profile_picture']);
                    }
                    // update DB with new filename
                    $stmtu = $mysqli->prepare('UPDATE users SET profile_picture=? WHERE id=?');
                    $stmtu->bind_param('si',$newname,$user_id);
                    $stmtu->execute();
                }
            }
        }
    }
    if(empty($errors)){
        $stmtu = $mysqli->prepare('UPDATE users SET name=? WHERE id=?');
        $stmtu->bind_param('si',$name,$user_id);
        $stmtu->execute();
        header('Location: profile.php?updated=1');
        exit;
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Profile</title></head><body>
<p><a href="users.php">Back</a> | <a href="logout.php">Logout</a></p>
<h2>My Profile</h2>
<?php if(isset($_GET['updated'])) echo '<p style="color:green">Updated.</p>'; ?>
<?php foreach($errors as $e) echo '<p style="color:red">'.sanitize($e).'</p>'; ?>
<form method="post" enctype="multipart/form-data">
  <label>Name:<br><input name="name" value="<?= sanitize($user['name']) ?>" required></label><br>
  <label>Email:<br><input value="<?= sanitize($user['email']) ?>" disabled></label><br>
  <p>Current picture:<br>
  <?php if($user['profile_picture']): ?>
    <img src="uploads/<?php echo sanitize($user['profile_picture']); ?>" width="120">
  <?php else: ?>
    <em>No picture uploaded</em>
  <?php endif; ?>
  </p>
  <label>Upload new picture (max 2MB):<br><input type="file" name="profile_picture" accept="image/*"></label><br>
  <button type="submit">Save</button>
</form>
</body></html>