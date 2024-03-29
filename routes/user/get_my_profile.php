<?php
    //api của admin

    require "../../middlewares/user.middleware.php";
    require "../../controllers/users.controller.php";

    function route_get_my_profile() {
        // Kiểm tra phương thức request là GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // lấy data từ req.query
     
        // thực hiện validate() 
        if(accessTokenValidator()){  
            // gọi controller
            getMyProfileController();
        }
    } else {
            // Trả về lỗi không hỗ trợ phương thức
            http_response_code(405);
            echo json_encode(array("message" => "Method Not Allowed"));
        }
        }
        route_get_my_profile()
?>