<?php
    require_once "ConDB.php";
    
    class userModel {

        static public function createUser($data){         
            $cantMail= self::getMail($data["use_mail"]);

            if ($cantMail == 0){
                $query="INSERT INTO users (use_id, use_mail, use_pss, use_dataCreate, us_identifier, us_key, us_status) VALUES (NULL,:use_mail,:use_pss,:use_dataCreate,:us_identifier,:us_key,:us_status)";
                $status = "0"; 
                $stament = Connection::connection()->prepare($query);
                $stament->bindParam(":use_mail", $data["use_mail"], PDO::PARAM_STR);
                $stament->bindParam(":use_pss", $data["use_pss"], PDO::PARAM_STR);
                $stament->bindParam(":use_dataCreate", $data["use_dataCreate"], PDO::PARAM_STR);
                $stament->bindParam(":us_identifier", $data["us_identifier"], PDO::PARAM_STR);
                $stament->bindParam(":us_key", $data["us_key"], PDO::PARAM_STR);
                $stament->bindParam(":us_status", $status, PDO::PARAM_STR);
                $message = $stament->execute() ? "OK" : Connection::connection()->errorInfo();
                $stament->closeCursor();
                $stament = null;
                $query="";
                
            } else{
                $message = "Usuario ya registrado"; 
            }

            return $message;

        }
        
        static private function getMail($mail){
            $query = ""; 
            $query="SELECT use_mail FROM users WHERE use_mail='$mail';";
            $stament = Connection::connection()->prepare($query);
            $stament->execute();
            $result=$stament->rowCount();
            return $result; 
        }

        //Trae todos los usuarios

        static public function getUsers($id){
            $query = ""; 
            $id = is_numeric($id) ? $id : 0; 
            $query="SELECT use_id, use_mail, use_dataCreate FROM users";
            $query.= ($id > 0) ? " WHERE users.use_id = '$id' AND " : "";
            $query.= ($id > 0) ? " us_status='1'" : "WHERE use_status = '1'";
            //echo $query
            $stament = Connection::connection()->prepare($query);
            $stament->execute();
            $result=$stament->fetchAll(PDO::FETCH_ASSOC);
            return $result; 
        }

        //Login 

        static public function login ($data){

            $query = ""; 
            $user = $data['use_mail'];
            $pss = md5($data['use_pss']);
            if (!empty($user) && !empty($pss)) {
                $query="SELECT us_identifier, us_key, use_id FROM users WHERE use_mail='$user' and use_pss= '$pss' and us_status = '1'"; 
                $stament = Connection::connection()->prepare($query);
                $stament->execute();
                $result=$stament->fetchAll(PDO::FETCH_ASSOC);
                return $result; 
            }else {
                $message = array (
                    "COD" => "001",
                    "MENSAJE" => (" ERROR EN CREDENCIALES")
                );
            }

    }

    static public function getUserAuth() {

        $query=""; 
        $query="SELECT us_identifier, us_key FROM users WHERE us_status = '1';";
        $stament = Connection::connection()->prepare($query);
        $stament->execute();
        $result=$stament->fetchAll(PDO::FETCH_ASSOC);
        return $result; 
        
    }

}
?>