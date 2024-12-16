<?php

//session start
use database\DataBase;
use database\CreateDB;


session_start();

//config
define('BASE_PATH', __DIR__);
define('CURRENT_DOMAIN', currentDomin() . '/news-site/');
define('DISPLAY_ERRORS', true);
define('DB_HOST', 'localhost');
define('DB_NAME', 'news');
define('DB_PORT', 3308);
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');


require_once 'database/DataBase.php';
require_once 'database/CreateDB.php';
require_once 'activities/Admin/Category.php';
//$db = new DataBase();
//$db = new CreateDB();
//$db->run();
function uri($reservedUrl, $class, $method, $requestMethod = 'GET')
{
    $currentUrl = explode('?', currentUrl())[0];
    $currentUrl = str_replace(CURRENT_DOMAIN, '', $currentUrl);
    $currentUrl = trim($currentUrl, '/');
    $currentUrlArray = explode('/', $currentUrl);
    $currentUrlArray = array_filter($currentUrlArray);
    // reserved Url array

    $reservedUrl = trim($reservedUrl, '/');
    $reservedUrlArray = explode('/', $reservedUrl);
    $reservedUrlArray = array_filter($reservedUrlArray);

    if (sizeof($currentUrlArray) != sizeof($reservedUrlArray) || methodField() != $requestMethod) {
        return false;
    }

    $parameters = [];
    for ($key = 0; $key < sizeof($currentUrlArray); $key++) {
        if ($reservedUrlArray[$key][0] == "{" && $reservedUrlArray[$key][strlen($reservedUrlArray[$key]) - 1] == "}") {
            array_push($parameters, $reservedUrlArray[$key]);
        }
        elseif ($currentUrlArray[$key] !== $reservedUrlArray[$key]) {
            return false;
        }
    }
    if (methodField() == 'POST') {
        $request = isset($_FILES) ? array_merge($_FILES, $_POST) : $_POST;
        $parameters = array_merge([$request], $parameters);
    }
    $object = new $class;
    call_user_func_array([$object, $method], $parameters);
    exit();
}
//uri('admin/category', 'Category', 'index');
//helpers
function protocol()
{
    return strpos($_SERVER['SERVER_PROTOCOL'], 'https/') === true ? 'https://' : 'http://';
}

function currentDomin()
{
    return protocol() . $_SERVER['HTTP_HOST'];
}
function asset($src)
{
    $domain = trim(CURRENT_DOMAIN, '/');
    $src = $domain . '/' . trim($src, '/');
    return $src;
}
function url($url)
{
    $domain = trim(CURRENT_DOMAIN, '/');
    $url = $domain . '/' . trim($url, '/');
    return $url;
}
function currentUrl()
{
    return currentDomin() . $_SERVER['REQUEST_URI'];
}
function methodField()
{
    return $_SERVER['REQUEST_METHOD'];
}

function displayErrors($displayError)
{
    if ($displayError) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
    else{
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(0);
    }
}
displayErrors(DISPLAY_ERRORS);

global $flashMessage;
if (isset($_SESSION['flash_message'])) {
    $flashMessage = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}
function flash($name, $value = null)
{
    if ($value === null) {
        global $flashMessage;
        $message = isset($flashMessage[$name]) ? $flashMessage[$name] : '';
        return $message;
    }
    else{
        $_SESSION['flash_message'][$name] = $value;
    }
}

function dd($var)
{
    echo '<pre>';
    var_dump($var);
    exit;
}


// Category

uri('admin/category', 'Admin\Category', 'index');


echo '404 - page not found';