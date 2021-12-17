"use strict";

var app = angular.module('ng-app');
app.controller('ArchivedController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken,$window)
{

    $scope.seller = {};
    $scope.seller.firstname = 'Default';
    $scope.seller.lastname = 'Default';
    $scope.getSellerById = function (id)
    {
        $http.post(site_settings.api_url + 'seller/get_edit_seller', {id: id})
                .then(function (response)
                {
                    $scope.seller = response.data;
//                    $scope.user.password = '********';
//                    console.log($scope.user);
                }).catch(function (error)
        {

        });
    };
    $scope.getSellerById($stateParams.id);


    $scope.authuser = $auth.getProfile().$$state.value;
    $scope.dtInstance = {};
    $scope.users = [];
    $scope.select = [];
    $scope.products = [];
    $scope.productStatus = [];
    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
        dataSrc: "data",
        url: site_settings.api_url + 'product/get_archived_products',
        type: "POST",
        headers: {Authorization: 'Bearer ' + ngAAToken.getToken()},
        data: {id: $stateParams.id}
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
                    function (nRow, aData, iDisplayIndex, iDisplayIndexFull)
                    {
                        $compile(nRow)($scope);
                    });


    function actionsHtml(data, type, full, meta)
    {
        var action_btn = '';
        action_btn += '<span ng-click="openProductViewDialog(' + data.id + ');" class="text-boxed m-0 deep-blue-bg white-fg" style="cursor: pointer;margin-left:5px; background-color: #1eaa36 !important;">VIEW</span>';
        return action_btn;
    }

    $scope.dtInstance = {};


    function reloadData()
    {

        var resetPaging = false;
        $scope.dtInstance.reloadData(callback, resetPaging);
    }

    $scope.getProductStatus = function ()
    {
        $http.get(site_settings.api_url + 'get_all_product_status')
                .then(function (response)
                {
                    $scope.product_status = response.data;
                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
        });
    };

    $scope.getProductStatus();

    function callback(json)
    {

    }
    function skuRender(data, type, full, meta)
    {
        if (data && data != '')
        {
            return data;
        } else
        {
            return '---';
        }

    }
    $scope.dtColumns = [
//        DTColumnBuilder.newColumn('profile_image').withTitle('Image').notSortable().renderWith(profileImage),
        DTColumnBuilder.newColumn(null).withTitle('').notVisible(),
        DTColumnBuilder.newColumn('sku').withTitle('SKU').renderWith(skuRender),
        DTColumnBuilder.newColumn('name').withTitle('Product Name'),

//        DTColumnBuilder.newColumn('sell_name').withTitle('Sell Name'),
        DTColumnBuilder.newColumn('price').withTitle('Price'),
        DTColumnBuilder.newColumn(null).withTitle('Product From').renderWith(productFromHtml),
        DTColumnBuilder.newColumn('status_id').withTitle('Status').renderWith(statusHtml),
//        DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable()
//                .renderWith(actionsHtml),
    ];

    $scope.changeProductStatus = function (status, product)
    {
        $scope.productStatus.push({'product_status_id': status, 'product_id': product});
    };

    function statusHtml(data, type, full, meta)
    {
        $scope.select[full.id] = false;
        $scope.products[full.id] = full;
        var action_btn = '';

        action_btn += '<md-checkbox ng-model="select[' + full.id + ']" style="margin-top: 10px;" layout="row" layout-xs="column">';
        action_btn += 'Re-open';
        action_btn += '</md-checkbox>';

        return action_btn;
    }

    function productFromHtml(data, type, full, meta)
    {
        if (full.status_id == 31)
        {
            return 'Products for Review';
        } else if (full.is_send_mail != 2 && full.is_awaiting_contract == 0 && full.status_quot_id == null)
        {
            return 'Awaiting Contract';
        } else if (full.is_send_mail != 2 && full.is_awaiting_contract == 1 && full.is_proposal_for_production == 0)
        {
            return 'For Production';
        } else if (full.is_send_mail != 2 && full.is_awaiting_contract == 1 && full.is_proposal_for_production == 1 && full.is_product_for_pricing == 0)
        {
            return 'Pricing';
        } else if (full.is_send_mail != 2 && full.is_awaiting_contract == 1 && full.is_proposal_for_production == 1 && full.is_product_for_pricing == 1)
        {
            return 'Approval';
        } else
        {
            return '---';
        }
    }

    $scope.openProductViewDialog = function (product)
    {
        $mdDialog.show({
            controller: 'ProductViewController',
            templateUrl: 'app/modules/product/views/product_view.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                product: product
            }
        });
    };

    $scope.reopen = function ()
    {
        var temp = [];

        for (var i in $scope.select)
        {
            if ($scope.select[i])
            {
                temp.push($scope.products[i]);
            }
        }

        if (temp.length > 0)
        {
            var confirm = $mdDialog.confirm()
                    .title('Would you like to Re-open for these Products?')
                    .ok('Yes')
                    .cancel('No');

            $mdDialog.show(confirm).then(function ()
            {
                $http.post(site_settings.api_url + 'product/reopen_product', temp)
                        .then(function (response)
                        {

                            $rootScope.message = 'Product status has been successfully saved.';

                            $rootScope.$emit("notification");
                            $rootScope.$emit("reloadProductTable");
                        }).catch(function (error)
                {
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                });
            }, function ()
            {

            });
        } else
        {
            $rootScope.message = 'Please select at least one product.';
            $rootScope.$emit("notification");
        }
    }

    $scope.openProductAddDialog = function (product)
    {
        $mdDialog.show({
            controller: 'ProductAddController',
            templateUrl: 'app/modules/product/views/product_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                product: product
            }
        });
    }

    $scope.openProductEditDialog = function (product)
    {
        $mdDialog.show({
            controller: 'ProductEditController',
            templateUrl: 'app/modules/product/views/product_edit.html',
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
            controller: 'ProductViewController',
            templateUrl: 'app/modules/product/views/product_view.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                product: product
            }
        });
    }

    $rootScope.$on("reloadProductTable", function (event, args)
    {
         $window.location.reload();

        reloadData();
    });
    $rootScope.$on("reloadProductTable1", function (event, args)
    {
        reloadData();

        $mdDialog.show({
            controller: 'CustomDialogController',
            templateUrl: 'app/modules/product/views/custom_dialog.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true
        });
    });

    function reloadData()
    {

        var resetPaging = false;
        $scope.dtInstance.reloadData(callback, resetPaging);
    }

    function callback(json)
    {

    }


    $scope.showConfirm = function (id)
    {

        // Appending dialog to document.body to cover sidenav in docs app
        var confirm = $mdDialog.confirm()
                .title('Would you like to delete this Product?')
                .ok('Yes')
                .cancel('No');

        $mdDialog.show(confirm).then(function ()
        {
            $http.post(site_settings.api_url + 'product/delete_product', {id: id})
                    .then(function (response)
                    {

                        $rootScope.message = 'Product Deleted Successfully';
                        $rootScope.$emit("notification");
                        $rootScope.$emit("reloadProductTable");

                    }).catch(function (error)
            {
                $rootScope.message = 'Something Went Wrong';
                $rootScope.$emit("notification");
            });
        }, function ()
        {

        });
    };
});

