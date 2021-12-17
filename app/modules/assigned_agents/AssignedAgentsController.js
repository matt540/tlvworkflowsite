"use strict";

var app = angular.module('ng-app');
app.controller('AssignedAgentsController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken, $window)
{
    $scope.authuser = $auth.getProfile().$$state.value;

    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
        dataSrc: "data",
        url: site_settings.api_url + 'products/assigned_agents',
        type: "POST",
        headers: {Authorization: 'Bearer ' + ngAAToken.getToken()},
        data: {id: $stateParams.id}
    }).withOption('processing', true) //for show progress bar
            .withOption('serverSide', true) // for server side processing
            .withPaginationType('full_numbers') // for get full pagination options // first / last / prev / next and page numbers
            .withDisplayLength(10) // Page size
            .withOption('aaSorting', [0, 'asc'])
            .withOption('autoWidth', false)
            .withOption('responsive', true)
            .withOption('dom', '<"top"f>rt<"bottom"<"left"<"length"l>><"right"<"info"i><"pagination"p>>>')
            .withDataProp('data');

    $scope.dtColumns = [
        DTColumnBuilder.newColumn(null).withTitle('Agent Name').renderWith(columnAgentNameRender),
        DTColumnBuilder.newColumn(null).withTitle('Seller').renderWith(columnSellerRender),
        DTColumnBuilder.newColumn(null).withTitle('Product Name').renderWith(columnProductNameRender),
        DTColumnBuilder.newColumn(null).withTitle('SKU').renderWith(columnProductSKURender),
    ];
     $scope.dtInstance = {};

    function columnAgentNameRender(data, type, full, meta) {
        return data.agent_name;
    }
    
    function columnSellerRender(data, type, full, meta) {
        return data.seller_name;
    }
    
    function columnProductNameRender(data, type, full, meta) {
        return data.product_name;
    }
    
    function columnProductSKURender(data, type, full, meta) {
        return data.product_sku;
    }
});
