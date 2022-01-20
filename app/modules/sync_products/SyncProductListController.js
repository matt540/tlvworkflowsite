"use strict";

var app = angular.module('ng-app');
app.controller('SyncProductListController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken) {
    $scope.category = [];
    $scope.subcategoryid = [];
    $scope.start_date = null;
    $scope.end_date = null;
    $scope.start_date_ord = null;
    $scope.end_date_ord = null;
    $scope.authuser = $auth.getProfile().$$state.value;
    $scope.dtInstance = {};
    $scope.users = [];
    $scope.select = [];
    $scope.productStatus = [];

    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
        dataSrc: "data",
        url: site_settings.api_url + 'get_all_sync_product',
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



    function productRender(data, type, full, meta)
    {
        if (data && data != '')
        {
            return data;
        } else
        {
            return '---';
        }
    }

    function renderprice(data, type, full, meta) {

        if (full.product_id.tlv_price !== '' && full.product_id.tlv_price !== null) {
            return full.product_id.tlv_price + ' USD';
        } else
        {
            return '--';
        }

    }

    function renderpricesale(data, type, full, meta) {


        if (full.wp_sale_price !== '' && full.wp_sale_price !== null) {
            return full.wp_sale_price + ' USD';
        } else
        {
            return '--';
        }
    }

    function renderSellerName(data, type, full, meta) {

        if (full.product_id.sellerid !== null) {
            return full.product_id.sellerid.firstname;
        } else
        {
            return '--';
        }
    }

    function actionsHtml(data, type, full, meta)
    {
        if (full.wp_product_id !== null && full.wp_product_id !== '') {
            return '<span ui-sref="sync_product_order({id: ' + full.wp_product_id + '})" class="text-boxed m-0 deep-orange-bg white-fg" style="cursor: pointer">Details</span>';
        } else
        {
            return '--';
        }
        
    }
           
    function renderPublishDate(data, type, full, meta) {
        // console.log(full.wp_published_date);

        if (full.wp_published_date !== null && full.wp_published_date.date !== '-0001-11-30 00:00:00.000000')
        {
            return moment(full.wp_published_date.date).local().format('MM/DD/YYYY');
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
                 
                // console.log(value.sub_category_name);
                      
                if (value.is_enable == '0')
                {
                      text.push(value.sub_category_name);
                }
                      
               });
                return text.join(", ");
            //return full.product_id.category.sub_category_name;
            
        } else
        {
            return '--';
        }

    }


    $scope.searchSellers = function () {
        $http.post(site_settings.api_url + 'search-sellers', {q: $scope.searchTerm})
                .then(function (response) {
                    $scope.sellers = response.data;
                })
                .catch(function () {
                    $scope.sellers = [];
                });
    };

    $scope.filtersubcat = function ()
    {
        if ($scope.category !== '0')
        {
            $scope.fetchsubcategory();
        }

    }


    $scope.filterproductseller = function ()
    {
        //$scope.fetchsubcategory();
        var start_date_new = '';
        var end_date_new = '';
        var start_date_order = '';
        var end_date_order = '';

        if ($scope.start_date !== 'Invalid date')
        {
            if (angular.isDefined($scope.start_date))
            {

                start_date_new = moment($scope.start_date).local().format('YYYY-MM-DD');

            }
        }

        if ($scope.end_date !== 'Invalid date')
        {
            if (angular.isDefined($scope.end_date))
            {
                end_date_new = moment($scope.end_date).local().format('YYYY-MM-DD');

            }
        }

         if ($scope.start_date_ord !== 'Invalid date')
        {
            if (angular.isDefined($scope.start_date_ord))
            {

                start_date_order = moment($scope.start_date_ord).local().format('YYYY-MM-DD');

            }
        }

        if ($scope.end_date_ord !== 'Invalid date')
        {
            if (angular.isDefined($scope.end_date_ord))
            {
                end_date_order = moment($scope.end_date_ord).local().format('YYYY-MM-DD');

            }
        }

//        if($scope.category !== '0')
//        {
//            $scope.fetchsubcategory();
//        }
        // console.log($scope.category);

        // end_date_new = moment($scope.end_date).local().format('YYYY-MM-DD');



        $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
            dataSrc: "data",
            url: site_settings.api_url + 'get_all_sync_product',
            timeout: 300000,
            type: "POST",
            headers: {Authorization: 'Bearer ' + ngAAToken.getToken()},
            data: {sellerid: $scope.sellerid, brand: $scope.brand, category: $scope.category, ship_size: $scope.shipping_size, wp_flat_rate: $scope.flatrate, wp_stock_status: $scope.sold, subcategoryid: $scope.subcategoryid, start_date_new: start_date_new, end_date_new: end_date_new, start_date_order: start_date_order, end_date_order: end_date_order}
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


    };


    $scope.dtColumns = [
        DTColumnBuilder.newColumn('product_id.sku').withTitle('SKU'),
        DTColumnBuilder.newColumn('product_id.name').withTitle('Product Name').withOption('width', '15%'),
        DTColumnBuilder.newColumn('product_id.product_category').withTitle('Sub Category').renderWith(renderSubcategory),
        DTColumnBuilder.newColumn('product_id.tlv_price').withTitle('Price').renderWith(renderprice),
        DTColumnBuilder.newColumn('wp_sale_price').withTitle('Sale Price').renderWith(renderpricesale),
        DTColumnBuilder.newColumn('product_id.sellerid.firstname').withTitle('Seller Name').renderWith(renderSellerName),
        DTColumnBuilder.newColumn('product_id.wp_published_date').withTitle('Publish Date').renderWith(renderPublishDate),
        DTColumnBuilder.newColumn('product_id.flat_rate_packaging_fee').withTitle('Flat Rate'),
        DTColumnBuilder.newColumn('wp_stock_status').withTitle('Stock Status'),
        DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable()
                .renderWith(actionsHtml)

    ];


    $scope.generateReportSyncProduct = function ()
    {
//        var product_report_send = $scope.product_report;
//        product_report_send.is_excel_generate = true;

         var start_date_new = '';
        var end_date_new = '';
        var start_date_order = '';
        var end_date_order = '';

        if ($scope.start_date !== 'Invalid date')
        {
            if (angular.isDefined($scope.start_date))
            {

                start_date_new = moment($scope.start_date).local().format('YYYY-MM-DD');

            }
        }

        if ($scope.end_date !== 'Invalid date')
        {
            if (angular.isDefined($scope.end_date))
            {
                end_date_new = moment($scope.end_date).local().format('YYYY-MM-DD');

            }
        }
        
        if ($scope.start_date_ord !== 'Invalid date')
        {
            if (angular.isDefined($scope.start_date_ord))
            {

                start_date_order = moment($scope.start_date_ord).local().format('YYYY-MM-DD');

            }
        }

        if ($scope.end_date_ord !== 'Invalid date')
        {
            if (angular.isDefined($scope.end_date_ord))
            {
                end_date_order = moment($scope.end_date_ord).local().format('YYYY-MM-DD');

            }
        }
        
        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'get_sync_product_order_report', {sellerid: $scope.sellerid, brand: $scope.brand, category: $scope.category, ship_size: $scope.shipping_size, wp_flat_rate: $scope.flatrate, wp_stock_status: $scope.sold, subcategoryid: $scope.subcategoryid, start_date_new: start_date_new, end_date_new: end_date_new, start_date_order: start_date_order, end_date_order: end_date_order})
                .then(function (response)
                {
                    $rootScope.loader = false;
                    var b = response.data;
                    var a = document.createElement('a');
                    document.getElementById("content").appendChild(a);
//                    a.download = b;
                    a.target = '_blank';
                    a.id = b;
                    // a.href = 'api/storage/exports/' + b;
                    a.href = b;
                    a.click();
                    document.body.removeChild(a);
                    $rootScope.loader = false;
//                    setTimeout(function () {
//                        a.click();
//                        //document.getElementById("content").removeChild(a);
//                    }, 300000);

                })
                .catch(function (error)
                {
                    $rootScope.loader = false;
//                $rootScope.message = 'Something Went Wrong';
//                $rootScope.$emit("notification");
                });



    }

    $http.get(site_settings.api_url + 'get_all_subcategorys_of_category_id/1')
            .then(function (response)
            {
                $scope.brands = response.data;

            }).catch(function (error)
    {
        $rootScope.message = 'Something Went Wrong';
        $rootScope.$emit("notification");
    });

    $http.get(site_settings.api_url + 'get_all_subcategorys_of_category_id/2')
            .then(function (response)
            {
                $scope.categorys = response.data;

            }).catch(function (error)
    {
        $rootScope.message = 'Something Went Wrong';
        $rootScope.$emit("notification");
    });

    $http.get(site_settings.api_url + 'getOptionsBySelectId/9')
            .then(function (response)
            {
                $scope.ship_sizes = response.data;
            })
            .catch(function (error)
            {
                $rootScope.message = 'Something Went Wrong.';
                $rootScope.$emit("notification");
                $rootScope.loader = false;

            });

    $scope.fetchsubcategory = function ()
    {
 
        $http.get(site_settings.api_url + 'get_all_subcategorys_of_category_id/2')
                .then(function (response) {

                    $scope.subcategory = response.data;
                    var childrens1 = [];
                    var childs =[];
                     // console.log( $scope.category);

                    angular.forEach($scope.subcategory, function (value, key) {

//                        console.log($scope.category);
                        if ($scope.category.includes(value.id.toString()))
                        {
                            // console.log(value.id);
                             
                            childs.push(value.childrens);

                            // console.log(value.id);
                        }
                        //console.log($scope.childrens);
                      
                        angular.forEach(childs, function (value, key) { 
                            angular.forEach(value, function (value2, key2) { 
                            
                            childrens1.push(value2);
                    
});

                        });
                    });
                    
                   
                   function removeDuplicates(data){
                       return [...new Set(data)]
                   }
                   $scope.childrens = removeDuplicates(childrens1);

                });

    }
});

