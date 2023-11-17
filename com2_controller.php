<?php
#! permission
session_start();
$users = ['admin', 'snappsi'];
if (!in_array($_SESSION['login'], $users)) {
  // If not, redirect to login.php
  $_SESSION['notification'] = 'bad';
  header('Location: login.php');
  exit;
}
#! end
require_once 'head.php';
require_once 'database.php';

#! activate
if ($_POST['action'] == 'activate') {
  $getter->publish($_POST['id']);
}
?>




<div class="container pt-2">
  <div class="list-group ">
    <!--     <div class="list-group-item ">
      Первый элемент списка
      <button type="button" class="float-end mx-2  btn  btn-primary">Опубликовать</button>
      <button type="button" class="float-end mx-2  btn btn-secondary">Отправить на доработку менеджеру</button>
    </div> -->

    <?
    // #!!! main foreach

#! Pagination
$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

#! main obj
$obj_list = $getter->get_com2_objects();

#! Pagination items slice
$items = array_slice($obj_list, $start, $limit);

#! Pagination
$total_pages = ceil(count($obj_list) / $limit);
echo '<nav aria-label="Page navigation example" class="d-flex justify-content-center pt-3">';
echo '<ul class="pagination pagination-lg">';


// Если текущая страница больше чем 3, добавить многоточие
if ($page > 3) {
    // Кнопка для первой страницы
echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';

echo '<li class="page-item"><a class="page-link" href="#">...</a></li>';


}

// Добавить ссылки на две страницы слева от текущей страницы
for ($i = max(1, $page - 2); $i < $page; $i++) {
    echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
}

// Добавить ссылку на текущую страницу
echo '<li class="page-item active"><a class="page-link" href="?page=' . $page . '">' . $page . '</a></li>';

// Добавить ссылки на две страницы справа от текущей страницы
for ($i = $page + 1; $i <= min($page + 2, $total_pages); $i++) {
    echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
}

// Если текущая страница меньше чем общее количество страниц минус 2, добавить многоточие
if ($page < $total_pages - 2) {
    echo '<li class="page-item"><a class="page-link" href="#">...</a></li>';
}

if ($page < $total_pages-3 or $page == $total_pages-3){
  // Кнопка для последней страницы
echo '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . '">' . $total_pages . '</a></li>';

}

echo '</ul>';
echo '</nav>';

// Форма для ввода номера страницы
echo '<form action="" method="get" class="d-flex justify-content-center pt-3 pb-3 mb-3>';
echo '<label for="page">Выберите страницу:</label>';
echo '<input type="number" id="page" name="page" min="1" max="' . $total_pages . '">';
echo '<input type="submit" value="Перейти">';
echo '</form>';

#! Pagination end

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
    <p class='text-break'><b> Внутренний номер: </b>  {$inside_array['inner_number']} </p>
    <p class='text-break'><b> вложенные файлы: </b>  {$imgs_string} </p>

    <p class='text-break'><b> Текст: </b>  <br>  {$inside_array['full_text']} </p>
    </div>
    
    <div class='p-2 ms-auto w-25'>
    <a href="getter.php?select_from_post={$inside_array['id']}" class="col-12 m-1  btn btn-primary" role="button">Посмотреть</a>


    <form action="com2_controller.php" method="post">
    <input type="hidden" name="action" value="activate">
    <input type="hidden" name="id" value="{$inside_array['id']}">
    <input type="submit" class='col-12 m-1   btn  btn-primary' value="Опубликовать">
    </form>

  
    <form action="discard.php" method="post">
    <input type="hidden" name="id" value="{$inside_array['id']}">
    <input type="submit" class='col-12 m-1   btn  btn-secondary' value="Отправить на доработку">
    </form>


    </div>

    </div>





    EOT;
    };




    

    ?>




  </div>
</div>