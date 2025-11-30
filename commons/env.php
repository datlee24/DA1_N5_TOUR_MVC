<?php 

// Biến môi trường, dùng chung toàn hệ thống
// Khai báo dưới dạng HẰNG SỐ để không phải dùng $GLOBALS

define('BASE_URL'       , 'http://localhost/DA1_N5_TOUR_MVC/');

define('DB_HOST'    , 'localhost');
define('DB_PORT'    , 3306);
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME'    , 'duan1');  // Tên database

// PATH ROOT – trỏ đúng về thư mục gốc dự án
define('PATH_ROOT', dirname(__DIR__) . '/');
//Cấu hình thư mục phần giao diện của quản trị tour
define('PATH_ADMIN', PATH_ROOT . '/views/admin/');
//Cấu hình thư mục phần giao diện hường dẫn viên 
define('PATH_GUIDE', PATH_ROOT . '/views/guide/');
