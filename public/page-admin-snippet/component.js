(function () {

    angular
        .module('larakit')
        .component('pageAdminSnippet', {
            templateUrl: '/packages/larakit/lkng-snippet/page-admin-snippet/component.html',
            controller: Controller
        });

    Controller.$inject = ['$http', 'BreadCrumbs', 'LkList'];

    function Controller($http, BreadCrumbs, LkList) {
        var $ctrl = this;

        // Хлебные крошки
        BreadCrumbs.clear();
        BreadCrumbs.add('admin-snippet');
        $ctrl.breadcrumbs = BreadCrumbs.all();

        //получаем настройки списка
        $ctrl.snippets = {};

        //функция загрузки данных
        $ctrl.load = function () {
            $http
                .get('/!/lkng-snippet/load')
                .then(
                    function (response) {
                        $ctrl.snippets = response.data.snippets;
                        $ctrl.items = response.data.items;
                    }
                )
            ;
        };
        $ctrl.save = function () {
            $http
                .post('/!/lkng-snippet/save', {items: $ctrl.items})
                .then(
                    function (response) {
                        $ctrl.load();
                        larakit_toastr(response.data);
                    }
                )
            ;
        };
        $ctrl.load();
    }
})();