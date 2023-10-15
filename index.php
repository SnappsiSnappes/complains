<?php
session_start();
// require_once 'head.php';

// Подключение к базе данных
require_once 'database.php';

// Регистрация
/* if (isset($_POST['register'])) {
    $login = $_POST['login'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $privilege = 'manager';
    $sql = "INSERT INTO users (login, password, privilege) VALUES (:login, :password, :privilege)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['login' => $login, 'password' => $password, 'privilege' => $privilege]);

    echo "Регистрация прошла успешно!";
} */

// Вход в систему
if (isset($_POST['login_submit'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE login = :login";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['login' => $login]);
    $user = $stmt->fetch();
    // print_r($_POST['password']);
    if ($user && $password == $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['login'] = $user['login'];
        $_SESSION['privilege'] = $user['privilege']; // предполагается, что у вас есть поле 'privilege' в таблице 'users'
        if($_SESSION['notification']=='bad'){ $_SESSION['notification']='';};

        header('Location: login.php');
        $_SESSION['notification']='auth_ok';
        echo "<h1> Вы успешно вошли в систему! </h1>";

    }else{
        header('Location: login.php');
        $_SESSION['notification']='auth_bad';
    }
    }
    // #!!! оно так работает, я хз почему, не трогать!
    header('Location: login.php');
    
    $_SESSION['notification']=='auth_ok'?:$_SESSION['notification']='auth_bad';


    require_once 'head.php';
    echo "<h1> Неверный логин или пароль! </h1>" ;

// Выход из системы
/* if (isset($_POST['logout'])) {
    session_destroy();
    echo "Вы вышли из системы!";
} */

?>
