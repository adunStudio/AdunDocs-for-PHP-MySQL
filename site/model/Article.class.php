<?
class Article extends Model {
    protected static $tableName = TABLE_ARTICLE;
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

    function setFileName($value) {
        $this->setColumnValue('fileName', $value);
    }
    function getFileName() {
        return $this->getColumnValue('fileName');
    }

    function setFileData($value) {
        $this->setColumnValue('fileData', $value);
    }
    function getFileData() {
        return $this->getColumnValue('fileData');
    }

    function setBtime($value) {
        $this->setColumnValue('btime', $value);
    }
    function getBtime() {
        return $this->getColumnValue('btime');
    }

    function setMtime($value) {
        $this->setColumnValue('mtime', $value);
    }
    function getMtime() {
        return $this->getColumnValue('mtime');
    }

}
?>