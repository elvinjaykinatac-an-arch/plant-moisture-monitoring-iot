<?php
session_start();

$error = "";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // FIXED LOGIN
    if ($username == "badsbro" && $password == "badsbro2026") {

        $_SESSION['user'] = $username;

        header("Location: dashboard.php");
        exit();

    } else {
        $error = "Invalid Username or Password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Login</title>

    <style>

        body {
            font-family: Arial;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background: white;
            padding: 30px;
            width: 300px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #ccc;
        }

        h2 {
            text-align: center;
            color: green;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            background: green;
            color: white;
            border: none;
            cursor: pointer;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

    </style>

</head>

<body>

<div class="login-box">

    <h2>🌱 Plant System Login</h2>

    <form method="POST">

        <input type="text" name="username" placeholder="Username" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="login">Login</button>

    </form>

    <?php
    if ($error != "") {
        echo "<div class='error'>$error</div>";
    }
    ?>

</div>

</body>
</html>