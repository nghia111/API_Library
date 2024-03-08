<?php
require_once '../../vendor/autoload.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;


function signToken($payload, $key, $expirationTime)
{
    try {
        $currentTimestamp = time();
        $algorithm = 'HS256';
        $payload['exp'] = $expirationTime;
        $payload['iat']=  $currentTimestamp;
        $token = JWT::encode($payload, $key, $algorithm);
        return $token;
    } catch (Exception $e) {
        // Xử lý lỗi tại đây (ví dụ: ghi log, thông báo lỗi, ...)
        echo 'Có lỗi xảy ra: ' . $e->getMessage();
        return null;
    }
}

function verifyToken($token, $key )
{
    try {
        $algorithms = 'HS256';
        $decoded = JWT::decode($token, new Key($key, $algorithms));
        return $decoded;
    } catch (Firebase\JWT\ExpiredException $e) {
        // Xử lý lỗi tại đây (ví dụ: ghi log, thông báo lỗi, ...)
        echo 'Có lỗi xác thực token: ' . $e->getMessage();
        return null;
    }
}



?>