app.controller('ArchivedSellerController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken)
{

    $scope.dtInstance = {};
    $scope.user = [];
    $scope.paramname = 2;
    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
        dataSrc: "data",
        url: site_settings.api_url + 'seller/get_archived_sellers',
        type: "POST",
        headers: {Authorization: 'Bearer ' + ngAAToken.getToken()},
    }).withOption('processing', true) //for show progress bar
            .withOption('serverSide', true) // for server side processing
            .withPaginationType('full_numbers') // for get full pagination options // first / last / prev / next and page numbers
            .withDisplayLength(10) // Page size
            .withOption('aaSorting', [0, 'desc'])
            .withOption('autoWidth', false)
            .withOption('responsive', true)
            .withOption('dom', '<"top"f>rt<"bottom"<"left"<"length"l>><"right"<"info"i><"pagination"p>>>')
            .withDataProp('data')
            .withOption('fnRowCallback',
                    function (nRow, aData, iDisplayIndex, iDisplayIndexFull)
                    {
                        $compile(nRow)($scope);
                    });


    function productsHtml(data, type, full, meta)
    {
        return '<span ui-sref="archived({id: ' + full.id + '})" class="text-boxed m-0 deep-orange-bg white-fg" style="cursor: pointer">VIEW PRODUCTS</span>';
    }

    function nullHtml(data, type, full, meta)
    {

        if (data == null || data == '')
        {
            return '---';
        } else
        {
            return data;
        }
    }

    $scope.dtInstance = {};


    function reloadData()
    {

        var resetPaging = false;
        $scope.dtInstance.reloadData(callback, resetPaging);
    }


    function callback(json)
    {

    }
    $scope.dtColumns = [
        DTColumnBuilder.newColumn('firstname').withTitle('First Name').renderWith(nullHtml),
        DTColumnBuilder.newColumn('lastname').withTitle('Last Name').renderWith(nullHtml),
        DTColumnBuilder.newColumn('email').withTitle('Email'),
        DTColumnBuilder.newColumn('displayname').withTitle('Dispaly Name'),
        DTColumnBuilder.newColumn(null).withTitle('Products').notSortable()
                .renderWith(productsHtml),
    ];

