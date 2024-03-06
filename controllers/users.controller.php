
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
        if(isset($_GET['role'])){ $user->role= $_GET['role'];} 
        
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
        
        $result = $user->login();
    

        echo json_encode(array("message"=>"Successfully",'access_token'=>$result[0],
                                                         'refresh_token'=>$result[1]));
      
    }









?>