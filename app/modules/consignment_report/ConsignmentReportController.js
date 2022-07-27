"use strict";

var app = angular.module('ng-app');
app.controller('ConsignmentReportController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken) {

    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
        dataSrc: "data",
        url: site_settings.api_url + 'get-consignment-report',
        timeout: 300000,
        type: "POST",
        headers: {Authorization: 'Bearer ' + ngAAToken.getToken()},
        data: {}
    }).withOption('processing', true) //for show progress bar
            .withOption('serverSide', true) // for server side processing
            .withPaginationType('full_numbers') // for get full pagination options // first / last / prev / next and page numbers
            .withDisplayLength(50) // Page size
            .withOption('aaSorting', [])
            .withOption('autoWidth', false)
            .withOption('responsive', true)
            .withOption('dom', '<"top"f>rt<"bottom"<"left"<"length"l>><"right"<"info"i><"pagination"p>>>')
            .withDataProp('data')
            .withOption('fnRowCallback',
                    function (nRow, aData, iDisplayIndex, iDisplayIndexFull)
                    {
                        $compile(nRow)($scope);
                    });


    // $scope.dtColumns = [
    //     DTColumnBuilder.newColumn('product_id.sellerid').withTitle('Seller Name').renderWith(renderSellerName),
    //     DTColumnBuilder.newColumn('product_id.sellerid').withTitle('Seller Email').renderWith(renderSellerEmail),
    //     DTColumnBuilder.newColumn('product_id.sku').withTitle('SKU'),
    //     DTColumnBuilder.newColumn('product_id.name').withTitle('Product Name').withOption('width', '15%'),
    //     DTColumnBuilder.newColumn('product_id.tlv_price').withTitle('Price').renderWith(renderprice),
    //     DTColumnBuilder.newColumn('product_id.wp_published_date').withTitle('Publish Date').renderWith(renderPublishDate),
    //
    // ];

    $scope.dtColumns = [
        DTColumnBuilder.newColumn('seller_name').withTitle('Seller Name'),
        DTColumnBuilder.newColumn('email').withTitle('Seller Email'),
        DTColumnBuilder.newColumn('sku').withTitle('SKU'),
        DTColumnBuilder.newColumn('name').withTitle('Product Name').withOption('width', '15%'),
        DTColumnBuilder.newColumn('tlv_price').withTitle('Price').renderWith(renderprice),
        DTColumnBuilder.newColumn('wp_published_date').withTitle('Publish Date').renderWith(renderPublishDate),

    ];

    function renderSellerName(data, type, full, meta) {
        if (full.product_id.sellerid !== null) {
            return full.product_id.sellerid.firstname + ' ' + full.product_id.sellerid.lastname;
        } else
        {
            return '--';
        }
    }
    function renderSellerEmail(data, type, full, meta) {
        if (full.product_id.sellerid !== null) {
            return full.product_id.sellerid.email;
        } else
        {
            return '--';
        }
    }
    function renderSellerAddress(data, type, full, meta) {
        if (full.product_id.sellerid !== null) {
            return full.product_id.sellerid.address;
        } else
        {
            return '--';
        }
    }
    function renderPublishDate(data, type, full, meta) {
        if (full.wp_published_date !== null && full.wp_published_date.date !== '-0001-11-30 00:00:00.000000')
        {
            return moment(full.wp_published_date.date).local().format('MM/DD/YYYY');
        } else
        {
            return '--';
        }
    }
    function renderCategory(data, type, full, meta)
    {
        if (full.product_id.product_category !== null && full.product_id.product_category !== '') {
            var text = [];
            angular.forEach(full.product_id.product_category, function (value, key) {
                if (value.is_enable == '1')
                {
                    text.push(value.sub_category_name);
                }
            });
            return text.join(", ");
        } else
        {
            return '--';
        }
    }
    function renderSubcategory(data, type, full, meta)
    {
        if (full.product_id.product_category !== null && full.product_id.product_category !== '') {
            var text = [];
            angular.forEach(full.product_id.product_category, function (value, key) {
                if (value.is_enable == '0')
                {
                    text.push(value.sub_category_name);
                }
            });
            return text.join(", ");
        } else
        {
            return '--';
        }
    }
    function renderBrand(data, type, full, meta)
    {
        if (full.product_id.brand !== null && full.product_id.brand !== '') {
            return full.product_id.brand.sub_category_name;
        } else
        {
            return '--';
        }
    }
    function renderprice(data, type, full, meta) {
        if (full.tlv_price !== '' && full.tlv_price !== null) {
            return full.tlv_price + ' USD';
        } else
        {
            return '--';
        }
    }


    $scope.generateReportConsignment = function ()
    {
        $rootScope.loader = true;

        $http.post(site_settings.api_url + 'consignment-report-export', {})
            .then(function (response)
            {
                $rootScope.loader = false;
                var b = response.data;
                var a = document.createElement('a');
                document.getElementById("content").appendChild(a);
                a.target = '_blank';
                a.id = b;
                a.href = b;
                a.click();
                document.body.removeChild(a);
                $rootScope.loader = false;
            })
            .catch(function (error)
            {
                $rootScope.loader = false;
            });
    }
});
