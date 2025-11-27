<?php 

// Biến môi trường, dùng chung toàn hệ thống
// Khai báo dưới dạng HẰNG SỐ để không phải dùng $GLOBALS

define('BASE_URL'       , 'http://localhost/DA1_N5_TOUR_MVC/');

define('DB_HOST'    , 'localhost');
define('DB_PORT'    , 3306);
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME'    , 'duan1');  // Tên database

define('PATH_ROOT'    , __DIR__ . '/../');
//Cấu hình thư mục phần giao diện của quản trị tour
define('PATH_ADMIN', PATH_ROOT . '/views/admin/');
