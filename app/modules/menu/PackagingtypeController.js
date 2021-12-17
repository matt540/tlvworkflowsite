"use strict";

var app = angular.module('ng-app');
app.controller('CategoryController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder,ngAAToken) {

    $scope.dtInstance = {};
    $scope.category = [];
    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
        dataSrc: "data",
        url: site_settings.api_url + 'category/get_categorys',
        type: "POST",
        headers: {Authorization: 'Bearer ' + ngAAToken.getToken()},
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
        var action_btn = '<md-fab-speed-dial  style=" transform: translate(-110px, 0px);"  md-open="demo' + data.id + '.isOpen" md-direction="left" ng-class="{ \'md-hover-full\': demo' + data.id + '.hover }" class="md-scale md-fab-top-right ng-isolate-scope md-left"  ng-mouseleave="demo' + data.id + '.isOpen=false" ng-mouseenter="demo' + data.id + '.isOpen=true">';
        action_btn += '<md-fab-trigger>';
        action_btn += '<md-button aria-label="menu" class="md-fab md-primary md-mini">';
        action_btn += '<md-icon md-svg-src="assets/angular-material-assets/icons/ic_call_to_action_white_24px.svg"></md-icon>';
        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">Action</md-tooltip>';

        action_btn += '</md-button>';
        action_btn += '</md-fab-trigger>';
        action_btn += '<md-fab-actions>';
        action_btn += '<md-button aria-label="EDIT" ng-click="openCategoryAddDialog(' + data.id + ');" class="md-fab md-accent md-raised md-mini">';
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

    $scope.dtInstance = {};

//    function itemImage(data, type, full, meta) {
//
//        if (data != '' && data != undefined)
//        {
//            return '<img  style="width:65px;height:65px;border-radius:85px;" src="Uploads/menu_item/' + data + '">';
//        }
//        else
//        {
//            return '';
//        }
//    }

    function reloadData() {

        var resetPaging = false;
        $scope.dtInstance.reloadData(callback, resetPaging);
    }


    function callback(json) {

    }
    $scope.dtColumns = [
//        DTColumnBuilder.newColumn('item_image').withTitle('Image').renderWith(itemImage),
        DTColumnBuilder.newColumn('category_name').withTitle('Category Name'),
        DTColumnBuilder.newColumn('status').withTitle('Status').renderWith(statusHtml),
//        DTColumnBuilder.newColumn('status').withTitle('Status'),
        DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable()
                .renderWith(actionsHtml),
    ];

    function statusHtml(data, type, full, meta) {

        if (data == 'Active')
        {
            return '<button aria-label="{{hover_' + full.id + ' ==true ? \'Inactive\' : \'Active\' }}"  class="md-primary md-raised md-button md-jd-theme md-ink-ripple"\n\
                     ng-class="hover==true ? md-warn : md-primary"    ng-mouseenter="hover_' + full.id + ' = true"    ng-mouseleave="hover_' + full.id + ' = false"\n\
                     type="button" ng-click="change_status(\'' + full.id + '\',\'' + 'InActive' + '\')" >{{hover_' + full.id + ' ==true ? \'Inactive\' : \'Active\' }}</button>';
        }
        else if (data == 'InActive')
        {
            return '<button aria-label="{{hover_' + full.id + ' ==true ? \'Active\' : \'InActive\' }}"  class="md-warn md-raised md-button md-default-theme md-ink-ripple"\n\
                     ng-class="hover==true ? md-primary : md-warn"    ng-mouseenter="hover_' + full.id + ' = true"    ng-mouseleave="hover_' + full.id + ' = false"\n\
                     type="button" ng-click="change_status(\'' + full.id + '\',\'' + 'Active' + '\')" >{{hover_' + full.id + ' ==true ? \'Active\' : \'InActive\' }}</button>';

        }
        else
        {
            return '<button aria-label="s" class="md-warm md-raised md-button md-default-theme md-ink-ripple" ng-class="hover==true ? md-warn : md-primary"    ng-mouseenter="hover = true"    ng-mouseleave="hover = false"\n\ type="button" ng-click="" >' + data + '</button>';

        }
    }

    $scope.change_status = function (category, status) {

        var deferred = $q.defer();
        var cred = {category: category, status: status}

        $http.post(site_settings.api_url + 'category/change_category_status', cred)
                .then(function (response) {
                    $rootScope.message = 'Status change Successfully';
                    $rootScope.$emit("notification");
                    reloadData();
                }).catch(function (error) {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
            $rootScope.loader = false;

        });
    }

    $scope.openCategoryAddDialog = function (categoryid)
    {
        $mdDialog.show({
            controller: 'CategoryAddController',
            templateUrl: 'app/modules/menu/views/category_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                categoryid: categoryid
            }
        });
    }

    $rootScope.$on("reloadCategoryTable", function (event, args) {

        reloadData();
    });

    $scope.showConfirm = function (id) {

        // Appending dialog to document.body to cover sidenav in docs app
        var confirm = $mdDialog.confirm()
                .title('Would you like to delete this Category?')
                .ok('Yes')
                .cancel('No');

        $mdDialog.show(confirm).then(function () {
            $http.post(site_settings.api_url + 'category/delete_category', {id: id})
                    .then(function (response) {

                        $rootScope.message = 'Category Deleted Successfully';
                        $rootScope.$emit("notification");
                        $rootScope.$emit("reloadCategoryTable");

                    }).catch(function (error) {
                $rootScope.message = 'Something Went Wrong';
                $rootScope.$emit("notification");
            });
        }, function () {

        });
    };
});
app.controller('CategoryAddController', function (categoryid, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder) {
    $scope.category = {};

    if (categoryid)
    {
        $scope.action = 'Edit';
        $http.post(site_settings.api_url + 'category/get_category', {id: categoryid})
                .then(function (response) {
                    $scope.category = response.data;
//                    if ($scope.category.item_image != '')
//                    {
//                        $scope.mockFiles = [
//                            {name: $scope.category.item_image, size: 5000, isMock: true, serverImgUrl: '/Uploads/menu_item/' + $scope.menu_item.item_image},
//                        ];
//
//                        $timeout(function () {
//                            $scope.myDz = $scope.dzMethods.getDropzone();
//
//                            // emit `addedfile` event with mock files
//                            // emit `complete` event for mockfile as they are already uploaded
//                            // decrease `maxFiles` count by one as we keep adding mock file
//                            // push mock file dropzone
//                            $scope.mockFiles.forEach(function (mockFile) {
//                                $scope.myDz.emit('addedfile', mockFile);
//                                $scope.myDz.emit('complete', mockFile);
//                                $scope.myDz.options.maxFiles = $scope.myDz.options.maxFiles - 1;
//                                $scope.myDz.files.push(mockFile);
//                            });
//                        });
//                    }
                }).catch(function (error) {

        });
    }
    else
    {
        $scope.action = 'Add';
    }

//    $scope.dzCallbacks = {
//        
//        'removedfile': function (file, response) {
//
//            $http.post('/api/deleteImage/menu_item/' + $scope.menu_item.item_image)
//                    .then(function (response) {
//                        $scope.menu_item.item_image = '';
//                        //$mdDialog.hide();
//                    }).catch(function (error) {
//            });
//        },
//        'sending': function (file, xhr, formData) {
//
//            formData.append('folder', 'menu_item');
//        },
//        'addedfile': function (file) {
//
//            if (file.isMock) {
//                $scope.myDz.createThumbnailFromUrl(file, file.serverImgUrl, null, true);
//            }
//        },
//        'success': function (file, xhr) {
//            $scope.menu_item.item_image = xhr.filename
//            return false;
//        }
//    };
//    $scope.dzMethods = {};
//    $scope.removeNewFile = function () {
//        $scope.dzMethods.removeFile($scope.newFile); //We got $scope.newFile from 'addedfile' event callback
//    }

    $scope.saveCategory = function ()
    {
        $http.post(site_settings.api_url + 'category/save_category', $scope.category)
                .then(function (response) {

                    $rootScope.message = 'Category Saved Successfully';
                    $rootScope.$emit("notification");
                    $rootScope.$emit("reloadCategoryTable");
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
//}).config(function (dropzoneOpsProvider) {
//    dropzoneOpsProvider.setOptions({
//        url: '/api/uploadImages',
//        maxFilesize: '10',
//        paramName: 'photo',
//        addRemoveLinks: true,
//        maxFiles: 5,
//        acceptedFiles: 'image/*',
//        thumbnailWidth: '150',
//        thumbnailHeight: '150',
//        dictDefaultMessage: '<img width="140" src="/assets/images/menu_item.png">',
//        maxfilesexceeded: function (file) {
////            this.removeAllFiles();
//            this.addFile(file);
//        },
//        sending: function (file, xhr, formData) {
//            formData.append('folder', 'menu_item');
//        },
//    });
});