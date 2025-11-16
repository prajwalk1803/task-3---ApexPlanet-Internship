<?php
require 'db.php';
require 'helpers.php';
require_login();
// only admin can see all users
if(is_admin()){
    $res = $mysqli->query('SELECT id,name,email,role,profile_picture,created_at FROM users ORDER BY id DESC');
} else {
    $stmt = $mysqli->prepare('SELECT id,name,email,role,profile_picture,created_at FROM users WHERE id=?');
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $res = $stmt->get_result();
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Users</title></head><body>
<p>Welcome, <?= sanitize($_SESSION['name']) ?> | <a href="profile.php">My Profile</a> | <a href="logout.php">Logout</a></p>
<h2>Users</h2>
<?php if(is_admin()): ?><p><a href="add_user.php">Add User</a></p><?php endif; ?>
<table border="1" cellpadding="6">
  <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Picture</th><th>Actions</th></tr>
  <?php while($row = $res->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= sanitize($row['name']) ?></td>
      <td><?= sanitize($row['email']) ?></td>
      <td><?= $row['role'] ?></td>
      <td><?php if($row['profile_picture']): ?><img src="uploads/<?php echo sanitize($row['profile_picture']); ?>" width="60"><?php endif; ?></td>
      <td>
        <?php if(is_admin()): ?>
          <a href="edit_user.php?id=<?= $row['id'] ?>">Edit</a> |
          <a href="delete_user.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete user?')">Delete</a>
        <?php else: ?>
          <a href="profile.php">Edit Profile</a>
        <?php endif; ?>
      </td>
    </tr>
  <?php endwhile; ?>
</table>
</body></html>