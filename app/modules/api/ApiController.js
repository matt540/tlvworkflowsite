"use strict";

var app = angular.module('ng-app');
app.controller('ApiController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken) {
    console.log('sadasd');

    $http.jsonp(site_settings.wp_api_url + 'cat-api.php?cat=product_condition', {jsonpCallbackParam: 'callback'})
            .then(function (response) {
                $scope.data = response.data;
                console.log($scope.data);
                $http.post(site_settings.api_url + 'save/rooms', $scope.data)
                        .then(function (response) {
                            $rootScope.message = 'Status change successfully.';
                            $rootScope.$emit("notification");
                            $rootScope.$emit("reloadUserTable");
                        }).catch(function (error) {
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                });
            })
            .catch(function (response) {
                console.log(response);
            });
});