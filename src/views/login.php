<?php
include 'includes/navbar.inc.php';
include 'includes/footer.inc.php';
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="static/css/main.css">
</head>
<body>

<div id="form">
    <form method="post">
        <label for="login">Login</label>
        <input type="text" id="login" name="login" placeholder="Login" required><br>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Password" required><br>
        <input type="submit" value="Login">
        <?php if (isset($model['error-message'])) : ?>
            <p id="message"><?= $model['error-message'] ?></p>
        <?php endif; ?>
    </form>
</div>
</body>
</html>