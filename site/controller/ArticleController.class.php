<?
header("Content-Type:text/html;charset=utf-8");
class ArticleController extends Controller {
    private $master = 'bananaappledocs';
    private $pattern = '12453';

    function __construct() {
        parent::__construct();
        session_start();
    }

    function index()
    {
        
    }

    function login()
    {

        if( $_POST['pattern'] == $this->pattern )
        {
            $_SESSION['master'] = $this->master;
            echo json_encode(array('result' => true));
            return;
        }
        echo json_encode(array('result' => false));
    }

    function logout()
    {
        session_destroy();
        echo json_encode(array('result' => true));
    }

    function docs()
    {
        $docs = array(
            'docs' => array(),
            'dirTree' => array(),
            'fileTree' => array()
        );

        $categorys = Category::getAll(array(), 'dirName ASC, subName ASC');
        $articles = Article::getAll(array(), 'fileName ASC');

        foreach($categorys as $category)
        {
            $dirName = $category->getDirName();
            $subName = $category->getSubName();

            if( !is_array($docs['docs'][$dirName]) &&  !is_array($docs['dirTree'][$dirName]) ) {
                $docs['docs'][$dirName] = array();
                $docs['dirTree'][$dirName] = array();
            }

            $docs['docs'][$dirName][$subName] = array();
            array_push($docs['dirTree'][$dirName], $subName);
        }

        foreach($articles as $article) {
            $dirName = $article->getDirName();
            $subName = $article->getSubName();

            $document = array(
                'dirName'=>$article->getDirName(),
                'subName'=>$article->getSubName(),
                'fileName' => $article->getFileName(),
                'btime' => $article->getBtime(),
                'mtime' => $article->getMtime()
            );

            $docs['docs'][$dirName][$subName][$article->getFileName()] = $document;
            array_push($docs['fileTree'], $document);
        }

        echo  json_encode($docs, JSON_UNESCAPED_UNICODE);

    }

    function view()
    {
        $dirName = $_POST['dirName'];
        $subName = $_POST['subName'];
        $fileName = $_POST['fileName'];

        $article = Article::getOne(array('dirName'=>$dirName, 'subName'=>$subName, 'fileName'=>$fileName));

        $doc = array(
            'dirName'=>$article->getDirName(),
            'subName'=>$article->getSubName(),
            'fileName' => $article->getFileName(),
            'fileData' => $article->getFileData(),
            'btime' => $article->getBtime(),
            'mtime' => $article->getMtime()
        );
        echo json_encode($doc, JSON_UNESCAPED_UNICODE);
    }

    function write()
    {

        $dirName = $_POST['dirName'];
        $subName = $_POST['subName'];
        $fileName = $_POST['fileName'];
        $fileData = $_POST['fileData'];

        if( $_SESSION['master'] == $this->master && $dirName && $subName && $fileName && $fileData ) {

            if( Article::getCount(array('dirName'=>$dirName, 'subName'=>$subName, 'fileName'=>$fileName)) > 0) {
                echo json_encode(array('result' => false, 'msg'=>'존재하는 파일명입니다.'));
                return;
            }

            $created_date = date("Y-m-d H:i:s");

            $article = new Article();
            $article->setDirName($dirName);
            $article->setSubName($subName);
            $article->setFileName($fileName);
            $article->setFileData($fileData);
            $article->setBtime($created_date);
            $article->setMtime($created_date);
            $article->save();

            echo json_encode(array('result' => true));
            return;
        }

        echo json_encode(array('result' => false, 'msg'=>'파라미터 값이 부족하거나 관리자가 아닙니다.'));
    }

    function edit()
    {
        $dirName = $_POST['dirName'];
        $subName = $_POST['subName'];
        $fileName = $_POST['fileName'];
        $fileData = $_POST['fileData'];
        $originDirName = $_POST['originDirName'];
        $originSubName = $_POST['originSubName'];
        $originFileName = $_POST['originFileName'];

        if ($_SESSION['master'] == $this->master && $dirName && $subName && $fileName && $fileData && $originDirName && originSubName && originFileName) {
            $oldArticle = Article::getOne(array('dirName' => $originDirName, 'subName' => $originSubName, 'fileName' => $originFileName));
            $id = $oldArticle->getId();
            $created_date = date("Y-m-d H:i:s");

            if (Article::getCount(array('dirName' => $dirName, 'subName' => $subName, 'fileName' => $fileName)) > 0) {
                echo json_encode(array('result' => false, 'msg' => '존재하는 파일명입니다.'));
                return;
            }

            $article = new Article();
            $article->setId($id);
            $article->setDirName($dirName);
            $article->setSubName($subName);
            $article->setFileName($fileName);
            $article->setFileData($fileData);
            $article->setMtime($created_date);
            $article->save();

            echo json_encode(array('result' => true));
            return;
        }
        echo json_encode(array('result' => false, 'msg' => '파라미터 값이 부족하거나 관리자가 아닙니다.'));
    }

