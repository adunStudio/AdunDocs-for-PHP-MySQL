<?
define('ROOT', dirname(realpath(__FILE__)).'/');
define('AREA', 'site');

$thisDir = explode('/', ROOT);
$conflen = strlen(array_pop($thisDir));

$B = substr(__FILE__, 0, strrpos(__FILE__, '/'));
$A = substr($_SERVER['DOCUMENT_ROOT'], strrpos($_SERVER['DOCUMENT_ROOT'], $_SERVER['PHP_SELF']));
$C = substr($B, strlen($A));
$posconf = strlen($C) - $conflen;
$D = substr($C, 0, $posconf);
$host = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $D;

define('ROOT_URL', $host);

/**
 * 설점, 함수 include
 */
include_once(ROOT . 'system/config/config.php');
include_once(ROOT . 'lib/functions.php');


/**
 * 에러 메시지
 */
function setErrorLogging() {
    error_reporting(E_ALL);

    if( DEVELOPMENT_ENVIRONMENT == true )
    {
        ini_set('display_errors', "1");
    }
    else
    {
        ini_set('display_errors', "0");
    }
    ini_set('log_errors', "1");
    ini_set('error_log',ROOT . 'system/log/error_log.php');
}

/**
 * 에러 추적
 *
 * @param $var
 * @param bool|false $append
 */
function trace($var, $append = false) {
    $oldString = "<?php\ndie();/*";
    if( $append ) {
        $oldString = file_get_contents(ROOT . 'system/log/output.php') . '/*';
    }
    file_put_contents(ROOT . 'system/log/output.php', $oldString . "\n---\n" . print_r($var, true) . "\n*/");
}

/**
 * 백슬래시 제거
 * @param $value
 * @return array|string
 */
function stripSlashesDeep($value) {
    $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);

    return $value;
}

/**
 * php.ini magic_quotes 확인
 */
function removeMagicQuotes() {
    if ( get_magic_quotes_gpc() ) {
        $_GET    = stripSlashesDeep($_GET   );
        $_POST   = stripSlashesDeep($_POST  );
        $_COOKIE = stripSlashesDeep($_COOKIE);
    }
}

/**
 * 글로벌 변수 삭제
 */
function unregisterGlobals() {
    if (ini_get('register_globals')) {
        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach ($array as $value) {
            foreach ($GLOBALS[$value] as $key => $var) {
                if ($var === $GLOBALS[$key]) {
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}

/**
 * 컨트롤러 호출
 */
function callHook() {
    global $url;

    $url = rtrim($url, '/');
    $urlArray = array();
    $urlArray = explode('/', $url);
    $controller = DEFAULT_CONROLLER;
    $action     = DEFAULT_ACTION;

    // 컨트롤러
    if( isset($urlArray[0]) && !empty($urlArray[0]) ){
        $controller = array_shift($urlArray);
    }
    // 액션
    if( isset($urlArray[0]) && !empty($urlArray[0]) ){
        $action = array_shift($urlArray);
    }

    // 데이터베이스
    Database::getInstance('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS);

    // 컨트롤러 로드
    $controllerName = $controller;
    $controller = ucwords($controller); // 첫 글자 => 대문자
    $controller = $controller . 'Controller';
    $model = rtrim($controller, 's');
    $dispatch = new $controller();
    
    if ( (int)method_exists($controller, $action) )
    {
        call_user_func(array($dispatch,$action));
    } else
    {
        error_log("Unknown page/action, Controller = ".$controller.", action = ".$action);
    }
}

function __autoload($className) {
    $paths = array(
        ROOT . 'lib/',
        ROOT . 'site/controller/',
        ROOT . 'site/model/',
    );

    foreach($paths as $path) {


        if( file_exists($path . $className . '.class.php') ) {
          
            require_once($path . $className . ".class.php");
            break;
        }
    }
}

/**
 * JSON 처리
 */
$_POST = json_decode(file_get_contents('php://input'), true);

setErrorLogging();
removeMagicQuotes();
unregisterGlobals();
callHook();


?>