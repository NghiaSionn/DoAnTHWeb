<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "../models/User.php";

class AuthController {

    public function register() {
        $user = new User();

        $username = $_POST["username"];
        $password = $_POST["password"];
        $role = $_POST["role"] ?? "user";

        if ($user->register($username, $password, $role)) {
            header("Location: /DoAnTHWeb/index.php?registered=1");
            exit();
        } else {
            echo "Đăng ký thất bại!";
        }
    }

    public function login() {
        $user = new User();
        $username = $_POST["username"];
        $password = $_POST["password"];

        $data = $user->login($username);

        if ($data && password_verify($password, $data["password"])) {
            $_SESSION["user_id"] = $data["id"];
            $_SESSION["username"] = $data["username"];
            $_SESSION["role"] = $data["role"];

            
            error_log("Session set: " . print_r($_SESSION, true));

            if ($data["role"] == "admin") {
                header("Location: ../views/books/list.php");
            } else {
                header("Location: /DoAnTHWeb/index.php");
            }
            exit();
        }

        echo "Sai tài khoản hoặc mật khẩu!";
    }

    public function logout() {
        session_destroy();
        header("Location: /DoAnTHWeb/index.php");
        exit();
    }
}

$action = $_GET["action"] ?? "";

$auth = new AuthController();

if ($action == "login") $auth->login();
if ($action == "register") $auth->register();
if ($action == "logout") $auth->logout();