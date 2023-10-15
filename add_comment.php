<?
# старт сессии
session_start();
require_once 'database.php';

#!! получение данных 
# $result = объект, пользоваться вот так result[0]['inner_number']
# $user_full_name = объект пользователя, пользоваться так $user_full_name['full_name']
# $inner_number = $_GET['select_from_post'];
# $imgs = картинки из этого объекта

/* файлы */
#! $_FILES['img[]']

/* внут номер */
#! $_POST['inner_number']

/* текст */
#! $_POST['commentText']

/* пользователь */
#! $_POST['from_who']

$inner_number = $_POST['inner_number'];
$result = $getter->getter('object', $inner_number);

$text =  $_POST['commentText'];
$files = $_FILES['img[]'];
$from_who = $_POST['from_who'];
if(!!!$from_who){$from_who='Гость';}





#!! конец получения данных

#! permission
$manager = $result[0]['user_login'];
$users = ['super_user','DM'];
// if (!in_array($_SESSION['privilege'],$users) and $manager != $_SESSION['login'] ) {
//     // If not, redirect to login.php
//     $_SESSION['notification'] = 'bad';
//     header('Location: login.php');
//     exit;
// }
#! end








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
  if ($_POST['action'] == 'add_comment') {


    #!! add comment
    try {


      $object = $getter->add_comment($inner_number,$text,$from_who);


      echo '<h1 class="alert alert-success container text-center"> Комментарий добавлен </h1>';

    } catch (Exception $e) {
      echo 'Ошибка: ' . $e;
    }
    #!! end 
    require_once 'head.php'; 

  }
  if ($_POST['action'] == 'delete_comment') {
    
    try{$id = $_POST['id'];}
    catch (Exception $e) {
        echo 'Ошибка: ' . $e;
      }
    

    #!! add comment
    try {


      $object = $getter->delete_comment($id);


      echo '<h1 class="alert alert-success container text-center"> Комментарий удален </h1>';

    } catch (Exception $e) {
      echo 'Ошибка: ' . $e;
    }
    #!! end 
    require_once 'head.php'; 

  }
}

?>

