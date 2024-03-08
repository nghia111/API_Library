<?php
// api của admin
    require "../../middlewares/book.middleware.php";
    require "../../controllers/books.controller.php";

    function route_create_book() {
        // Kiểm tra phương thức request là GET
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // lấy data từ req.query
     
        // thực hiện validate() 
        if( createBookValidator()){
            // gọi controller
            createBookController();
         }
            
        
    } else {
            // Trả về lỗi không hỗ trợ phương thức
            http_response_code(405);
            echo json_encode(array("message" => "Method Not Allowed"));
        }
        }
        route_create_book()
?>