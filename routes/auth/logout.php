<?php

require "../../middlewares/user.middleware.php";
require "../../controllers/users.controller.php";

function route_logout() {
    // Kiểm tra phương thức request là POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // thực hiện validate() 
            //nghĩa là người dùng phải đăng nhập vào thì mới gọi được logout
        if(accessTokenValidator()){
            if(refreshTokenValidator()){
                // gọi controller
                logoutController();
            }
        }
    
        
} else {
        // Trả về lỗi không hỗ trợ phương thức
        http_response_code(405);
        echo json_encode(array("message" => "Method Not Allowed"));
    }
    }
    route_logout();
?>