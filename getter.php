<?
# старт сессии
session_start();
require_once 'database.php';

#!! получение данных для авторизации
# $result = объект, пользоваться вот так $result[0]['inner_number']
# $user_full_name = объект пользователя, пользоваться так $user_full_name['full_name']
# $inner_number = $_GET['select_from_post'];
# $imgs = картинки из этого объекта
if (isset($_GET['select_from_post'])) {
    $select_from_post = $_GET['select_from_post'];
    $result = $getter->getter('object', $select_from_post);
    // var_dump($result);

    $inner_number = $_GET['select_from_post'];

    $status = $result[0]['is_published'] ? 'Опубликовано' : 'На проверке у администрации';
}
#!! конец получения данных для авторизации

#!! permission
if ($result[0]['is_published'] == 0){
$manager = $result[0]['user_login'];
$users = ['super_user', 'DM'];
if (!in_array($_SESSION['privilege'], $users) and $manager != $_SESSION['login']) {
    // If not, redirect to login.php
    $_SESSION['notification'] = 'bad';
    header('Location: login.php');
    exit;
}
}
#!! end

#!! получение обычных данных

$user_full_name = $getter->get_user_full_name($inner_number);
// print_r($user_full_name);

$comments = $getter->getter('comment_object', $inner_number);

# валидация фотографий - не комментировать foreach
$img_obj = $getter->get('img', $select_from_post);
$array = $img_obj;
$imgs = [];
foreach ($array as $subArray) {
    if (isset($subArray['img'])) {
        // echo $subArray['img'] . "<br>";
        $imgs[] = $subArray['img'];
    }
}

#!! конец получения обычных данных

require_once 'head.php';



?>


<style>
    .swiper-slide img {
        display: block;

        margin-left: auto;
        margin-right: auto;
        max-width: 700px;
        /* adjust this to the desired maximum size */
        max-height: 700px;
        /* adjust this to the desired maximum size */
        object-fit: contain;
        max-inline-size: 100%;
        block-size: auto;
        inline-size: 100%;

        /* this will prevent distortion */
    }
</style>






<?
#!! delete button && mail button && publish button && search
if ($_SESSION['privilege'] == 'super_user') {

    #!search
    echo <<<EOT
    <div class="container text-center pt-5 pb-3 ">

    <form action="" method="get">
    <label class="h5" for="select_from_post">Укажите внутренний номер магазина:</label>
    <input type="text" id="select_from_post" name="select_from_post">
    <input type="submit" class='btn btn-primary' value="Поиск">
    </form>
    </div>
    EOT;
    # search end

    if (!$result[0]) {
        echo '<h1 class="mt-5 alert alert-danger container text-center"> Жалоба не найдена </h1>';
        return 0;
    };


    #! delete button
    echo <<<EOT
    <div class='container'>
    
    <form action="discard.php" method="post">
    <input type="hidden" name="inner_number" value="{$_GET['select_from_post']}">


    <div class="d-grid gap-2 col-6 mx-auto">

    <input type="submit" class='col-12 m-1 btn  btn-secondary' value="Отправить на доработку менеджеру">

    </div>

    </form>
    </div>
    EOT;
    # end delete

    #! mail button
    $agreement = $result[0]['agreement'];
    if ($agreement == 'company1') {
        $mail = 'company1@gmail.com';
    } else {
        $mail = 'company2@gmail.com';
    }

    $text = $result[0]['full_text'];
    $escaped_text = htmlspecialchars($text, ENT_QUOTES);

    echo <<<EOT
    <div class='container'>

    <form action="sendMail.php" method="post">
    <input type="hidden" name="inner_number" value="{$_GET['select_from_post']}">
    <input type="hidden" name="title" value="{$result[0]['title']}">
    <input type="hidden" name="text" value="'.$escaped_text.'">
    <input type="hidden" name="mail" value="{$mail}">

    <div class="d-grid gap-2 col-6 mx-auto">

    <input type="submit" class='col-12 m-1  btn  btn-secondary' value="Отправить на почту {$mail}">
    </div>

    </form>
    </div>
    EOT;
    # end mail

    #! publish button
    if ($result[0]['is_published'] == 0) {
        echo <<<EOT
    <div class='container'>
    <form action="Koroteev_controller.php" method="post">
    <input type="hidden" name="action" value="activate">
    <input type="hidden" name="inner_number" value="{$_GET['select_from_post']}">
    <div class="d-grid gap-2 col-6 mx-auto">
    <input type="submit" class='col-12 m-1  btn  btn-secondary' value="Опубликовать">
    </div>
    </form>
    </div>
    EOT;
        # end publish button


    }
}
if (!$result[0]) {
    echo '<h1 class="mt-5 alert alert-danger container text-center"> Жалоба не найдена </h1>';
    return 0;
};

