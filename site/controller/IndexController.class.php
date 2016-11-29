<?
class IndexController extends Controller {
    function __construct() {
        parent::__construct();
    }

    function index() {

        $mobile = false;
        $theme = isset($_COOKIE['theme'])  ? $_COOKIE['theme'] : './css/style_black.css';

        $arr_browser = array ("Android", "iPhone","iPod","IEMobile","Mobile","lgtelecom","PPC", "SymbianOS","BlackBerry");
        for($i = 0 ; $i < count($arr_browser) ; ++$i)
        {
            if(strpos($_SERVER['HTTP_USER_AGENT'],$arr_browser[$i]) == true)
            {
                $mobile = true;
            }
        }

        $this->setView('', 'docs');
        $this->setVariable('mobile', $mobile);
        $this->setVariable('theme', $theme);
    }
}
?>