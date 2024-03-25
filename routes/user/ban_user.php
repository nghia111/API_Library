<?php
//api của admin
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

require "../../middlewares/user.middleware.php";
require "../../controllers/users.controller.php";

function route_ban_user()
{
    // Kiểm tra phương thức request là GET
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // lấy data từ req.query

        // thực hiện validate() 
        if (accessTokenValidator()) {
            if (isAdmin()) {
                if (banUserValidator()) {
                    // gọi controller
                    banUserController();
                }
            }
        }
    } else {
        // Trả về lỗi không hỗ trợ phương thức
        http_response_code(405);
        echo json_encode(array("message" => "Method Not Allowed"));
    }
}
route_ban_user();
?>