#!! delete button for creator
if ($manager == $_SESSION['login'] && !!$manager) {

    echo <<<EOT
    <div class='container '>
    
    <form action="discard_simple.php" method="post">
    <input type="hidden" name="inner_number" value="{$_GET['select_from_post']}">
    <input type="hidden" name="action" value="delete">


    <div class="d-grid gap-2 col-6 mx-auto">

    <input type="submit" class='col-12 m-1 btn  btn-secondary' value="Удалить жалобу">

    </div>

    </form>
    EOT;
}
?>

<?

?>

<div class="container p-5">
    <div class="col-12  ">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php
                // Open a directory, and read its contents

                if (isset($_GET['select_from_post']) and $result) {
                    $inner_number = $_GET['select_from_post'];
                    $dir = "img/" . $inner_number . "/"; // specify the directory where images are stored

                    // Open a directory, and read its contents
                    if (is_dir($dir)) { // блок вывода фотографий - проверяет их по внутреннему номеру в папке на сервере - в папке img - 
                        if ($dh = opendir($dir)) {
                            while (($file = readdir($dh)) !== false) {
                                // filter out "." and ".."
                                if ($file != "." && $file != "..") {
                                    $path = $dir . $file;
                                    $filetype = pathinfo($path, PATHINFO_EXTENSION);
                                    if ($filetype == 'png' || $filetype == 'jpg' || $filetype == 'jpeg') {
                                        //проверка на название файла - должно совпадать из базы данных, можно удалить этот if
                                        if (in_array($file, $imgs)) {
                                            // #! если файл jpg / png тогда показываем в слайдшоу
                                            echo "<div class='swiper-slide '><img src='" . $dir . $file . "' alt='Image'></div>";
                                        }
                                    }
                                }
                            }
                            closedir($dh);
                        }
                    }
                } ?>

            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
            <!-- Add Navigation -->
            <div class="swiper-button-next border border-primary  bg-body-tertiary p-5 ml-5"></div>
            <div class="swiper-button-prev border border-primary  bg-body-tertiary p-5 mr-5"></div>
        </div>
    </div>

    <br>

    <h1 class='text-center '>Жалоба на магазин <? echo $inner_number ?></h1>
    <div class='col-4 mt-4 p-4 border text-center '>
        <!-- #! отобразить pdf / mp3 / mp4-->
        <? if (isset($_GET['select_from_post']) and $result) {
            $inner_number = $_GET['select_from_post'];
            $dir = "img/" . $inner_number . "/"; // specify the directory where images are stored

            // Open a directory, and read its contents
            if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                    echo '<b><p>Файлы приложенные менеджером:</b></p>';

                    while (($file = readdir($dh)) !== false) {
                        // filter out "." and ".."

                        if ($file != "." && $file != "..") {
                            $path = $dir . $file;
                            $filetype = pathinfo($path, PATHINFO_EXTENSION);


                            if ($filetype == 'pdf' || $filetype == 'mp3' || $filetype == 'mp4' || $filetype == 'docx') {
                                echo "<div>Скачать <a href='" . $path . "' download>" . $file . "</a></div>";
                            }
                        }
                    }
                    closedir($dh);
                }
            } else {
                echo '<p>Нет файлов во вложении</p>';
            }
        }
        ?>
    </div>

    <div class='col-4 mt-4 p-4 border text-center '>
        Менеджер - <? echo $user_full_name['full_name'] ?>
    </div>
    <div class='col-4 mt-4 p-4 border text-center '>
        Договор с : <? echo $result[0]['agreement']; ?> <br>
        Обслуживает : <? echo $result[0]['service']; ?>
    </div>


    <!-- #! время, заголовок , статус -->
    <div class="row">
        <div class="col-12">
            <!-- заголовок -->
            <h1 class="text-center display-4"><?php echo strip_tags($result[0]['title']) ?></h1>

            <div class="d-flex justify-content-end gap-2">
                <!-- время и статус -->
                <div class="border p-2 mt-2 mb-2 pb-1 pt-2  5 "><?php echo 'Статус: ' . $status ?> </div>

                <div class="border p-2 mt-2 mb-2 pb-1 pt-2  "><?php echo 'Дата создания: ' . $result[0]['date_when_created'] ?> </div>
            </div>
            <div class="border p-3 ">
                <p class=" text-break fs-4 m-5 "><?php echo strip_tags($result[0]['full_text'],'<br><p><a><b>'); ?></p>
            </div>

        </div>
    </div>




</div>

