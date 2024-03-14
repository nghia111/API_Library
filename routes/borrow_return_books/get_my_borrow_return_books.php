<?php

    require "../../middlewares/borrow_return_books.middleware.php";
    require "../../controllers/borrow_return_books.controller.php";
    require "../../middlewares/user.middleware.php";

    function route_get_my_borrow_return_books() {
        // Kiểm tra phương thức request là POST
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // lấy data từ req.query
     
        // thực hiện validate() 
        if(accessTokenValidator()){  
            // gọi controller
            getMyBorrowReturnBooksController();
        }
    } else {
            // Trả về lỗi không hỗ trợ phương thức
            http_response_code(405);
            echo json_encode(array("message" => "Method Not Allowed"));
        }
        }
        route_get_my_borrow_return_books()
?>