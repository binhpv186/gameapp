var gameapp = angular.module('gameapp', ['ngRoute', 'ngSanitize'])
.config(function($routeProvider, $locationProvider) {
    $locationProvider.html5Mode(true);
    $routeProvider
    .when('/', {
        templateUrl: 'templates/main.html',
        controller: 'Main'
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
.factory('GameData', function ($rootScope, $http) {
    var data = {};

    return {
        init : function () {
            $http.get("data/categories.json").then(function(response) {
                if(angular.isObject(response)) {
                    $rootScope.categories = response.data;
                    data.categories = response.data;
                } else {
                    return false;
                }
            }, function(errorResponse) {
                return false;
            });
            $http.get("data/games.json").then(function(response) {
                if(angular.isObject(response)) {
                    data.games = response.data;
                } else {
                    return false;
                }
            }, function(errorResponse) {
                return false;
            });
            return true;
        },

        listCategory : function () {
            return data.categories;
        },

        getCategory : function (category) {
            return data.categories;
        }
    }
})
.run(function($rootScope, GameData) {
    var init = GameData.init();
    if(init) {
        $rootScope.categories = GameData.listCategory();
        console.log('init ok');
    } else {
        console.log('init false');
    }

})
.controller('Main', function($rootScope, $scope, $http) {
    $scope.title = 'Game App';
    $scope.welcome = 'Welcome you';
    document.querySelector('title').innerHTML = 'Game App';
})
.controller('Category', function($rootScope, $scope, $http, $routeParams, filterFilter, GameData) {
    var category = filterFilter($rootScope.categories, {slug: $routeParams.slug});
    $scope.title = category[0].title;
    $scope.welcome = 'Welcome you';
    document.querySelector('title').innerHTML = category[0].title;
})
.controller('Detail', function($rootScope, $scope, $http, $routeParams, $location, filterFilter, $sce) {
    var game = filterFilter($rootScope.games, {slug: $routeParams.slug});
    if(game != undefined && game.length > 0) {
        $scope.item = game[0];
        $scope.iframeSrc = $sce.trustAsResourceUrl('game/' + $routeParams.slug + '/');
        document.querySelector('title').innerHTML = game[0].title;
    } else {
        $location.path('/404.html');
    }
})
.filter('toTrusted', ['$sce', function($sce) {
    return function(text) {
        return $sce.trustAsHtml(text);
    };
}]);