<?php
require_once 'head.php';
?>

<!-- #!! bad notification -->
<?
if($_SESSION['notification']=='bad'){ echo '<h1 class="alert alert-danger container text-center"> Доступ ограничен </h1>' ;};
if($_SESSION['notification']=='auth_ok'){ echo '<h1 class="alert alert-success container text-center"> Вы успешно авторизовались </h1>' ;};
if($_SESSION['notification']=='auth_bad'){ echo '<h1 class="alert alert-danger container text-center"> Неверный логин или пароль </h1>' ;};
if($_SESSION['notification']=='auth_bad_exists'){ echo '<h1 class="alert alert-danger container text-center"> Пользователь уже существует </h1>' ;};
if($_SESSION['notification']=='good_email'){ echo '<h1 class="alert alert-danger container text-center"> Сообщение отправлено </h1>' ;};
?>

<p class='display-6  text-center container pt-5 '> Просмотр жалоб доступен после авторизации </p>



<? if($_COOKIE['login']){
    $user_db = $getter->validate_user($_COOKIE['login']);
    $login = $user_db[0]['login'];
    $password = $user_db[0]['password'];
}?>

<div class=' container '>


    <form action="server_confirm.php" class=' form-control p-5  container-sm text-center ' style='width: 22rem;' method="post">
    <h4>Вход</h4>

        <div>
            <label for="text" class=' col-form-label'>Логин</label>

            <input type="text" class="form-control" id="login" name="login" value="<?echo $login?>" required>
            <br>
            <label for="password" class=' col-form-label'>Пароль</label>
            <input type="password" class="form-control" id="password" name="password" value="<?echo $password?>" required>
            <br>
            <input type="submit" class='btn btn-primary' name="login_submit" value="Войти">
        </div>


    </form>


    <form action="server_confirm.php" method="post" style='width: 22rem;'class='mt-3 form-control p-5  container-sm text-center'>
    <h4>Регистрация</h4>

    <div class="mb-3">
        <label for="login" class="form-label">Логин</label>
        <input type="text" class="form-control" id="login" name="login" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Пароль</label>
        <input type="password" class="form-control" id="password" name="password" required>
        <input type="hidden" name="register" required>
    </div>

    <div class="mb-3">
        <label for="full_name" class="form-label">Полное имя</label>
        <input type="text" class="form-control" id="full_name" name="full_name" required>
        <input type="hidden" name="register" required>
    </div>

    <button type="submit" name="login_submit" class="btn btn-primary">Зарегистрироваться</button>
</form>

</div>






<? require_once 'bottom.php' ?>





<!--  <div class='position-absolute top-50 start-50 translate-middle '>


    <form action='server_confirm.php' class=' form-control p-5  container-sm text-center ' style='width: 22rem;' method="post">
        <div>
            <label for="login" class=' col-form-label'>Логин</label>

            <input id='login' type="text" class="form-control" name="login">
            <br>
            <label for="password" class="form-label">Пароль</label>
            <input type="password" class="form-control" id="password" name="password" required> <br>
            <button type="submit" name="login_submit" class="btn btn-primary">Войти</button>

            <button type="submit" name="register" class="btn btn-primary">Зарегистрироваться</button>
            <button type="submit" name="logout" class="btn btn-primary">Выйти</button>
        </div>
        <br>

    </form>

</div> -->