<div class="container py-5">
    <div class="row">
        <div class="col-12 border p-5">
            <div class="comments ">
                <h2 class="mb-4">Комментарии</h2>




                <?php
                // Определите количество комментариев на странице и общее количество комментариев
                $limit = 7;
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                $start = ($page - 1) * $limit;

                // Выведите комментарии для текущей страницы
                $items = array_slice($comments, $start, $limit);
                foreach ($items as $inside_array) {
                    if ($_SESSION['login'] == $inside_array['user_login']) {
                        /* отображаем кнопку Удалить Комментарий только если чел является создателем комментария */
                        $delete_comment = <<<EOT
                            <form 
                            
                            method="post"  id="commentForm_delete_{$inside_array['id']}" action='add_comment.php' enctype="multipart/form-data">
                            <input type="hidden" name="action" value="delete_comment">
                            <input type="hidden" name="inner_number" value="{$inner_number}">

                            <input type="hidden" name="id" value="{$inside_array['id']}">
                            <button type="submit" class="btn btn-primary">Удалить Комментарий</button>

                            </form>

                            EOT;
                    }else{$delete_comment='';};

                    if ($inside_array['user_login']=='Гость'  and $_SESSION['privilege']=='super_user') {
                        $delete_comment = <<<EOT
                        <form 
                        
                        method="post"  id="commentForm_delete_{$inside_array['id']}" action='add_comment.php' enctype="multipart/form-data">
                        <input type="hidden" name="action" value="delete_comment">
                        <input type="hidden" name="inner_number" value="{$inner_number}">

                        <input type="hidden" name="id" value="{$inside_array['id']}">
                        <button type="submit" class="btn btn-primary">Удалить Комментарий</button>

                        </form>

                        EOT;
                    }




                    echo <<<EOT
                    <div class="comment d-flex mb-4">
                        <div class="flex-grow-1 ms-3">
                            <h4>{$inside_array['user_login']}</h4>
                            <p>Дата: {$inside_array['date_when_created']}</p>
                            <p>{$inside_array['text']}</p>

                            {$delete_comment}


                        </div>
                    </div>
                    EOT;
                }

                // Выведите ссылки на другие страницы
                $total_pages = ceil(count($comments) / $limit);
                echo '<nav aria-label="Page navigation example" class="d-flex justify-content-center pt-3">';
                echo '<ul class="pagination pagination-lg">';
                for ($i = 1; $i <= $total_pages; $i++) {
                    // Сохраните текущий URL и удалите существующий параметр 'page'
                    $url = $_SERVER['REQUEST_URI'];
                    $url = preg_replace('/(&|\?)page=\d+/', '', $url);

                    // Добавьте параметр 'page'
                    $separator = (parse_url($url, PHP_URL_QUERY) == NULL) ? '?' : '&';
                    if ($i == $page) {
                        echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"$url$separator" . "page=$i\">$i</a></li>";
                    } else {
                        echo "<li class=\"page-item\"><a class=\"page-link\" href=\"$url$separator" . "page=$i\">$i</a></li>";
                    }
                }
                echo '</ul>';
                echo '</nav>';
                ?>



            </div>
            <!-- блок комментариев -->
            <form method="post" id=commentForm action='add_comment.php' enctype="multipart/form-data">

                <div class="mb-3">
                    <label for="commentText" class="form-label">Текст комментария</label>
                    <textarea class="form-control" id="commentText" name='commentText' rows="3" required></textarea>
                </div>
<!-- #! картинку загружать, отложено на лучшие времена...                <div class="mb-3">
                    <label for="commentImage" multiple class="form-label" name='img[]'>Загрузить изображение</label>
                    <input class="form-control" type="file" id="commentImage">
                </div> -->
                <!-- Скрытое поле для передачи уникального имени в POST -->
                <input type="hidden" name="inner_number" value="<?php echo $_GET['select_from_post']; ?>">
                <input type="hidden" name="from_who" value="<?php echo $_SESSION['login']; ?>">
                <input type="hidden" name="action" value="add_comment">
                <input type="hidden" name="start_page" value="<?php echo $_SERVER['REQUEST_URI']; ?>">


                <button type="submit" class="btn btn-primary">Отправить</button>
            </form>
        </div>
    </div>


</div>
</div>
</div>







<script>
    var swiper = new Swiper('.swiper-container', {

        slidesPerView: 1,
        spaceBetween: 1,
        effect: 'flip',
        grabCursor: true,
        longSwipes: true,
        loop: true,
        zoom: {
            maxRatio: 5,
        },
        edgeSwipeDetection: true,
        centeredSlides: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',

        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });
</script>

<script>
    /* отправляем комментарий */
    $(document).ready(function() {
        $("#commentForm").on("submit", function(event) {
            event.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: 'add_comment.php',
                type: 'POST',
                data: formData,
                success: function(data) {
                    location.reload(); // Обновить страницу

                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    });
</script>

<script>
    /* удаляем комментарий */
    $(document).ready(function() {  <?php
    foreach ($comments as $inside_array) {
        echo <<<EOT
        $("#commentForm_delete_{$inside_array['id']}").on("submit", function(event) {
            event.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: 'add_comment.php',
                type: 'POST',
                data: formData,
                success: function(data) {
                    location.reload(); // Обновить страницу
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
        EOT;
    }
    ?>});
</script>

</div>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->






<? require_once 'bottom.php' ?>
