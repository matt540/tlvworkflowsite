"use strict";

var app = angular.module('ng-app');
app.controller('AuctionAgreementController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.auction_agreement = {};
    $scope.auction_agreement.agreement_date = new Date();

    var todayDate = new Date();
    $scope.auction_agreement.day = todayDate.getDate();
    $scope.auction_agreement.month = todayDate.getMonth() + 1;
    $scope.auction_agreement.year = parseInt(todayDate.getFullYear().toString().substr(-2));

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
    });

    if ($stateParams.auction_agreement_id)
    {
        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'auction_agreement/check_auction_agreement', {'auction_agreement_id': $stateParams.auction_agreement_id})
                .then(function (response)
                {
                    $scope.response = response.data;
                    if ($scope.response.is_valid)
                    {
                        if ($scope.response.status)
                        {
                            $state.go('auction_agreement_completed', {status: 'already_submitted'});
                        }

                    } else
                    {
                        $state.go('auction_agreement_completed', {status: 'invalid'});
                    }
                    $rootScope.loader = false;
                })
                .catch(function (error) {
                    $rootScope.loader = false;
                });

    }

    if ($stateParams.auction_agreement_id)
    {
        $scope.SaveAuctionAgreement = function (is_valid)
        {
            if (is_valid)
            {
                $scope.auction_agreement.id = $stateParams.auction_agreement_id;

                if (!$scope.signature)
                {
                    $rootScope.message = 'Signature is Required';
                    $rootScope.$emit("notification");
                    return;
                }

                if ($scope.auction_agreement.agreement_date)
                {
                    $scope.auction_agreement.agreement_date = moment($scope.auction_agreement.agreement_date).local().format("MM/DD/YYYY");
                }

                $rootScope.loader = true;
                $http.post(site_settings.api_url + 'auction_agreement/save_auction_agreement', {'auction_agreement': $scope.auction_agreement, 'signature': $scope.signature})
                        .then(function (response)
                        {
                            $scope.response = response.data;
                            $state.go('auction_agreement_completed', {status: 'completed'});
                            $rootScope.loader = false;
                        })
                        .catch(function (error) {
                            $rootScope.loader = false;
                            $state.go('auction_agreement_completed', {status: 'completed'});
                        });
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

app.controller('AuctionAgreementCompletedController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
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
