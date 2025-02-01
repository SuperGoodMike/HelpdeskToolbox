<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    header("Location: index.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (authenticate($username, $password)) {
        $_SESSION['loggedin'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = 'Incorrect username or password';
    }
}

function authenticate($username, $password) {
    $htpasswd = file('/etc/secure/toolbox/.htpasswd');
    foreach ($htpasswd as $line) {
        list($user, $pass) = explode(':', trim($line));
        error_log("Checking user: $user");
        if ($user === $username) {
            if (crypt($password, $pass) == $pass) {
                error_log("Password match for user: $username");
                return true;
            } else {
                error_log("Password mismatch for user: $username");
            }
        } else {
            error_log("Username mismatch: expected $username, found $user");
        }
    }
    return false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="login.php" method="post">
        <h2>Login</h2>
        <?php if ($error): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
