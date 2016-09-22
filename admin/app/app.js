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
.factory('GameData', function ($rootScope, $http, $httpParamSerializer, filterFilter) {
    var data = {};

    return {
        init : function () {
            $http.get("../data/categories.json").then(function(response) {
                if(angular.isObject(response)) {
                    var cats = [];
                    angular.forEach(response.data.data, function (value, index) {
                        cats.push({id:index,title:value.title,slug:value.slug});
                    });
                    $rootScope.categories = cats;
                    data.categories = cats;
                } else {
                    return false;
                }
            }, function(errorResponse) {
                return false;
            });
            $http.get("data/games.json").then(function(response) {
                if(angular.isObject(response)) {
                    var games = [];
                    angular.forEach(response.data.data, function (value, index) {
                        games.push({id:index,title:value.title,slug:value.slug,category:value.category});
                    });
                    data.games = games;
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

        listAllGame : function () {
            return data.games;
        },

        getGame : function (game) {
            var games = filterFilter(data.games, {slug: game});
            if(games != undefined && games.length > 0) {
                return games[0];
            } else {
                return false;
            }
        },

        getCategory : function (id) {
            var cat = filterFilter(data.categories, {id: id});
            var returnData = {title:'Not found!',games:[]};
            if(cat != undefined) {
                var returnData = {id:cat[0].id,title:cat[0].title,slug:cat[0].slug};
            }
            return returnData;
        },
        saveCategory : function (postdata) {
            return $http.post('lib/add_category.php', $httpParamSerializer(postdata), {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
                if(response.data.error == false) {
                    var method = response.data.method;
                    if(method == 'add') {
                        data.categories.push(response.data.data);
                    }
                } else {

                }
                return !response.data.error;
            }, function(errorResponse) {
                return false;
            });
        }
    }
})
.run(function($rootScope, GameData) {
    var init = GameData.init();
    if(init) {
        console.log('init ok');
    } else {
        console.log('init false');
    }
})
.controller('Main', function($scope, $http, $routeParams) {
    $scope.title = 'Game App';
    $scope.welcome = 'Welcome you';
    document.querySelector('title').innerHTML = 'Game App';
})
.controller('Category', function($rootScope, $scope, $http, $httpParamSerializer, orderByFilter, filterFilter, GameData) {
    $scope.categories = GameData.listCategory();
    $scope.submitCategory = function () {
        $http.post('lib/add_category.php', $httpParamSerializer($scope.form), {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
            if(response.data.error == false) {
                var method = response.data.method;
                if(method == 'add') {
                    $scope.categories.push(response.data.data);
                }
                $scope.form = angular.copy({});
                $('#modal1').closeModal();
            } else {

            }
        }, function(errorResponse) {
            return false;
        });
    }
    $scope.editCategory = function (id) {
        var game = GameData.getCategory(id);
        $scope.form = angular.copy(game);
        $('#modal1').openModal();
        Materialize.updateTextFields();
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