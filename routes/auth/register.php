<?php
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

require "../../middlewares/user.middleware.php";
require "../../controllers/users.controller.php";

function route_register() {
    // Kiểm tra phương thức request là POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // thực hiện validate() 
        if(registerValidator()){
            // gọi controller
            registerController();
        }
    
        
} else {
        // Trả về lỗi không hỗ trợ phương thức
        http_response_code(405);
        echo json_encode(array("message" => "Method Not Allowed"));
    }
    }
    route_register();
?>