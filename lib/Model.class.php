<?
/**
 * 베이스 모델 클래스
 */
class Model {
    protected static $tableName = '';
    protected static $primaryKey = '';
    protected $columns;

    function __construct() {
        $this->columns = array();
    }

    function setColumnValue($column, $value) {
        $this->columns[$column] = $value;
    }

    function getColumnValue($column) {
        return $this->columns[$column];
    }

    function save() {
        $class = get_called_class();
        $query =  "REPLACE INTO " . static::$tableName . " (" . implode(",", array_keys($this->columns)) . ") VALUES(";
        $keys = array();
        foreach ($this->columns as $key => $value) {
            $keys[":".$key] = $value;
        }
        $query .= implode(",", array_keys($keys)).")";
        $db = Database::getInstance();
        $s = $db->getPreparedStatment($query);
        $s->execute($keys);
    }

    function delete(){
        $class = get_called_class();
        $query = "DELETE FROM " . static::$tableName . " WHERE ".static::$primaryKey."=:id LIMIT 1";
        $db = Database::getInstance();
        $s = $db->getPreparedStatment($query);
        $s->execute(array(':id'=>$this->columns[static::$primaryKey]));
    }

    function createFromDb($column){
        foreach ($column as $key => $value) {
            $this->columns[$key] = $value;
        }
    }

    static function getAll($condition=array(),$order=NULL,$startIndex=NULL,$count=NULL){
        $query = "SELECT * FROM " . static::$tableName;
        if(!empty($condition)){
            $query .= " WHERE ";
            foreach ($condition as $key => $value) {
                $query .= $key . "=:".$key." AND ";
            }
        }
        $query = rtrim($query,' AND ');
        if($order){
            $query .= " ORDER BY " . $order;
        }
        if($startIndex !== NULL){
            $query .= " LIMIT " . $startIndex;
            if($count){
                $query .= "," . $count;
            }
        }
        return self::get($query,$condition);
    }

    static function get($query,$condition=array()){
        $db = Database::getInstance();
        $s = $db->getPreparedStatment($query);
        foreach ($condition as $key => $value) {
            $condition[':'.$key] = $value;
            unset($condition[$key]);
        }
        $s->execute($condition);
        $result = $s->fetchAll(PDO::FETCH_ASSOC);
        $collection = array();
        $className = get_called_class();
        foreach($result as $row){
            $item = new $className();
            $item->createFromDb($row);
            array_push($collection,$item);
        }
        return $collection;
    }

    static function getOne($condition=array(),$order=NULL,$startIndex=NULL){
        $query = "SELECT * FROM " . static::$tableName;
        if(!empty($condition)){
            $query .= " WHERE ";
            foreach ($condition as $key => $value) {
                $query .= $key . "=:".$key." AND ";
            }
        }
        $query = rtrim($query,' AND ');
        if($order){
            $query .= " ORDER BY " . $order;
        }
        if($startIndex !== NULL){
            $query .= " LIMIT " . $startIndex . ",1";
        }
        $db = Database::getInstance();
        $s = $db->getPreparedStatment($query);
        foreach ($condition as $key => $value) {
            $condition[':'.$key] = $value;
            unset($condition[$key]);
        }
        $s->execute($condition);
        $row = $s->fetch(PDO::FETCH_ASSOC);
        $className = get_called_class();
        $item = new $className();
        $item->createFromDb($row);
        return $item;
    }

    static function getByPrimaryKey($value){
        $condition = array();
        $condition[static::$primaryKey] = $value;
        return self::getOne($condition);
    }

    static function getCount($condition=array()){
        $query = "SELECT COUNT(*) FROM " . static::$tableName;
        if(!empty($condition)){
            $query .= " WHERE ";
            foreach ($condition as $key => $value) {
                $query .= $key . "=:".$key." AND ";
            }
        }
        $query = rtrim($query,' AND ');
        $db = Database::getInstance();
        $s = $db->getPreparedStatment($query);
        foreach ($condition as $key => $value) {
            $condition[':'.$key] = $value;
            unset($condition[$key]);
        }
        $s->execute($condition);
        $countArr = $s->fetch();
        return $countArr[0];
    }

}
?>