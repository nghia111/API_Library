<?php
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    require "../../middlewares/borrow_return_books.middleware.php";
    require "../../controllers/borrow_return_books.controller.php";
    require "../../middlewares/user.middleware.php";

    function route_create_return_book() {
        // Kiểm tra phương thức request là POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // lấy data từ req.query
     
        // thực hiện validate() 
        if(accessTokenValidator()){ 
            if(notBanned()){
                if(createReturnBookValidator()){
                    // gọi controller
                    createReturnBookController();
                }
            } 
        }
    } else {
            // Trả về lỗi không hỗ trợ phương thức
            http_response_code(405);
            echo json_encode(array("message" => "Method Not Allowed"));
        }
        }
        route_create_return_book()
?>