    function delete()
    {
        $dirName = $_POST['dirName'];
        $subName = $_POST['subName'];
        $fileName = $_POST['fileName'];
        $trashName = $_POST['trashName'];

        if ($_SESSION['master'] == $this->master && $dirName  && $subName && $fileName && $trashName == $fileName ) {
            $oldArticle = Article::getOne(array('dirName' => $dirName, 'subName' => $subName, 'fileName' => $fileName));

            $id = $oldArticle->getId();

            $article = new Article();
            $article->setId($id);
            $article->delete();
            echo json_encode(array('result' => true));
            return;
        }

        echo json_encode(array('result' => false, 'msg' => '파라미터 값이 부족하거나 관리자가 아닙니다.'));

    }

    function directory()
    {
        $dirName = $_POST['dirName'];
        $subName = $_POST['subName'];

        if ($_SESSION['master'] != $this->master) {
            echo json_encode(array('result' => false, 'msg' => '파라미터 값이 부족하거나 관리자가 아닙니다.'));
            return;
        }

        if( $dirName && !$subName ) {
            if( Category::getCount(array('dirName'=>$dirName)) > 0 ) {
                echo json_encode(array('result' => false, 'msg' => '중복된 디렉토리 명입니다.'));
                return;
            }

            $category =  new Category();
            $category->setDirName($dirName);
            $category->save();
            echo json_encode(array('result' => true));
            return;
        }

        if( $dirName && $subName )
        {
            if( Category::getCount(array('dirName'=>$dirName, 'subName'=>$subName)) > 0 ) {
                echo json_encode(array('result' => false, 'msg' => '중복된 디렉토리 명입니다.'));
                return;
            }

            if(  Category::getCount(array('dirName'=>$dirName, 'subName'=>'')) > 0 )
            {
                $category = Category::getOne(array('dirName'=>$dirName, 'subName'=>''));
                $category->setSubName($subName);
                $category->save();
                echo json_encode(array('result' => true));
                return;
            }
            else
            {
                $category =  new Category();
                $category->setDirName($dirName);
                $category->setSubName($subName);
                $category->save();
                echo json_encode(array('result' => true));
                return;
            }
        }

        echo json_encode(array('result' => false, 'msg' => '파라미터 값이 부족하거나 관리자가 아닙니다.'));
    }


    // editormd
    function upload()
    {
        if ($_SESSION['master'] != $this->master) {
            echo json_encode(array('result' => false, 'msg' => '파라미터 값이 부족하거나 관리자가 아닙니다.'));
            return;
        }

        $UPLOAD_KIND = array("image/bmp", "image/jpeg", "image/gif", "image/jpeg", "image/JPG", "image/PNG", "image/png");
        $time = time();
        $file = $_FILES['editormd-image-file']['name'];
        if( !$file || !in_array($_FILES['editormd-image-file']['type'], $UPLOAD_KIND) )
        {
            echo json_encode(array('success' => 0, 'message' => '올바른 파일이 아닙니다..'));
            return;
        }

        $file = iconv("UTF-8", "EUC-KR", $_FILES['editormd-image-file']['name']);

        $uploadDir = $_SERVER["DOCUMENT_ROOT"] . "/public/upload/";
        if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777); }

        move_uploaded_file($_FILES['editormd-image-file']['tmp_name'], $uploadDir.$time.$file);
        echo json_encode(array('success' => 1, 'url' => "/upload/".$time.$file ));
    }

    function test() {
        $old = Article::getCount(array('dirName'=>'codesafer', 'subName'=>'개념글', 'fileName'=>'ddd'));
        echo $old;
    }


}
?>