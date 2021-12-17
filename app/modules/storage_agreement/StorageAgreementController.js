"use strict";

var app = angular.module('ng-app');
app.controller('StorageAgreementController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.storage_agreement = {};
    $scope.storage_agreement.local_vault_date = new Date();
    $scope.storage_agreement.consignor_date = new Date();
    $scope.storage_agreement.consignor_storer = 'The Local Vault, LLC';
    
    
    var todayDate = new Date();
    $scope.storage_agreement.day = todayDate.getDate();
    $scope.storage_agreement.month = todayDate.getMonth() + 1;
    $scope.storage_agreement.year = parseInt(todayDate.getFullYear().toString().substr(-2));

    var EMPTY_IMAGE = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAjgAAADcCAQAAADXNhPAAAACIklEQVR42u3UIQEAAAzDsM+/6UsYG0okFDQHMBIJAMMBDAfAcADDATAcwHAAwwEwHMBwAAwHMBzAcAAMBzAcAMMBDAcwHADDAQwHwHAAwwEMB8BwAMMBMBzAcADDATAcwHAADAcwHADDAQwHMBwAwwEMB8BwAMMBDAfAcADDATAcwHAAwwEwHMBwAAwHMBzAcAAMBzAcAMMBDAcwHADDAQwHwHAAwwEwHMBwAMMBMBzAcAAMBzAcwHAADAcwHADDAQwHMBwAwwEMB8BwAMMBDAfAcADDATAcwHAAwwEwHMBwAAwHMBzAcCQADAcwHADDAQwHwHAAwwEMB8BwAMMBMBzAcADDATAcwHAADAcwHMBwAAwHMBwAwwEMBzAcAMMBDAfAcADDAQwHwHAAwwEwHMBwAAwHMBzAcAAMBzAcAMMBDAcwHADDAQwHwHAAwwEMB8BwAMMBMBzAcADDATAcwHAADAcwHMBwAAwHMBwAwwEMB8BwAMMBDAfAcADDATAcwHAAwwEwHMBwAAwHMBzAcAAMBzAcAMMBDAcwHADDAQwHwHAAwwEMB8BwAMMBMBzAcADDkQAwHMBwAAwHMBwAwwEMBzAcAMMBDAfAcADDAQwHwHAAwwEwHMBwAMMBMBzAcAAMBzAcwHAADAcwHADDAQwHMBwAwwEMB8BwAMMBMBzAcADDATAcwHAADAcwHMBwAAwHMBwAwwEMBzAcAMMBDAegeayZAN3dLgwnAAAAAElFTkSuQmCC';



    $scope.$watch('dataurl', function (newVal, oldVal)
    {
        if (newVal == EMPTY_IMAGE)
        {
            delete $scope.signature;

        }

        if (newVal && newVal != EMPTY_IMAGE)
        {
            $scope.signature = $scope.accept()
        }

        console.log(newVal);
//        console.log(oldVal);
//        console.log(newVal);
    });
    if ($stateParams.product_storage_agreement_id)
    {
        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'storage_agreement/check_storage_agreement', {'product_storage_agreement_id': $stateParams.product_storage_agreement_id})
                .then(function (response)
                {
                    $scope.response = response.data;
                    if ($scope.response.is_valid)
                    {
                        if ($scope.response.status)
                        {
//                            $rootScope.message = 'Your seller agreement already submited.';
//                            $rootScope.$emit("notification");
//                            $state.go('login');
                            $state.go('storage_agreement_completed', {status: 'already_submitted'});
                        }

                    } else
                    {
//                        $rootScope.message = 'Invalid Url';
//                        $rootScope.$emit("notification");
//                        $state.go('login');
                        $state.go('storage_agreement_completed', {status: 'invalid'});


                    }

                    $rootScope.loader = false;
                }).catch(function (error)
        {
            console.log(error);
            $rootScope.loader = false;
        });

    }
    if ($stateParams.product_storage_agreement_id)
    {
        console.log($stateParams.product_storage_agreement_id);

        $scope.SaveSellerAgreement = function (is_valid)
        {
            if (is_valid)
            {
                $scope.storage_agreement.id = $stateParams.product_storage_agreement_id;
                console.log($scope.storage_agreement);
                if (!$scope.signature)
                {
                    $rootScope.message = 'Signature is Required';
                    $rootScope.$emit("notification");
                    return;
                }
                if ($scope.storage_agreement.consignor_date)
                {
                    $scope.storage_agreement.consignor_date = moment($scope.storage_agreement.consignor_date).local().format("MM/DD/YYYY");
                }
                $rootScope.loader = true;
                $http.post(site_settings.api_url + 'storage_agreement/save_storage_agreement', {'seller_ageement': $scope.storage_agreement, 'signature': $scope.signature})
                        .then(function (response)
                        {
                            $scope.response = response.data;
//                            $rootScope.message = 'Thank you. Your seller contract has been submitted.';
//                            $rootScope.message = 'Thank you. Your contract has been submitted. We will be in touch shortly.';
//                            $rootScope.$emit("notification");
//                            $state.go('login');
                            $state.go('storage_agreement_completed', {status: 'completed'});
                            $rootScope.loader = false;
                        }).catch(function (error)
                {
                    console.log(error);
                    $rootScope.loader = false;
                    $state.go('storage_agreement_completed', {status: 'completed'});
                });
//                console.log('valid')
            } else
            {
                if (angular.element('input.ng-invalid').length > 0)
                {
                    angular.element('input.ng-invalid').first().focus();
                } else
                {
                    angular.element('textarea.ng-invalid').first().focus();
                }


                $rootScope.message = 'Some fields are empty please fill it';
                $rootScope.$emit("notification");
                return;
            }



        };
    }
});

