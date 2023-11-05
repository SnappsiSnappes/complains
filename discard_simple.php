<?
# старт сессии
session_start();
require_once 'database.php';

#!! получение данных 
# $result = объект, пользоваться вот так result[0]['inner_number']
# $user_full_name = объект пользователя, пользоваться так $user_full_name['full_name']
# $inner_number = $_GET['select_from_post'];
# $imgs = картинки из этого объекта

$id = $_POST['id'];
$result = $getter->getter('object', $id);
// var_dump($result);







#!! конец получения данных

#! permission
$manager = $result[0]['user_login'];
$users = ['super_user','DM'];
if (!in_array($_SESSION['privilege'],$users) and $manager != $_SESSION['login'] ) {
    // If not, redirect to login.php
    $_SESSION['notification'] = 'bad';
    header('Location: login.php');
    exit;
}
#! end

require_once 'head.php'; 







// $to_user = $getter->getter('object',$_POST['inner_number']);
// $to_user = $to_user[0]['user_login'];
// var_dump($to_user)
// var_dump($_POST)



// var_dump($to_user[0]['user_login']);

?>

<?

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  #!! проверка на существование (1)
  $object_confirm = $getter->getter('object', $_POST['id']);
  if ($object_confirm[0]['id'] == 0 or $object_confirm[0]['id'] == null) {
    echo '<h1 class="alert alert-danger container text-center"> Жалоба не найдена </h1>';
    return;}
    #!! конец

# send msg before delete 
  if ($_POST['action'] == 'delete') {



    #!! delete
    try {


      $object = $getter->delete($_POST['id']);


      echo '<h1 class="alert alert-success container text-center"> Жалоба удалена </h1>';

    } catch (Exception $e) {
      echo 'Ошибка: ' . $e;
    }
    #!! end delete
  }
}
?>

