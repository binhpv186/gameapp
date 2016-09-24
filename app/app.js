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
.factory('GameData', function ($rootScope, $http, filterFilter) {
    var data = {};

    return {
        init : function () {
            $http.get("data/categories.json").then(function(response) {
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
                    var autocompletedata = new Object();
                    angular.forEach(response.data.data, function (value, index) {
                        games.push({id:index,title:value.title,slug:value.slug,category:value.category});
                        autocompletedata[value.title] = {link:value.slug + '.html',img:'game/' + value.slug + '/thumb.jpg'};
                    });
                    data.games = games;

                    $('input.autocomplete').autocomplete({
                        data: autocompletedata
                    });
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

        getCategory : function (category) {
            var cat = filterFilter(data.categories, {slug: category});
            var returnData = {title:'Not found!',games:[]};
            if(cat != undefined) {
                var returnData = {title:cat[0].title,games:[]};
                var games = filterFilter(data.games, {category: cat[0].id});
                if(games != undefined) {
                    angular.forEach(games, function (value, index) {
                        returnData.games.push({title:value.title,slug:value.slug});
                    });
                }

            }
            return returnData;
        }
    }
})
.run(function($rootScope, $location, $anchorScroll, GameData) {
    var init = GameData.init();
    if(init) {
        $rootScope.categories = GameData.listCategory();
        console.log('init ok');
    } else {
        console.log('init false');
    }
    $rootScope.goTop = function () {
        $location.hash('body');
        $anchorScroll();
    }
})
.controller('Main', function($scope, GameData) {
    $scope.games = GameData.listAllGame();
    $scope.title = 'PGame - Play game HTML5';
    document.querySelector('title').innerHTML = 'PGame - Play game HTML5 for free';
})
.controller('Category', function($rootScope, $scope, $routeParams, $location, GameData) {
    var data = GameData.getCategory($routeParams.slug);
    $scope.data = data;
    $scope.$parent.path = $location.path();
    $scope.$parent.isActive = function (viewLocation) {
        var active = (viewLocation === $location.path());
        return active;
    };
    $scope.$parent.metatitle = data.title + ' Games - PGAMES';
    // document.querySelector('title').innerHTML = data.title;
})
.controller('Detail', function($scope, $routeParams, $location, $anchorScroll, GameData, $sce) {
    var game = GameData.getGame($routeParams.slug);
    if(game != undefined) {
        $scope.item = game;
        $scope.iframeSrc = $sce.trustAsResourceUrl('game/' + $routeParams.slug + '/');
        $scope.metatitle = game.title;
        document.querySelector('title').innerHTML = game.title;
        document.querySelector('meta[property="og:image"]').setAttribute('content', $location.host() + 'game/' + $routeParams.slug + '/thumb.jpg');
    } else {
        $location.path('/404.html');
    }

    $scope.resizeIframe = function (obj) {
        var oIFrame = document.getElementById(obj);
        oIFrame.style.height = oIFrame.contentWindow.document.body.scrollHeight + 'px';
        //Scroll to game
        $location.hash(obj);
        $anchorScroll();
    }

    $scope.fullScreen = function(obj) {
        var elem = document.getElementById(obj);
        if (!(document.fullscreenEnabled || document.webkitFullscreenEnabled || document.mozFullScreenEnabled || document.msFullscreenEnabled)) {
            throw('Fullscreen mode not supported');
        }

        if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.requestFullScreen) {
                elem.requestFullScreen();
            } else if (elem.webkitRequestFullScreen) {
                elem.webkitRequestFullscreen();
            } else if (elem.webkitRequestFullScreen) {
                elem.webkitRequestFullScreen();
            } else if (elem.mozRequestFullScreen) {
                elem.mozRequestFullScreen();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            } else {
                throw('Fullscreen API not supported');
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            }
        }
    }
})
.filter('toTrusted', ['$sce', function($sce) {
    return function(text) {
        return $sce.trustAsHtml(text);
    };
}]);