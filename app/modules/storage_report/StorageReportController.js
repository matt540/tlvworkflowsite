"use strict";

var app = angular.module('ng-app');
app.controller('StorageReportController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken, $window)
{
    $scope.authuser = $auth.getProfile().$$state.value;
    $scope.dtInstance = {};
    $scope.users = [];
    $scope.select = [];
    $scope.productStatus = [];
    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
        dataSrc: "data",
        url: site_settings.api_url + 'storage_report/get_storage_products',
        type: "POST",
        headers: {Authorization: 'Bearer ' + ngAAToken.getToken()}
    }).withOption('processing', true) //for show progress bar
            .withOption('serverSide', true) // for server side processing
            .withPaginationType('full_numbers') // for get full pagination options // first / last / prev / next and page numbers
            .withDisplayLength(25) // Page size
            .withOption('aaSorting', [1, 'asc'])
            .withOption('autoWidth', false)
            .withOption('responsive', true)
            .withOption('dom', '<"top"f>rt<"bottom"<"left"<"length"l>><"right"<"info"i><"pagination"p>>>')
            .withDataProp('data')
            .withOption('fnDrawCallback', function (data) {
                $scope.filterData = data.oAjaxData;
            })
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


    function callback(json)
    {

    }

    function columnRender(data, type, full, meta)
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
        DTColumnBuilder.newColumn('seller_name').withTitle('Seller Name').renderWith(columnRender),
        DTColumnBuilder.newColumn('name').withTitle('Product Name'),
        DTColumnBuilder.newColumn('sku').withTitle('SKU').renderWith(columnRender),
        DTColumnBuilder.newColumn('storage_pricing').withTitle('Storage Cost').renderWith(priceRender).notSortable(),
        DTColumnBuilder.newColumn('storage_date').withTitle('Storage Start Date').notSortable()
    ];

    function priceRender(data, type, full, meta)
    {
        if (data != '')
        {
            return '$' + data;

        } else
        {
            return '- - - ';
        }
    }

    $scope.downloadStorageReport = function () {

        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'storage_agreement/export_storage_report',$scope.filterData)
                .then(function (response)
                {
                    $rootScope.loader = false;
                    $scope.productStatus = [];
                    var b = response.data;
                    var a = document.createElement('a');
                    document.getElementById("content").appendChild(a);
                   // a.download = b;
                    a.target = '_blank';
                    a.id = b;
                    // a.href = 'api/storage/exports/' + b;
                    a.href = b;
                    a.click();
                    document.body.removeChild(a);
                })
                .catch(function (error)
                {
                    $rootScope.loader = false;
                });
    };
});