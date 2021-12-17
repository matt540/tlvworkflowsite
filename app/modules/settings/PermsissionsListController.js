"use strict";

var app = angular.module('ng-app');
app.controller('PermsissionsListController', function ($rootScope, $document, $compile, $resource, $scope, $auth, $http, msNavigationService, site_settings, DTOptionsBuilder, $mdDialog, DTColumnBuilder) {
    $scope.roles = [];
    $scope.dtInstance = {};
    $scope.listPermissions = function ()
    {
        $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
            dataSrc: "data",
            url: site_settings.api_url + 'permission/get_permissions',
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
            DTColumnBuilder.newColumn('name').withTitle('Permission Name'),
            DTColumnBuilder.newColumn('title').withTitle('Permission Title'),
            DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable()
                    .renderWith(actionsHtml),
        ];
    }
    function actionsHtml(data, type, full, meta) {


        var action_btn = '<md-fab-speed-dial md-open="demo' + data.id + '.isOpen" md-direction="left" ng-class="{ \'md-hover-full\': demo' + data.id + '.hover }" class="md-scale md-fab-top-right ng-isolate-scope md-left"  ng-mouseleave="demo' + data.id + '.isOpen=false" ng-mouseenter="demo' + data.id + '.isOpen=true">';
        action_btn += '<md-fab-trigger>';
        action_btn += '<md-button aria-label="menu" class="md-fab md-primary md-mini">';
        action_btn += '<md-icon md-svg-src="assets/angular-material-assets/icons/ic_call_to_action_white_24px.svg"></md-icon>';
        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">Action</md-tooltip>';

        action_btn += '</md-button>';
        action_btn += '</md-fab-trigger>';
        action_btn += '<md-fab-actions>';
        action_btn += '<md-button aria-label="EDIT" ng-click="openAddPermissionDialog(' + data.id + ');" class="md-fab md-accent md-raised md-mini">';
        action_btn += '<md-icon md-svg-src="assets/angular-material-assets/icons/ic_mode_edit_white_24px.svg" aria-label="Twitter"></md-icon>';
        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">EDIT</md-tooltip>';
        action_btn += '</md-button>';


        action_btn += '</md-fab-actions></md-fab-speed-dial>';
        return action_btn;

    }
    $scope.listPermissions();



    $rootScope.$on("reloadPermissionTable", function (event, args) {
        
        reloadData();
    });

    function reloadData() {

        var resetPaging = false;
        $scope.dtInstance.reloadData(callback, resetPaging);
    }

    function callback(json) {

    }



    $scope.openAddPermissionDialog = function (permission)
    {

        $mdDialog.show({
            controller: 'PermissionAddController',
            templateUrl: 'app/modules/settings/views/permission_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                permission: permission
            }
        });
    }
});

app.controller('PermissionAddController', function ($q, $rootScope, $document, $stateParams, $scope, $auth, $http, msNavigationService, site_settings, DTOptionsBuilder, $mdDialog, permission) {
    $scope.permission = {};
    $scope.permission_categories = [];
    if (permission)
    {
        $http.post(site_settings.api_url + 'permission/get_permission', {id: permission})
                .then(function (response) {
                    $scope.permission = response.data[0];


                }).catch(function (error) {

        });

    }
    $http.post(site_settings.api_url + 'permission/get_permission_categories')
            .then(function (response) {
                $scope.permission_categories = response.data;


            }).catch(function (error) {

    });
    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    }
    $scope.isEmpty = function (obj) {
        for (var i in obj)
            if (obj.hasOwnProperty(i))
                return false;
        return true;
    };
    $scope.savePermission = function ()
    {

        $http.post(site_settings.api_url + 'permission/save_permission', $scope.permission)
                .then(function (response) {

                    $rootScope.message = 'Permission Saved Successfully';
                    $rootScope.$emit("notification");
                    $rootScope.$emit("reloadPermissionTable");

                    $mdDialog.hide();
                }).catch(function (error) {
            $rootScope.message = 'Something Went Wrong';
            $rootScope.$emit("notification");
        });
    }
});