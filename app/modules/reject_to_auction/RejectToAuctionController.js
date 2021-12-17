"use strict";

var app = angular.module('ng-app');
app.controller('RejectToAuctionController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken, $window)
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
                })
                .catch(function (error) {});
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
        url: site_settings.api_url + 'reject_to_auction/get_reject_to_auction_products',
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

    function callback(json) {}

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
        DTColumnBuilder.newColumn('sku').withTitle('SKU').renderWith(skuRender),
        DTColumnBuilder.newColumn('name').withTitle('Product Name'),
        DTColumnBuilder.newColumn('price').withTitle('Price'),
    ];

    $scope.changeProductStatus = function (status, product)
    {
        $scope.productStatus.push({'product_status_id': status, 'product_id': product});
    };

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
});

app.controller('RejectToAuctionSellerController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken)
{

    $scope.dtInstance = {};
    $scope.user = [];
    $scope.paramname = 2;
    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
        dataSrc: "data",
        url: site_settings.api_url + 'seller/get_reject_to_acution_sellers',
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
        return '<span ui-sref="reject_to_auction_product({id: ' + full.id + '})" class="text-boxed m-0 deep-orange-bg white-fg" style="cursor: pointer">VIEW PRODUCTS</span>';
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


    function callback(json) {}

    $scope.dtColumns = [
        DTColumnBuilder.newColumn('firstname').withTitle('First Name').renderWith(nullHtml),
        DTColumnBuilder.newColumn('lastname').withTitle('Last Name').renderWith(nullHtml),
        DTColumnBuilder.newColumn('email').withTitle('Email'),
        DTColumnBuilder.newColumn('displayname').withTitle('Dispaly Name'),
        DTColumnBuilder.newColumn(null).withTitle('Products').notSortable()
                .renderWith(productsHtml),
    ];

    $rootScope.$on("reloadUserTable", function (event, args)
    {
        reloadData();
    });

    function reloadData()
    {
        var resetPaging = false;
        $scope.dtInstance.reloadData(callback, resetPaging);
    }

    function callback(json) {}
});

