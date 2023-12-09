<?php

class userController {

    private $_method; 
    private $_complement;
    private $_data; 

    function __construct($method, $complement, $data) {
        $this->_method = $method;
        $this->_complement = $complement==null ? 0: $complement;
        $this->_data = $data != 0 ? $data : "";
    }

    public function index() {
        switch ($this->_method) {
            case 'GET':
                if ($this->_method==0) {
                    $user = userModel::getUsers(); 
                    $json = $user;
                    echo json_encode($json, true);
                    return; 
                }else{
                    $user = userModel::getUsers($this->_complement);
                    $json = $user;
                    echo json_encode($json, true);
                    return;
                }
                break;
                case 'POST':
                    $createUser = userModel::createUser($this->generateSalting()); 
                    $json = array(
                        "response:"=>$createUser
                    );
                    echo json_encode($json, true);
                    return;
                case 'UPDATE':
                    $json = array(
                        "ruta"=>"update de user"
                    );
                    echo json_encode($json, true);
                    return;
                case 'DELETE':
                    $json = array(
                        "ruta"=>"delete de user"
                    );
                    echo json_encode($json, true);
                    return;
                default:


        }   
    }

    private function generateSalting(){
            $trimmedData="";
            if(($this->_data != "") || (!empty($this->_data))){
                $trimmedData = array_map('trim', $this->_data);
                $trimmedData['use_pss'] = md5($trimmedData['use_pss']);

                //generando salting para crendenciales

                $identifier = str_replace("$", "ue3", crypt($trimmedData['use_mail'],'ue56'));
                $key = str_replace("$", "ue3", crypt($trimmedData["use_pss"],'ue56'));
                $trimmedData['us_identifier'] = $identifier;
                $trimmedData['us_key'] = $key;
                return $trimmedData;
            }
        }
}

?>