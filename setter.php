<?php

#! permission
session_start();
$users = ['super_user', 'manager'];
if (!in_array($_SESSION['privilege'], $users)) {
    // If not, redirect to login.php
    $_SESSION['notification'] = 'bad';
    header('Location: login.php');
    exit;
}
#! end

require_once 'head.php';
require_once 'database.php';


// $validation_msg = '<h1> Жалоба уже есть в базе активных жалоб </h1>';
// if($obj_check[0]){
// echo '<h1> Жалоба уже есть в базе </h1>';
// }
// else{echo '<h1> в базе нет, объект уникален </h1>';}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // var_dump($_FILES);
    // die();
    $inner_number = $_POST['inner_number'];
    #!! main insert
    //#! отключение проверки $obj_check = $getter->get('object', $inner_number);
    // #!! проверка на существующие записи 
    if (!$obj_check[0]) {


        $title = $_POST['title'];
        $full_text = $_POST['full_text'];
        $date_when_created = date('Y-m-d_H:i:s');
        $user_login = $_SESSION['login'];
        $agreement = $_POST['agreement'];
        $service = $_POST['service'];




        #??
        // #! Загрузка данных в таблицу object
        $stmt = $pdo->prepare("INSERT INTO object (inner_number, title, full_text, date_when_created, user_login,agreement,service) VALUES (?, ?, ?, ?,?,?,?)");
        $stmt->execute([
            $inner_number,
            $title,
            $full_text,
            $date_when_created,
            $user_login,
            $agreement,
            $service
        ]);

        // Получение ID последнего добавленного объекта
        $obj_id = $pdo->lastInsertId('object');



        // Загрузка изображений в таблицу img

        if ($_FILES['img']['name'][0] && !empty($_FILES['img']['tmp_name']) ) {


            // Создание папки для изображений
            $target_dir = 'img/' . $obj_id;
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            foreach ($_FILES['img']['tmp_name'] as $key => $tmp_name) {

                if ($_FILES['img']['error'][$key] == 0) {
                    $img = $date_when_created . '_' . $_FILES['img']['name'][$key];
                    $target = $target_dir . '/' . $img;

                    // Перемещение загруженного файла в целевой каталог
                    if (move_uploaded_file($tmp_name, $target)) {
                        $stmt = $pdo->prepare("INSERT INTO img (obj_id, img, date_when_created, inner_number) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$obj_id, $img, $date_when_created, $inner_number]);
                    }
                }
            }
        }
        echo '<h1 class="alert alert-success container text-center"> Жалоба была создана </h1>';
    } else {
        echo '<h1 class="alert alert-danger container text-center"> В базе уже есть жалоба на этот магазин </h1>';
    }
    #??
}

?>

<?
// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $service = $_POST['service'];
//     $agrement = $_POST['agrement'];
//     //print_r($agrement);
//     // Теперь вы можете использовать $service и $agrement
// }
?>








