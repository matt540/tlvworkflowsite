"use strict";
var app = angular.module('ng-app');
app.controller('ExportController', function ($rootScope, $timeout, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder) {

    $scope.export = {};

    $scope.export_products = function ()
    {

        $scope.export.start_date = moment($scope.export.start_date).local().format("YYYY-MM-DD");
        $scope.export.end_date = moment($scope.export.end_date).local().format("YYYY-MM-DD");
        
        $http.post(site_settings.api_url + 'export_products', $scope.export)
                .then(function (response) {
                    var a = document.createElement('iframe');
                    document.body.appendChild(a);
                    a.src = '../api/storage/exports/' + response.data;
                }).catch(function (error) {
            alert("error");
        });
    };
    
    $scope.export_today_products = function ()
    {
        
        $scope.export.start_date = $scope.export.end_date = moment(new Date()).local().format("YYYY-MM-DD");

        $http.post(site_settings.api_url + 'export_today_products', $scope.export)
                .then(function (response) {
                    var a = document.createElement('iframe');
                    document.body.appendChild(a);
                    a.src = '../api/storage/exports/' + response.data;
                }).catch(function (error) {
            alert("error");
        });
    };
    
});