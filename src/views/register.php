<?php
include 'includes/navbar.inc.php';
include 'includes/footer.inc.php';
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="static/css/main.css">
</head>
<body>

<div id="form">
    <form method="post">
        <label for="login">Login</label>
        <input type="text" id="login" name="login" placeholder="Login" required><br>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Email" required><br>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Password"><br>
        <label for="passwordConfirm">Confirm password</label>
        <input type="password" id="passwordConfirm" placeholder="Confirm password"><br>
        <input type="submit" id="submit" value="Register">
        <?php if (isset($model['error-message'])) : ?>
            <p id="message"><?= $model['error-message'] ?></p>
        <?php endif; ?>
    </form>
</div>
</body>
<script>
    const password = document.getElementById("password");
    const passwordConfirm = document.getElementById("passwordConfirm");
    const submit = document.getElementById("submit");

    submit.addEventListener("click", function(event) {
        if (password.value === "" || password.value !== passwordConfirm.value) {
            event.preventDefault();
            alert("Passwords don't match");
        }
    });
</script>
</html>