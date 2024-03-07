<?php

require "../../middlewares/user.middleware.php";
require "../../controllers/users.controller.php";

function route_login() {
    // Kiểm tra phương thức request là POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // thực hiện validate() 
        if(loginValidator()){
            // gọi controller
            loginController();
        }
    
        
} else {
        // Trả về lỗi không hỗ trợ phương thức
        http_response_code(405);
        echo json_encode(array("message" => "Method Not Allowed"));
    }
    }
    route_login();
?>