"use strict";

var app = angular.module('ng-app');
app.controller('ProductApprovedController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken) {

    $scope.authuser = $auth.getProfile().$$state.value;
    $scope.dtInstance = {};
    $scope.users = [];
    $scope.select = [];
    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
        dataSrc: "data",
        url: site_settings.api_url + 'product_approved/get_products_approved',
        type: "POST",
        headers: {Authorization: 'Bearer ' + ngAAToken.getToken()},
    }).withOption('processing', true) //for show progress bar
            .withOption('serverSide', true) // for server side processing
            .withPaginationType('full_numbers') // for get full pagination options // first / last / prev / next and page numbers
            .withDisplayLength(10) // Page size
            .withOption('aaSorting', [1, 'asc'])
            .withOption('autoWidth', false)
            .withOption('responsive', true)
            .withOption('dom', '<"top"f>rt<"bottom"<"left"<"length"l>><"right"<"info"i><"pagination"p>>>')
            .withDataProp('data')
            .withOption('fnRowCallback',
                    function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                        $compile(nRow)($scope);
                    });




    function actionsHtml(data, type, full, meta) {
        var action_btn = '<md-fab-speed-dial style=" transform: translate(-110px, 0px);" md-open="demo' + data.id + '.isOpen" md-direction="left" ng-class="{ \'md-hover-full\': demo' + data.id + '.hover }" class="md-scale md-fab-top-right ng-isolate-scope md-left"  ng-mouseleave="demo' + data.id + '.isOpen=false" ng-mouseenter="demo' + data.id + '.isOpen=true">';
        action_btn += '<md-fab-trigger>';
        action_btn += '<md-button aria-label="menu" class="md-fab md-primary md-mini">';
        action_btn += '<md-icon md-svg-src="assets/angular-material-assets/icons/ic_call_to_action_white_24px.svg"></md-icon>';
        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">Action</md-tooltip>';

        action_btn += '</md-button>';
        action_btn += '</md-fab-trigger>';
        action_btn += '<md-fab-actions>';
        action_btn += '<md-button aria-label="EDIT" ng-click="openProductEditDialog(' + data.id + ');" class="md-fab md-accent md-raised md-mini">';
        action_btn += '<md-icon md-svg-src="assets/angular-material-assets/icons/ic_mode_edit_white_24px.svg" aria-label="Twitter"></md-icon>';
        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">EDIT</md-tooltip>';
        action_btn += '</md-button>';
//        action_btn += '<md-button ng-click="showConfirm(' + data.id + ')" aria-label="DELETE" class="md-fab md-warn md-raised md-mini">';
//        action_btn += '<md-icon  md-font-icon="icon-delete" aria-label="Facebook"></md-icon>';
//        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">DELETE</md-tooltip>';
//        action_btn += '</md-button>';

        action_btn += '<md-button aria-label="VIEW" ng-click="openProductViewDialog(' + data.id + ');" class="md-fab md-accent md-raised md-mini">';
        action_btn += '<md-icon  md-font-icon="icon-eye" aria-label="VIEW"></md-icon>';
        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">VIEW PRODUCT</md-tooltip>';
        action_btn += '</md-button>';
        action_btn += '<md-button aria-label="DELETE" ng-click="showConfirm(' + data.id + ');" class="md-fab md-warn md-raised md-mini">';
        action_btn += '<md-icon  md-font-icon="icon-delete" aria-label="DELETE"></md-icon>';
        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">DELETE PRODUCT</md-tooltip>';
        action_btn += '</md-button>';

        action_btn += '</md-fab-actions></md-fab-speed-dial>';
        return action_btn;
//        $scope.persons[data.id] = data;
//        return '<md-button aria-label="Edit" ui-sref="edit_profile({id:\'' + full.id + '\'})" class="md-fab md-mini md-button  md-ink-ripple">  <md-icon md-svg-src="assets/img/edit.svg" class="icon"></md-icon></md-button><md-button aria-label="Delete" type="submit" class="md-fab md-mini md-button  md-ink-ripple md-warn">  <md-icon md-font-icon="icon-delete" class="icon" ></md-icon></md-button>';
    }
    function profileImage(data, type, full, meta) {

//        console.log('data');
        if (data != '' && data != undefined)
        {
            return '<img  style="width:40px;height:40px;border-radius:30px;" src="Uploads/profile/' + data + '">';
        }
        else
        {
            return '<img  style="width:40px;height:40px;border-radius:30px;" src="/assets/images/avatars/profile.jpg">';
        }
    }

    $scope.dtInstance = {};


    function reloadData() {

        var resetPaging = false;
        $scope.dtInstance.reloadData(callback, resetPaging);
    }

    $scope.getProductStatus = function () {
        $http.get(site_settings.api_url + 'get_all_product_approved_status')
                .then(function (response) {
                    $scope.product_status = response.data;
                }).catch(function (error) {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
        });
    };

    $scope.getProductStatus();

    function callback(json) {

    }

    $scope.dtColumns = [
//        DTColumnBuilder.newColumn('profile_image').withTitle('Image').notSortable().renderWith(profileImage),
        DTColumnBuilder.newColumn('name').withTitle('Product Name'),
//        DTColumnBuilder.newColumn('sell_name').withTitle('Sell Name'),
        DTColumnBuilder.newColumn('price').withTitle('Price'),
        DTColumnBuilder.newColumn('status_id').withTitle('Status').renderWith(statusHtml),
        DTColumnBuilder.newColumn('images_from').withTitle('Schedule').renderWith(schedule).notSortable(),
        DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable()
                .renderWith(actionsHtml),
    ];

    function schedule(data, type, full, meta) {

        if (full.images_from == 1)
        {
            if (full.is_scheduled == '1')
            {
                return '<button ng-disabled="true" aria-label="{{hover_' + full.id + ' ==true ? \'Schedule\' : \'Schedule\' }}"  class="md-primary md-raised md-button md-jd-theme md-ink-ripple"\n\
                     ng-class="hover==true ? md-warn : md-primary"    ng-mouseenter="hover_' + full.id + ' = true"    ng-mouseleave="hover_' + full.id + ' = false"\n\
                     type="button" ng-click="takeSchedule(\'' + full.id + '\')" >Schedule</button>';

            }
            else
            {
                return '<button aria-label="{{hover_' + full.id + ' ==true ? \'Schedule\' : \'Schedule\' }}"  class="md-primary md-raised md-button md-jd-theme md-ink-ripple"\n\
                     ng-class="hover==true ? md-warn : md-primary"    ng-mouseenter="hover_' + full.id + ' = true"    ng-mouseleave="hover_' + full.id + ' = false"\n\
                     type="button" ng-click="takeSchedule(\'' + full.id + '\')" >Schedule</button>';
            }
        }
        else
        {
            return '<button ng-disabled="true" aria-label="{{hover_' + full.id + ' ==true ? \'Schedule\' : \'Schedule\' }}"  class="md-primary md-raised md-button md-jd-theme md-ink-ripple"\n\
                     ng-class="hover==true ? md-warn : md-primary"    ng-mouseenter="hover_' + full.id + ' = true"    ng-mouseleave="hover_' + full.id + ' = false"\n\
                     type="button" ng-click="takeSchedule(\'' + full.id + '\')" >Schedule</button>';
        }

    }

    function statusHtml(data, type, full, meta) {
//        console.log(full);
        if ($scope.authuser.roles[0].id == 1)
        {
            $scope.select[full.id] = full.status_id;

            var status_dropdown = '<md-select aria-label="orderstatus" style="margin:0;" ng-model="select[' + full.id + ']" ng-change="changeProductStatus(' + full.id + ')">';
            status_dropdown += '<md-option ng-repeat="ps in product_status" value="{{ps.id}}"><em>{{ps.value_text}}</em></md-option>';
            status_dropdown += '</md-select>';

            return status_dropdown;
        }
        else
        {
            return full.status_value;
        }
    }

    $scope.changeProductStatus = function (id)
    {
        $http.post(site_settings.api_url + 'product_approved/change_product_approved_status', {product_id: id, product_status_id: $scope.select[id]})
                .then(function (response) {
                    $rootScope.message = 'Status change successfully.';
                    $rootScope.$emit("notification");
                    $rootScope.$emit("reloadUserTable");
                }).catch(function (error) {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
        });
    }

    $scope.openProductEditDialog = function (product)
    {
        $mdDialog.show({
            controller: 'ProductApprovedEditController',
            templateUrl: 'app/modules/product/views/product_approved_edit.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                product: product
            }
        });
    }

    $scope.takeSchedule = function (product)
    {
        $mdDialog.show({
            controller: 'ScheduleAddController',
            templateUrl: 'app/modules/schedule/views/schedule_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                product: product
            }
        });
    }

    $scope.openProductViewDialog = function (product)
    {
        $mdDialog.show({
            controller: 'ProductApprovedViewController',
            templateUrl: 'app/modules/product/views/product_approved_view.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                product: product
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
                .title('Would you like to delete this Approved Product?')
                .ok('Yes')
                .cancel('No');

        $mdDialog.show(confirm).then(function () {
            $http.post(site_settings.api_url + 'product_approved/delete_product_approved', {id: id})
                    .then(function (response) {

                        $rootScope.message = 'Approved Product Deleted Successfully';
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

app.controller('ProductApprovedEditController', function (product, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder) {
    $scope.product = {};
    $scope.products_count = 1;
    $scope.products_combo = [{name: '', price: '', description: ''}];
    $scope.product_images = [];
    $scope.edit = false;
    $scope.addNewSeller = function () {
        $mdDialog.show({
            controller: 'SellerAddController',
            templateUrl: 'app/modules/product/views/seller_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            skipHide: true,
        });
    };
    $scope.getCategorys = function () {
        $http.get(site_settings.api_url + 'get_all_categorys')
                .then(function (response) {
                    $scope.categorys = response.data;
                    $scope.getCat();
//                    $rootScope.$emit("notification");
                }).catch(function (error) {
            $rootScope.message = 'Something Went Wrong';
            $rootScope.$emit("notification");
        });
    };

    $scope.getSubCategorys = function () {
        $http.get(site_settings.api_url + 'get_all_subcategorys')
                .then(function (response) {
                    $scope.subcategorys = $scope.subcategorys_temp = response.data;

                }).catch(function (error) {
            $rootScope.message = 'Something Went Wrong';
            $rootScope.$emit("notification");
        });
    };


    $scope.getCat = function ()
    {
        $scope.product.cat = {};
        for (var i in $scope.categorys)
        {
            if ($scope.product[$scope.categorys[i].category_name.toLowerCase()] != null && $scope.categorys[i].category_name.toLowerCase() != 'condition')
            {
                $scope.product.cat[$scope.categorys[i].category_name] = $scope.product[$scope.categorys[i].category_name.toLowerCase()].id;
            }
            else if ($scope.product['con'] != null && $scope.categorys[i].category_name.toLowerCase() == 'condition')
            {
                $scope.product.cat[$scope.categorys[i].category_name] = $scope.product['con'].id;
            }
        }
    };
    $scope.getSellersFromWP = function () {

        $http.jsonp(site_settings.wp_api_url + 'seller-api.php', {jsonpCallbackParam: 'callback'})
                .then(function (response) {
                    $scope.sellers = response.data;
                })
                .catch(function (response) {
                    console.log(response);
                });
    };

    $scope.getSellersFromWP();

    if (product)
    {
        $scope.edit = true;
        $scope.action = 'Edit';
        $http.post(site_settings.api_url + 'product_approved/get_product_approved', {id: product})
                .then(function (response) {
                    $scope.product = response.data;
//                    $scope.product.sell_name = response.data.sell_id.name;
                    $scope.product_images = [];

//                    for (var i in $scope.product.product_images)
//                    {
//                        $scope.product_images.push($scope.product.product_images[i].id);
//                    }

                    $scope.getCategorys();
                    $scope.getSubCategorys();

                    if ($scope.product.product_images != '')
                    {
                        $scope.mockFiles = [];
                        for (var i in $scope.product.product_images)
                        {
                            $scope.product_images.push($scope.product.product_images[i].id);

                            $scope.mockFiles.push(
                                    {name: $scope.product.product_images[i].name, id: $scope.product.product_images[i].id, size: 5000, isMock: true, serverImgUrl: '/Uploads/product/' + $scope.product.product_images[i].name}
                            );
                        }

                        $timeout(function () {
                            $scope.myDz = $scope.dzMethods.getDropzone();

                            // emit `addedfile` event with mock files
                            // emit `complete` event for mockfile as they are already uploaded
                            // decrease `maxFiles` count by one as we keep adding mock file
                            // push mock file dropzone
                            $scope.mockFiles.forEach(function (mockFile) {
                                $scope.myDz.emit('addedfile', mockFile);
                                $scope.myDz.emit('complete', mockFile);
                                $scope.myDz.options.maxFiles = $scope.myDz.options.maxFiles - 1;
                                $scope.myDz.files.push(mockFile);
                            });
                        });

//                        console.log($scope.product_images);
                    }

                }).catch(function (error) {
            console.log(error)

        });

    }
    else
    {
        $scope.action = 'Add';
    }

    $scope.dzCallbacks = {
        'removedfile': function (file, response) {

            var data = {};
            data.folder = 'product';
            data.product_id = product;

            if (file.id == undefined)
            {
                for (var k in $scope.product_images)
                {
                    if ($scope.product_images[k] == JSON.parse(file.xhr.responseText).id)
                    {
                        $scope.product_images.splice(k, 1);
                    }
                }
                data.name = JSON.parse(file.xhr.responseText).filename;
                data.id = JSON.parse(file.xhr.responseText).id;
            }
            else
            {
                for (var k in $scope.product_images)
                {
                    if ($scope.product_images[k] == file.id)
                    {
                        $scope.product_images.splice(k, 1);
                    }
                }
                data.name = file.name;
                data.id = file.id;
            }

            data.imgs = $scope.product_images;

            $http.post('/api/product/deleteImage', data)
                    .then(function (response) {
                        console.log('deleted');
                        console.log($scope.product_images);
                    }).catch(function (error) {
            });
        },
        'sending': function (file, xhr, formData) {

            formData.append('folder', 'product');
        },
        'addedfile': function (file) {

            if (file.isMock) {
                $scope.myDz.createThumbnailFromUrl(file, file.serverImgUrl, null, true);
            }
        },
        'success': function (file, xhr) {
//            console.log(xhr);
            $scope.product_images.push(xhr.id);
//            console.log($scope.product_images);
            return false;
        }
    };
    $scope.dzMethods = {};
    $scope.removeNewFile = function () {
        $scope.dzMethods.removeFile($scope.newFile); //We got $scope.newFile from 'addedfile' event callback
    }

    $scope.saveProduct = function ()
    {
        $scope.product.products = $scope.products_combo;
        $scope.product.productimages = $scope.product_images;

        $http.post(site_settings.api_url + 'product_approved/edit_product_approved', $scope.product)
                .then(function (response) {

                    $rootScope.message = response.data;
                    $rootScope.$emit("notification");
                    $rootScope.$emit("reloadUserTable");
                    $mdDialog.hide();
                }).catch(function (error) {
            $rootScope.message = error.data;
            $rootScope.$emit("notification");
        });
    };


    $scope.addProductField = function ()
    {
        $scope.products_count++;
        $scope.products_combo.push({name: '', price: '', description: ''})
    }
    $scope.removeProductField = function (c)
    {
        $scope.products_count--;
        $scope.products_combo.splice(c, 1);

    }


    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };
}).config(function (dropzoneOpsProvider) {
    dropzoneOpsProvider.setOptions({
        url: '/api/product/upload_Images',
        maxFilesize: '10',
        paramName: 'photo',
        addRemoveLinks: true,
        maxFiles: 10,
        acceptedFiles: 'image/*',
        thumbnailWidth: '100',
        thumbnailHeight: '100',
        dictDefaultMessage: '',
        maxfilesexceeded: function (file) {
//            this.removeAllFiles();
            this.addFile(file);
        },
        sending: function (file, xhr, formData) {
            formData.append('folder', 'product');
        },
    });
});

app.controller('ProductApprovedViewController', function (product, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder) {
    $scope.product = {};
    $scope.products_count = 1;
    $scope.products_combo = [{name: '', price: '', description: ''}];
    $scope.product = {};
//    $scope.product.cat = [];
    $scope.edit = false;

    $scope.getSellersFromWP = function () {

        $http.jsonp(site_settings.wp_api_url + 'seller-api.php', {jsonpCallbackParam: 'callback'})
                .then(function (response) {
                    $scope.sellers = response.data;
                    if (product)
                    {
                        $scope.edit = true;
                        $scope.action = 'View';
                        $http.post(site_settings.api_url + 'product_approved/get_product_approved', {id: product})
                                .then(function (response) {
                                    $scope.product = response.data;
                                    for (var i in $scope.sellers)
                                    {

                                        if ($scope.product.seller_id == $scope.sellers[i].ID)
                                        {
                                            $scope.product.seller_name = $scope.sellers[i].data.display_name;
                                            $scope.product.seller_email = $scope.sellers[i].data.user_email;
                                        }
                                    }
//                    console.log($scope.product);
//                    $scope.product.sell_name = response.data.sell_id.name;
//                    $scope.product.sell_name = response.data.sell_id.name;

//                    $scope.getCategorys();
//                    $scope.getSubCategorys();

//                    if ($scope.user.profile_image != '')
//                    {
//                        $scope.mockFiles = [
//                            {name: $scope.user.profile_image, size: 5000, isMock: true, serverImgUrl: '/Uploads/profile/' + $scope.user.profile_image},
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
                })
                .catch(function (response) {
                    console.log(response);
                });
    };

    $scope.getSellersFromWP();


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

    $scope.saveProduct = function ()
    {
        $scope.product.products = $scope.products_combo;

        $http.post(site_settings.api_url + 'product/edit_product', $scope.product)
                .then(function (response) {

                    $rootScope.message = response.data;
                    $rootScope.$emit("notification");
                    $rootScope.$emit("reloadUserTable");
                    $mdDialog.hide();
                }).catch(function (error) {
            $rootScope.message = error.data;
            $rootScope.$emit("notification");
        });
    };


    $scope.addProductField = function ()
    {
        $scope.products_count++;
        $scope.products_combo.push({name: '', price: '', description: ''})
    }
    $scope.removeProductField = function (c)
    {
        $scope.products_count--;
        $scope.products_combo.splice(c, 1);

    }


    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };
})

app.controller('ScheduleAddController', function (product, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder) {

    $scope.schedule = {};
    $scope.schedule.product_id = product;
    $scope.action = 'Add';

    $http.post(site_settings.api_url + 'schedule/get_schedule_by_product', $scope.schedule)
            .then(function (response) {
//                console.log(response);
                if (response.data[0] != null)
                {
                    $scope.schedule = response.data[0];
                    $scope.schedule.product_id = $scope.schedule.product_id.id;
                    $scope.schedule.date = new Date($scope.schedule.date);
                    $scope.schedule.time = new Date($scope.schedule.time);
//                    $scope.schedule.time = moment($scope.schedule.time).local().format("MM/DD/YYYY HH:mm:ss");
                }
            }).catch(function (error) {
        $rootScope.message = 'Something Went Wrong';
        $rootScope.$emit("notification");
    });

    $scope.saveSchedule = function ()
    {
        var temp = $scope.schedule;

        temp.date = moment($scope.schedule.date).local().format("MM/DD/YYYY");
        temp.time = moment($scope.schedule.time).local().format("MM/DD/YYYY HH:mm:ss");

        $http.post(site_settings.api_url + 'schedule/save_schedule', temp)
                .then(function (response) {

                    $rootScope.message = response.data;
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
});
app.controller('SellerAddController', function ($timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder) {
    $scope.user = {};
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
    };
    $scope.wp_key = 'AbcBsdsa1';
    $scope.saveUser = function ()
    {
        var url = 'seller-api.php';
        url += '?email=' + $scope.user.email;
        url += '&firstname=' + $scope.user.firstname;
        url += '&lastname=' + $scope.user.lastname;
        url += '&password=' + $scope.user.password;
        url += '&shop_name=' + $scope.user.shop_name;
        url += '&shop_url=' + $scope.user.shop_url;
        url += '&address=' + $scope.user.address;
        url += '&phone=' + $scope.user.phone;
        url += '&key=' + $scope.wp_key;
        console.log(url);


//        $http.jsonp(site_settings.wp_api_url + url, {jsonpCallbackParam: 'callback'})
//                .then(function (response) {
//                    $scope.sellers = response.data;
////            console.log($scope.sellers);
//                })
//                .catch(function (response) {
//                    console.log(response);
//                });

//        $http.post(site_settings.api_url + '/signup', $scope.user)
//                .then(function (response) {
//
//                    $rootScope.message = 'User Saved Successfully';
//                    $rootScope.$emit("notification");
//                    $rootScope.$emit("reloadUserTable");
//                    $mdDialog.hide();
//                }).catch(function (error) {
//            $rootScope.message = 'Something Went Wrong';
//            $rootScope.$emit("notification");
//        });
    };
    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };
})
//        .config(function (dropzoneOpsProvider) {
//    dropzoneOpsProvider.setOptions({
//        url: '/api/uploadImages',
//        maxFilesize: '10',
//        paramName: 'photo',
//        addRemoveLinks: true,
//        maxFiles: 2,
//        acceptedFiles: 'image/*',
//        thumbnailWidth: '150',
//        thumbnailHeight: '150',
//        dictDefaultMessage: '<img width="200" src="/assets/images/avatars/profile.jpg">',
//        maxfilesexceeded: function (file) {
//            this.removeAllFiles();
//            this.addFile(file);
//
//        },
//        sending: function (file, xhr, formData) {
//
//            formData.append('folder', 'profile');
//        },
//    });
//});