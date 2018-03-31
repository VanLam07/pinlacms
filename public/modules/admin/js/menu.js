(function ($) {

    angular.module('ngMenu', ['ui.nestedSortable'])
            .config(function ($interpolateProvider, $qProvider) {
                $interpolateProvider.startSymbol('{%');
                $interpolateProvider.endSymbol('%}');
                
                $qProvider.errorOnUnhandledRejections(false);
            })
            .controller('MenuCtrl', function ($scope, $http, $timeout) {
                $scope.menus = [];

                function reloadMenus() {
                        $http.get(menu_nested_url, {
                            params: {
                                lang: _lang,
                                group_id: group_id,
                                per_page: -1
                            }
                        }).then(function (result) {
                            $scope.menus = result.data;
                        }).catch(function (err) {
                            console.log(err);
                        });
                }
                $timeout(function () {
                    reloadMenus();
                }, 1000);

                $scope.options = {
                };

                $scope.posts = [];
                $timeout(function () {
                $scope.posts_params = {'fields[]': ['pd.title', 'posts.id']};
                    $http.get(api_url + 'posts', {
                        params: $scope.posts_params
                    }).then(function (result) {
                        var data = result.data;
                        $scope.post_total = data.total;
                        $scope.post_per_page = data.per_page;
                        $scope.post_next_page_url = data.next_page_url;
                        $scope.post_prev_page_url = data.prev_page_url;
                        $scope.posts = data.data;
                    });
                }, 1500);

                $scope.postGoUrl = function (url) {
                    if (url) {
                        $http.get(url, {
                            params: $scope.posts_params
                        }).then(function (data) {
                            $scope.posts = data.data;
                            $scope.post_next_page_url = data.next_page_url;
                            $scope.post_prev_page_url = data.prev_page_url;
                        });
                    }
                };

                $scope.pages = [];
                $timeout(function () {
                    $http.get(api_url + 'pages', {
                        params: {'fields[]': ['pd.title', 'posts.id'], per_page: -1}
                    }).then(function (results) {
                        $scope.pages = results.data;
                    });
                }, 2000);

                $scope.cats = [];
                $timeout(function () {
                    $http.get(api_url + 'cats', {
                        params: {
                            'fields[]': ['td.name', 'taxs.id'],
                            per_page: -1
                        }
                    }).then(function (results) {
                        $scope.cats = results.data;
                    });
                }, 2500);
                
                $scope.albums = [];
                $timeout(function () {
                    $http.get(api_url + 'albums', {
                        params: {
                            'fields[]': ['td.name', 'taxs.id'],
                            per_page: -1
                        }
                    }).then(function (results) {
                        $scope.albums = results.data;
                    });
                }, 3000);

                $scope.newMenus = [];
                $scope.newcustom = {};
                $scope.addMenu = function (item, type) {
                    var index = $scope.newMenus.indexOf(item);
                    if (index > -1) {
                        $scope.newMenus.splice(index, 1);
                    } else {
                        item.menu_type = type;
                        $scope.newMenus.push(item);
                    }
                };

                $scope.createMenus = function () {
                    if ($scope.newcustom.title) {
                        $scope.addMenu($scope.newcustom, 0);
                    }
                    if ($scope.newMenus.length > 0) {
                        $http.post(store_items_url, {
                            menuItems: $scope.newMenus,
                            group_id: group_id,
                            _token: _token,
                            lang: _lang
                        }).then(function (data) {
                            $scope.newcustom = {};
                            $scope.newMenus = [];
                            reloadMenus();
                            $('.list_types input[type="checkbox"]').prop('checked', false);
                        }).catch(function (err) {
                            console.log(err);
                        });
                    }
                };

                $scope.removeMenu = function (item) {
                    $http.delete(remove_item_url, {
                        params: {id: item.id}
                    }).then(function (data) {
                        reloadMenus();
                    });
                };

                $scope.updateOrder = function (e) {
                    e.preventDefault();
                    $http.post(order_items_url, {
                        menus: $scope.menus, 
                        _token: _token
                    }).then(function (data) {
                        $scope.updateStatus = data.data;
                    }).catch(function (err) {
                        $scope.updateStatus = 'Error!';
                        e.preventDefault();
                    });
                };
            })
            .directive('ngConfirm', function () {
                return{
                    priority: -10,
                    link: function (scope, element, attrs) {
                        element.bind('click', function (e) {
                            var cnfirm = confirm(attrs.ngConfirm);
                            if (!cnfirm) {
                                e.stopImmediatePropagation();
                                e.preventDefault;
                            }
                        });
                    }
                };
            })
            .filter('menutype', function () {
                return function (type) {
                    if (menu_type[type] != "undefined") {
                        return menu_type[type];
                    }
                    return 'N/A';
                };
            });

})(jQuery);


