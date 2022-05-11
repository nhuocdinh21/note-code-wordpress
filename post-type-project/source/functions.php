<?php
// Add custom Theme Functions here

define('IMGPATH', get_stylesheet_directory_uri() . '/assets/images/');
define('CSSPATH', get_stylesheet_directory_uri() . '/assets/css/');
define('JSPATH', get_stylesheet_directory_uri() . '/assets/js/');
define('DIRPATH', get_stylesheet_directory_uri() . '/');

// include file
require_once 'inc/vn-optionpage.php';
require_once 'inc/vn-script.php';
require_once 'inc/vn-shortcode.php';
require_once 'inc/vn-function.php';
require_once 'inc/vn-saveimage.php';
require_once 'inc/vn-ajaxpagination.php';