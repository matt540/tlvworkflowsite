"use strict";

var app = angular.module('ng-app');

app.controller('EmailTemplateController', function ($timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder) {
    
    $scope.email_template = {};
    $scope.summernoteOption = {
        height: 140, //set editable area's height
        codemirror: {// codemirror options
            theme: 'monokai'
        }
    };
    if ($stateParams.id)
    {
        $http.post(site_settings.api_url + 'get_email_template', {id: $stateParams.id})
                .then(function (response) {
                    $scope.email_template = response.data;
                }).catch(function (error) {
        });
    }
    
    $scope.saveEmailTemplate = function ()
    {
        $http.post(site_settings.api_url + 'save/email_template', $scope.email_template)
                .then(function (response) {
                    $rootScope.message = response.data;
                    $rootScope.$emit("notification");
                }).catch(function (error) {
            $rootScope.message = error.data;
            $rootScope.$emit("notification");
        });
    };

});

