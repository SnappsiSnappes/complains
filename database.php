<?php
$host = 'db';
$db   = 'Complains';
$user = 'root';
$pass = 'root';
$port = 3306;

// $dsn = "mysql:host=".$host.';dbname='.$db;
$dsn = 'mysql:host='.$host.";port={$port}".';dbname='.$db;

$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass,$opt);

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
    public function get($table, $inner_number)
    {
        #!! говнокод
        
        if ($table == 'object') {
            $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE inner_number = :inner_number and is_published = 0");
        } else {
            $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE inner_number = :inner_number");
        }; #! флаг is published . if - для магазинов , else - для img фотографий
        $stmt->bindParam(':inner_number', $inner_number);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getter($table, $inner_number)
    {
        
        if ($table == 'object') {
            $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE inner_number = :inner_number");
        } else {
            $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE inner_number = :inner_number");
        }; #! флаг is published . if - для магазинов , else - для img фотографий
        $stmt->bindParam(':inner_number', $inner_number);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function get_user_full_name($inner_number)
    {

            $stmt = $this->pdo->prepare("SELECT full_name,login FROM users where login in (select user_login from object where inner_number =:inner_number)");
            $stmt->bindParam(':inner_number', $inner_number);

/*         else {
            $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE inner_number = :inner_number");
        }; #! флаг is published . if - для магазинов , else - для img фотографий */
        $stmt->execute();
        return $stmt->fetch();
    }

    public function get_company1_objects()
    {

            $stmt = $this->pdo->prepare("SELECT * FROM object WHERE is_published = 0 and service = 'company1'");
 #! флаг is published . if - для магазинов , else - для img фотографий
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function get_company2_objects()
    {

            $stmt = $this->pdo->prepare("SELECT * FROM object WHERE is_published = 0 and service = 'company2'");
 #! флаг is published . if - для магазинов , else - для img фотографий
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function get_Active_objects()
    {

            $stmt = $this->pdo->prepare("SELECT * FROM object WHERE is_published = 1 ");
 #! флаг is published . if - для магазинов , else - для img фотографий
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function get_personal_objects($user_login)
    {

        $stmt = $this->pdo->prepare("SELECT * FROM object 
            WHERE user_login = :user_login ");
 #! флаг is published . if - для магазинов , else - для img фотографий
        $stmt->bindParam(':user_login', $user_login);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function publish($inner_number)
    {

        $stmt = $this->pdo->prepare("UPDATE object set is_published = 1 WHERE inner_number = :inner_number ");
        $stmt->bindParam(':inner_number', $inner_number);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function delete($inner_number)
    {

        $target_dir = 'img/' . $inner_number;
        function deleteDirectory($dirPath) {
          if (! is_dir($dirPath)) {
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
      
      try
      {
      deleteDirectory($target_dir);
      } catch (Exception $e) {}

        $stmt = $this->pdo->prepare("DELETE FROM object WHERE inner_number = :inner_number ");
        $stmt->bindParam(':inner_number', $inner_number);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function send_msg($from_user,$to_user,$date , $text){
        $count = $this->get_msg($to_user);
        $count = count($count);
        if($count >= 25) {
            $this->delete_last_msg($to_user);
        }
        $stmt = $this->pdo->prepare("INSERT INTO msg (from_user, to_user, date_when_sent, text) VALUES (?, ?, ?, ?)");
        $stmt->execute([$from_user, $to_user, $date, $text]);
        return $stmt->fetch();
    }

    public function get_msg($to_user, $get_viewed=false, $kill_viewed=false){
        if ($kill_viewed==true){
            $stmt = $this->pdo->prepare("UPDATE msg set viewed = 1  where to_user = :to_user and viewed = 0" );
            $stmt->bindParam(':to_user', $to_user);
            $stmt->execute();
            return $stmt->fetchAll();
        }

        if ($get_viewed==true){
            $stmt = $this->pdo->prepare("SELECT * from msg where to_user = :to_user and viewed = 0 ORDER BY  date_when_sent DESC" );
            $stmt->bindParam(':to_user', $to_user);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        $stmt = $this->pdo->prepare("SELECT * from msg where to_user = :to_user ORDER BY  date_when_sent DESC" );
        $stmt->bindParam(':to_user', $to_user);
        $stmt->execute();
        return $stmt->fetchAll();

    }

    public function delete_last_msg($to_user){
        $stmt = $this->pdo->prepare("DELETE FROM msg WHERE to_user = :to_user ORDER BY date_when_sent DESC LIMIT 1");
        $stmt->bindParam(':to_user', $to_user);
        $stmt->execute();
    }
    

    public function add_comment($inner_number,$text,$from_who,$img=false)
    {
        $date_when_created = date('Y-m-d_H:i:s', strtotime('+3 hours'));

        $stmt = $this->pdo->prepare("INSERT INTO comment_object (inner_number, user_login, text, date_when_created) VALUES (?, ?, ?, ?)");
        $stmt->execute([$inner_number, $from_who, $text, $date_when_created]);


    }

    public function get_comments($inner_number)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM comment_object WHERE inner_number = :inner_number");
        $stmt->bindParam(':inner_number', $inner_number);
        $stmt->execute();

    }

    public function delete_comment($id){
        $stmt = $this->pdo->prepare("DELETE FROM comment_object WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

    }

    public function validate_user($login){
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE login = :login");
        $stmt->bindParam(':login', $login);
        $stmt->execute();
        return $stmt->fetchAll();

    }

}

$getter = new Getter($pdo);
