"use strict";

var app = angular.module('ng-app');

app.controller('EmailSendRecordController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken)
{

    $scope.dtInstance = {};
    $scope.user = [];
    $scope.paramname = 2;
    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
        dataSrc: "data",
        url: site_settings.api_url + 'email_send_record/get_email_send_records',
        type: "POST",
        headers: {Authorization: 'Bearer ' + ngAAToken.getToken()},
    }).withOption('processing', true) //for show progress bar
            .withOption('serverSide', true) // for server side processing
            .withPaginationType('full_numbers') // for get full pagination options // first / last / prev / next and page numbers
            .withDisplayLength(10) // Page size
            .withOption('aaSorting', [0, 'asc'])
            .withOption('autoWidth', false)
            .withOption('responsive', true)
            .withOption('dom', '<"top"f>rt<"bottom"<"left"<"length"l>><"right"<"info"i><"pagination"p>>>')
            .withDataProp('data')
            .withOption('fnRowCallback',
                    function (nRow, aData, iDisplayIndex, iDisplayIndexFull)
                    {
                        $compile(nRow)($scope);
                    });




    function actionsHtml(data, type, full, meta)
    {

        var action_btn = '';
        action_btn += '<span ng-click="openViewBodyDialog(' + data.id + ');" class="text-boxed m-0 deep-blue-bg white-fg" style="cursor: pointer; background-color: #126491 !important;">VIEW</span>';

        return action_btn;
    }

    $scope.dtInstance = {};


    function reloadData()
    {

        var resetPaging = false;
        $scope.dtInstance.reloadData(callback, resetPaging);
    }


    function createdByRendor(data, type, full, meta)
    {
        if(data)
        {
            return data.firstname+' '+data.lastname;
        }
        else
        {
            return '---';
        }
    }
    function dateRender(data, type, full, meta)
    {
        if (data)
        {
            return moment.utc(data.date).local().format('MM/DD/YYYY h:m A');
        } else
        {
            return '---';
        }

    }
    function callback(json)
    {

    }
    $scope.dtColumns = [
//        DTColumnBuilder.newColumn('profile_image').withTitle('Image').notSortable().renderWith(profileImage),
        DTColumnBuilder.newColumn('email').withTitle('Seller Email'),
        DTColumnBuilder.newColumn('subject').withTitle('Subject'),
        DTColumnBuilder.newColumn('created_by').withTitle('Created By').renderWith(createdByRendor),
        DTColumnBuilder.newColumn('created_at').withTitle('Created At').renderWith(dateRender),
        DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable()
                .renderWith(actionsHtml),
    ];

    $scope.openViewBodyDialog = function (id)
    {
        $mdDialog.show({
            controller: 'ViewBodyController',
            templateUrl: 'app/modules/email_send_record/views/email_send_record_view.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                id: id
            }
        });
    }

    $rootScope.$on("reloadUserTable", function (event, args)
    {

        reloadData();
    });

    function reloadData()
    {

        var resetPaging = false;
        $scope.dtInstance.reloadData(callback, resetPaging);
    }

    function callback(json)
    {

    }
});

app.controller('ViewBodyController', function (id, $document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken)
{
    $scope.user = [];
    $scope.email_send_record = {};
    $scope.getEmailSendRecordById = function (id)
    {
        $http.post(site_settings.api_url + 'email_send_record/get_email_send_record', {id: id})
                .then(function (response)
                {
                    $scope.email_send_record = response.data;

                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong';
            $rootScope.$emit("notification");
        });
    }

    $scope.getEmailSendRecordById(id);
    
    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };

});