app.controller('SyncProductOrderController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken) {

    $scope.authuser = $auth.getProfile().$$state.value;
    $scope.dtInstance = {};

//    $scope.users = [];
//    $scope.select = [];
//    $scope.productStatus = [];
//    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
//        dataSrc: "data",
//        url: site_settings.api_url + 'get_sync_product_order',
//        type: "POST",
//        headers: {Authorization: 'Bearer ' + ngAAToken.getToken()},
//        data: {wp_product_id: $stateParams.id}
//    }).withOption('processing', true) //for show progress bar
//            .withOption('serverSide', true) // for server side processing
//            .withPaginationType('full_numbers') // for get full pagination options // first / last / prev / next and page numbers
//            .withDisplayLength(10) // Page size
//            .withOption('aaSorting', [])
//            .withOption('autoWidth', false)
//            .withOption('responsive', true)
//            .withOption('dom', '<"top"f>rt<"bottom"<"left"<"length"l>><"right"<"info"i><"pagination"p>>>')
//            .withDataProp('data');
//
//
//    function productRender(data, type, full, meta)
//    {
//        if (data && data != '')
//        {
//            return data;
//        } else
//        {
//            return '---';
//        }
//    }
//
//
//
//    $scope.dtColumns = [
//        DTColumnBuilder.newColumn('order_number').withTitle('Order Number'),
//        DTColumnBuilder.newColumn('status').withTitle('Status'),
//    ];


    $http.post(site_settings.api_url + 'get_sync_product_order', {wp_product_id: $stateParams.id})
            .then(function (response) {

                $scope.orders = response.data.data;
                $scope.product = response.data.product_detail;
                
                //    console.log( $scope.orders);

                $scope.wp_published_date = '';

                if ($scope.product.wp_published_date !== null && $scope.product.wp_published_date.date !== '-0001-11-30 00:00:00.000000')
                {
                    $scope.wp_published_date = formatDatePublish($scope.product.wp_published_date.date);
                }
                
                
                //  console.log($scope.wp_published_date);
                

                angular.forEach($scope.orders, function (value, key) {
                    $scope.orders[key].billing = JSON.parse(value.billing);
                    $scope.orders[key].shipping = JSON.parse(value.shipping);
                    $scope.orders[key].line_items_product = JSON.parse(value.line_items_product);
                    $scope.orders[key].shipping_lines = JSON.parse(value.shipping_lines);
                    // var metadata = JSON.parse(value.meta_data);

                    var order_list = JSON.parse(value.order_list);

                    $scope.orders[key].date_created = formatDate(value.date_created);

                   console.log(value.created_at);

                    angular.forEach(order_list, function (value_order_list, key_order_list) {



                        if (value_order_list['lv_order_product_id'] == $stateParams.id)
                        {
                            //   console.log($stateParams.id);


                            $scope.orders[key].order_list = value_order_list;

                        }


                    });





//                    $scope.metadata_arr = {};
//
//                    angular.forEach(metadata, function (value_metadata, key_metadata) {
//
//                        if (value_metadata['value'] === $stateParams.id)
//                        {
//                            var prokey = value_metadata['key'];
//                            var order_comm_seller = prokey.split('_');
//
//                            $scope.orders[key].order_comm_seller_position = 'lv_order_commission_' + order_comm_seller[3];
//
//                        }
//
//
//                    });

//                    angular.forEach(metadata, function (value_metadata1, key_metadata1) {
//
//                        if (value_metadata1['key'].includes($scope.orders[key].order_comm_seller_position))
//                        {
//
//                            if (value_metadata1['key'] === $scope.orders[key].order_comm_seller_position + '_lv_order_comm_seller')
//                            {
//                                $scope.metadata_arr['seller'] = value_metadata1['value'];
//                            }
//
//                            if (value_metadata1['key'] === $scope.orders[key].order_comm_seller_position + '_lv_order_comm_product')
//                            {
//                                $scope.metadata_arr['product'] = value_metadata1['value'];
//                            }
//                            
//                            if (value_metadata1['key'] === $scope.orders[key].order_comm_seller_position + '_lv_order_comm_sub_total')
//                            {
//                                $scope.metadata_arr['subtotal'] = value_metadata1['value'];
//                            }
//                            
//                            if (value_metadata1['key'] === $scope.orders[key].order_comm_seller_position + '_lv_order_orignal_total')
//                            {
//                                $scope.metadata_arr['original_total'] = value_metadata1['value'];
//                            }
//                            
//                            if (value_metadata1['key'] === $scope.orders[key].order_comm_seller_position + '_lv_order_comm_commission')
//                            {
//                                $scope.metadata_arr['commission'] = value_metadata1['value'];
//                            }
//
//                        }
//
//                    });
//
//                    $scope.orders[key].metadata = $scope.metadata_arr;
                function formatDate(data)
                {
                    if (data)
                    {
                            return moment(data).local().format('MM/DD/YYYY');
                    } else
                    {
                        return '-----';
                    }
                }
                
                });

                

                 function formatDatePublish(data)
                {
                    if (data)
                    {
                        return moment(data).local().format('MM/DD/YYYY');
                    } else
                    {
                        return '-----';
                    }
                }


               //  console.log($scope.orders);



            }).catch(function (error) {

    });

});
