
<?php   
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');

    require "../../db.php";
    require "../../models/users.model.php";

    function getUsersController(){
        $db = new Database();

        $connect = $db->connect();
        $user = new User( $connect);
        if(isset($_GET['id'])){ $user->id= $_GET['id'];} 
        if(isset($_GET['name'])){ $user->name= $_GET['name'];} 
        if(isset($_GET['email'])){ $user->email= $_GET['email'];} 
        
        $result = $user->getUsers();
        $num = $result->rowCount();
    
        if($num>0){
            $results_array= [];
            while($row= $result->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $item = array(
                    'id'=> $id,
                    'name'=>$name,
                    'email'=>$email,
                    'role'=>$role,
                );
                array_push($results_array,$item);
            }
            echo json_encode(array("message"=>"Successfully",'data'=>$results_array));
        }
        else{
            echo json_encode(array('message:'=>"không tìm thấy user"));    
        }
    }
  
    function loginController (){
        $db = new Database();

        $connect = $db->connect();
        $user = new User( $connect);
        
        $user->id =  $_REQUEST['user']['id'];
        $user->name = $_REQUEST['user']['name'];
        $user->role = $_REQUEST['user']['role'];

        $result = $user->login();
    

        echo json_encode(array("message"=>"Successfully",'access_token'=>$result[0],
                                                         'refresh_token'=>$result[1],
                                                         'role'=>$result[2]));
      
    }

    function logoutController(){
        $db = new Database();

        $connect = $db->connect();
        $user = new User( $connect);
        
        $result = $user->logout();
    

        echo json_encode($result);
    }

    function registerController(){
        $db = new Database();

        $connect = $db->connect();
        $user = new User( $connect);
        if(isset($_POST['name'])){ $user->name= $_POST['name'];} 
        if(isset($_POST['email'])){ $user->email= $_POST['email'];} 
        if(isset($_POST['password'])){
            $password = hash_password($_POST['password']);
            $user->setPassword($password);
        } 
        $user->role = 'UR';
        $result = $user->register();
    

        echo json_encode($result);
    }

    function updateUserController(){
        $db = new Database();

        $connect = $db->connect();
        $user = new User( $connect);
        $id = $_GET['id'];
        $name = $_POST['name'];
        $user->id = $id;
        $user->name = $name;
        $result = $user->updateName();
        echo json_encode($result);
    }

    function deleteUserController(){
        $db = new Database();

        $connect = $db->connect();
        $user = new User( $connect);
        $id = $_GET['id'];
        $user->id = $id;
        $result = $user->deleteUser();
        echo json_encode($result);
    }

    function getMyProfileController(){
        $db = new Database();

        $connect = $db->connect();
        $user = new User( $connect);
        $user->id = $_REQUEST['decode_authorization']->id;
        
        $result = $user->getMyProfile();
        echo json_encode($result);
        
    }

    

    function refreshTokenController(){
        $db = new Database();

        $connect = $db->connect();
        $user = new User( $connect);
        $user->id=$_REQUEST['decode_refreshToken']->id;
        $user->name=$_REQUEST['decode_refreshToken']->name;
        $user->role=$_REQUEST['decode_refreshToken']->role;
        $result = $user->refreshToken();
        echo json_encode(array("message"=>"Successfully",'access_token'=>$result[0],
                                                         'refresh_token'=>$result[1],
                                                         'role'=>$result[2]));
    }

?>