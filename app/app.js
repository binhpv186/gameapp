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
    .when('/:slug/', {
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
.controller('Category', function($rootScope, $scope, $http, $routeParams, filterFilter) {
    var category = filterFilter($rootScope.categories, {slug: $routeParams.slug});
    // console.log(category);
    $scope.title = category[0].title;
    $scope.welcome = 'Welcome you';
    document.querySelector('title').innerHTML = category[0].title;
})
.controller('Detail', function($scope, $http, $routeParams) {
    console.log($routeParams.slug);
    $http.get('game/' + $routeParams.slug + '/info.json').success(function(res){
        $scope.item = res;
        $scope.slug = $routeParams.slug;
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