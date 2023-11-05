<?
session_start();
require_once 'database.php';


if (isset($_GET['inner_number'])) {
    $inner_number = $_GET['inner_number'];

    # временный результат, получаем данные чтобы заполнить основную переменную $result
    # таким образом работает поиск и по id и по inner_number
    $temp_result = $getter->getter_by_inner_number('object', $inner_number);

    $result = $getter->getter('object', $temp_result[0]['id']);

    $id = $temp_result[0]['id'];
    $_GET['select_from_post'] = $id;

    $status = $result[0]['is_published'] ? 'Опубликовано' : 'На проверке у администрации';
}


if (isset($_GET['select_from_post']) && empty($_GET['inner_number'])) {
    $select_from_post = $_GET['select_from_post'];
    $result = $getter->getter('object', $select_from_post);
    $inner_number = $result['0']['inner_number'];
        # проверка на несколько жалоб на один и тот же магаз
    $temp_result = $getter->getter_by_inner_number('object', $inner_number);

    $id = $_GET['select_from_post'];

    $status = $result[0]['is_published'] ? 'Опубликовано' : 'На проверке у администрации';
}


#!! конец получения данных для авторизации

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

#!! получение обычных данных

$user_full_name = $getter->get_user_full_name($id);

$comments = $getter->getter('comment_object', $id, true);


#! создание списка comments_imgs для функции GET_PICTURES
if (!!$comments) {
    foreach ($comments as $subarray) {
        $comments_ids[] = $subarray['id'];
    } #array(2) { [0]=> int(67) [1]=> int(68) }

    foreach ($comments_ids as $subarray) {
        $comments_imgs[$subarray] = $getter->get_comment_imgs($subarray);
    };
}

function GET_PICTURES($id, $comments_imgs)
{
    $pictures = array();
    if (isset($comments_imgs[$id])) {
        foreach ($comments_imgs[$id] as $img) {
            $pictures[] = $img['text'];
        }
    }
    return $pictures;
}



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



<!-- Модальное окно -->
<div class="modal fade " id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Форма с двумя столбцами -->
                <h1 class="mt-3 text-center display-5">Редактирование жалобы</h1>

                <form id='edit_form' action="edit_obj.php" method='post'>
                    <div class="row">
                        <div class="col">
                            <label for="edit_title" class=" col-form-label ">Название</label>
                            <input id='edit_title' name='edit_title' type="text" class="form-control" placeholder="Название" value='<?= $result[0]['title']; ?>'>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col pt-4">
                            <label for="edit_text" class=" col-form-label ">Основной текст</label>
                            <textarea id='edit_text' name='edit_text' rows='15' class="form-control" placeholder="Текст"><?php echo trim(strip_tags(str_replace('<br>', "\n", $result[0]['full_text']))); ?></textarea>

                            <input type='hidden' name='id' value='<?= $id ?>'>

                        </div>
                    </div>
                    <!-- Кнопка отправить -->
                    <div class='d-grid gap-2'>
                        <button type="submit" class="btn btn-primary mt-3 ">Отправить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Открытие модального окна при нажатии на кнопку
        $("#openModal").click(function() {
            $("#myModal").modal('show');
        });

        // Закрытие модального окна при нажатии вне формы
        $(document).click(function(event) {
            if (!$(event.target).closest('.modal-content').length && !$(event.target).is('.modal-content')) {
                $("#myModal").modal('hide');


            }

        });


        // обработка
        $("#edit_form").on("submit", function(event) {
            event.preventDefault(); // Предотвращение стандартного поведения формы

            var formData = $(this).serialize(); // Сбор данных формы

            $.ajax({
                url: "edit_obj.php", // URL, на который отправляются данные
                type: "POST", // Метод отправки данных
                data: formData, // Данные формы
                success: function(response) {
                    location.reload(); // Обновить страницу
                    $("#myModal").modal('hide'); // Закрытие модального окна
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Код, который выполняется при ошибке запроса
                    console.log(textStatus, errorThrown);
                }
            })
        })

    });
