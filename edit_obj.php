<?session_start();

require_once 'database.php';



#!! permission
if ($result[0]['is_published'] == 0) {
    $manager = $result[0]['user_login'];
    $users = ['super_user'];
    if (!in_array($_SESSION['privilege'], $users) and $manager != $_SESSION['login']) {
        // If not, redirect to login.php
        $_SESSION['notification'] = 'bad';
        header('Location: login.php');
        exit;
    }
}
#!! end

$id = $_POST['id'];
$text = $_POST['edit_text'];
$text = str_replace("\n","<br>",$text);
$title = $_POST['edit_title'];

var_dump($_POST);

require_once 'head.php';

if (isset($_POST['edit_title']) && isset($_POST['edit_text']) && isset($_POST['id'])){
    $getter->edit_obj($id,$text,$title);
    echo '<h1 class="alert alert-success container text-center"> Вы успешно изменили жалобу. </h1>'
;}else{
    echo 'ничего не вышло.'
;};

require_once 'bottom.php'
?>