app.controller('StorageAgreementCompletedController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.title = '';
    $scope.message = '';

    switch ($stateParams.status)
    {
        case 'already_submitted':
            $scope.title = 'Already Submitted';
            $scope.message = 'You have already submitted your contract.';
            break;
        case 'completed':
            $scope.title = 'Thank You';
            $scope.message = 'Your contract has been submitted. We will be in touch shortly.';
            break;
        case 'invalid':
            $scope.title = 'Invalid Url';
            $scope.message = 'You are accessing Invalid Url.';
            break;

        default:
            $scope.title = 'Invalid Url';
            $scope.message = 'You are accessing Invalid Url.';
            break;
    }

});


app.controller('StorageAgreementReportController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken)
{
    $scope.product_report = {};
    $scope.product_report.state = 'all';

    $scope.$watch("product_report.state", function (newVal, oldVal)
    {
        $scope.is_generate_report = false;

    });
    $scope.$watch("product_report.seller_id", function (newVal, oldVal)
    {
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
                    a.download = b;
                    a.target = '_blank';
                    a.id = b;
                    a.href = 'api/storage/exports/' + b;
                    a.click();
                    document.body.removeChild(a);
                }).catch(function (error)
        {
            $rootScope.loader = false;
//                $rootScope.message = 'Something Went Wrong';
//                $rootScope.$emit("notification");
            console.log(error);
        });


        $
    }



    $scope.generateReport = function ()
    {
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
    $scope.$watch("product_report.start_date", function (newVal, oldVal)
    {
        if (newVal != oldVal)
        {
            $scope.product_report.start_date_updated = moment($scope.product_report.start_date).local().format('YYYY-MM-DD');
            $scope.is_generate_report = false;
//            $scope.is_generate_report = false;
        }
    });
    $scope.$watch("product_report.end_date", function (newVal, oldVal)
    {
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


    $scope.getReport = function (product_report)
    {
        product_report.state = 'all';
        product_report.is_excel_generate = false;

        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'storage_agreement/get_storage_agreement_report', product_report)
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

    $scope.renderDate = function (date)
    {
        return moment.utc(date.date).format('MM/DD/YYYY');

    };
    $scope.renderSeller = function (seller_id)
    {
        var seller = {};
        $scope.sellers.forEach(function (value)
        {
            if (seller_id == value.id)
            {
                seller = value;
            }

        });
        return seller;
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
