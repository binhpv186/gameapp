var gameapp = angular.module('gameapp', ['ui.router', 'ngSanitize'])
.config(function($stateProvider, $locationProvider, USER_ROLES) {
    $locationProvider.html5Mode(true);
    $stateProvider
    .state('dashboard', {
        url: '/dashboard',
        templateUrl: 'templates/dashboard.html',
        controller: 'Main',
        data: {
            authorizedRoles: [USER_ROLES.admin, USER_ROLES.editor]
        }
    })
    .state('login', {
        url: '/login',
        templateUrl: 'templates/login.html',
        controller: 'Login',
        data: {
            authorizedRoles: [USER_ROLES.all]
        }
    })
    .state('category', {
        url: '/category',
        templateUrl: 'templates/category.html',
        controller: 'Category',
        data: {
            authorizedRoles: [USER_ROLES.admin, USER_ROLES.editor]
        }
    })
    .state('game', {
        url: '/game',
        templateUrl: 'templates/game.html',
        controller: 'Game',
        data: {
            authorizedRoles: [USER_ROLES.admin, USER_ROLES.editor]
        }
    })
    .state('403', {
        url: '/403',
        templateUrl: 'templates/403.html',
    })
})
.constant('USER_ROLES', {
    all: '*',
    admin: 'admin',
    editor: 'editor',
    guest: 'guest'
})
.constant('AUTH_EVENTS', {
    loginSuccess: 'auth-login-success',
    loginFailed: 'auth-login-failed',
    logoutSuccess: 'auth-logout-success',
    sessionTimeout: 'auth-session-timeout',
    notAuthenticated: 'auth-not-authenticated',
    notAuthorized: 'auth-not-authorized'
})
.factory('localStorage', function ($window){
    if($window.localStorage){
        return $window.localStorage;
    }
    throw new Error('Local storage support is needed');
})
.service('Session', function ($log, localStorage) {
    // Instantiate data when service
    // is loaded
    this._user = JSON.parse(localStorage.getItem('session.user'));
    this._role = JSON.parse(localStorage.getItem('session.user.role'));
    this._accessToken = JSON.parse(localStorage.getItem('session.accessToken'));

    this.getUser = function(){
        return this._user;
    };

    this.setUser = function(user){
        this._user = user;
        localStorage.setItem('session.user', JSON.stringify(user));
        return this;
    };

    this.getRole = function(){
        return this._role;
    };

    this.setRole = function(role){
        this._role = role;
        localStorage.setItem('session.user.role', JSON.stringify(role));
        return this;
    };

    this.getAccessToken = function(){
        return this._accessToken;
    };

    this.setAccessToken = function(token){
        this._accessToken = token;
        localStorage.setItem('session.accessToken', JSON.stringify(token));
        return this;
    };

    /**
     * Destroy session
     */
    this.destroy = function destroy(){
        this.setUser(null);
        this.setRole(null);
        this.setAccessToken(null);
    };
})
.factory('AuthService', function ($http, $state, $httpParamSerializer, Session, USER_ROLES) {
    var authService = {};

    authService.login = function (credentials) {
        return $http
            .post('lib/login.php', $httpParamSerializer(credentials), {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
            .then(function (res) {
                var data = res.data;
                Session.setUser(data.user.id);
                Session.setRole(data.user.role);
                Session.setAccessToken(data.id);
                return res.data.user;
            });
    };

    authService.logout = function () {
        return $http
            .post('lib/logout.php')
            .then(function (res) {
                Session.destroy();
                $state.go('login');
            });
    };

    authService.isAuthenticated = function () {
        return Session.getUser() !== null;
    };

    authService.isAuthorized = function (authorizedRoles) {
        if (!angular.isArray(authorizedRoles)) {
            authorizedRoles = [authorizedRoles];
        }
        if(authorizedRoles.indexOf(USER_ROLES.all) !== -1) {
            return true;
        } else {
            return (authService.isAuthenticated() &&
            authorizedRoles.indexOf(Session.getRole()) !== -1);
        }
    };

    return authService;
})
.factory('GameData', function ($rootScope, $http, $httpParamSerializer, filterFilter) {
    var data = {};

    return {
        init : function () {
            $http.get("../data/categories.json").then(function(response) {
                if(angular.isObject(response)) {
                    var cats = [];
                    angular.forEach(response.data.data, function (value, index) {
                        cats.push({id:index,title:value.title,slug:value.slug,desc:value.desc,meta_title:value.meta_title,meta_desc:value.meta_desc});
                    });
                    $rootScope.categories = cats;
                    data.categories = cats;
                } else {
                    return false;
                }
            }, function(errorResponse) {
                return false;
            });
            $http.get("../data/games.json").then(function(response) {
                if(angular.isObject(response)) {
                    var games = [];
                    angular.forEach(response.data.data, function (value, index) {
                        games.push({id:index,title:value.title,slug:value.slug,desc:value.desc,meta_title:value.meta_title,meta_desc:value.meta_desc,category:value.category});
                    });
                    $rootScope.games = games;
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
        getGameById : function (id) {
            var games = filterFilter(data.games, {id: id});
            if(games != undefined && games.length > 0) {
                return games[0];
            } else {
                return false;
            }
        },
        saveGame : function (postdata) {
            $http.post('lib/add_game.php', $httpParamSerializer(postdata), {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
                if(response.data.error == false) {
                    var method = response.data.method;
                    if(method == 'add') {
                        $scope.games.push(response.data.data);
                    }
                    $scope.form = angular.copy({});
                    $('#modal1').closeModal();
                }
                return (response.data.error == false);
            }, function(errorResponse) {
                return false;
            });
        },
        getCategory : function (id) {
            var cat = filterFilter(data.categories, {id: id});
            var returnData = {title:'Not found!',games:[]};
            if(cat != undefined) {
                var returnData = {id:cat[0].id,title:cat[0].title,slug:cat[0].slug,meta_title:cat[0].meta_title,meta_desc:cat[0].meta_desc};
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
                    // console.log(response.data.data);
                    return response.data.data;
                }
                return (response.data.error == false);
            }, function(errorResponse) {
                return false;
            });
        },
        deleteCategory : function (categoryId) {
            $http.post('lib/delete_category.php', 'id='+categoryId, {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
                if(response.data.error == false) {
                    var category = filterFilter($rootScope.categories, {id: categoryId})[0];
                    var index = $rootScope.categories.indexOf(category);
                    $rootScope.categories.splice(index, 1);
                } else {

                }
            });
        }
    }
})
.run(function($rootScope, $state, AUTH_EVENTS, USER_ROLES, AuthService, Session, GameData) {
    $rootScope.$on('$stateChangeStart', function (event, next, current) {
        if(next.data !== undefined && next.data.authorizedRoles !== undefined) {
            var authorizedRoles = next.data.authorizedRoles;
        } else {
            var authorizedRoles = USER_ROLES.all;
        }
        if (!AuthService.isAuthorized(authorizedRoles)) {
            event.preventDefault();
            if (AuthService.isAuthenticated()) {
                // user is not allowed
                $rootScope.$broadcast(AUTH_EVENTS.notAuthorized);
                $state.go('dashboard');
            } else {
                // user is not logged in
                $rootScope.$broadcast(AUTH_EVENTS.notAuthenticated);
                $state.go('login');
            }
        } else {
            // $state.go('403');
            // $state.$current.templateUrl('templates/403.html');
        }
    });
    $rootScope.auth = AuthService;
    $rootScope.session = Session;
    var init = GameData.init();
    if(init) {
        console.log('init ok');
    } else {
        console.log('init false');
    }
})
.controller('Main', function($scope) {
    $scope.title = 'Game App';
    $scope.welcome = 'Welcome you';
    document.querySelector('title').innerHTML = 'Game App';
})
.controller('Login', function ($scope, $rootScope, $state, AUTH_EVENTS, AuthService) {
    if(AuthService.isAuthenticated()) {
        $state.go('dashboard');
    }
    $scope.credentials = {
        username: '',
        password: ''
    };
    $scope.login = function (credentials) {
        AuthService.login(credentials).then(function (user) {
            $rootScope.$broadcast(AUTH_EVENTS.loginSuccess);
            // $scope.setCurrentUser(user);
            $state.go('dashboard');
        }, function () {
            $rootScope.$broadcast(AUTH_EVENTS.loginFailed);
        });
    };
})
.controller('Category', function($rootScope, $scope, $http, $httpParamSerializer, orderByFilter, filterFilter, GameData) {
    // $scope.categories = GameData.listCategory();
    $scope.submitCategory = function () {
        return $http.post('lib/add_category.php', $httpParamSerializer($scope.form), {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
            if(response.data.error == false) {
                var method = response.data.method;
                if(method == 'add') {
                    $rootScope.categories.push(response.data.data);
                }
                var category = filterFilter($rootScope.categories, {id: response.data.data.id})[0];
                if(category) {
                    var index = $rootScope.categories.indexOf(category);
                    if(index !== -1) {
                        $rootScope.categories[index] = angular.copy(response.data.data);
                    }
                    $scope.form = angular.copy({});
                    $('#modal1').closeModal();
                }

                return response.data.data;
            }
            return (response.data.error == false);
        }, function(errorResponse) {
            return false;
        });
    }
    $scope.editCategory = function (id) {
        var game = GameData.getCategory(id);
        $scope.form = angular.copy(game);
        $('#modal1').openModal();
    }
    $scope.deleteCategory = function (categoryId) {
        $http.post('lib/delete_category.php', 'id='+categoryId, {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
            if(response.data.error == false) {
                var category = filterFilter($rootScope.categories, {id: categoryId})[0];
                var index = $rootScope.categories.indexOf(category);
                $rootScope.categories.splice(index, 1);
            } else {

            }
        });
    }
    document.querySelector('title').innerHTML = 'Category Manager - Admin';
})
.controller('Game', function($rootScope, $scope, $http, $httpParamSerializer, orderByFilter, filterFilter, GameData) {
    // $scope.games = GameData.listAllGame();
    $scope.submitGame = function () {
        return $http.post('lib/add_game.php', $httpParamSerializer($scope.form), {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
            if(response.data.error == false) {
                var method = response.data.method;
                if(method == 'add') {
                    $rootScope.games.push(response.data.data);
                }
                var category = filterFilter($rootScope.games, {id: response.data.data.id})[0];
                if(category) {
                    var index = $rootScope.games.indexOf(category);
                    if(index !== -1) {
                        $rootScope.games[index] = angular.copy(response.data.data);
                    }
                    $scope.form = angular.copy({});
                    $('#modal1').closeModal();
                }

                return response.data.data;
            }
            return (response.data.error == false);
        }, function(errorResponse) {
            return false;
        });
    }
    $scope.editGame = function (id) {
        // $('select').material_select('destroy');
        var editGame = GameData.getGameById(id);
        // console.log(editGame);
        $scope.form = angular.copy(editGame);
        // $scope.form.category = filterFilter($rootScope.categories, {id: editGame.category})[0];
        $('#modal1').openModal();
    }
    $scope.deleteGame = function (id) {
        $http.post('lib/delete_game.php', 'id='+id, {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
            if(response.data.error == false) {
                var game = filterFilter($rootScope.games, {id: id})[0];
                var index = $rootScope.games.indexOf(game);
                $rootScope.games.splice(index, 1);
            } else {

            }
        });
    }
    document.querySelector('title').innerHTML = 'Game App';
})
.filter('toTrusted', ['$sce', function($sce) {
    return function(text) {
        return $sce.trustAsHtml(text);
    };
}]);