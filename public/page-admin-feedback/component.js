(function () {
    angular
        .module('lkng-feedback', []);

    angular
        .module('lkng-feedback')
        .component('pageAdminFeedback', {
            templateUrl: '/!/ng/components/page-admin-feedback/component.html',
            controller: Controller
        });

    Controller.$inject = ['BreadCrumbs', 'LkList', '$http', 'LkEvent'];

    function Controller(BreadCrumbs, LkList, $http, LkEvent) {
        var $ctrl = this;
        $ctrl.url_config = '/!/ajax/admin-feedback/config';
        $ctrl.url_load = '/!/ajax/admin-feedback/load';
        $ctrl.url_delete = '/!/ajax/admin-feedback/delete';

        // Хлебные крошки
        BreadCrumbs.clear();
        BreadCrumbs.add('admin-feedback');
        $ctrl.breadcrumbs = BreadCrumbs.all();

        //получаем настройки списка
        LkList.config($ctrl);

        //функция загрузки данных
        $ctrl.load = function (is_clear_filters, page) {
            LkList.actionLoad($ctrl, is_clear_filters, page);
        };

        $ctrl.setState = function (model, state) {
            var cfrm = true;
            if (-1 == state) {
                cfrm = confirm('Вы действительно хотите удалить заявку?');
            }
            if (cfrm) {
                $http
                    .post('/!/ajax/admin-feedback/save', {
                        id: model.id,
                        state: state,
                    })
                    .then(function (response) {
                        larakit_toastr(response.data);
                        $ctrl.load();
                        LkEvent.fire('sidebar-reload');
                    });
            }
        };

    }
})();