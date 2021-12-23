"use strict";

var app = angular.module('ng-app');
app.controller('ProductReportController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken)
{
    $scope.product_report = {};
    $scope.product_report.state = 'all';
    $scope.states = [
        {key: 'product', value: "Product For Review"},
        {key: 'proposal', value: "Proposal"},
        {key: 'product_for_production', value: "Product For Production"},
//        {key: 'product_for_production', value: "Product For Production"},
        {key: 'copyright', value: "Copywright"},
        {key: 'approvalproducts', value: "Approval"},
        {key: 'approved', value: "Approved"},
        {key: 'all', value: "All"},
    ];
    $scope.$watch("product_report.state", function (newVal, oldVal) {
        $scope.is_generate_report = false;

    });
    $scope.$watch("product_report.seller_id", function (newVal, oldVal) {
        $scope.is_generate_report = false;

    });
    $scope.getAllSellers = function ()
    {
        $http.get(site_settings.api_url + 'seller/get_all_seller')
                .then(function (response)
                {
                    $scope.sellers = response.data;
                    if ($scope.sellers.length > 0)
                    {
                        $scope.product_report.seller_id = $scope.sellers[0].id;
//                        $scope.getReport($scope.product_report);
                    }

//                    $scope.user.password = '********';
//                    console.log($scope.user);
                }).catch(function (error)
        {

        });
    };
    $scope.is_generate_report = false;

    $scope.generateReportExcel = function ()
    {
        var product_report_send = $scope.product_report;
        product_report_send.is_excel_generate = true;

        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'product_report/get_product_report', $scope.product_report)
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

                    setTimeout(function () {
                        a.click();
                        //document.getElementById("content").removeChild(a);
                    }, 500);

                })
                .catch(function (error)
                {
                    $rootScope.loader = false;
//                $rootScope.message = 'Something Went Wrong';
//                $rootScope.$emit("notification");
                });



    }



    $scope.generateReport = function () {
        if (!$scope.product_report.seller_id)
        {
            $rootScope.message = 'Please Select Seller First';
            $rootScope.$emit("notification");

        } else
        {
            $scope.getReport($scope.product_report);
            $scope.is_generate_report = true;

        }

    }
    $scope.$watch("product_report.start_date", function (newVal, oldVal) {
        if (newVal != oldVal)
        {
            $scope.product_report.start_date_updated = moment($scope.product_report.start_date).local().format('YYYY-MM-DD');
            $scope.is_generate_report = false;
//            $scope.is_generate_report = false;
        }
    });
    $scope.$watch("product_report.end_date", function (newVal, oldVal) {
        if (newVal != oldVal)
        {
            $scope.product_report.end_date_updated = moment.utc($scope.product_report.end_date).local().format('YYYY-MM-DD');
            $scope.is_generate_report = false;
//            $scope.is_generate_report = false;
        }
    });
    $scope.getAllSellers();

//    $scope.getSellerById($stateParams.id);

    $scope.authuser = $auth.getProfile().$$state.value;
    $scope.details_all = [];

//    $scope.total = 0;
    $scope.totalAmount = function (amount1, amount2) {
//        $scope.total = parseInt(amount1) + parseInt(amount2);
//       return $scope.total;
        return (parseInt(amount1) + parseInt(amount2)) || '';
    }
    $scope.renderSeller = function (seller_id) {
        var seller = {};
        $scope.sellers.forEach(function (value) {
            if (seller_id == value.id)
            {
                seller = value;
            }

        });
        return seller;
    }

    $scope.getReport = function (product_report) {
        product_report.state = 'all';
        product_report.is_excel_generate = false;

        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'product_report/get_product_report', product_report)
                .then(function (response)
                {
                    $rootScope.loader = false;
                    $scope.details_all = response.data;
                }).catch(function (error)
        {
            $rootScope.loader = false;
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
        });

