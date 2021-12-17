"use strict";

var app = angular.module('ng-app');
app.controller('NotificationMailController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken)
{
    $scope.notification = {};
    $scope.saveEmails = function () {
        $http.post(site_settings.api_url + 'setEmails', $scope.notification)
                .then(function (response)
                {
                    $rootScope.message = 'Saved Successfully.';
                    $rootScope.$emit("notification");
                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
        });
    };
    $http.get(site_settings.api_url + 'getOptionsBySelectId/7')
            .then(function (response)
            {
                $scope.notification = response.data;
            }).catch(function (error)
    {
        $rootScope.message = 'Something Went Wrong.';
        $rootScope.$emit("notification");
    });

});