//    function statusHtml(data, type, full, meta) {
//
//        if (data == 'Active')
//        {
//            return '<button aria-label="{{hover_' + full.id + ' ==true ? \'Inactive\' : \'Active\' }}"  class="md-primary md-raised md-button md-jd-theme md-ink-ripple"\n\
//                     ng-class="hover==true ? md-warn : md-primary"    ng-mouseenter="hover_' + full.id + ' = true"    ng-mouseleave="hover_' + full.id + ' = false"\n\
//                     type="button" ng-click="change_status(\'' + full.id + '\',\'' + 'InActive' + '\')" >{{hover_' + full.id + ' ==true ? \'Inactive\' : \'Active\' }}</button>';
//        }
//        else if (data == 'InActive')
//        {
//            return '<button aria-label="{{hover_' + full.id + ' ==true ? \'Active\' : \'InActive\' }}"  class="md-warn md-raised md-button md-default-theme md-ink-ripple"\n\
//                     ng-class="hover==true ? md-primary : md-warn"    ng-mouseenter="hover_' + full.id + ' = true"    ng-mouseleave="hover_' + full.id + ' = false"\n\
//                     type="button" ng-click="change_status(\'' + full.id + '\',\'' + 'Active' + '\')" >{{hover_' + full.id + ' ==true ? \'Active\' : \'InActive\' }}</button>';
//
//        }
//        else
//        {
//            return '<button aria-label="s" class="md-warm md-raised md-button md-default-theme md-ink-ripple" ng-class="hover==true ? md-warn : md-primary"    ng-mouseenter="hover = true"    ng-mouseleave="hover = false"\n\ type="button" ng-click="" >' + data + '</button>';
//
//        }
//    }
//
//    $scope.change_status = function (seller, status) {
//
//        var deferred = $q.defer();
//        var cred = {seller: seller, status: status}
//
//        $http.post(site_settings.api_url + 'seller/change_seller_status', cred)
//                .then(function (response) {
//                    $rootScope.message = 'Status change Successfully';
//                    $rootScope.$emit("notification");
//                    reloadData();
//                }).catch(function (error) {
//            $rootScope.message = 'Something Went Wrong.';
//            $rootScope.$emit("notification");
//            $rootScope.loader = false;
//
//        });
//    }
//
//
    $scope.openSellerAddDialog = function (user)
    {
        $mdDialog.show({
            controller: 'SellerAddController',
            templateUrl: 'app/modules/seller/views/seller_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                user: user
            }
        });
    }

    $rootScope.$on("reloadUserTable", function (event, args)
    {

        reloadData();
    });

    function reloadData()
    {

        var resetPaging = false;
        $scope.dtInstance.reloadData(callback, resetPaging);
    }

    function callback(json)
    {

    }


    $scope.showConfirm = function (id)
    {

        // Appending dialog to document.body to cover sidenav in docs app
        var confirm = $mdDialog.confirm()
                .title('Would you like to delete this Seller?')
                .ok('Yes')
                .cancel('No');

        $mdDialog.show(confirm).then(function ()
        {
            $http.post(site_settings.api_url + 'seller/delete_seller', {id: id})
                    .then(function (response)
                    {
                        $scope.ids = response.data;

                        $rootScope.message = 'Seller Deleted Successfully';
                        $rootScope.$emit("notification");
                        $rootScope.$emit("reloadUserTable");

                    }).catch(function (error)
            {
                $rootScope.message = 'Something Went Wrong';
                $rootScope.$emit("notification");
            });
        }, function ()
        {

        });
    };
});


