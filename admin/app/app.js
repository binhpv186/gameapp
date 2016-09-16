var gameapp = angular.module('gameapp', ['ngRoute', 'ngSanitize'])
.config(function($routeProvider, $locationProvider) {
    $locationProvider.html5Mode(true);
    $routeProvider
    .when('/', {
        templateUrl: 'templates/main.html',
        controller: 'Main'
    })
    .when('/404.html', {
        templateUrl: 'templates/detail.html',
        controller: 'Detail'
    })
    .when('/:slug.html', {
            templateUrl: 'templates/detail.html',
            controller: 'Detail'
    })
    .when('/category/', {
        templateUrl: 'templates/category.html',
        controller: 'Category'
    })
    .otherwise({templateUrl:'templates/404.html'})
})
.run(function($rootScope, categories) {
    $rootScope.categories = categories;
})
.controller('Main', function($scope, $http, $routeParams) {
    $scope.title = 'Game App';
    $scope.welcome = 'Welcome you';
    document.querySelector('title').innerHTML = 'Game App';
})
.controller('Category', function($rootScope, $scope, $http, $httpParamSerializer, orderByFilter, filterFilter) {
    $scope.submitCategory = function () {
        var lastCategory = orderByFilter($rootScope.categories, '-id')[0];
        $scope.form.id = parseInt(lastCategory.id)+1;
        $http.post('lib/add_category.php', $httpParamSerializer($scope.form), {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
            if(response.data.error == false) {
                $rootScope.categories.push($scope.form);
                console.log($rootScope.categories);
                $scope.form = angular.copy({});
                $('#modal1').closeModal();
            } else {

            }
        });
    }
    $scope.editCategory = function () {

    }
    $scope.deleteCategory = function (categoryId) {
        $http.post('lib/delete_category.php', 'id='+categoryId, {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
            if(response.data.error == false) {
                var category = filterFilter($rootScope.categories, {id: categoryId})[0];
                console.log(category);
                var index = $rootScope.categories.indexOf(category);
                $rootScope.categories.splice(index, 1);
            } else {

            }
        });
    }
    document.querySelector('title').innerHTML = 'Category Manager - Admin';
})
.controller('Detail', function($scope, $http, $routeParams) {
    console.log($routeParams.slug);
    $http.get('game/' + $routeParams.slug + '/info.json').success(function(res){
        $scope.item = res;
    }).error(function (responseError) {
        $scope.item = {'title' : 'Error', 'desc' : 'Cannot load game data'};
    });
    $scope.title = 'Game App';
    $scope.welcome = 'Welcome you';
    document.querySelector('title').innerHTML = 'Game App';
})
.filter('toTrusted', ['$sce', function($sce) {
    return function(text) {
        return $sce.trustAsHtml(text);
    };
}]);