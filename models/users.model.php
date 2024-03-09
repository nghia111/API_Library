<?php
class User{
    private $conn;
    
    public $id;	
    public $name	;
    public $email	;
    private $password;	
    public $role ;

    //connect db 
    public function   __construct($db){    
        $this->conn = $db;
    }



    public function setPassword($password) {
        $this->password = $password;
    }



    public function getUsers(){
        $this->role = "UR";
        $query_params= [];
        $query = "SELECT id,name,email,role FROM users where 1=1 AND role = :role ";
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

        $offset = ($_GET['page'] - 1) *$_GET['limit'];

        $query= $query."LIMIT ".$_GET['limit']."
                        OFFSET ".$offset." ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role',$this->role);
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
        $expirationAccessTokenTime =strtotime("+5 day");
        $accessToken = signToken($payload,$accessTokenKey,$expirationAccessTokenTime);
        

        $refreshTokenKey ="CAiNaYLARefResHTOkenKeY12344321242123";
        $expirationRefreshTokenTime =strtotime("+100 day");
        $refreshToken = signToken($payload,$refreshTokenKey,$expirationRefreshTokenTime);

        $query = "INSERT INTO refresh_tokens (user_id, value) VALUES (:user_id, :value)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id',$_REQUEST['user']['id'] );
        $stmt->bindParam(':value', $refreshToken);
        $stmt->execute();

        $this->conn = null;
        return [$accessToken,$refreshToken];
    }

        
    public function logout(){

        $refresh_token = preg_split("/\s+/", $_POST['refresh_token'])[1];

        $query = "DELETE FROM refresh_tokens WHERE value= :value;";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':value', $refresh_token);
        $stmt->execute();

        $this->conn = null;
        return array("message"=>"đăng xuất thành công!");
    }
    
    public function register(){


        $this->role = "UR";
        $query = "INSERT INTO users (name,email,password,role) values (:name,:email,:password,:role)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':role', $this->role );
        $stmt->execute();

        $this->conn = null;
        return array("message"=>"đăng ký thành công.");
    }

    public function updateName(){
        $this->role = "UR";
        $query = "UPDATE users SET name= :name, updatedAt = CURRENT_TIMESTAMP()  where id= :id AND role = :role ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':role', $this->role);
        $stmt->execute();
        // lấy số row được cập nhật
        $affectedRows = $stmt->rowCount();
        $this->conn = null;
        if($affectedRows >0){
            return array("message"=>"cập nhật thành công.","name"=>$this->name);
        }else{
            http_response_code(404);
            return array("errors"=>"cập nhật thất bại, user not found");

        }
    }

    public function deleteUser(){
        $this->role = "UR";
        $query = "DELETE FROM users  WHERE  id= :id AND role = :role ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':role', $this->role);
        $stmt->execute();
        // lấy số row được cập nhật
        $affectedRows = $stmt->rowCount();
        $this->conn = null;
        if($affectedRows >0){
            return array("message"=>"xóa user thành công.");
        }else{
            http_response_code(404);
            return array("errors"=>"xóa thất bại, user not found");

        }
    }
    
    public function refreshToken(){
        $payload = array('id'=> $_REQUEST['decode_refreshToken']->id,
        'name'=>$_REQUEST['decode_refreshToken']->name,
        'role'=>$_REQUEST['decode_refreshToken']->role);
        $accessTokenKey = "dayLaKEyAcCes5ToKEn123456123123";
        $expirationAccessTokenTime =strtotime("+5 day");
        $accessToken = signToken($payload,$accessTokenKey,$expirationAccessTokenTime);


        $refreshTokenKey ="CAiNaYLARefResHTOkenKeY12344321242123";
        $expirationRefreshTokenTime = $_REQUEST['decode_refreshToken']->exp;
        $refreshToken = signToken($payload,$refreshTokenKey,$expirationRefreshTokenTime);

        $query = "UPDATE refresh_tokens set value=:value, updatedAt=CURRENT_TIMESTAMP() where user_id=:user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id',$_REQUEST['decode_refreshToken']->id );
        $stmt->bindParam(':value', $refreshToken);
        $stmt->execute();

        $this->conn = null;
        return [$accessToken,$refreshToken];
    }
}
?>