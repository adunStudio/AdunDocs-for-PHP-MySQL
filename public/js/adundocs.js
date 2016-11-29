var converter = new showdown.Converter();

var AdunDocs = angular.module('AdunDocs', ['ngRoute', 'ngCookies', 'ngSanitize', 'ngMessages']);

AdunDocs.config(['$interpolateProvider', function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
}]);


AdunDocs.config(['$routeProvider', function($routeProvider) {
    $routeProvider
        .when('/',        {templateUrl: 'views/intro.html'})
        .when('/about',   {templateUrl: 'views/about.html',    controller: 'AboutCtrl'})
        .when('/aboutkor',{templateUrl: 'views/aboutkor.html', controller: 'AboutCtrl'})
        .when('/news',    {templateUrl: 'views/news.html',    controller: 'newsCtrl'})
        .when('/tips',    {templateUrl: 'views/tips.html', controller: 'AboutCtrl'})
        /*          ��α�             */
        .when('/blog',                                          {templateUrl: 'views/blog.html',                    controller: 'blogCtrl'})
        .when('/blog/write/:dirCategoryName/',  {templateUrl: 'views/blog/blog_write.html',         controller: 'BlogWriteCtrl'})
        .when('/blog/write/:dirCategoryName/:subCategoryName',  {templateUrl: 'views/blog/blog_write.html',         controller: 'BlogWriteCtrl'})
        .when('/blog/edit/:postid',                             {templateUrl: 'views/blog/blog_edit.html',          controller: 'blogEditCtrl'})
        .when('/blog/:dirCategoryName',                         {templateUrl: 'views/blog/blog_dirCategory.html',   controller: 'BlogDirCategoryCtrl'})
        .when('/blog/:dirCategoryName/:subCategoryName',        {templateUrl: 'views/blog/blog_subCategory.html',   controller: 'BlogSubCategoryCtrl'})
        .when('/blog/:dirCategoryName/:subCategoryName/:title', {templateUrl: 'views/blog/blog_view.html',          controller: 'BlogViewCtrl'})

        /*         ����                */
        .when('/write',                                  {templateUrl: 'views/write.html',   controller: 'writeCtrl'})
        .when('/write/:dirName',                                  {templateUrl: 'views/write.html',   controller: 'writeCtrl'})
        .when('/write/:dirName/:subName',                                  {templateUrl: 'views/write.html',   controller: 'writeCtrl'})
        .when('/trash/:subName/:fileName',            {templateUrl: 'views/trash.html',     controller: 'TrashCtrl'})
        .when('/edit/:dirName/:subName/:fileName',       {templateUrl: 'views/edit.html',     controller: 'editCtrl'})
        .when('/search/:dirName/:subName/:fileName',     {templateUrl: 'views/view.html',     controller: 'searchCtrl'})
        .when('/:dirName',                               {templateUrl: 'views/dir.html',      controller: 'dirCtrl'})
        .when('/:dirName/:subName',                      {templateUrl: 'views/sub.html',      controller: 'subCtrl'})
        .when('/:dirName/:subName/:fileName',            {templateUrl: 'views/view.html',     controller: 'viewCtrl'})
        .otherwise({redirectTo: '/'});
}]);

AdunDocs.directive('ngRightClick', function($parse) {
    return function(scope, element, attrs) {
        var fn = $parse(attrs.ngRightClick);
        element.bind('contextmenu', function(event) {
            scope.$apply(function() {
                event.preventDefault();
                if(element.attr('href') != undefined) {
                    location.href = element.attr('href');
                }
                fn(scope, {$event:event});
            });
        });
    };
});



