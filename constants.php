<?php
    define('accessTokenKey', "dayLaKEyAcCes5ToKEn123456123123");
    define('expirationAccessTokenTime', strtotime("+15 minutes"));
    define('refreshTokenKey', "CAiNaYLARefResHTOkenKeY12344321242123");
    define('expirationRefreshTokenTime', strtotime("+100 day"));
    define('request_borrow',0 );
    define('accepted_borrow',1 );
    define('rejected_borrow',2 );
    define('request_return',3);
    define('expired',4);

    define('notBanned',0);
    define('isBanned',1);

    define('host','localhost' );
    define('username','root');
    define('password','' );
    define('dbName','library');
?>