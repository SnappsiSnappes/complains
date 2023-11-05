<?
# старт сессии
session_start();
require_once 'database.php';

#!! получение данных 
# $result = объект, пользоваться вот так result[0]['id']
# $user_full_name = объект пользователя, пользоваться так $user_full_name['full_name']
# $id = $_GET['select_from_post'];
# $imgs = картинки из этого объекта

/* файлы */
#! $_FILES['img[]']

/* id */
#! $_POST['id']

/* текст */
#! $_POST['commentText']

/* пользователь */
#! $_POST['from_who']

$id_company = $_POST['id_company'];
$id_comment = $_POST['id_comment'];
$result = $getter->getter('object', $id_company);

$text =  $_POST['commentText'];
$files = $_FILES['img'];#$_POST['img[]'];#;#
// #! если нет файлов то null
if ($files['name'][0]==''){$files=null;};

$from_who = $_POST['from_who'];
if(!!!$from_who){$from_who='Гость';}
$date = date('Y-m-d_H:i:s');

// array(5) { ["name"]=> array(2) { [0]=> string(6) "s2.png" [1]=> string(5) "s.png" } ["type"]=> array(2) { [0]=> string(9) "image/png" [1]=> string(9) "image/png" } ["tmp_name"]=> array(2) { [0]=> string(14) "/tmp/phpuIVPhO" [1]=> string(14) "/tmp/phpC6qyhQ" } ["error"]=> array(2) { [0]=> int(0) [1]=> int(0) } ["size"]=> array(2) { [0]=> int(115070) [1]=> int(115070) } }

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
  $object_confirm = $getter->getter('object', $id_company);
  if ($object_confirm[0]['id'] == 0 or $object_confirm[0]['id'] == null) {
    echo '<h1 class="alert alert-danger container text-center"> Жалоба не найдена </h1>';
    return;}
    #!! конец

  if ($_POST['action'] == 'add_comment') {


    #!! add comment
    try {

      if (isset($files)) {
        $object = $getter->add_comment($id_company, $text, $from_who, $files);
    } else {
        $object = $getter->add_comment($id_company, $text, $from_who);
    }
    


          


      echo '<h1 class="alert alert-success container text-center"> Комментарий добавлен </h1>';

    } catch (Exception $e) {
      echo 'Ошибка: ' . $e;
    }
    // #! отправка уведомления
    $text_msg = "На вашу жалобу по магазину с внутренним номером: https://koroteevav.ru/complain/getter.php? select_from_post=".$id_company."\n Оставлен комментарий от: {$from_who}
    \n {$text}";
    $getter->send_msg($from_who,$manager,$date,$text_msg);
    #!! end 
    require_once 'head.php'; 

  }
  if ($_POST['action'] == 'delete_comment') {
    
    try{$id = $_POST['id_comment'];}
    catch (Exception $e) {
        echo 'Ошибка: ' . $e;
      }
    

    #!! delete comment
    try {


      $object = $getter->delete_comment($id_company,$id_comment);


      echo '<h1 class="alert alert-success container text-center"> Комментарий удален </h1>';

    } catch (Exception $e) {
      echo 'Ошибка: ' . $e;
    }
    #!! end 
    require_once 'head.php'; 

  }
}

?>

