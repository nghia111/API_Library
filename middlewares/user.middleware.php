<?php
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');

    require "../../utils/crypto.php";
    require "../../utils/jwt.php";

    function getUsersValidator() {
            $errors = [];
            
         // Kiểm tra $id
            if (isset($_GET['id']) && !is_numeric($_GET['id'])) {
                $errors[] = "ID phải là một số.";
            }
        
            // Kiểm tra $name
            if (isset($_GET['name'])) {
                if (!is_string($_GET['name']) || empty($_GET['name'])) {
                    $errors[] = "Name phải là một chuỗi và không được để trống.";
                }
            }
            
            // Kiểm tra $email
            if (isset($_GET['email'])) {
                if (!filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Email không hợp lệ.";
                }
            }
  
            if (!empty($errors)) {
                    http_response_code(422);
                    echo json_encode(array("error:"=> $errors)) ;
                
                return false;
            }
            return true;
    }

    function loginValidator(){
            $db = new Database();
            $conn = $db -> connect();
            $errors = [];
            if(isset($_POST['email']) && isset($_POST['password'])){
                if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                    $errors[] = "email không hợp lệ";
                }
                else if(strlen($_POST['password']) < 6){
                    $errors[] = "password phải từ 6 ký tự trở lên";
                }
                else {
                    $query = "SELECT * FROM USERS WHERE email = :email";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':email',$_POST['email']);
                    $stmt->execute();
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
           
                    if($user && compare_password( $_POST['password'],$user['password'])){
                        //xác thực thành công

                        $query = "SELECT * FROM refresh_tokens WHERE  user_id = :user_id";
                        $stmt = $conn->prepare($query);
                        $stmt->bindParam(':user_id',$user['id']);
                        $stmt->execute();
                        $refresh_token = $stmt->fetch(PDO::FETCH_ASSOC);
                        if(!$refresh_token){
                            // thành công
                        $_REQUEST['user'] = $user;

                        }else{
                            $errors[] = "bạn đã login rồi";
                        }


                    }else{
                        $errors[] = "email hoặc password sai"; 
                    }
                }
            }
            else{
               $errors[] = "yêu cầu có email và password "; 
            }
            if (!empty($errors)) {
                http_response_code(422);
                echo json_encode(array("error:"=> $errors)) ;
                $conn = null;
            return false;
         }
            $conn = null;
            return true;        
    }
        
    function accessTokenValidator(){
        try{
        // Kiểm tra xem Access Token có được gửi lên hay không
        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            http_response_code(401);
            echo json_encode(array("errors" => "yêu cầu có access_token để xác thực"));
            return false;
        }
           // Lấy giá trị của Access Token từ header
           $accessToken = $_SERVER['HTTP_AUTHORIZATION'];
           // Kiểm tra xem Access Token có đúng định dạng hay không
           if (!preg_match('/Bearer\s(\S+)/', $accessToken, $matches)) {
               http_response_code(401);
               echo json_encode(array("errors" => "access_token chưa đúng định dạng"));
               return false;
           }
           // Lấy giá trị thực tế của Access Token
            $accessToken = $matches[1];
            $decodeAuthorization = verifyToken($accessToken,"dayLaKEyAcCes5ToKEn123456123123");
            if(!$decodeAuthorization){
                http_response_code(401);
                return false;
            }
            $_REQUEST['decode_authorization'] = $decodeAuthorization;
            return true;
        }catch(Exception $e){
            echo json_encode(array("errors"=>"token hết hạn hoặc không hợp lệ: " . $e->getMessage()));
            return false;
        }

    }

    function refreshTokenValidator(){

        try{
            // Kiểm tra xem Refresh Token có được gửi lên hay không
            if (!isset($_POST['refresh_token'])) {
                http_response_code(401);
                echo json_encode(array("errors" => "yêu cầu có refresh_token để xác thực"));
                return false;
            }
               // Lấy giá trị của Refresh Token từ body
               $refreshToken = $_POST['refresh_token'];
               // Kiểm tra xem Access Token có đúng định dạng hay không
               if (!preg_match('/Bearer\s(\S+)/', $refreshToken, $matches)) {
                   http_response_code(401);
                   echo json_encode(array("errors" => "refresh_token chưa đúng định dạng"));
                   return false;
               }
               // Lấy giá trị thực tế của refresh Token
                $refresh_token = $matches[1];

               //kiểm tra có trong db không
                $db = new Database();
                $conn = $db -> connect();
        
                $query = "SELECT * FROM refresh_tokens WHERE value = :value";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':value',$refresh_token);
                $stmt->execute();
                $isExist = $stmt->fetch(PDO::FETCH_ASSOC);
                if(!$isExist){
                    http_response_code(401);
                    echo json_encode(array("errors" => "refresh_token này không tồn tại trong db (bạn chưa đăng nhập)"));
                    $conn = null;
                    return false;
                }




                $decode_refreshToken = verifyToken($refresh_token,"CAiNaYLARefResHTOkenKeY12344321242123");
                if(!$decode_refreshToken){
                    http_response_code(401);
                    return false;
                }
                $_REQUEST['decode_refreshToken'] = $decode_refreshToken;
      

                $conn = null;
                return true;
            }catch(Exception $e){
                echo json_encode(array("errors"=>"token hết hạn hoặc không hợp lệ: " . $e->getMessage()));
                $conn = null;
                return false;
            }
    
    }
     
    function isAdmin(){
        if($_REQUEST['decode_authorization']){
            if($_REQUEST['decode_authorization']->role=='AD'){
            return true;
        }
            else{
                http_response_code(401);
                echo json_encode(array("errors" => "yêu cầu quyền admin"));
                return false;
            }
        }else{
            http_response_code(401);
            echo json_encode(array("errors" => "yêu cầu đăng nhập"));

        }
    }

    function registerValidator(){
        // Kiểm tra xem req body có gửi lên các trường name, email, password, confirm_password hay không
        
        $errors = [];

        if (!isset($_POST['name']) || empty($_POST['name']) ||
        !isset($_POST['email']) || empty($_POST['email']) ||
        !isset($_POST['password']) || empty($_POST['password']) ||
        !isset($_POST['confirm_password']) || empty($_POST['confirm_password'])
        ) {
            http_response_code(422);
            echo json_encode(array("error:"=> "vui lòng điền đầy đủ thông tin: name, email, password, confirm_password")) ;
            return false;
        }

        // Kiểm tra định dạng email
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email không hợp lệ.";
        }

        // Kiểm tra độ dài mật khẩu
        $password = $_POST['password'];
        if (strlen($password) < 6) {
            $errors[] = "Mật khẩu phải chứa ít nhất 6 kí tự.";
        }

        // Kiểm tra khớp mật khẩu và xác nhận mật khẩu
        $confirmPassword = $_POST['confirm_password'];
        if ($password !== $confirmPassword) {
            $errors[] = "Mật khẩu không khớp.";
        }

        if (!empty($errors)) {
            // lỗi validate 
            http_response_code(422);
            echo json_encode(array("error:"=> $errors)) ;
            return false;
        }else{
            //kiểm tra người dùng đã tồn tại trong db chưa
            $db = new Database();
            $conn = $db -> connect();
            
            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':email',$email);
            $stmt->execute();
            $isExist = $stmt->fetch(PDO::FETCH_ASSOC);
            $conn = null;
            if($isExist){
                http_response_code(409);
                echo json_encode(array("error:"=> "Email đã tồn tại")) ;
                return false;
            }
            return true;

        }



    }

    function updateUserValidator(){
        $errors=[];

        if (!isset($_POST['name']) || empty($_POST['name'])) {
            $errors[] = "yêu cầu truyền name lên req body ";
        }
        if(!isset($_GET['id'])|| empty($_GET['id'])   ){
            $errors[] = "yêu cầu truyền id lên req query ";
        }
        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode(array("error:"=> $errors)) ;
            return false;
        }
        return true;


    }

    function deleteUserValidator(){
        if(!isset($_GET['id'])|| empty($_GET['id'])   ){
            http_response_code(422);
            echo json_encode(array("error:"=> "yêu cầu truyền id lên req query ")) ;
            return false;
        }
        return true;
    }



?>
