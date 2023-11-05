<?php
#! permission

session_start();
$users = ['super_user','manager'];
if (!in_array($_SESSION['privilege'],$users)  ) {
    // If not, redirect to login.php
    $_SESSION['notification'] = 'bad';
    header('Location: login.php');
    exit;
}
#! end

require_once 'head.php';
require_once 'database.php';

?>




<div class="container pt-2">
  <div class="list-group ">


    <?
    // #!!! main foreach

    #! Pagination
    $limit = 5;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $start = ($page - 1) * $limit;

    #! main obj
    $obj_list = $getter->get_personal_objects($_SESSION['login']);

    #! Pagination items slice
    $items = array_slice($obj_list, $start, $limit);

    foreach ($items as $inside_array) {
      #! внутренний номер
      $id = $inside_array['id'];

      #! полное имя мэнэджера
      $user_full_name = $getter->get_user_full_name($id);

      #! imgs = список картинок и файлов текущего объекта
      $img_obj = $getter->get('img', $id);
      $img_obj?$imgs_string='Есть':$imgs_string='Нет';
      

      echo <<<EOT



    <div class='list-group-item hstack gap-3 '>

    <div class='p-2 w-75 '>
    
    <p class='text-break'><b> Менеджер: </b> {$user_full_name['full_name']} </p>
    <p class='text-break'><b> Название заявки:</b> {$inside_array['title']}</p>
    <p class='text-break'><b> Дата заявки: </b> {$inside_array['date_when_created']} </p>
    <p class='text-break'><b> Внутренний номер: </b>  {$inside_array['id']} </p>
    <p class='text-break'><b> вложенные файлы: </b>  {$imgs_string} </p>

    <p class='text-break'><b> Текст: </b>  <br>  {$inside_array['full_text']} </p>
    </div>
    
    <div class='p-2 ms-auto w-25'>
    <a href="getter.php?select_from_post={$inside_array['id']}" class="col-12 m-1 h-50 btn btn-primary" role="button">Посмотреть</a>
    </div>

    </div>





    EOT;
    };


    #! Pagination
    $total_pages = ceil(count($obj_list) / $limit);
    echo '<nav aria-label="Page navigation example" class="d-flex justify-content-center pt-3">';
    echo '<ul class="pagination pagination-lg">';
    for ($i = 1; $i <= $total_pages; $i++) {
        echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
    }
    echo '</ul>';
    echo '</nav>';
    
    

    ?>




  </div>
</div>