</script>
<!-- #!!! конец модального окна -->

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
#!search id
echo <<<EOT
    <div class="container text-center pt-5 pb-3 ">

    <form action="" method="get">
    <label class="h5" for="select_from_post">Укажите id жалобы:</label>
    <input type="text" id="select_from_post" name="select_from_post">
    <input type="submit" class='btn btn-primary' value="Поиск">
    </form>
    </div>
    EOT;
#! search end

#!search inner
echo <<<EOT
    <div class="container text-center pt-1 pb-3 ">

    <form action="" method="get">
    <label class="h5" for="inner_number">Внутренний номер :</label>
    <input type="text" id="inner_number" name="inner_number">
    <input type="submit" class='btn btn-primary' value="Поиск">
    </form>
    </div>
    EOT;
#! search inner




#! check if exist
if (!$result[0]) {
    if (isset($_GET['select_from_post'])) {
        echo '<h1 class="mt-5 alert alert-danger container text-center"> Жалоба не найдена </h1>';
        return 0;
    } else {
        return 0;
    }
};
#! check end

#!! delete button && mail button && publish button 
if ($_SESSION['privilege'] == 'super_user') {


    #! delete button
    echo <<<EOT
    <div class='container'>
    
    <form action="discard.php" method="post">
    <input type="hidden" name="id" value="{$_GET['select_from_post']}">


    <div class="d-grid gap-2 col-6 mx-auto">

    <input type="submit" class='col-12 m-1 btn  btn-secondary' value="Отправить на доработку менеджеру">

    </div>

    </form>
    </div>
    EOT;
    #! end delete

    #! mail button
    $agreement = $result[0]['agreement'];
    if ($agreement == 'com1') {
        $mail = 'почта1';
    } else {
        $mail = 'почта2';
    }

    $text = $result[0]['full_text'];
    $escaped_text = htmlspecialchars($text, ENT_QUOTES);
    echo <<<EOT
    <div class='container'>

    <form action="sendMail.php" method="post">
    <input type="hidden" name="id" value="{$_GET['select_from_post']}">
    <input type="hidden" name="title" value="{$result[0]['title']}">
    <input type="hidden" name="text" value="'.$escaped_text.'">
    <input type="hidden" name="mail" value="{$mail}">

    <div class="d-grid gap-2 col-6 mx-auto">

    <input type="submit" class='col-12 m-1  btn  btn-secondary' value="Отправить на почту {$mail}">
    </div>

    </form>
    </div>
    EOT;
    #! end mail

    #! publish button
    if ($result[0]['is_published'] == 0) {
        echo <<<EOT
    <div class='container'>
    <form action="Koroteev_controller.php" method="post">
    <input type="hidden" name="action" value="activate">
    <input type="hidden" name="id" value="{$_GET['select_from_post']}">
    <div class="d-grid gap-2 col-6 mx-auto">
    <input type="submit" class='col-12 m-1  btn  btn-secondary' value="Опубликовать">
    </div>
    </form>
    </div>
    EOT;
        #! end publish button


    }
}

#!! delete button for creator`
if ($manager == $_SESSION['login'] && !!$manager) {

    echo <<<EOT
    <div class='container '>
    
    <form action="discard_simple.php" method="post">
    <input type="hidden" name="id" value="{$_GET['select_from_post']}">
    <input type="hidden" name="action" value="delete">


    <div class="d-grid gap-2 col-6 mx-auto">

    <input type="submit" class='col-12 m-1 btn  btn-secondary' value="Удалить жалобу">

    </div>

    </form>
    EOT;
}
?>



<div class="container p-5">
<?
#!! если уже более 1 жалобы на магазин тогда отобразить их список

