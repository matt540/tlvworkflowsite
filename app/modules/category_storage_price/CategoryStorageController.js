"use strict";

var app = angular.module('ng-app');
app.controller('CategoryStorageController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken, $window)
{

    $scope.authuser = $auth.getProfile().$$state.value;
    $scope.dtInstance = {};
    $scope.users = [];
    $scope.select = [];
    $scope.productStatus = [];
    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
        dataSrc: "data",
        url: site_settings.api_url + 'category/get_product_subcategory',
        type: "POST",
        headers: {Authorization: 'Bearer ' + ngAAToken.getToken()},
        data: {category_id: 2}
    }).withOption('processing', true) //for show progress bar
            .withOption('serverSide', true) // for server side processing
            .withPaginationType('full_numbers') // for get full pagination options // first / last / prev / next and page numbers
            .withDisplayLength(25) // Page size
            .withOption('aaSorting', [1, 'asc'])
            .withOption('autoWidth', false)
            .withOption('responsive', true)
            .withOption('dom', '<"top"f>rt<"bottom"<"left"<"length"l>><"right"<"info"i><"pagination"p>>>')
            .withDataProp('data')
            .withOption('fnRowCallback',
                    function (nRow, aData, iDisplayIndex, iDisplayIndexFull)
                    {
                        $scope.productStatus = [];
                        $scope.Archive = false;
                        $scope.Complete = false;
                        $scope.Delete = false;
                        $scope.Reject = false;
                        $compile(nRow)($scope);
                    });




    function actionsHtml(data, type, full, meta)
    {

        var action_btn = '';

//        action_btn += '<md-button aria-label="EDIT" ng-click="openCategoryStorageAddDialog(' + data.id + ');" class="md-fab md-accent md-raised md-mini">';
//        action_btn += '<md-icon md-svg-src="assets/angular-material-assets/icons/ic_mode_edit_white_24px.svg" aria-label="Twitter"></md-icon>';
//        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">EDIT</md-tooltip>';
//        action_btn += '</md-button>';
        action_btn += '<span ng-click="openCategoryStorageAddDialog(' + data.id + ');" class="text-boxed m-0 deep-blue-bg white-fg" style="cursor: pointer; background-color: #126491 !important;">EDIT</span>';


        return action_btn;
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
//        DTColumnBuilder.newColumn('image').withTitle('Image').notSortable().renderWith(profileImage),
//        DTColumnBuilder.newColumn('sku').withTitle('SKU').renderWith(skuRender),
        DTColumnBuilder.newColumn('sub_category_name').withTitle('Category Name'),
        DTColumnBuilder.newColumn('category_storage_price').withTitle('Storage Price'),
//        DTColumnBuilder.newColumn('price').withTitle('Suggested Retail Price').renderWith(renderPrice),
//        DTColumnBuilder.newColumn(null).withTitle('Price').renderWith(renderPriceMaxMin).notSortable(),
//        DTColumnBuilder.newColumn('tlv_suggested_price_max').withTitle('Suggested Price'),
//        DTColumnBuilder.newColumn('for_production_created_at').withTitle('Date').renderWith(renderDate),
//        DTColumnBuilder.newColumn('quote_created_at').withTitle('Date').renderWith(renderDate),
//        DTColumnBuilder.newColumn(null).withTitle('Aging').renderWith(agingRender),
//        DTColumnBuilder.newColumn('is_send_mail').withTitle('Proposal Accepted').renderWith(proposalStatus),
//        DTColumnBuilder.newColumn('status_id').withTitle('Status').renderWith(statusHtml),
//        DTColumnBuilder.newColumn('images_from').withTitle('Schedule').renderWith(schedule).notSortable(),
        DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable()
                .renderWith(actionsHtml),
    ];

    $scope.openCategoryStorageAddDialog = function (product_quotation)
    {
        $mdDialog.show({
            controller: 'CategoryStorageAddController',
            templateUrl: 'app/modules/category_storage_price/views/category_storage_price_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                product_quotation: product_quotation
            }
        });
    };

    $rootScope.$on("reloadUserTable", function (event, args)
    {
        $window.location.reload();
        $scope.select = [];

        reloadData();
    });
    $rootScope.$on("reloadProductForProductionTable", function (event, args)
    {
//         $window.location.reload();
        $scope.select = [];

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

});

app.controller('CategoryStorageAddController', function (product_quotation, $parse, $document, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.products = {};

    if (product_quotation)
    {
        $http.post(site_settings.api_url + 'subcategory/get_subcategory', {'id': product_quotation})
                .then(function (response)
                {
                    $scope.products = response.data;
                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong';
            $rootScope.$emit("notification");
        });
    }
    ;

    $scope.saveStoragePriceCategory = function ()
    {

        $http.post(site_settings.api_url + 'subcategory/save_storage_price_subcategory', $scope.products)
                .then(function (response)
                {

                    $rootScope.message = response.data;
                    $rootScope.message = 'Storage Price add Successfully';
                    $rootScope.$emit("notification");
                    $rootScope.$emit("reloadProductForProductionTable");
                    $mdDialog.hide();
                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong';
            $rootScope.$emit("notification");
        });
    };



    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };
});
