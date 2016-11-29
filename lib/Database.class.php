<?
/**
 * 데이터베이스 싱글톤
 * 해쉬를 이용해서 빠른 쿼리
 */
class Database extends PDO {
    protected static $instance;

    protected $cache;

    static function getInstance($dsn = DB_HOST, $dbname = DB_NAME, $dbpass = DB_PASS) {
        if( !self::$instance ) {
            self::$instance = new Database($dsn, $dbname, $dbpass);
        }

        return self::$instance;
    }

    function __construct($dsn, $dbname, $dbpass) {
        parent::__construct($dsn,$dbname,$dbpass);

        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->exec("set names utf8");

        $this->cache = array();
    }

    function getPreparedStatment($query) {
        $hash = md5($query);
        if( !isset($this->cache[$hash]) ){
            $this->cache[$hash] = $this->prepare($query);
        }
        return $this->cache[$hash];
    }

    function __destruct() {
        $this->cache = NULL;
    }
}
?>