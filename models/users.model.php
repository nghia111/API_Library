<?php
require "../../utils/jwt.php";
class User{
    private $conn;
    
    public $id;	
    public $name	;
    public $email	;
    private $password;	
    public $role;

    //connect db 
    public function   __construct($db){    
        $this->conn = $db;
    }



  



    public function getUsers(){
        $query_params= [];
        $query = "SELECT id,name,email,role FROM users where 1=1 ";
        if($this->id){
            $query = $query."AND id = ?";
            array_push($query_params,$this->id);
        }
        if($this->name){
            $query = $query."AND name =?";
            array_push($query_params,$this->name);
        }
        if($this->email){
            $query = $query."AND email = ?";
            array_push($query_params,$this->email);
        }
        if($this->role){
            $query = $query."AND role = ?";
            array_push($query_params,$this->role);
        }

        $offset = ($_GET['page'] - 1) *$_GET['limit'];

        $query= $query."LIMIT ".$_GET['limit']."
                        OFFSET ".$offset." ";

        $stmt = $this->conn->prepare($query);
        for ($i = 0; $i < count($query_params); $i++) {
            $stmt->bindParam($i + 1, $query_params[$i]);
        }
        $stmt->execute();
        $this->conn = null;
        return $stmt;
    }
    
    public function login(){
        $payload = array('id'=> $_REQUEST['user']['id'],
                         'name'=>$_REQUEST['user']['name'],
                         'role'=>$_REQUEST['user']['role']);
        $accessTokenKey = "dayLaKEyAcCes5ToKEn123456123123";
        $accessToken = signToken($payload,$accessTokenKey,'5 day');
        

        $refreshTokenKey ="CAiNaYLARefResHTOkenKeY12344321242123";
        $refreshToken = signToken($payload,$refreshTokenKey,'100 day');

        $query = "INSERT INTO refresh_tokens (user_id, value) VALUES (:user_id, :value)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id',$_REQUEST['user']['id'] );
        $stmt->bindParam(':value', $refreshToken);
        $stmt->execute();

        $this->conn = null;
        return [$accessToken,$refreshToken];
    }
}






?>