app.controller('ProductViewController', function (product, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.product = {};
    $scope.products_count = 1;
    $scope.products_combo = [{name: '', price: '', description: ''}];
    $scope.product = {};
//    $scope.product.cat = [];
    $scope.edit = false;
    $scope.getSellersFromWP = function ()
    {
        $rootScope.loader = true;

        $http.jsonp(site_settings.wp_api_url + 'seller-api.php', {jsonpCallbackParam: 'callback'})
                .then(function (response)
                {
                    $scope.sellers = response.data;
                    if (product)
                    {
                        $scope.edit = true;
                        $scope.action = 'View';
                        $http.post(site_settings.api_url + 'product/get_product', {id: product})
                                .then(function (response)
                                {
                                    $scope.product = response.data;
                                    console.log($scope.product);
//                                    $scope.product.sell_name = response.data.sell_id.name;
                                    for (var i in $scope.sellers)
                                    {

                                        if ($scope.product.seller_id == $scope.sellers[i].ID)
                                        {
                                            $scope.product.seller_name = $scope.sellers[i].data.display_name;
                                            $scope.product.seller_email = $scope.sellers[i].data.user_email;
                                        }
                                    }
                                    $rootScope.loader = false;

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

                                }).catch(function (error)
                        {
                            $rootScope.loader = false;
                            console.log(error);

                        });

                    } else
                    {
                        $scope.action = 'Add';
                    }
                })
                .catch(function (response)
                {
                    console.log(response);
                    $rootScope.loader = false;
                });
    };

    $scope.getSellers = function ()
    {
        $rootScope.loader = true;
        $http.get(site_settings.api_url + 'seller/get_all_seller')
                .then(function (response)
                {
                    $scope.sellers = response.data;

                    if (product)
                    {
                        $scope.edit = true;
                        $scope.action = 'View';
                        $http.post(site_settings.api_url + 'product/get_product', {id: product})
                                .then(function (response)
                                {
                                    $rootScope.loader = false;
                                    $scope.product = response.data;
                                    for (var i in $scope.sellers)
                                    {

                                        if ($scope.product.sellerid.id == $scope.sellers[i].id)
                                        {
                                            $scope.product.seller_name = $scope.sellers[i].displayname;
                                            $scope.product.seller_email = $scope.sellers[i].email;
                                        }
                                    }
                                    $rootScope.loader = false;



                                }).catch(function (error)
                        {
                            $rootScope.loader = false;
                            console.log(error);

                        });

                    } else
                    {
                        $scope.action = 'Add';
                    }

                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
        });
    };

//    $scope.getSellersFromWP();
    $scope.getSellers();


    $scope.dzCallbacks = {
        'addedfile': function (file)
        {

            if (file.isMock)
            {
                $scope.myDz.createThumbnailFromUrl(file, file.serverImgUrl, null, true);
            }
        },
        'success': function (file, xhr)
        {
            $scope.user.profile_image = xhr.filename
            return false;
        }

    };
    $scope.dzMethods = {};
    $scope.removeNewFile = function ()
    {
        $scope.dzMethods.removeFile($scope.newFile); //We got $scope.newFile from 'addedfile' event callback
    }

    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };
});