//        $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
//            dataSrc: "data",
//            url: site_settings.api_url + 'product_report/get_product_report',
//            type: "POST",
//            headers: {Authorization: 'Bearer ' + ngAAToken.getToken()},
//            data: product_report
//        }).withOption('processing', true) //for show progress bar
//                .withOption('serverSide', true) // for server side processing
//                .withPaginationType('full_numbers') // for get full pagination options // first / last / prev / next and page numbers
//                .withDisplayLength(10) // Page size
//                .withOption('aaSorting', [2, 'asc'])
//                .withOption('autoWidth', false)
//                .withOption('responsive', true)
//                .withOption('dom', '<"top"f>rt<"bottom"<"left"<"length"l>><"right"<"info"i><"pagination"p>>>')
//                .withDataProp('data')
//                .withOption('fnRowCallback',
//                        function (nRow, aData, iDisplayIndex, iDisplayIndexFull)
//                        {
//                            $compile(nRow)($scope);
//                        });


    }


    function profileImage(data, type, full, meta)
    {

//        console.log('data');
        if (data != null && data != undefined)
        {
            var array = data.split(',');
            return '<a href="Uploads/product/' + array[0] + '" fancyboxable><img  style="width:40px;height:40px;border-radius:30px;" src="Uploads/product/' + array[0] + '"></a>';
        } else
        {
            return '<a href="/assets/images/avatars/profile.jpg" fancyboxable><img  style="width:40px;height:40px;border-radius:30px;" src="/assets/images/avatars/profile.jpg"></a>';
        }
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
    function agingRender(data, type, full, meta)
    {
        return 1;
//        if (full.for_production_created_at != null)
//        {
//
//            var startDate = moment.utc(full.quote_created_at.date).local();
//            var endDate = moment.utc(full.for_production_created_at.date).local();
//
//            var result = endDate.diff(startDate, 'days');
//            return result + 1 + " Day";
//
//        } else
//        {
//            var startDate = moment.utc(full.quote_created_at.date).local();
//            var endDate = moment.utc(new Date()).local();
//            var result = endDate.diff(startDate, 'days');
//            return result + 1 + " Day";
//
//        }
    }
    $scope.renderdDate = function (date) {
        return moment.utc(date.date).format('MM/DD/YYYY');

    };
    $scope.renderDate = function (date) {
        return moment.utc(date.date).local().format('MM/DD/YYYY');

    };
    function renderDate(data, type, full, meta)
    {
        if ($scope.product_report.state == 'product_for_production')
        {
            if (full.for_production_created_at)
            {
                return moment.utc(full.for_production_created_at.date).local().format('MM/DD/YYYY');
            } else
            {
                return '-----';
            }
        } else if ($scope.product_report.state == 'product')
        {
            if (full.created_at)
            {
                return moment.utc(full.created_at.date).local().format('MM/DD/YYYY');
            } else
            {
                return '-----';
            }

        } else if ($scope.product_report.state == 'proposal')
        {
            if (full.quote_created_at)
            {
                return moment.utc(full.quote_created_at.date).format('MM/DD/YYYY');
            } else
            {
                return '-----';
            }

        } else if ($scope.product_report.state == 'copyright')
        {
            if (full.copyright_created_at)
            {
                return moment.utc(full.copyright_created_at.date).local().format('MM/DD/YYYY');
            } else
            {
                return '-----';
            }

        } else if ($scope.product_report.state == 'approvalproducts')
        {
            if (full.approved_created_at)
            {
                return moment.utc(full.approved_created_at.date).local().format('MM/DD/YYYY');
            } else
            {
                return '-----';
            }

        } else if ($scope.product_report.state == 'approved')
        {
            if (full.updated_at)
            {
                return moment.utc(full.updated_at.date).local().format('MM/DD/YYYY');
            } else
            {
                return '-----';
            }

        }
    }
    function statusHtml(data, type, full, meta)
    {
        if ($scope.product_report.state == 'product')
        {
            if (full.status_value)
            {
                return full.status_value;
            } else
            {
                return '---';
            }
        } else if ($scope.product_report.state == 'proposal')
        {
            if (full.is_send_mail)
            {
                if (full.is_send_mail == 2)
                {
                    return 'Rejected';
                } else
                {
                    return 'Pending';
                }
//                else if(full.is_send_mail == ){
//                    
//                }
            } else
            {
                return '---';
            }
        } else if ($scope.product_report.state == 'product_for_production')
        {
            if (full.is_product_for_production && full.is_product_for_production == 0)
            {
                return 'Pending';
            } else if (full.is_product_for_production == 2)
            {
                return 'Rejected';
            }

        } else if ($scope.product_report.state == 'copyright')
        {
            console.log(full.is_copyright);
            if (full.is_copyright == 0)
            {
                return 'Pending';
            } else if (full.is_copyright == 2)
            {
                return 'Rejected';
            }
            return '123';

        } else if ($scope.product_report.state == 'approvalproducts')
        {
//            console.log(full.is_copyright );
            if (full.status_id == 17)
            {
                return 'Pending';
            } else if (full.status_id == 19)
            {
                return 'Rejected';
            }
            return '123';

        } else if ($scope.product_report.state == 'approved')
        {
//            console.log(full.is_copyright );
            if (full.status_id == 18)
            {
                return 'Synced';
            } else
            {
                return '---';
            }
            return '123';

        }

    }


    $scope.dtColumns = [
        DTColumnBuilder.newColumn('image').withTitle('Image').notSortable().renderWith(profileImage),
        DTColumnBuilder.newColumn('sku').withTitle('SKU').renderWith(skuRender),
        DTColumnBuilder.newColumn('name').withTitle('Product Name'),
//        DTColumnBuilder.newColumn('sell_name').withTitle('Sell Name'),
        DTColumnBuilder.newColumn(null).withTitle('Created Date').renderWith(renderDate),
//        DTColumnBuilder.newColumn(null).withTitle('Aging').renderWith(agingRender),
//        DTColumnBuilder.newColumn('price').withTitle('Price'),
//        DTColumnBuilder.newColumn('tlv_suggested_price_max').withTitle('Suggested Max Price'),
//        DTColumnBuilder.newColumn('tlv_suggested_price_min').withTitle('Suggested Min Price'),

        DTColumnBuilder.newColumn(null).withTitle('Status').renderWith(statusHtml),
//        DTColumnBuilder.newColumn('images_from').withTitle('Schedule').renderWith(schedule).notSortable(),
//        DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable()
//                .renderWith(actionsHtml),
    ];

    $scope.openProductQuotationViewDialog = function (product_quotation)
    {
        $mdDialog.show({
            controller: 'ProductQuotationReportViewController',
            templateUrl: 'app/modules/product_report/views/product_report_view.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                product_quotation: product_quotation
            }
        });
    }
    $scope.downloadDocumentInWordProposal = function ()
    {
        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'export_products_document_in_word_proposal', {products: $scope.productStatus, seller: $stateParams.id})
                .then(function (response)
                {
                    $rootScope.loader = false;
                    $scope.productStatus = [];
                    var b = response.data;
                    var a = document.createElement('a');
                    document.getElementById("content").appendChild(a);
                    a.download = b;
                    a.target = '_blank';
                    a.id = b;
                    a.href = '../Uploads/word/' + b;
                    a.click();
                    document.body.removeChild(a);
                }).catch(function (error)
        {
            $rootScope.loader = false;
//                $rootScope.message = 'Something Went Wrong';
//                $rootScope.$emit("notification");
            console.log(error);
        });

    }

});
app.controller('ProductQuotationReportViewController', function (product_quotation, $parse, $document, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };

    $rootScope.loader = true;
    $scope.action = 'View';
    $scope.getAllNames = function (array) {
        var names = '';
        array.forEach(function (value, key) {
            names += value.sub_category_name;
            if (key != (names.length - 1))
            {
                names + ', ';

            }
        });
        return names;
    };


    $http.post(site_settings.api_url + 'product_quotation/get_product_quotation', {id: product_quotation})
            .then(function (response)
            {
                $scope.product_quotation = response.data;
                $scope.product_quotation.product_id.product_category_name = $scope.getAllNames($scope.product_quotation.product_id.product_category);
                $scope.product_quotation.product_id.product_collection_name = $scope.getAllNames($scope.product_quotation.product_id.product_collection);
                $scope.product_quotation.product_id.product_color_name = $scope.getAllNames($scope.product_quotation.product_id.product_color);
                $scope.product_quotation.product_id.product_con_name = $scope.getAllNames($scope.product_quotation.product_id.product_con);
                $scope.product_quotation.product_id.product_look_name = $scope.getAllNames($scope.product_quotation.product_id.product_look);
                $scope.product_quotation.product_id.product_room_name = $scope.getAllNames($scope.product_quotation.product_id.product_room);

                console.log(response.data);
                $rootScope.loader = false;

            });
});