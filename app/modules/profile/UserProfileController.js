"use strict";

var app = angular.module('ng-app');
app.controller('UserProfileController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder) {

    $scope.dtInstance = {};
    $scope.users = [];
    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
        dataSrc: "data",
        url: site_settings.api_url + 'users/get_users',
        type: "POST",
        headers: {Authorization: 'Bearer ' + localStorage.getItem('ngStorage-ngAA_token').replace(/["]/g, "")},
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




    function actionsHtml(data, type, full, meta) {
        var action_btn = '<md-fab-speed-dial md-open="demo' + data.id + '.isOpen" md-direction="left" ng-class="{ \'md-hover-full\': demo' + data.id + '.hover }" class="md-scale md-fab-top-right ng-isolate-scope md-left"  ng-mouseleave="demo' + data.id + '.isOpen=false" ng-mouseenter="demo' + data.id + '.isOpen=true">';
        action_btn += '<md-fab-trigger>';
        action_btn += '<md-button aria-label="menu" class="md-fab md-primary md-mini">';
        action_btn += '<md-icon md-svg-src="assets/angular-material-assets/icons/ic_call_to_action_white_24px.svg"></md-icon>';
        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">Action</md-tooltip>';

        action_btn += '</md-button>';
        action_btn += '</md-fab-trigger>';
        action_btn += '<md-fab-actions>';
        action_btn += '<md-button aria-label="EDIT" ng-click="openUserAddDialog(' + data.id + ');" class="md-fab md-accent md-raised md-mini">';
        action_btn += '<md-icon md-svg-src="assets/angular-material-assets/icons/ic_mode_edit_white_24px.svg" aria-label="Twitter"></md-icon>';
        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">EDIT</md-tooltip>';
        action_btn += '</md-button>';
        action_btn += '<md-button ng-click="showConfirm(' + data.id + ')" aria-label="DELETE" class="md-fab md-warn md-raised md-mini">';
        action_btn += '<md-icon  md-font-icon="icon-delete" aria-label="Facebook"></md-icon>';
        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">DELETE</md-tooltip>';
        action_btn += '</md-button>';

        action_btn += '</md-fab-actions></md-fab-speed-dial>';
        return action_btn;
//        $scope.persons[data.id] = data;
//        return '<md-button aria-label="Edit" ui-sref="edit_profile({id:\'' + full.id + '\'})" class="md-fab md-mini md-button  md-ink-ripple">  <md-icon md-svg-src="assets/img/edit.svg" class="icon"></md-icon></md-button><md-button aria-label="Delete" type="submit" class="md-fab md-mini md-button  md-ink-ripple md-warn">  <md-icon md-font-icon="icon-delete" class="icon" ></md-icon></md-button>';
    }
    function profileImage(data, type, full, meta) {

        if (data != '' && data != undefined)
        {
            return '<img  style="width:40px;height:40px;border-radius:30px;" src="Uploads/profile/' + data + '">';
        }
        else
        {
            return '';
        }
    }

    $scope.dtInstance = {};


    function reloadData() {

        var resetPaging = false;
        $scope.dtInstance.reloadData(callback, resetPaging);
    }


    function callback(json) {

    }
    $scope.dtColumns = [
//        DTColumnBuilder.newColumn('profile_image').withTitle('Image').renderWith(profileImage),
        DTColumnBuilder.newColumn('contactname').withTitle('Contact name'),
        DTColumnBuilder.newColumn('companyname').withTitle('Company name'),
        DTColumnBuilder.newColumn('email').withTitle('Email'),
        DTColumnBuilder.newColumn('name').withTitle('Role'),
//        DTColumnBuilder.newColumn('status').withTitle('Status').renderWith(statusHtml),
        DTColumnBuilder.newColumn('status').withTitle('Status'),
        DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable()
                .renderWith(actionsHtml),
    ];

    $scope.sync_users = function () {
        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'users/sync_users')
                .then(function (response) {
                    $rootScope.message = 'Synchronization Done Successfully';
                    $rootScope.$emit("notification");
                    $rootScope.loader = false;
                }).catch(function (error) {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
            $rootScope.loader = false;

        });
    };

    $scope.openUserAddDialog = function (user)
    {
        $mdDialog.show({
            controller: 'UsersAddController',
            templateUrl: 'app/modules/users/views/user_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                user: user
            }
        });
    }

    $rootScope.$on("reloadUserTable", function (event, args) {

        reloadData();
    });

    function reloadData() {

        var resetPaging = false;
        $scope.dtInstance.reloadData(callback, resetPaging);
    }

    function callback(json) {

    }


    $scope.showConfirm = function (id) {

        // Appending dialog to document.body to cover sidenav in docs app
        var confirm = $mdDialog.confirm()
                .title('Would you like to delete this User?')
                .ok('Yes')
                .cancel('No');

        $mdDialog.show(confirm).then(function () {
            $http.post(site_settings.api_url + 'users/delete_user', {id: id})
                    .then(function (response) {

                        $rootScope.message = 'User Deleted Successfully';
                        $rootScope.$emit("notification");
                        $rootScope.$emit("reloadUserTable");

                    }).catch(function (error) {
                $rootScope.message = 'Something Went Wrong';
                $rootScope.$emit("notification");
            });
        }, function () {

        });
    };
});
app.controller('UsersAddController', function (user, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder) {
    $scope.user = {};
    $scope.roles = {};

    $scope.getRoles = function () {
        $rootScope.loader = true;
        $http.get(site_settings.api_url + 'get_roles')
                .then(function (response) {
                    $scope.roles = response.data;
//                    $rootScope.$emit("notification");
                    $rootScope.loader = false;
                }).catch(function (error) {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
            $rootScope.loader = false;
        });
    };

    $scope.getRoles();

    if (user)
    {
        $scope.action = 'EDIT';
        $http.post(site_settings.api_url + 'users/get_user', {id: user})
                .then(function (response) {
                    $scope.user = response.data;
                    $scope.user.usertype = $scope.user.roles;
                    $scope.user.password = '********';
                    $scope.user.usertype = $scope.user.roles[0].id;

                }).catch(function (error) {

        });

    }
    else
    {
        $scope.action = 'ADD';
    }

    $scope.dzCallbacks = {
        'addedfile': function (file) {

            if (file.isMock) {
                $scope.myDz.createThumbnailFromUrl(file, file.serverImgUrl, null, true);
            }
        },
        'success': function (file, xhr) {
            $scope.user.profile_image = xhr.filename
            return false;
        }

    };
    $scope.dzMethods = {};
    $scope.removeNewFile = function () {
        $scope.dzMethods.removeFile($scope.newFile); //We got $scope.newFile from 'addedfile' event callback
    }

    $scope.saveUser = function ()
    {
        $http.post(site_settings.api_url + '/signup', $scope.user)
                .then(function (response) {

                    $rootScope.message = 'User Saved Successfully';
                    $rootScope.$emit("notification");
                    $rootScope.$emit("reloadUserTable");
                    $mdDialog.hide();
                }).catch(function (error) {
            $rootScope.message = 'Something Went Wrong';
            $rootScope.$emit("notification");
        });
    };
    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };
}).config(function (dropzoneOpsProvider) {
    dropzoneOpsProvider.setOptions({
        url: '/api/uploadImages',
        maxFilesize: '10',
        paramName: 'photo',
        addRemoveLinks: true,
        maxFiles: 1,
        acceptedFiles: 'image/*',
        thumbnailWidth: '150',
        thumbnailHeight: '150',
        dictDefaultMessage: '<img width="200" src="/assets/images/avatars/profile.jpg">',
        maxfilesexceeded: function (file) {
            this.removeAllFiles();
            this.addFile(file);

        },
        sending: function (file, xhr, formData) {

            formData.append('folder', 'profile');
        },
    });
});