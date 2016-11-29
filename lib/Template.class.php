<?
/**
 * 템플릿 클래스
 */
class Template {
    protected $file;
    protected $folder;
    protected $variables;

    function __construct() {
        $this->variables = array();
    }

    function set($folder, $file) {
        $this->folder = $folder;
        $this->file = $file;
    }

    function setVariable($key,$value){
        $this->variables[$key] = $value;
    }

    function render() {
        extract($this->variables);

        $fileName = ROOT . AREA . "/view/" . $this->folder . '/' . $this->file . '.php';

        if( file_exists($fileName) ) {
            include($fileName);
        }
    }

}
?>