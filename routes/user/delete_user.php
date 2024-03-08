<?php
    //api của admin

    require "../../middlewares/user.middleware.php";
    require "../../controllers/users.controller.php";

    function route_delete_users() {
        // Kiểm tra phương thức request là GET
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // lấy data từ req.query
     
        // thực hiện validate() 
        if(accessTokenValidator()){  
            if(isAdmin()){
                if( deleteUserValidator()){
                    // gọi controller
                    deleteUserController();
                }
            }
        }
    } else {
            // Trả về lỗi không hỗ trợ phương thức
            http_response_code(405);
            echo json_encode(array("message" => "Method Not Allowed"));
        }
        }
        route_delete_users()
?>