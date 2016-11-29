
AdunDocs.controller('dirCtrl', ['$scope', '$routeParams', function dirCtrl($scope, $routeParams) {
    var dirName  =  $routeParams.dirName;
    var check  =  $routeParams.check;


    $scope.setDocStat(dirName);


    var dirEl =  angular.element(document.getElementById('_' + dirName));

    if( $scope.isToggleCheck == false || check ) {
        $scope.toggleCheck(dirEl);
    }
}]);