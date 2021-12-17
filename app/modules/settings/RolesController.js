"use strict";

var app = angular.module('ng-app');
app.controller('RolesController', function ($rootScope, $document, $compile, $resource, $scope, $auth, $http, msNavigationService, site_settings, DTOptionsBuilder, $mdDialog, DTColumnBuilder) {
    $scope.roles = [];
    $scope.dtInstance = {};
    $scope.listRoles = function ()
    {
        $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
            dataSrc: "data",
            url: site_settings.api_url + 'roles/get_roles',
            type: "POST",
//            headers: {Authorization: 'Bearer ' + localStorage.getItem('ngStorage-ngAA_token').replace(/["]/g, "")},
        }).withOption('processing', true) //for show progress bar
                .withOption('serverSide', true) // for server side processing
                .withPaginationType('full_numbers') // for get full pagination options // first / last / prev / next and page numbers
                .withDisplayLength(10) // Page size
                .withOption('aaSorting', [0, 'asc'])
                .withOption('autoWidth', false)
                .withOption('responsive', true)
                .withOption('dom', '<"top"f>rt<"bottom"<"left"<"length"l>><"right"<"info"i><"pagination"p>>>')
                .withDataProp('data')
                .withOption('fnRowCallback',
                        function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                            $compile(nRow)($scope);
                        });
        $scope.dtColumns = [
            DTColumnBuilder.newColumn('name').withTitle('Role Name'),
            DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable()
                    .renderWith(actionsHtml),
        ];
    }
    function actionsHtml(data, type, full, meta) {

        var action_btn = '<md-fab-speed-dial md-direction="right" ng-class="md-fling" class="md-scale">';
        action_btn += '<md-fab-trigger>';
        action_btn += '<md-button aria-label="menu" class="md-fab md-primary md-mini">';
        action_btn += '<md-icon md-svg-src="assets/angular-material-assets/icons/ic_call_to_action_white_24px.svg"></md-icon>';
        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">Action</md-tooltip>';

        action_btn += '</md-button>';
        action_btn += '</md-fab-trigger>';
        action_btn += '<md-fab-actions>';
        action_btn += '<md-button aria-label="EDIT" ng-click="openAddRoleDialog(' + data.id + ');" class="md-fab md-accent md-raised md-mini">';
        action_btn += '<md-icon md-svg-src="assets/angular-material-assets/icons/ic_mode_edit_white_24px.svg" aria-label="Twitter"></md-icon>';
        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">EDIT</md-tooltip>';
        action_btn += '</md-button>';
        action_btn += '<md-button ng-click="showConfirm(' + data.id + ')" aria-label="DELETE" class="md-fab md-warn md-raised md-mini">';
        action_btn += '<md-icon  md-font-icon="icon-delete" aria-label="Facebook"></md-icon>';
        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">DELETE</md-tooltip>';
        action_btn += '</md-button>';

        action_btn += '</md-fab-actions></md-fab-speed-dial>';
        return action_btn;
//        return '<md-button aria-label="Edit" ui-sref="edit_profile({id:\'' + data.id + '\'})" class="md-fab md-mini md-button md-ink-ripple">  <md-icon md-svg-icon="assets/angular-material-assets/icons/ic_mode_edit_white_24px.svg" class="icon"></md-icon></md-button><md-button aria-label="Delete" type="submit" class="md-fab md-mini md-button md-default-theme md-ink-ripple md-warn">  <md-icon md-font-icon="icon-delete" class="icon" ></md-icon></md-button>';
    }
    $scope.listRoles();
    $scope.openAddRoleDialog = function (role)
    {

        $mdDialog.show({
            controller: 'RolesAddController',
            templateUrl: 'app/modules/settings/views/roles_add.html',
            parent: angular.element($document.body),
//            targetEvent: ev,
            clickOutsideToClose: true,
            locals: {
                role: role
            }
        });
    }


    $rootScope.$on("reloadRoleTable", function (event, args) {
        console.log('In')
        reloadData();
    });

    function reloadData() {

        var resetPaging = false;
        $scope.dtInstance.reloadData(callback, resetPaging);
    }

    function callback(json) {

    }


    $scope.showConfirm = function (id) {
        console.log('dhfg')
        // Appending dialog to document.body to cover sidenav in docs app
        var confirm = $mdDialog.confirm()
                .title('Would you like to delete this Role?')
//                .textContent('')
//                .ariaLabel('Lucky day')
                .ok('Yes')
                .cancel('No');

        $mdDialog.show(confirm).then(function () {
            $http.post(site_settings.api_url + 'roles/delete_role', {id: id})
                    .then(function (response) {

                        $rootScope.message = 'Role Deleted Successfully';
                        $rootScope.$emit("notification");
                        $rootScope.$emit("reloadRoleTable");

                    }).catch(function (error) {
                $rootScope.message = 'Something Went Wrong';
                $rootScope.$emit("notification");
            });
        }, function () {

        });
    };
});
app.controller('RolesAddController', function ($q, $rootScope, $document, $stateParams, $scope, $auth, $http, msNavigationService, site_settings, DTOptionsBuilder, $mdDialog, role) {
    $scope.role = {};
    
    if (role)
    {

        $http.post(site_settings.api_url + 'roles/get_role', {id: role})
                .then(function (response) {
                    $scope.role = response.data[0];


                }).catch(function (error) {

        });

    }
    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    }

    $scope.saveRole = function ()
    {

        $http.post(site_settings.api_url + 'roles/save_role', $scope.role)
                .then(function (response) {

                    $rootScope.message = 'Role Saved Successfully';
                    $rootScope.$emit("notification");
                    $rootScope.$emit("reloadRoleTable");
                    $mdDialog.hide();
                }).catch(function (error) {
            $rootScope.message = 'Something Went Wrong';
            $rootScope.$emit("notification");
        });
    }
});