<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="mt-3 text-center">Создание жалобы</h1>

            <form id='form_to_get' class="form_to_get  pt-5 pb-5" method="post">
                <label for="Get_data_inner" class=" ">Внутренний номер (Авто заполнение):</label>
                <input type="text" id="Get_data_inner" name="Get_data_inner" placeholder="">
                <input type="button" id="submit__" value="Заполнить форму">
            </form>
            <!-- <p class=" my-3">Внимание! К письму обязательно прикреплять суть разговора с ДМ и СПВ, а так же накладные и фотоотчёты!</p> -->

            <!-- <p class="alert alert-warning my-3">Дополните недостающую информацию</p> -->
            <form id='myForm' action="#" method="post" enctype="multipart/form-data" class="row pb-5">
                <div class="col-6 ">
                    <label for="external_number">Внешний номер магазина</label>
                    <input class="form-control mb-1" name='external_number' type="text" value="">
                </div>
                <div class="col-6">
                    <label for="type_store">Тип магазина</label>
                    <input name="type_store" class="form-control mb-1" required value="">
                </div>
                <div class="col-6">
                    <label for="rf">Субъект РФ</label>
                    <input name="rf" class="form-control mb-1" required value="">
                </div>
                <div class="col-6">
                    <label for="adress">Адрес</label>
                    <input name="adress" class="form-control mb-1" type="text" required value="">
                </div>
                <div class="col-6">
                    <label for="fiodm">ФИО ДМ</label>
                    <input name="fiodm" class="form-control mb-1" type="text" required value="">
                    <label for="tdm">Телефон ДМ</label>
                    <input name="tdm" class="form-control mb-1" type="text" required value="">
                    <label for="rdm">Суть разговора с ДМ</label>
                    <input name="rdm" class="form-control mb-1" type="text" required>
                </div>
                <div class="col-6">
                    <label for="fiosp">ФИО СПВ</label>
                    <input name="fiosp" class="form-control mb-1" type="text" required value="">
                    <label for="tsp">Телефон СПВ</label>
                    <input name="tsp" class="form-control mb-1" type="text" required value="">
                    <label for="rsp">Суть разговора с СПВ</label>
                    <input name="rsp" class="form-control mb-1" type="text" required>
                </div>
                <div class="mb-3 row pt-2">
                    <label for="inner_number" class="col-sm-2 col-form-label">Внутренний номер:</label>
                    <div class="col-sm-10">
                        <input type="number" min=1 class="form-control" id="inner_number" name="inner_number" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="title" class="col-sm-2 col-form-label">Название жалобы:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="full_text" class="col-sm-2 col-form-label">Текст жалобы:</label>
                    <div class="col-sm-10">
                        <textarea id="full_text" name="full_text" class="form-control" rows="5" required></textarea>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="img" class="col-sm-2 col-form-label">Приложите файлы - разрешены форматы: img,png,jpg,jpeg,docx,mp3,mp4,pdf</label>
                    <div class="col-sm-10">
                        <input type="file" id="img" name="img[]" multiple class="form-control" accept=".png,.jpg,.jpeg,.docx,.mp3,.mp4,.pdf">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="service" class="col-sm-2 col-form-label">Обслуживает (администратор будет выбран по этому полю)</label>
                    <div class="col-sm-10">
                        <select name="service" id='service' class='form-control' required>
                            <option value="">Выберите...</option>
                            <option value='com2'>com2</option>
                            <option value='com1'>com1</option>
                        </select>
                    </div>
                </div>
                <div class='mb-3 row'>
                    <label for='agreement' class='col-sm-2 col-form-label'>Договор С</label>
                    <div class='col-sm-10'>
                        <select name='agreement' id='agreement' class='form-control' required>
                            <option value=''>Выберите...</option>
                            <option value='com2'>com2</option>
                            <option value='com1'>com1</option>
                        </select>
                    </div>
                </div>
                <div class="d-grid gap-2 col-6 mx-auto">
                    <button type='submit' class='btn btn-primary btn-lg'>Отправить жалобу</button>
                </div>
            </form>





            <script>
                $(document).ready(function() {
                    $("#submit__").click(function(event) {
                        event.preventDefault();

                        var formData = $("#Get_data_inner").val(); // Получаем данные из формы

                        $.ajax({
                            url: 'php_modules/crest/auto_fill.php',
                            type: 'POST',
                            data: {
                                Get_data_inner: formData
                            }, // Передаем данные из формы
                            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                            success: function(response) {
                                var data = JSON.parse(response);
                                console.log(data);
                                // теперь вы можете использовать data как JSON-объект
                                // например, выведите значение 'адресс'
                                console.log(data['адресс']);

                                $('input[name="external_number"]').val(data["внешний"]);
                                $('input[name="type_store"]').val(data["тип магазина"]);
                                $('input[name="rf"]').val(data["субъект рф"]);
                                $('input[name="adress"]').val(data["адресс"]);
                                $('input[name="fiodm"]').val(data["фио дм"]);
                                $('input[name="tdm"]').val(data["тел дм"]);
                                $('input[name="fiosp"]').val(data["фио св"]);
                                $('input[name="tsp"]').val(data["тел св"]);
                                $('input[name="inner_number"]').val(data["внутренний"]);

                                data['обслуживает'] == "64954" ? $('select[id="service"]').val('com1.') : $('select[id="service"]').val('com2');

                                data['договор с'] == "64954" ? $('select[id="agreement"]').val('com1.') : $('select[id="agreement"]').val('com2');

                            }
                        });
                    });
                });
            </script>




            <script>
                document.getElementById('img').addEventListener('change', function() {
                    for (var i = 0; i < this.files.length; i++) {
                        var file = this.files[i];
                        var filetype = file.name.split('.').pop().toLowerCase();
                        if (['png', 'jpg', 'jpeg', 'docx', 'mp3', 'mp4', 'pdf'].indexOf(filetype) == -1) {
                            alert('Недопустимый тип файла: ' + file.name);
                            this.value = '';
                            return;
                        }
                    }
                });


                var labels = {
                    'inner_number': '<br>Внутренний номер',

                    'title': '<br>Название жалобы',
                    'mname': '<br>ФИ менеджера',
                    'external_number': '<br>Внешний номер магазина',
                    'type_store': '<br>Тип магазина',
                    'rf': '<br>Субъект РФ ',
                    'adress': '<br>Адрес ',
                    'fiodm': '<br>ФИО ДМ ',
                    'tdm': '<br>Телефон ДМ ',
                    'rdm': '<br>Суть разговора с ДМ ',
                    'fiosp': '<br>ФИО СПВ ',
                    'tsp': '<br>Телефон СПВ ',
                    'rsp': '<br>Суть разговора с СПВ',
                    'service': '<br>Обслуживает',
                    'agrement': '<br>Договор С',
                    'img[]': '<br> Фотографии',
                };

                document.getElementById('myForm').addEventListener('submit', function(event) {
                    // event.preventDefault(); // Отменить отправку формы

                    var output = '';
                    var inputs = this.getElementsByTagName('input');
                    for (var i = 0; i < inputs.length; i++) {
                        if (inputs[i].name != 'img[]' && inputs[i].name != 'title' && inputs[i].name != 'inner_title' && inputs[i].name != 'inner_number') {
                            var label = labels[inputs[i].name] || inputs[i].name;

                            output += label + ' = ' + inputs[i].value;
                        }
                    }

                    document.getElementById('full_text').value = output + ' <br><b> Основной текст:</b><br>' + document.getElementById('full_text').value;
                });
            </script>


            <? require_once 'bottom.php';
