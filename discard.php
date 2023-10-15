<?php
#! permission
session_start();
$users = ['super_user'];
if (!in_array($_SESSION['privilege'],$users)  ) {
    // If not, redirect to login.php
    $_SESSION['notification'] = 'bad';
    header('Location: login.php');
    exit;
}
#! end

require_once 'head.php';
require_once 'database.php';



// $to_user = $getter->getter('object',$_POST['inner_number']);
// $to_user = $to_user[0]['user_login'];
// var_dump($to_user)
// var_dump($_POST)



// var_dump($to_user[0]['user_login']);

?>

<?

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  #!! проверка на существование (1)
  $object_confirm = $getter->getter('object', $_POST['inner_number']);
  if ($object_confirm[0]['inner_number'] == 0 or $object_confirm[0]['inner_number'] == null) {
    echo '<h1 class="alert alert-danger container text-center"> Жалоба не найдена </h1>';
    return;}
    #!! конец

# send msg before delete 
  if ($_POST['action'] == 'delete') {
    #!! (2)(второй раз post , на этот раз action = delete)
    $to_user = $getter->getter('object',$_POST['inner_number']);
    $to_user = $to_user[0]['user_login'];
    $date_when_created = date('Y-m-d_H:i:s', strtotime('+3 hours'));

$msg = $getter->send_msg($_SESSION['login'],$to_user,$date_when_created,$_POST['full_text']);
    #!! end

    #!! delete (3)
    try {
      $object_confirm = $getter->getter('object', $_POST['inner_number']);

      if ($object_confirm[0]['inner_number'] == 0 or $object_confirm[0]['inner_number'] == null) {
        echo '<h1 class="alert alert-danger container text-center"> Жалоба не найдена </h1>';
        return;
      };

      $object = $getter->delete($_POST['inner_number']);


      echo '<h1 class="alert alert-success container text-center"> Жалоба удалена </h1>';



    } catch (Exception $e) {
      echo 'Ошибка: ' . $e;
    }
    #!! end delete
  }
}
?>

<div class="container">

  <div class="row">
    <div class="col-12">
      <h1 class="mt-3 text-center">Сообщение менеджеру</h1>

      <form action="#" method="post" class="row pb-5">
        <div class="mb-3 row">
          <label for="full_text" class="">Текст cообщения:</label>
          <div class="col-12">
            <textarea id="full_text" name="full_text" class="form-control" rows="5"  required>Заявка на жалобу отклонена. Внутренний номер магазина: <? echo $_POST['inner_number']; ?>. Причина:</textarea>
          </div>
          <div hidden class="col-12">
            <input type="hidden" name="inner_number" value="<? echo $_POST['inner_number']; ?>">
            <input type="hidden" name="action" value="delete">

          </div>

        </div>

        <div class="d-grid gap-2 col-6 mx-auto">
          <button type='submit' class='btn btn-primary btn-lg'>Отправить сообщение</button>
        </div>


      </form>
    </div>
  </div>
</div>