(function () {

    angular
        .module('lkng-snippet', []);
    angular
        .module('lkng-snippet')
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
        $ctrl.params = {};

        $ctrl.reset = function (context, code, lang, val) {
            if(confirm("Вы действительно хотите установить исходный текст?")){
                $ctrl.items[context][code]['langs'][lang] = val;
            }
        };
        $ctrl.isShowContext = function (context) {
            if (undefined == $ctrl.params.context) {
                return true;
            }
            if ($ctrl.params.context.length == 0) {
                return true;
            }
            return (context.toLowerCase()).indexOf($ctrl.params.context.toLowerCase()) !== -1;
        };
        $ctrl.isShowCode = function (code, snippet) {
            var cnt = 0;
            _.each(snippet.langs, function (val, lang) {
                if ($ctrl.isShowTranslate(val)) {
                    cnt++;
                }
            });
            if (!cnt) {
                return false;
            }
            if (undefined == $ctrl.params.code) {
                return true;
            }
            if ($ctrl.params.code.length == 0) {
                return true;
            }
            return cnt && (code.toLowerCase()).indexOf($ctrl.params.code.toLowerCase()) !== -1;
        };
        $ctrl.isShowTranslate = function (translate) {
            if (undefined == $ctrl.params.translate) {
                return true;
            }
            if ($ctrl.params.translate.length == 0) {
                return true;
            }
            return (translate.toLowerCase()).indexOf($ctrl.params.translate.toLowerCase()) !== -1;
        };
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