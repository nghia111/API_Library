<?php
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');

    function getBooksValidator() {
        $errors = [];
        if(isset($_GET['limit'])){
            $limit = $_GET['limit'];
        }else{
            $limit = null;
        }     
        if(isset($_GET['page'])){
            $page = $_GET['page'];
        }else{
            $page = null;
        }
        // Kiểm tra $id
        if (isset($_GET['id']) && !is_numeric($_GET['id'])) {
            $errors[] = "ID phải là một số.";
        }
    
        // Kiểm tra $title
        if (isset($_GET['title'])) {
            if (!is_string($_GET['title']) || empty($_GET['title'])) {
                $errors[] = "title phải là một chuỗi và không được để trống.";
            }
        }
        
        // Kiểm tra $available
        if (isset($_GET['available']) && !is_numeric($_GET['available']) ) {
            $errors[] = "available phải là một số";
            if($_GET['available']<0){
                $errors[]= "available phải >= 0";
            }
        }
    
        // Kiểm tra $description
        if (isset($_GET['description'])) {
            if (!is_string($_GET['description']) || empty($_GET['description'])) {
                $errors[] = "description phải là một chuỗi và không được để trống.";
            }
        }
        // Kiểm tra $category
        if (isset($_GET['category_code'])) {
             if (!is_string($_GET['category_code']) || empty($_GET['category_code'])) {
                  $errors[] = "category_code phải là một chuỗi và không được để trống.";
              }
           }
        
        
        if ($limit ==null) {
            $errors[] = "Tham số 'limit' là bắt buộc.";
        }else{
            if(intval($limit)<=0 || intval($limit)>99){
                $errors[] = "0 < 'limit' < 100.";
            }
        }
        if ($page == null) {
            $errors[] = "Tham số 'page' là bắt buộc.";
        }else{
            if(intval($page)<=0){
                $errors[] = "'page' > 0 .";
            }
        }
        if (!empty($errors)) {
                http_response_code(422);
                echo json_encode(array("error:"=> $errors)) ;
            
            return false;
        }
        return true;
}
    function getBookById() {
        $errors = [];
       
        // Kiểm tra $id
        if (isset($_GET['id']) && !is_numeric($_GET['id'])) {
            $errors[] = "ID phải là một số.";
        }
    
    
        if (!empty($errors)) {
                http_response_code(422);
                echo json_encode(array("error:"=> $errors)) ;
            
            return false;
        }
        return true;
}

    function createBookValidator() {
        $errors = [];
        if (!isset($_POST['title']) || empty($_POST['title']) ||
        !isset($_POST['available']) || empty($_POST['available']) ||
        !isset($_POST['description']) || empty($_POST['description']) ||
        !isset($_POST['category_code']) || empty($_POST['category_code'])||
        !isset($_POST['author']) || empty($_POST['author'])||
        !isset($_POST['image']) 
        ) {
            http_response_code(422);
            echo json_encode(array("error:"=> "vui lòng điền đầy đủ thông tin: title, available, description, category_code, author, image")) ;
            return false;
        }

        // Kiểm tra title
        if (!is_string($_POST['title'])) {
            $errors[] = "title phải là string.";
        }

        // Kiểm tra description
        if (!is_string($_POST['description'])) {
            $errors[] = "description phải là string.";
        }

        // Kiểm tra category_code
        if (!is_string($_POST['category_code'])) {
            $errors[] = "category_code phải là string.";
        }else{
            $valid_categories = array(
                "ACC8",
                "AND13",
                "ATD13",
                "ATR3",
                "AYU13",
                "BSU8",
                "BYI9",
                "CER5",
                "CLU8",
                "CNH17",
                "CNH9",
                "CSH9",
                "CSL8",
                "CYO12",
                "DTE7",
                "EAR7",
                "FKO14",
                "FNI7",
                "FYA7",
                "HHE6",
                "HLI10",
                "HNI18",
                "HRO6",
                "HRU5",
                "HYI7",
                "MCU5",
                "MYY7",
                "NNO10",
                "NSO6",
                "NTE9",
                "PGA9",
                "PLA10",
                "PSO8",
                "PYH10",
                "PYO6",
                "PYS10",
                "REO7",
                "RNE8",
                "SEC7",
                "SEU8",
                "SNC15",
                "SPE9",
                "SSH13",
                "SSP16",
                "STE14",
                "SYP12",
                "TLR6",
                "TRH8",
                "WNO14",
                "YTO11"
            );
            if(!in_array($_POST['category_code'],$valid_categories)){
                $errors[]= "category_code không hợp lệ";
            }
        }
        // Kiểm tra author
        if (!is_string($_POST['author'])) {
            $errors[] = "author phải là string.";
        }
        // Kiểm tra image
        if (!empty($_POST['image'])) {
            if (!is_string($_POST['image'])) {
                $errors[] = "image phải là string.";
            }
            }
        
        // Kiểm tra available
        if (!is_numeric($_POST['available'])) {
            $errors[] = "available phải là number";
        }else{
            if($_POST['available'] <0){
                $errors[]= "available phải >= 0";
            }
        }
        if (!empty($errors)) {
            // lỗi validate 
            http_response_code(422);
            echo json_encode(array("error:"=> $errors)) ;
            return false;
        }
        return true;





    }

    function deleteBookValidator(){
        if(!isset($_GET['id'])|| empty($_GET['id'])   ){
            http_response_code(422);
            echo json_encode(array("error:"=> "yêu cầu truyền id lên req query ")) ;
            return false;
        }
        return true;
    

    }
    
    function updateBookValidator(){
        $id = $_GET['id'] ?? '';
        if(empty($id) || !isset($_GET['id']) ){
            http_response_code(422);
            echo json_encode(array("error:"=> "yêu cần truyền book id lên query ")) ;
            return false;

        }



        $title = $_POST['title'] ?? '';
        $available = $_POST['available'] ?? '';
        $image = $_POST['image'] ?? '';
        $description = $_POST['description'] ?? '';
        $category_code = $_POST['category_code'] ?? '';
        $author = $_POST['author'] ?? '';
        if (!empty($title) || !empty($available) || !empty($image) || !empty($description) || !empty($category_code) || !empty($author)) {
            $errors = [];
            // Kiểm tra dữ liệu cho biến title
            if (!empty($title)) {
                if(!is_string($title)){
                    $errors[] = "title phải là string";
                }
            }

            // Kiểm tra dữ liệu cho biến available
            if (!empty($available)) {
                if(!is_numeric($available)){
                    $errors[]="available phải là number";
                    if($available<0){
                        $errors[]="available phải >= 0";
                    }
                }
            }

            // Kiểm tra dữ liệu cho biến image
            if (!empty($image)) {
                if(!is_string($image)){
                    $errors[] = "image phải là string";
                }
            }
            

            // Kiểm tra dữ liệu cho biến description
            if (!empty($description)) {
                if(!is_string($description)){
                    $errors[] = "description phải là string";
                }
            }

            // Kiểm tra dữ liệu cho biến category_code
            if (!empty($category_code)) {
                if(!is_string($category_code)){
                    $errors[] = "category_code phải là string";
                }else{
                    $valid_categories = array(
                        "ACC8",
                        "AND13",
                        "ATD13",
                        "ATR3",
                        "AYU13",
                        "BSU8",
                        "BYI9",
                        "CER5",
                        "CLU8",
                        "CNH17",
                        "CNH9",
                        "CSH9",
                        "CSL8",
                        "CYO12",
                        "DTE7",
                        "EAR7",
                        "FKO14",
                        "FNI7",
                        "FYA7",
                        "HHE6",
                        "HLI10",
                        "HNI18",
                        "HRO6",
                        "HRU5",
                        "HYI7",
                        "MCU5",
                        "MYY7",
                        "NNO10",
                        "NSO6",
                        "NTE9",
                        "PGA9",
                        "PLA10",
                        "PSO8",
                        "PYH10",
                        "PYO6",
                        "PYS10",
                        "REO7",
                        "RNE8",
                        "SEC7",
                        "SEU8",
                        "SNC15",
                        "SPE9",
                        "SSH13",
                        "SSP16",
                        "STE14",
                        "SYP12",
                        "TLR6",
                        "TRH8",
                        "WNO14",
                        "YTO11"
                    );
                    if(!in_array($category_code,$valid_categories)){
                        $errors[]= "category_code không hợp lệ";
                    }
                }
            }

            // Kiểm tra dữ liệu cho biến author
            if (!empty($author)) {
                if(!is_string($author)){
                    $errors[] = "author phải là string";
                }
            }
            if (!empty($errors)) {
                http_response_code(422);
                echo json_encode(array("error:"=> $errors)) ;
            
            return false;
            }
        return true;
        }else{
            http_response_code(422);
            echo json_encode(array("error:"=> "bạn cần phải truyền data update lên body ")) ;
            return false;

        }

    }
?>