if( count($temp_result)>1){
    $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $parsedUrl = parse_url($url);
    $baseUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . '/complain/';
    
    echo <<<EOT

    <div class='px-5 mx-5 py-4 pt-4 mt-5 mb-5  border d-inline-flex flex-column '> Список жалоб на тот же магазин: <div class=' list-group flex-column '> 

    EOT;

    foreach ($temp_result as $subarray) {
        $url = $baseUrl.'getter.php?select_from_post='.$subarray['id'];
        echo "<br> <a class='text-center bg-primary-subtle list-group-item list-group-item-action link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover' href='{$url}'>id = {$subarray['id']}</a> ";
    };
    
    echo '</div></div> <br><br>';
#!! конец
} ?>
    <div class="col-12  ">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php
                // Open a directory, and read its contents

                if (isset($_GET['select_from_post']) and $result) {
                    $id = $_GET['select_from_post'];
                    $dir = "img/" . $id . "/"; // specify the directory where images are stored

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
                                        // if (in_array($file, $imgs)) {
                                        // #!! если файл jpg / png тогда показываем в слайдшоу
                                        echo "<div class='swiper-slide '><img src='" . $dir . $file . "' alt='Image'></div>";
                                        // }
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

    <h1 class='text-center pb-3'>Жалоба на магазин <? echo 'id = ' . $id . ' <br> Внутренний номер = ' . $inner_number ?></h1>
    <div class='col-4 mt-4 p-4 border text-center '>
        <!-- #! отобразить pdf / mp3 / mp4-->
        <? if (isset($_GET['select_from_post']) and $result) {
            $id = $_GET['select_from_post'];
            $dir = "img/" . $id; // specify the directory where images are stored

            // Open a directory, and read its contents
            if (is_dir($dir)) {

                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {
                        // filter out "." and ".."

                        if ($file != "." && $file != "..") {
                            $path = $dir .'/'. $file;
                            $filetype = pathinfo($path, PATHINFO_EXTENSION);


                            if ($filetype == 'pdf' || $filetype == 'mp3' || $filetype == 'mp4' || $filetype == 'docx') {
                                echo "<div>Скачать <a href='" . $path . "' download>" . $file . "</a></div>";
                                $files_not_imgs++;
                            }
                        }
                    }
                    closedir($dh);
                }
            }
            if ($files_not_imgs == 0) {
                echo '<p>Нет файлов во вложении</p>';
            }
        }
        ?>
    </div>

    <div class='col-4 mt-4 p-4 border text-center '>
        <!-- #!! full name -->
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
            <h1 class="text-center display-4 pb-3 pt-3"><?php echo $result[0]['title'] ?></h1>

            <div class="d-flex justify-content-end gap-2">


                <!-- время -->
                <div class="border p-2 mt-2 mb-2 pb-1 pt-2   "><?php echo 'Статус: ' . $status ?> </div>

                <!-- дата -->
                <div class="border p-2 mt-2 mb-2 pb-1 pt-2  "><?php echo 'Дата создания: ' . $result[0]['date_when_created'] ?> </div>

                <!-- btn edit -->
                <button type="button" class=" btn btn-sm rounded-0 btn-secondary p-2 mt-2 mb-2 pb-1 pt-2 " id="openModal">Редактировать </button>

            </div>
            <div class="border p-3 ">
                <p class=" text-break fs-4 m-5 "><?php echo $result[0]['full_text']; ?></p>
            </div>

        </div>
    </div>




</div>

