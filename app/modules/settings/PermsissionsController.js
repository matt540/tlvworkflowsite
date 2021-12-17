"use strict";

var app = angular.module('ng-app');
app.controller('PermsissionsController', function ($rootScope, $document, $compile, $resource, $scope, $auth, $http, msNavigationService, site_settings, DTOptionsBuilder, $mdDialog, DTColumnBuilder) {
    $scope.permissions = [];
    $scope.roles = {};
    $scope.role = '';
    $scope.selected = [];
    $http.post(site_settings.api_url + 'roles/get_all_roles')
            .then(function (response) {
                $scope.roles = response.data;


            }).catch(function (error) {

    });
    $http.post(site_settings.api_url + 'permission/get_all_permissions')
            .then(function (response) {
                $scope.permissions = response.data;


            }).catch(function (error) {

    });
    $scope.setRole = function (id)
    {
        $scope.role = id;
        $http.post(site_settings.api_url + 'permission/get_permissions_by_role', {id: id})
                .then(function (response) {
                    $scope.selected = [];
                    for (var p in response.data)
                    {
                        $scope.selected[response.data[p].id] = true;
                    }
                    console.log($scope.selected)
                }).catch(function (error) {

        });
    }
    $scope.listPermissions = function ()
    {
        
    }
    $scope.setPermission = function (item, status)
    {
        console.log(status)
        var new_per = {};
        new_per.role = $scope.role;
        new_per.permission = item;
        new_per.permission_status = status;
        $http.post(site_settings.api_url + 'permission/set_permissions', {permission: new_per})
                .then(function (response) {

                }).catch(function (error) {

        });
    }
    $scope.exists = function (item, list) {
        if (list.indexOf(item) > -1)
        {
            console.log(1)
            return true;
        }
        else
        {
            return false;
        }
    }

   
});
