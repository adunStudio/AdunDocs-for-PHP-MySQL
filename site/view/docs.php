<!--
   _       _                ___
  /_\   __| |_   _ _ __    /   \___   ___ ___  __  ___   _ ____
 //_\\ / _` | | | | '_ \  / /\ / _ \ / __/ __| \ \/ / | | |_  /
/  _  \ (_| | |_| | | | |/ /_// (_) | (__\__ \_ >  <| |_| |/ /
\_/ \_/\__,_|\__,_|_| |_/___,' \___/ \___|___(_)_/\_\\__, /___|
                                                     |___/
AdunDocs는 블로그연동과 마크다운을 지원하며, DB가 필요 없는 오픈형 문서 노트 입니다.
GIT : https://github.com/adunStudio/AdunDocs
Blog: http://www.oppacoding.com/
-->
<!DOCTYPE html>
<html <?if($mobile) {?>class="_mobile"<?}?> ng-app="AdunDocs" ng-controller="DocsCtrl">
<head>
    <title>AdunDocs</title>
    <meta name="viewport"      content="width=device-width initial-scale=1 maximum-scale=1" />
    <meta name="description"   content="AdunDocs는 블로그연동과 마크다운을 지원하며, DB가 필요 없는 오픈형 문서 노트 입니다.">
    <meta name="author"        content="adunstudio">
    <meta name="keywords"      content="티스토리 마크다운, 네이버 블로그 마크다운, 아둔독스, AdunDocs">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <!-- bootstrap -->
    <link rel="stylesheet" href="./css/bootstrap.css"./>
    <!-- style -->
    <link id="theme" rel="stylesheet" href="<?=$theme?>"./>
    <!-- editormd -->
    <link rel="stylesheet" href="./editor.md/css/editormd.css"./>
    <!-- patternLock -->
    <link rel="stylesheet" href="./css/patternLock.css"./>
    <!-- summernote -->
    <link rel="stylesheet" href="./summernote/summernote.css"./>

    <link rel="stylesheet" href="./css/custom.css"./>

    <!-- jQuery -->
    <script src="./js/lib/jquery.min.js"></script>
    <!-- jQuery-UI -->
    <script src="./js/lib/jquery-ui.min.js"></script>
    <!-- jQuery-UI.position-->
    <script src="./js/lib/jquery.ui.position.min.js"></script>
    <!-- jQuery-contenxtMenu-->
    <script src="./js/lib/jquery.contextMenu.min.js"></script>
    <!-- Bootstrap -->
    <script src="./js/lib/bootstap.min.js"></script>
    <!-- Angular -->
    <script src="./js/lib/angular.min.js"></script>
    <!-- Angular-router -->
    <script src="./js/lib/angular-route.min.js"></script>
    <!-- Angular-cookies -->
    <script src="./js/lib/angular-cookies.min.js"></script>
    <!-- Angular-sanitize -->
    <script src="./js/lib/angular-sanitize.min.js"></script>
    <!-- Angular-messages -->
    <script src="./js/lib/angular-messages.min.js"></script>
    <!-- PatternLock -->
    <script src="./js/lib/patternLock.min.js"></script>
    <!-- Moment -->
    <script src="./js/lib/moment.js"></script>
    <!-- ShowDown -->
    <script src="https://cdn.rawgit.com/showdownjs/showdown/1.4.3/dist/showdown.min.js"></script>
    <!-- md -->
    <script src="./js/lib/md.min.js"></script>
    <!-- MouseTrap -->
    <script src="./js/lib/mousetrap.min.js"></script>
    <!-- MathJax -->
    <script type="text/javascript" src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_HTML"></script>
    <!-- editor.md -->
    <script src="./editor.md/editormd.js"></script>
    <script src="./editor.md/languages/en.js"></script>
    <!-- summernote -->
    <script src="./summernote/summernote.js"></script>
    <link rel="stylesheet" href="http://www.oppacoding.com/ckeditor/plugins/codesnippet/lib/highlight/styles/github.css" >

    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.6.0/highlight.min.js"></script>
</head>
<body class="_booting _loading" ng-keypress="key();">
<div role="alert" class="_notif _in" id="save_noti" style="display: none;" >
    <h5 class="_notif-title"> 글이 임시저장 되었습니다. </h5>

    <p class="_notif-text"><!--<a class="_notif-link" href="#" data-behavior="reboot">Reload the page</a> to use the new
        version.-->
<!--
        <button  type="button" class="_notif-close" title="Close">Close</button>
-->
    </p>
</div>
<div class="_app" role="application" id="app" >
    <!--<div role="alert" class="_notice _top">
        <p class="_notice-text"><strong> AdunDocs는 현재 개발중 입니다. 메뉴에서 우클릭을 하시면 다양한 ..</strong></p>
    </div>-->
    <script>
        $('._notice-text').click(function() {
            $('#sli').toggleClass('closed')
        })
    </script>
    <div  ng-show="!isTrash" role="complementary" class="_path" >
        <a ng-show="docStat.dirName && !isTrash" href="#/[[docStat.dirName]]?check=1" class="_path-item _icon-[[docStat.dirName | lowercase]]">[[docStat.dirName]]</a>
        <a ng-show="docStat.subName && !isTrash" href="#/[[docStat.dirName]]/[[docStat.subName]]?check=1" class="_path-item">[[docStat.subName]]</a>
        <span ng-show="docStat.fileName" class="_path-item">[[docStat.fileName]]</span>
        <span ng-show="isLogin && docStat.dirName && docStat.subName && docStat.fileName && !isTrash" style="float: right;" class="_path-item"><a href="#/edit/[[docStat.dirName]]/[[docStat.subName]]/[[docStat.fileName]]" class="edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></span>
        <span ng-show="docStat.mtime" style="float: right;" class="_path-item">최종 수정 날짜: [[docStat.mtime | date: "y-MM-dd"]] </span>
        <span ng-show="docStat.btime" style="float: right;" class="_path-item">작성 날짜: [[docStat.btime | date: "y-MM-dd"]] </span>
    </div>

    <div ng-show="isTrash" role="complementary" class="_path" >
        <a href="" class="_path-item _icon-trash">[[docStat.dirName]]</a>
        <a href="" class="_path-item">[[docStat.subName]]</a>
        <span class="_path-item">[[docStat.fileName]]</span>
        <span style="float: right;" class="_path-item">삭제된 날짜: [[docStat.btime | date: "y-MM-dd"]] </span>
    </div>

    <div  ng-show="blogStat.dirCategory" role="complementary" class="_path">
        <a ng-show="blogStat.dirCategory" href="#blog/[[blogStat.dirCategory]]?check=1" class="_path-item _icon-tistory">[[blogStat.dirCategory]]</a>
        <a ng-show="blogStat.subCategory" href="#blog/[[blogStat.dirCategory]]/[[blogStat.subCategory]]?check=1" class="_path-item">[[blogStat.subCategory]]</a>
        <span ng-show="blogStat.title" class="_path-item">[[blogStat.title]]</span>
        <span ng-show="blogStat.title" style="float: right;" class="_path-item"><a href="#/blog/edit/[[blogStat.postid]]" class="edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></span>
        <span ng-show="!blogStat.title && blogStat.dirCategory && blogStat.dirCategory != '분류없음'" style="float: right;" class="_path-item"><a href="#/blog/write/[[blogStat.dirCategory]]/[[blogStat.subCategory]]" ><i class="fa fa-pencil" aria-hidden="true"></i> New Post</a></span>
        <span ng-show="blogStat.dateCreated" style="float: right;" class="_path-item">작성 날짜: [[blogStat.dateCreated | date: "y-MM-dd hh:mm"]] </span>
    </div>

    <!-- 배너 -->
    <header class="_header" role="banner">
        <button ng-click="historyBack();" type="button" class="_mobile-btn _back-btn">Back</button>
        <button ng-click="historyForward();" type="button" class="_mobile-btn _forward-btn">Forward</button>
        <button ng-click="naviToggle()" type="button" class="_mobile-btn _menu-btn">Menu</button>
        <button ng-click="initialize();" type="button" class="_mobile-btn _home-btn">Home</button>
        <form  class="_search" role="search">
            <input ng-model="search" ng-change="searchDoc();" type="search" class="_search-input" placeholder="Search…" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" maxlength="30" aria-label="Search">
            <button type="reset" class="_search-clear" title="Clear search">Clear search</button>
            <div class="_search-tag"></div>
        </form>
        <h1 class="_logo">
            <a ng-click="initialize();" href="javascript:void(0);" class="_nav-link" title="Offline API Documentation Browser">AdunDocs</a>
        </h1>
        <nav class="_nav" role="navigation">
            <a href="#blog"  class="_nav-link" title="blog">[[blogName]]</a>
            <a href="#about" class="_nav-link" title="about">About</a>
            <a href="#news"  class="_nav-link" title="news">News</a>
            <a href="#tips"  class="_nav-link" title="news">Tips</a>
            <a ng-show="isLogin"                      href="#write"  class="_nav-link" title="write">Write</a>
            <a ng-hide="isLogin" ng-click="login();"  href=""        class="_nav-link" title="login"><i class="fa fa-user" aria-hidden="true"></i></a>
            <a ng-show="isLogin" ng-click="logout();" href="" class="_nav-link"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
        </nav>
    </header>
    <!-- 배너 -->

    <!-- 로그인 -->
    <div  ng-controller="LoginCtrl" class="_login"  style="display: none;">
        <div class="login-ui-wrap">
            <div class="login-ui-page page-lock">
                <div class="login-ui-date-time">
                    <div class="login-ui-time">[[h]]:[[m]] PM</div>
                    <div class="login-ui-day">[[day]]</div>
                    <div class="login-ui-date">[[month]] [[date]], [[year]]</div>
                </div>
                <div class="login-lock-wrap">
                    <div class="login-lock-title" data-title="Draw a pattern to unlock"></div>
                    <div class="login-lock"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- 로그인 -->

    <!--
    네비게이션 컨트롤러로 빼야함 -->
    <!-- 네비게이션 -->
    <div ng-controller="navigationCtrl" id="navigation">
        <section class="_sidebar" tabindex="-1">
            <div ng-show="settingMode" id="settingForm" role="form" class="_list _list-picker _in">
                <label class="_list-item _icon-html">
                    <input ng-model="htmlMode" ng-change="setting_htmlButton()" type="checkbox" name="html" class="_list-checkbox">
                    <span class="_list-text">블로그를 HTML방식으로 포스팅</span>
                </label>
                <label class="_list-item _icon-html">
                    <input ng-model="autoMode" ng-change="setting_autoButton()" type="checkbox" name="auto" class="_list-checkbox">
                    <span class="_list-text">AutoSave 기능 사용</span>
                </label>
                <label class="_list-item _icon-editormd">
                    <select ng-model="editorTheme" ng-change="changeEditorTheme()" class="_list-select">
                        <option ng-repeat="theme in editorThemes" value="[[theme]]">[[theme]]</option>
                    </select>
                    <span class="_list-text">Editor Theme</span>
                </label>
                <div class="_list-note">Tip: 설정을 완료한 후에는 새로고침을 해주시기 바랍니다.</div>
                <a href="" ng-click="settingButton()" class="_list-link" target="_blank" >확인</a>
            </div>
            <div ng-show="!settingMode" role="navigation" class="_list dir" id="list">
                <div ng-show="search" class="_list" >
                    <a ng-repeat="s in searchResult" ng-click="searchToggle($event);" href="./#/search/[[s.dirName]]/[[s.subName]]/[[s.fileName]]" class="_list-item _list-hover _list-result _icon-[[s.dirName | lowercase]]" tabindex="-1"><span ng-click="toLocation($event);" data-link="./#/[[s.dirName]]/[[s.subName]]/[[s.fileName]]" class="_list-reveal"  title="Reveal in list"></span><span class="_list-text">[[s.fileName]]</span></a>
                </div>

                <a ng-show="!search" ng-repeat-start="(dir, dirValue) in docs" ng-click="toggleDir($event)" ng-right-click="toggleDir($event)"   id="_[[dir]]" href="./#/[[dir]]"  class="_list-item _icon-[[dir | lowercase]] _list-dir _-dir-_ isdir"  data-write="./#/write/[[dir]]" title="[[dir]]" tabindex="-1"><span class="_list-arrow"></span><span class="_list-count">[[getDeepLength(dirValue)]]</span><span class="_list-text">[[dir]]</span></a>
                <div ng-repeat-end="" class="_list _list-sub" >
                    <a ng-show="!search" ng-repeat-start="(sub, subValue) in docs[dir]" ng-click="toggleSub($event)" ng-right-click="toggleSub($event)" id="_[[dir]]_[[sub]]" href="./#/[[dir]]/[[sub]]" class="_list-item _list-dir _-sub-_ issub" data-write="./#/write/[[dir]]/[[sub]]" title="[[sub]]" tabindex="-1"><span class="_list-arrow"></span><span class="_list-count">[[getLength(subValue)]]</span><span class="_list-text">[[sub]]</span></a>
                    <div ng-repeat-end="" class="_list _list-sub">
                        <a ng-show="!search" ng-repeat="(name, file) in docs[dir][sub]" ng-click="toggleFile($event)" ng-right-click="toggleFile($event)" id="_[[dir]]_[[sub]]_[[name]]" href="./#/[[dir]]/[[sub]]/[[name]]" class="_list-item _list-hover _-file-_ isfile" data-edit="./#/edit/[[dir]]/[[sub]]/[[name]]" title="[[name]]" tabindex="-1">[[name]]</a>
                    </div>
                </div>

                <!-- Tistory -->
                <h6 ng-show="blogCategory" class="_list-title"> Tistory</h6>
                <a ng-show="blogCategory" ng-repeat-start="(dir, dirValue) in blogCategory" ng-click="toggleDir($event)" ng-right-click="toggleDir($event)"      id="blog_[[dir]]" href="./#/blog/[[dir]]"  class="_list-item _icon-tistory _list-dir _-blog_dir-_ isdir"  data-newpost="./#/blog/write/[[dir]]" title="[[dir]]" tabindex="-1"><span class="_list-arrow"></span><span class="_list-count">[[getDeepLength(dirValue)]]</span><span class="_list-text">[[dir]]</span></a>
                <div ng-repeat-end="" class="_list _list-sub" >
                    <a ng-show="!search" ng-repeat-start="(sub, subValue) in blogCategory[dir]" ng-click="toggleSub($event)" ng-right-click="toggleSub($event)" id="blog_[[dir]]_[[sub]]" href="./#/blog/[[dir]]/[[sub]]" class="_list-item _list-dir _-blog_sub-_ issub"  data-newpost="./#/blog/write/[[dir]]/[[sub]]" title="[[sub]]" tabindex="-1"><span class="_list-arrow"></span><span class="_list-count">[[getLength(subValue)]]</span><span class="_list-text">[[sub]]</span></a>
                    <div ng-repeat-end="" class="_list _list-sub">
                        <a ng-show="!search" ng-repeat="(name, file) in blogCategory[dir][sub]" ng-click="toggleFile($event)" ng-right-click="toggleFile($event)" id="blog_[[dir]]_[[sub]]_[[name]]" href="#/blog/[[dir]]/[[sub]]/[[name]]" class="_list-item _list-hover _-blog-_ isfile" data-edit="./#/blog/edit/[[file.postid]]" title="[[name]]" tabindex="-1">[[name]]</a>
                    </div>
                </div>
                <!-- Tistory -->

                <!-- 휴지통 -->
                <h6 ng-show="trashs" ng-repeat-start="(dir, dirValue) in trashs" ng-click="toggleDir($event)" ng-right-click="toggleDir($event)" id="_휴지통" class="_list-item _list-title isdir"><span class="_list-arrow"></span>휴지통 ([[getDeepLength(dirValue)]])</h6>
                <div ng-repeat-end="" class="_list _list-sub" >
                    <a ng-show="!search" ng-repeat-start="(sub, subValue) in trashs[dir]" ng-click="toggleSub($event)" ng-right-click="toggleSub($event)" id="_휴지통_[[sub]]"  class="_list-item _list-dir issub" title="[[sub]]" tabindex="-1"><span class="_list-arrow"></span><span class="_list-count">[[getLength(subValue)]]</span><span class="_list-text">[[sub]]</span></a>
                    <div ng-repeat-end="" class="_list _list-sub">
                        <a ng-show="!search" ng-repeat="(name, file) in trashs[dir][sub]" ng-click="toggleFile($event)" ng-right-click="toggleFile($event)" id="_휴지통_[[sub]]_[[name]]" href="./#/trash/[[sub]]/[[name]]" class="_list-item _list-hover isfile" title="[[name]]" tabindex="-1">[[name]]</a>
                    </div>
                </div>
                <!-- 휴지통 -->

            </div>

            <div class="_sidebar-footer" ng-controller="ThemeCtrl">
                <button ng-click="settingButton()" type="button" class="_sidebar-footer-link _sidebar-footer-edit" data-pick-docs="">
                    Setting
                </button>
                <button type="button" class="_sidebar-footer-link _sidebar-footer-light" title="Toggle light" ng-click="toggleTheme();" id="themeChangeButton">Toggle light</button>
                <button type="button" class="_sidebar-footer-link _sidebar-footer-layout" title="Toggle layout" ng-click="toggleWidth();">Toggle layout</button>
            </div>
        </section>

        <!-- changeName Modal -->
        <div id="changeNameModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Change Name</h4>
                    </div>
                    <div class="modal-body">
                        <label for="changeName">
                            <i class="fa fa-folder" aria-hidden="true"></i> Name
                        </label>
                        <form name="changeNameForm">
                            <input ng-model="changeName" ng-required="true" ng-pattern="nameRegExp" type="text"  name="changeName" id="changeName" class="form-control" maxlength="50">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div ng-messages="changeNameForm.changeName.$error" class="pull-left text-danger" style="font-weight: bold" role="alert">
                            <p ng-message="required"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> 파일 이름을 적어주세요.</p>
                            <p ng-message="pattern"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> \ / : * ? " < > | . 같은 문자를 사용할 수 없습니다. </p>
                        </div>
                        <button ng-click="reName()" type="button" class="btn btn-primary">Change</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- changeName Modal -->

        <!-- trash Modal -->
        <div id="trashModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Throw away in the trash</h4>
                    </div>
                    <div class="modal-body">
                        <label for="changeName">
                            <i class="fa fa-trash" aria-hidden="true"></i> Trash
                        </label>
                        <form name="trashForm">
                            <input ng-model="trashName" ng-required="true" type="text"  name="trashName" id="trashName" placeholder="[[fileName]]" class="form-control" maxlength="50">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="pull-left text-danger" style="font-weight: bold" role="alert">
                            <p><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> 삭제할 파일과 같은 이름을 적어주세요.</p>
                        </div>
                        <button ng-click="trash()" type="button" class="btn btn-primary">Throw</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- trash Modal -->

        <!-- trashPost Modal -->
        <div id="trashPostModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Throw away in the trash</h4>
                    </div>
                    <div class="modal-body">
                        <label for="changeName">
                            <i class="fa fa-trash" aria-hidden="true"></i> Trash
                        </label>
                        <form name="trashPostForm">
                            <input ng-model="trashPostName" ng-required="true" type="text"  name="trashPostName" id="trashPostName" placeholder="[[fileName]]" class="form-control" maxlength="50">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="pull-left text-danger" style="font-weight: bold" role="alert">
                            <p><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> 삭제할 파일과 같은 이름을 적어주세요.</p>
                        </div>
                        <button ng-click="trashPost()" type="button" class="btn btn-primary">Throw</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- trashPost Modal -->

    </div>
    <!-- 네비게이션 -->

    <!-- 문서 -->
    <div class="_container" role="document">
        <main class="_content" id="content" role="main" tabindex="1">
            <nav class="_mobile-nav">
                <a href="#blog" class="_mobile-nav-link">Blog</a>
                <a href="#about" class="_mobile-nav-link">About</a>
                <a href="#news" class="_mobile-nav-link">News</a>
                <a href="#tips" class="_mobile-nav-link">Tip</a>
            </nav>
<!--
            _content-loading
-->
            <!-- 뷰 -->
            <div class="" ng-view>

            </div>
            <!-- 뷰 -->
        </main>
    </div>
    <!-- 문서 -->
</div>

<style data-size="18rem" data-resizer="">
    ._container { margin-left: 18rem; }
    ._search, ._list, ._sidebar-footer { width: 18rem; }
    ._list-hover.clone { min-width: 18rem; }
    ._notice, ._path, ._resizer {
        left: 18rem; }
</style>

<!--
<div role="alert" class="_notif _notif-tip _in"><p class="_notif-text">
    <strong>ProTip</strong>
    <span class="_notif-info">(click to dismiss)</span>
</p><p class="_notif-text">
    Hit <code class="_label">↓</code> <code class="_label">↑</code> <code class="_label">←</code> <code class="_label">→</code> to navigate the sidebar.<br>
    Hit <code class="_label">space / shift space</code>, <code class="_label">alt ↓/↑</code> or <code class="_label">shift ↓/↑</code> to scroll the page.
</p><p class="_notif-text">
    <a href="./help#shortcuts" class="_notif-link">See all keyboard shortcuts</a></p></div>
-->

</body>
</html>
<!-- requirejs 로 고쳐야하는데 귀찮음 -->
<script src="./js/adundocs.js"></script>
<script src="./js/controllers/adundocs_DocsCtrl.js"></script>
<script src="./js/controllers/adundocs_ViewCtrl.js"></script>
<script src="./js/controllers/adundocs_SearchCtrl.js"></script>
<script src="./js/controllers/adundocs_ThemeCtrl.js"></script>
<script src="./js/controllers/adundocs_DirCtrl.js"></script>
<script src="./js/controllers/adundocs_SubCtrl.js"></script>
<script src="./js/controllers/adundocs_NewsCtrl.js"></script>
<script src="./js/controllers/adundocs_AboutCtrl.js"></script>
<script src="./js/controllers/adundocs_WriteCtrl.js"></script>
<script src="./js/controllers/adundocs_LoginCtrl.js"></script>
<script src="./js/controllers/adundocs_EditCtrl.js"></script>
<script src="./js/controllers/adundocs_NavigationCtrl.js"></script>
<script src="./js/controllers/adundocs_TrashCtrl.js"></script>
<script src="./js/controllers/adundocs_BlogCtrl.js"></script>
<script src="./js/controllers/blog/adundocs_BlogViewCtrl.js"></script>
<script src="./js/controllers/blog/adundocs_BlogEditCtrl.js"></script>
<script src="./js/controllers/blog/adundocs_BlogDirCategoryCtrl.js"></script>
<script src="./js/controllers/blog/adundocs_BlogSubCategoryCtrl.js"></script>
<script src="./js/controllers/blog/adundocs_BlogWriteCtrl.js"></script>


