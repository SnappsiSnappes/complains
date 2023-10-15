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

// чистка 
$obj_list = $getter->get_msg($_SESSION['login'],true,true);
?>




<div class="container pt-2">
  <div class="list-group ">


    <?
    // #!!! main foreach

    #! Pagination
    $limit = 10;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $start = ($page - 1) * $limit;

    #! main obj
    $obj_list = $getter->get_msg($_SESSION['login']);

    #! Pagination items slice
    $items = array_slice($obj_list, $start, $limit);



    foreach ($items as $inside_array) {


      

      echo <<<EOT



    <div class='list-group-item hstack gap-3 '>

    <div class='p-2 w-100 '>
    
    <p class='text-break'><b> От пользователя: </b> {$inside_array['from_user']} </p>
    <p class='text-break'><b> Дата:</b> {$inside_array['date_when_sent']}</p>

    <p class='text-break'><b> Текст: </b>  <br>  {$inside_array['text']} </p>
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