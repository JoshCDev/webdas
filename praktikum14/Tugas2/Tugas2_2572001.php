<!-- 2572001 - Joshua Christopher Gunawan -->
<?php

session_start();
include_once("config.php");

$form = isset($_GET["form"]) && $_GET["form"] == "register" ? "register" : "login";
$msg = "";
$alert = "danger";

if (isset($_GET["logout"])) {
    session_unset();
    session_destroy();
    header("Location: Tugas2_2572001.php");
    exit;
}

if (isset($_POST["btnRegister"])) {
    $form = "register";
    $username = isset($_POST["username"]) ? trim($_POST["username"]) : "";
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";

    if ($username == "" || $email == "" || $password == "") {
        $msg = "Semua field wajib diisi.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->execute([
            "username" => $username,
            "email" => $email
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user["email"] == $email) {
                $msg = "Email sudah terdaftar.";
            } else {
                $msg = "Username sudah terdaftar.";
            }
        } else {
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->execute([
                "username" => $username,
                "email" => $email,
                "password" => $hashPassword
            ]);
            $msg = "Data sudah disimpan.";
            $alert = "success";
        }
    }
}

if (isset($_POST["btnLogin"])) {
    $form = "login";
    $credential = isset($_POST["credential"]) ? trim($_POST["credential"]) : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :credential OR email = :credential LIMIT 1");
    $stmt->execute(["credential" => $credential]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["username"] = $user["username"];
        header("Location: Tugas2_2572001.php");
        exit;
    }

    $msg = "Password salah!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="2572001 - Joshua Christopher Gunawan">
    <title>LoginRegister - 2572001</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" /> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> 
    <style>
        body {
            background-color: #f7f7f7;
            font-family: Arial, Helvetica, sans-serif;
        }

        .auth-card {
            width: 625px;
            margin: 6px auto;
            padding: 24px 20px 38px;
            background-color: #ffffff;
            border: 1px solid #d4d4d4;
            border-radius: 5px;
        }

        h1 {
            text-align: center;
            font-size: 36px;
            margin-bottom: 28px;
        }

        label {
            font-size: 20px;
        }

        .form-block {
            margin-bottom: 23px;
        }

        .form-control {
            height: 48px;
        }

        .btn {
            height: 48px;
            font-size: 20px;
        }

        .switch-text {
            text-align: center;
            font-size: 20px;
            margin-top: 24px;
        }

        .alert {
            padding: 24px 20px;
            font-size: 20px;
        }

        .dashboard-content {
            width: 560px;
            margin: 30px 24px;
        }
    </style>
</head>
<body>
<?php
if (isset($_SESSION["username"])) {
?>
    <div class="dashboard-content">
        <div class="alert alert-success">
            Selamat datang, <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong>
        </div>
        <a class="btn btn-danger" href="Tugas2_2572001.php?logout=1">Logout</a>
    </div>
<?php
} else {
?>
    <div class="auth-card">
<?php
    if ($form == "register") {
        echo "<h1>Register</h1>";
        if ($msg != "") {
            echo "<div class='alert alert-$alert'>" . htmlspecialchars($msg) . "</div>";
        }
?>
        <form method="POST" action="Tugas2_2572001.php?form=register">
            <div class="form-block">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>
            <div class="form-block">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="form-block">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" name="btnRegister" class="btn btn-primary w-100">Register</button>
        </form>
        <p class="switch-text">Sudah punya akun? <a href="Tugas2_2572001.php">Login</a></p>
<?php
    } else {
        echo "<h1>Login</h1>";
        if ($msg != "") {
            echo "<div class='alert alert-$alert'>" . htmlspecialchars($msg) . "</div>";
        }
?>
        <form method="POST" action="Tugas2_2572001.php">
            <div class="form-block">
                <label for="credential" class="form-label">Email / Username</label>
                <input type="text" class="form-control" id="credential" name="credential">
            </div>
            <div class="form-block">
                <label for="login-password" class="form-label">Password</label>
                <input type="password" class="form-control" id="login-password" name="password">
            </div>
            <button type="submit" name="btnLogin" class="btn btn-success w-100">Login</button>
        </form>
        <p class="switch-text">Belum punya akun? <a href="Tugas2_2572001.php?form=register">Register</a></p>
<?php
    }
?>
    </div>
<?php
}
?>
</body>
</html>