<div class="container py-5">
    <div class="row">
        <div class="col-12 border p-5">
            <div class="comments ">
                <h2 class="mb-4 display-4 text-center">Комментарии</h2>




                <?php
                // Определите количество комментариев на странице и общее количество комментариев 
                #!!! limit
                $limit = 5;
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                $start = ($page - 1) * $limit;

                // Выведите комментарии для текущей страницы
                $items = array_slice($comments, $start, $limit);

                //#!!! pagination 
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
                //#!!! pagination end

                foreach ($items as $inside_array) {
                    if ($_SESSION['login'] == $inside_array['user_login']) {
                        /* отображаем кнопку Удалить Комментарий только если чел является создателем комментария */
                        $delete_comment = <<<EOT
                            <form 
                            
                            method="post"  id="commentForm_delete_{$inside_array['id']}" action='add_comment.php' enctype="multipart/form-data">
                            <input type="hidden" name="action" value="delete_comment">
                            <input type="hidden" name="id_company" value="{$id}">

                            <input type="hidden" name="id_comment" value="{$inside_array['id']}">
                            <button type="submit" class="btn btn-secondary">Удалить Комментарий</button>

                            </form>

                            EOT;
                    } else {
                        $delete_comment = '';
                    };

                    if ($inside_array['user_login'] == 'Гость'  and $_SESSION['privilege'] == 'super_user') {
                        $delete_comment = <<<EOT
                        <form 
                        
                        method="post"  id="commentForm_delete_{$inside_array['id']}" action='add_comment.php' enctype="multipart/form-data">
                        <input type="hidden" name="action" value="delete_comment">
                        <input type="hidden" name="id" value="{$id}">

                        <input type="hidden" name="id" value="{$inside_array['id']}">
                        <button type="submit" class="btn btn-primary">Удалить Комментарий</button>

                        </form>

                        EOT;
                    }


                    $current_pic_array = GET_PICTURES($inside_array['id'], $comments_imgs);

                    $imgTags = array();
                    foreach ($current_pic_array as $picture) {
                        $imgTags[] = "<a href='img/{$id}/{$inside_array['id']}/{$picture}' data-lightbox='image-1'><img class='pb-3' style='max-width:300px' src='img/{$id}/{$inside_array['id']}/{$picture}'></a>";
                    }
                    $imgTagsString = implode('', $imgTags);

                    echo <<<EOT
                    <div class="comment d-flex mb-4">
                        <div class="flex-grow-1 ms-3">
                            <h4>{$inside_array['user_login']}</h4>
                            <p>Дата: {$inside_array['date_when_created']}</p>
                            <p class='text-break'>{$inside_array['text']}</p>

                            <p> {$comments_imgs[$inside_array['id']]['text']}   </p>

                            
                            {$imgTagsString}
                            
                            {$delete_comment}


                        </div>
                    </div>
                    EOT;
                }


                ?>



            </div>
            <!-- блок комментариев -->
            <form method="post" class='col-12' id=commentForm action='add_comment.php' enctype="multipart/form-data">

                <div class="mb-3">
                    <label for="commentText" class="form-label">Текст комментария</label>
                    <textarea class="form-control" id="commentText" name='commentText' rows="3" required></textarea>
                </div>
                <!-- #!!!                -->
                <div class="mb-3">
                    <label for="img[]" class="form-label ">Загрузить изображения (img,jpg,jpeg)</label>

                    <input class="form-control" type="file" id="img[]" name='img[]' multiple accept=".png,.jpg,.jpeg,.docx,.mp3,.mp4,.pdf">
                </div>
                <!-- Скрытое поле для передачи уникального имени в POST -->
                <input type="hidden" name="id_company" value="<?php echo $_GET['select_from_post']; ?>">
                <input type="hidden" name="from_who" value="<?php echo $_SESSION['login']; ?>">
                <input type="hidden" name="action" value="add_comment">


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
    $(document).ready(function() {
        $("#commentForm").on("submit", function(event) {
            event.preventDefault(); // Остановить отправку формы

            var formData = new FormData(this);
            var totalSize = 0;
            var allowedFormats = ["image/png", "image/jpg", "image/jpeg"];
            var isValid = true; // Флаг для проверки валидности файлов

            // Проверка размера и формата файлов
            $.each($('input[name="img[]"]')[0].files, function(i, file) {
                totalSize += file.size;
                if ($.inArray(file.type, allowedFormats) == -1) {
                    alert("Недопустимый формат файла: " + file.name);
                    isValid = false; // Установка флага в false
                    return false;
                }
            });

            // Проверка общего размера файлов (не более 50 МБ)
            if (totalSize > 50 * 1024 * 1024) {
                alert("Общий размер файлов не должен превышать 50 МБ.");
                isValid = false; // Установка флага в false
            }

            // Если файлы невалидны, прерываем выполнение функции
            if (!isValid) {
                return false;
            }

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
    $(document).ready(function() {
        <?php
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
        ?>
    });
</script>

</div>







<? require_once 'bottom.php' ?>