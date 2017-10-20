(function () {

    angular
        .module('lkng-snippet', []);
    angular
        .module('lkng-snippet')
        .component('pageAdminSnippet', {
            templateUrl: '/packages/larakit/lkng-snippet/page-admin-snippet/component.html',
            controller: Controller
        });

    Controller.$inject = ['$http', 'BreadCrumbs', '$route'];

    function Controller($http, BreadCrumbs, $route) {
        var $ctrl = this;
        $ctrl.key = $route.current.params.key;
        console.log($ctrl.key);
        // Хлебные крошки
        BreadCrumbs.clear();
        BreadCrumbs.add('admin-snippet');
        $ctrl.breadcrumbs = BreadCrumbs.all();

        //получаем настройки списка
        $ctrl.snippets = {};
        $ctrl.params = {};

        $ctrl.reset = function (key, code, lang, val) {
            if (confirm("Вы действительно хотите установить исходный текст?")) {
                $ctrl.items[key]['items'][code]['langs'][lang] = val;
            }
        };
        //функция загрузки данных
        $ctrl.load = function () {
            $http
                .get('/!/lkng-snippet/load')
                .then(
                    function (response) {
                        $ctrl.snippets = response.data.snippets;
                        $ctrl.items = response.data;
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