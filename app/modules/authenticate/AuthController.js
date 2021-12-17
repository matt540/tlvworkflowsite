"use strict";

var app = angular.module('ng-app');
app.controller('AuthController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder) {
 
    $scope.email = '';
    $scope.site_settings = site_settings;
    $scope.user = {};
    if ($stateParams.token != undefined && $stateParams.token != '')
    {
        $http.get(site_settings.api_url + 'password_admin/reset/' + $stateParams.token)
                .then(function (response) {
                    $scope.user.email = response.data.email;
                    $scope.user.token = $stateParams.token;
                }).catch(function (error) {
                    $state.go('login');
        });
    }

    $scope.forgotPassword = function () {
        var credentials = {
            email: $scope.email,
        }
        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'forgot_password', credentials)
                .then(function (response) {
                    $scope.success_exist = true;
                    $scope.success_exist_text = 'Please check mail for reset password link.';
                    $rootScope.message = 'Mail sent successfully.';
                    $rootScope.$emit("notification");
                    $rootScope.loader = false;
                }).catch(function (error) {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
            $rootScope.loader = false;
        });
    }

    $scope.resetPassword = function () {
        var credentials = {
            email: $scope.user.email,
            password: $scope.user.password,
            password_confirmation: $scope.user.passwordConfirm,
            token: $scope.user.token,
        }
        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'password_admin/reset', credentials)
                .then(function (response) {
                    $rootScope.message = 'Password Reset Successfully.';
                    $rootScope.$emit("notification");
                    $rootScope.loader = false;
                    $state.go('login');
                }).catch(function (error) {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
            $rootScope.loader = false;
        });
    }

});

app.controller('SignupController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder) {

    $scope.signupData = {};
    $scope.company_temp = 0;
    $scope.getRoles = function () {
        $rootScope.loader = true;
        $http.get(site_settings.api_url + 'get_roles')
                .then(function (response) {
                    $scope.roles = response.data;
              
                    $rootScope.loader = false;
                }).catch(function (error) {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
            $rootScope.loader = false;
        });
    };
    
    $scope.getPaymentOption = function () {
        $rootScope.loader = true;
        $http.get(site_settings.api_url + 'get_payment_option')
                .then(function (response) {
                    $scope.payment_option = response.data;
                    console.log($scope.payment_option);
                    $rootScope.loader = false;
                }).catch(function (error) {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
            $rootScope.loader = false;
        });
    };

    $scope.getAllCompany = function ()
    {
        $http.get(site_settings.api_url + 'getAllCompany')
                .then(function (response) {
                    $scope.allcompany = response.data;
                });
    };
    
    $scope.getRoles();
    $scope.getPaymentOption();
    $scope.getAllCompany();

    $scope.signup = function () {

        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'signup', $scope.signupData)
                .then(function (response) {
                    $rootScope.message = 'Successfully Registered';
                    $state.go('login');
                    $rootScope.loader = false;
                }).catch(function (error) {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
            $rootScope.loader = false;
        });
    };

    $scope.changeSelect = function ()
    {
        $scope.company_temp = 1;
    };


});