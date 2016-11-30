var converter = converter || new showdown.Converter();

AdunDocs.controller('searchCtrl', ['$scope', '$http', '$routeParams', '$timeout', function searchCtrl($scope, $http, $routeParams, $timeout) {
    var dirName  = $routeParams.dirName;
    var subName  = $routeParams.subName;
    var fileName = $routeParams.fileName;


    $http({
        method  : 'POST',
        url     : './article/view',
        data    : {
            dirName: dirName,
            subName: subName,
            fileName: fileName
        },
        headers : {'Content-Type': 'application/json'}
    }).then(function (response) {
        var html = markdown = converter.makeHtml(response.data.fileData);

        $('#main').html(html).find('pre code').each(function(i, block) {
            hljs.highlightBlock(block);
        });
    });


    $scope.setDocStat(dirName, subName, fileName, $scope.docs[dirName][subName][fileName].btime, $scope.docs[dirName][subName][fileName].mtime);

}]);