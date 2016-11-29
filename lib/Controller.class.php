<?
/**
 * 베이스 컨트롤러 클래스
 */
class Controller
{
    protected $template;

    function __construct()
    {
        $this->template = new Template();
    }

    // 인덱스
    function index()
    {
        error_log("Controller[" . get_called_class() . "] index가 정의되지 않았습니다.");
    }

    // 뷰페이지 설정
    protected function setView($folder, $file) {
        $this->template->set($folder, $file);
    }

    // 변수 설정
    protected function setVariable($key, $value) {
        $this->template->setVariable($key,$value);
    }

    function __destruct() {
        $this->template->render();
    }
}
?>