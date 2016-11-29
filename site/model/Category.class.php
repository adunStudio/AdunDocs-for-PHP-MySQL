<?
class Category extends Model {
    protected static $tableName = TABLE_CATEGORY;
    protected static $primaryKey = 'id';


    function setId($value) {
        $this->setColumnValue('id', $value);
    }
    function getId() {
        return $this->getColumnValue('id');
    }

    function setDirName($value) {
        $this->setColumnValue('dirName', $value);
    }
    function getDirName() {
        return $this->getColumnValue('dirName');
    }

    function setSubName($value) {
        $this->setColumnValue('subName', $value);
    }
    function getSubName() {
        return $this->getColumnValue('subName');
    }
}
?>