<?php

    require "../../middlewares/book.middleware.php";
    require "../../controllers/books.controller.php";

    function route_all_categories() {
        // Kiểm tra phương thức request là GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // lấy data từ req.query
     
        // gọi controller
        getAllCategoriesController();
            
        
    } else {
            // Trả về lỗi không hỗ trợ phương thức
            http_response_code(405);
            echo json_encode(array("message" => "Method Not Allowed"));
        }
        }
        route_all_categories()
?>