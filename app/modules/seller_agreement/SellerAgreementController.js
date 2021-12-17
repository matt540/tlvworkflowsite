"use strict";

var app = angular.module('ng-app');
app.controller('SellerAgreementNewController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.seller_agreement = {};
    $scope.seller_agreement.local_vault_date = new Date();
    $scope.seller_agreement.consignor_date = new Date();

    var EMPTY_IMAGE = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAjgAAADcCAQAAADXNhPAAAACIklEQVR42u3UIQEAAAzDsM+/6UsYG0okFDQHMBIJAMMBDAfAcADDATAcwHAAwwEwHMBwAAwHMBzAcAAMBzAcAMMBDAcwHADDAQwHwHAAwwEMB8BwAMMBMBzAcADDATAcwHAADAcwHADDAQwHMBwAwwEMB8BwAMMBDAfAcADDATAcwHAAwwEwHMBwAAwHMBzAcAAMBzAcAMMBDAcwHADDAQwHwHAAwwEwHMBwAMMBMBzAcAAMBzAcwHAADAcwHADDAQwHMBwAwwEMB8BwAMMBDAfAcADDATAcwHAAwwEwHMBwAAwHMBzAcCQADAcwHADDAQwHwHAAwwEMB8BwAMMBMBzAcADDATAcwHAADAcwHMBwAAwHMBwAwwEMBzAcAMMBDAfAcADDAQwHwHAAwwEwHMBwAAwHMBzAcAAMBzAcAMMBDAcwHADDAQwHwHAAwwEMB8BwAMMBMBzAcADDATAcwHAADAcwHMBwAAwHMBwAwwEMB8BwAMMBDAfAcADDATAcwHAAwwEwHMBwAAwHMBzAcAAMBzAcAMMBDAcwHADDAQwHwHAAwwEMB8BwAMMBMBzAcADDkQAwHMBwAAwHMBwAwwEMBzAcAMMBDAfAcADDAQwHwHAAwwEwHMBwAMMBMBzAcAAMBzAcwHAADAcwHADDAQwHMBwAwwEMB8BwAMMBMBzAcADDATAcwHAADAcwHMBwAAwHMBwAwwEMBzAcAMMBDAegeayZAN3dLgwnAAAAAElFTkSuQmCC';
    $scope.FillToAddress = function ()
    {
        if ($scope.seller_agreement.address_as_above)
        {
            $scope.seller_agreement.other_address = $scope.seller_agreement.address;
            $scope.seller_agreement.other_city = $scope.seller_agreement.city;
            $scope.seller_agreement.other_state = $scope.seller_agreement.state;
            $scope.seller_agreement.other_zip = $scope.seller_agreement.zip;
        } else
        {
            $scope.seller_agreement.other_address = '';
            $scope.seller_agreement.other_city = '';
            $scope.seller_agreement.other_state = '';
            $scope.seller_agreement.other_zip = '';
        } 
    }

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
    if ($stateParams.product_quote_agreement_id)
    {
        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'seller_agreement/check_seller_agreement', {'product_quote_agreement_id': $stateParams.product_quote_agreement_id})
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
                            $state.go('seller_agreement_completed', {status: 'already_submitted'});
                        }

                    } else
                    {
//                        $rootScope.message = 'Invalid Url';
//                        $rootScope.$emit("notification");
//                        $state.go('login');
                        $state.go('seller_agreement_completed', {status: 'invalid'});


                    }

                    $rootScope.loader = false;
                }).catch(function (error)
        {
            console.log(error);
            $rootScope.loader = false;
        });

    }

    $scope.SaveSellerAgreement = function (is_valid)
    {
        if (is_valid)
        {
            $scope.seller_agreement.id = '1';
            console.log($scope.seller_agreement);
            if (!$scope.signature)
            {
                $rootScope.message = 'Signature is Required';
                $rootScope.$emit("notification");
                return;
            }
            if ($scope.seller_agreement.local_vault_date)
            {
                $scope.seller_agreement.local_vault_date = moment($scope.seller_agreement.local_vault_date).local().format("MM/DD/YYYY");
            }
            if ($scope.seller_agreement.consignor_date)
            {
                $scope.seller_agreement.consignor_date = moment($scope.seller_agreement.consignor_date).local().format("MM/DD/YYYY");
            }
            $rootScope.loader = true;
            $http.post(site_settings.api_url + 'seller_agreement/save_new_seller_agreement', {'seller_ageement': $scope.seller_agreement, 'signature': $scope.signature})
                    .then(function (response)
                    {
                        $scope.response = response.data;
//                            $rootScope.message = 'Thank you. Your seller contract has been submitted.';
//                            $rootScope.message = 'Thank you. Your contract has been submitted. We will be in touch shortly.';
//                            $rootScope.$emit("notification");
//                            $state.go('login');
//                        $state.go('seller_agreement_completed', {status: 'completed'});
                        $rootScope.loader = false;
                    }).catch(function (error)
            {
                console.log(error);
                $rootScope.loader = false;
//                $state.go('seller_agreement_completed', {status: 'completed'});
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
});
app.controller('SellerAgreementController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.seller_agreement = {};
    $scope.seller_agreement.local_vault_date = new Date();
    $scope.seller_agreement.consignor_date = new Date();
    
    
    var todayDate = new Date();
    $scope.seller_agreement.day = todayDate.getDate();
    $scope.seller_agreement.month = todayDate.getMonth() + 1;
    $scope.seller_agreement.year = parseInt(todayDate.getFullYear().toString().substr(-2));

    var EMPTY_IMAGE = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAjgAAADcCAQAAADXNhPAAAACIklEQVR42u3UIQEAAAzDsM+/6UsYG0okFDQHMBIJAMMBDAfAcADDATAcwHAAwwEwHMBwAAwHMBzAcAAMBzAcAMMBDAcwHADDAQwHwHAAwwEMB8BwAMMBMBzAcADDATAcwHAADAcwHADDAQwHMBwAwwEMB8BwAMMBDAfAcADDATAcwHAAwwEwHMBwAAwHMBzAcAAMBzAcAMMBDAcwHADDAQwHwHAAwwEwHMBwAMMBMBzAcAAMBzAcwHAADAcwHADDAQwHMBwAwwEMB8BwAMMBDAfAcADDATAcwHAAwwEwHMBwAAwHMBzAcCQADAcwHADDAQwHwHAAwwEMB8BwAMMBMBzAcADDATAcwHAADAcwHMBwAAwHMBwAwwEMBzAcAMMBDAfAcADDAQwHwHAAwwEwHMBwAAwHMBzAcAAMBzAcAMMBDAcwHADDAQwHwHAAwwEMB8BwAMMBMBzAcADDATAcwHAADAcwHMBwAAwHMBwAwwEMB8BwAMMBDAfAcADDATAcwHAAwwEwHMBwAAwHMBzAcAAMBzAcAMMBDAcwHADDAQwHwHAAwwEMB8BwAMMBMBzAcADDkQAwHMBwAAwHMBwAwwEMBzAcAMMBDAfAcADDAQwHwHAAwwEwHMBwAMMBMBzAcAAMBzAcwHAADAcwHADDAQwHMBwAwwEMB8BwAMMBMBzAcADDATAcwHAADAcwHMBwAAwHMBwAwwEMBzAcAMMBDAegeayZAN3dLgwnAAAAAElFTkSuQmCC';

    $scope.FillToAddress = function ()
    {
        if ($scope.seller_agreement.address_as_above)
        {
            $scope.seller_agreement.other_address = $scope.seller_agreement.address;
            $scope.seller_agreement.other_city = $scope.seller_agreement.city;
            $scope.seller_agreement.other_state = $scope.seller_agreement.state;
            $scope.seller_agreement.other_zip = $scope.seller_agreement.zip;
        } else
        {
            $scope.seller_agreement.other_address = '';
            $scope.seller_agreement.other_city = '';
            $scope.seller_agreement.other_state = '';
            $scope.seller_agreement.other_zip = '';
        }
    }

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
    if ($stateParams.product_quote_agreement_id)
    {
        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'seller_agreement/check_seller_agreement', {'product_quote_agreement_id': $stateParams.product_quote_agreement_id})
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
                            $state.go('seller_agreement_completed', {status: 'already_submitted'});
                        }

                    } else
                    {
//                        $rootScope.message = 'Invalid Url';
//                        $rootScope.$emit("notification");
//                        $state.go('login');
                        $state.go('seller_agreement_completed', {status: 'invalid'});


                    }

                    $rootScope.loader = false;
                }).catch(function (error)
        {
            console.log(error);
            $rootScope.loader = false;
        });

    }
    if ($stateParams.product_quote_agreement_id)
    {
        console.log($stateParams.product_quote_agreement_id);

        $scope.SaveSellerAgreement = function (is_valid)
        {
            if (is_valid)
            {
                $scope.seller_agreement.id = $stateParams.product_quote_agreement_id;
                console.log($scope.seller_agreement);
                if (!$scope.signature)
                {
                    $rootScope.message = 'Signature is Required';
                    $rootScope.$emit("notification");
                    return;
                }
//                if ($scope.seller_agreement.local_vault_date)
//                {
//                    $scope.seller_agreement.local_vault_date = moment($scope.seller_agreement.local_vault_date).local().format("MM/DD/YYYY");
//                }
                if ($scope.seller_agreement.consignor_date)
                {
                    $scope.seller_agreement.consignor_date = moment($scope.seller_agreement.consignor_date).local().format("MM/DD/YYYY");
                }

                $rootScope.loader = true;
                $http.post(site_settings.api_url + 'seller_agreement/save_seller_agreement', {'seller_ageement': $scope.seller_agreement, 'signature': $scope.signature})
                        .then(function (response)
                        {
                            $scope.response = response.data;
//                            $rootScope.message = 'Thank you. Your seller contract has been submitted.';
//                            $rootScope.message = 'Thank you. Your contract has been submitted. We will be in touch shortly.';
//                            $rootScope.$emit("notification");
//                            $state.go('login');
                            $state.go('seller_agreement_completed', {status: 'completed'});
                            $rootScope.loader = false;
                        }).catch(function (error)
                {
                    console.log(error);
                    $rootScope.loader = false;
                    $state.go('seller_agreement_completed', {status: 'completed'});
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

app.controller('SellerAgreementCompletedController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
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
