<?php
$host = 'db';
$db   = 'Complains';
$user = 'root';
$pass = 'root';
$port = 3306;

// $dsn = "mysql:host=".$host.';dbname='.$db;
$dsn = 'mysql:host=' . $host . ";port={$port}" . ';dbname=' . $db;

$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);

$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


// while($row = $stmt->fetchall(PDO::FETCH_ASSOC)){
//     echo $row['id']. ' '. $row['vegetables_weight']. '<br>';
// }


class Getter
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    public function get($table, $id)
    {

        if ($table == 'object') {
            $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE id = :id and is_published = 0");
        } else {
            $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE obj_id = :id");
        }; #! флаг is published . if - для магазинов , else - для img фотографий
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getter($table, $id , $desc = false)
    {
                #!!! говнокод !!! очень плохой код!!

        if ($desc == true) { $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE obj_id_fk = :id order by date_when_created DESC");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetchAll();
        
        };


        if ($table == 'object') {
            $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE id = :id");
        } else {
            
            $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE obj_id_fk = :id");
        }; #! флаг is published . if - для магазинов , else - для img фотографий
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function getter_by_inner_number($table, $inner_number)
    {

        if ($table == 'object') {
            $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE inner_number = :inner_number");
        } 
        $stmt->bindParam(':inner_number', $inner_number);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    

    public function get_user_full_name($id)
    {

        $stmt = $this->pdo->prepare("SELECT full_name,login FROM users where login in (select user_login from object where id =:id)");
        $stmt->bindParam(':id', $id);

        #! флаг is published . if - для магазинов , else - для img фотографий */
        $stmt->execute();
        return $stmt->fetch();
    }

    public function get_com1_objects()
    {

        $stmt = $this->pdo->prepare("SELECT * FROM object WHERE is_published = 0 and service = 'com1'");
        #! флаг is published . if - для магазинов , else - для img фотографий

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function get_com2_objects()
    {

        $stmt = $this->pdo->prepare("SELECT * FROM object WHERE is_published = 0 and service = 'com2' ORDER BY date_when_created DESC");
        #! флаг is published . if - для магазинов , else - для img фотографий

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function get_Active_objects()
    {

        $stmt = $this->pdo->prepare("SELECT * FROM object WHERE is_published = 1 ORDER BY date_when_created DESC");
        #! флаг is published . if - для магазинов , else - для img фотографий

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function get_personal_objects($user_login)
    {

        $stmt = $this->pdo->prepare("SELECT * FROM object 
            WHERE user_login = :user_login ORDER BY date_when_created DESC");
        #! флаг is published . if - для магазинов , else - для img фотографий
        $stmt->bindParam(':user_login', $user_login);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function publish($id)
    {

        $stmt = $this->pdo->prepare("UPDATE object set is_published = 1 WHERE id = :id ");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function delete($id)
    {

        $target_dir = 'img/' . $id;
        function deleteDirectory($dirPath)
        {
            if (!is_dir($dirPath)) {
                throw new InvalidArgumentException("$dirPath must be a directory");
            }
            if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
                $dirPath .= '/';
            }
            $files = glob($dirPath . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    deleteDirectory($file);
                } else {
                    unlink($file);
                }
            }
            rmdir($dirPath);
        }

        try {
            deleteDirectory($target_dir);
        } catch (Exception $e) {
        }

        $stmt = $this->pdo->prepare("DELETE FROM object WHERE id = :id ");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function send_msg($from_user, $to_user, $date, $text)
    {
        $count = $this->get_msg($to_user);
        $count = count($count);
        if ($count >= 25) {
            $this->delete_last_msg($to_user);
        }
        $stmt = $this->pdo->prepare("INSERT INTO msg (from_user, to_user, date_when_sent, text) VALUES (?, ?, ?, ?)");
        $stmt->execute([$from_user, $to_user, $date, $text]);
        return $stmt->fetch();
    }

    public function get_msg($to_user, $get_viewed = false, $kill_viewed = false)
    {
        if ($kill_viewed == true) {
            $stmt = $this->pdo->prepare("UPDATE msg set viewed = 1  where to_user = :to_user and viewed = 0");
            $stmt->bindParam(':to_user', $to_user);
            $stmt->execute();
            return $stmt->fetchAll();
        }

        if ($get_viewed == true) {
            $stmt = $this->pdo->prepare("SELECT * from msg where to_user = :to_user and viewed = 0 ORDER BY  date_when_sent DESC");
            $stmt->bindParam(':to_user', $to_user);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        $stmt = $this->pdo->prepare("SELECT * from msg where to_user = :to_user ORDER BY  date_when_sent DESC");
        $stmt->bindParam(':to_user', $to_user);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function delete_last_msg($to_user)
    {
        $stmt = $this->pdo->prepare("DELETE FROM msg WHERE to_user = :to_user ORDER BY date_when_sent DESC LIMIT 1");
        $stmt->bindParam(':to_user', $to_user);
        $stmt->execute();
    }


    public function add_comment($id, $text, $from_who, $img = null)
    {


        $date_when_created = date('Y-m-d_H:i:s');


        if (!$img) {
            $stmt = $this->pdo->prepare("INSERT INTO comment_object (obj_id_fk, user_login, text, date_when_created) VALUES (?, ?, ?, ?)");
            $stmt->execute([$id, $from_who, $text, $date_when_created]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO comment_object (obj_id_fk, user_login, text, date_when_created) VALUES (?,?, ?, ?)");
            $stmt->execute([$id, $from_who, $text, $date_when_created]);
            #!!___________________________________________
            $obj_id = $this->pdo->lastInsertId('comment_object');



            foreach ($img['name'] as $tmp_name) {

                $img_text = $obj_id . '_' . $tmp_name;
                $stmt = $this->pdo->prepare("INSERT INTO comment_img (comment_object_id,text, date_when_created) VALUES (?,?,?)");
                $stmt->execute([$obj_id, $img_text, $date_when_created]);
            }
            #!!! загрузгка в папку
            # создание папки , если таковой нет
            $target_dir = 'img/' . $id . '/' . $obj_id; # '_comm_' . $obj_id ;
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            foreach ($_FILES['img']['tmp_name'] as  $key => $tmp_name) {
                $img = $obj_id . '_' . $_FILES['img']['name'][$key];
                $target = $target_dir . '/' . $img;
                move_uploaded_file($tmp_name, $target);
            }
            // Загрузка изображений в таблицу img

        }
    }

    public function get_comments($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM comment_object WHERE id = :id ");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function delete_comment($id_company,$id_comment)
    {


        $path = 'img/' . $id_company . '/' . $id_comment . '/';

        function deleteDirectory_2($dirPath)
        {
            if (!is_dir($dirPath)) {
                throw new InvalidArgumentException("$dirPath must be a directory");
            }
            if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
                $dirPath .= '/';
            }
            $files = glob($dirPath . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    deleteDirectory($file);
                } else {
                    unlink($file);
                }
            }
            rmdir($dirPath);
        }

        try {
            deleteDirectory_2($path);
        } catch (Exception $e) {
            
        }

        $stmt = $this->pdo->prepare("DELETE FROM comment_object WHERE id = :id");
        $stmt->bindParam(':id', $id_comment);
        $stmt->execute();
    }

    public function get_comment_imgs($id)
    {
        $stmt = $this->pdo->prepare("SELECT text FROM comment_img WHERE comment_object_id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchall();
    }

    public function validate_user($login)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE login = :login");
        $stmt->bindParam(':login', $login);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function edit_obj($id,$text,$title)
    {

        $stmt = $this->pdo->prepare("UPDATE object set title =:title, full_text=:text where id =:id");
        $stmt->bindParam(':text',$text);
        $stmt->bindParam(':title',$title);
        $stmt->bindParam(':id',$id);

        $stmt->execute();
        return $stmt->fetchAll();

    }
}

$getter = new Getter($pdo);
