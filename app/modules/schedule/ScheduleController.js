"use strict";

var app = angular.module('ng-app');
app.controller('ScheduleController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken) {

    $scope.authuser = $auth.getProfile().$$state.value;
    $scope.dtInstance = {};
    $scope.users = [];
    $scope.select = [];
    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
        dataSrc: "data",
        url: site_settings.api_url + 'schedule/get_schedules',
        type: "POST",
        headers: {Authorization: 'Bearer ' + ngAAToken.getToken()},
    }).withOption('processing', true) //for show progress bar
            .withOption('serverSide', true) // for server side processing
            .withPaginationType('full_numbers') // for get full pagination options // first / last / prev / next and page numbers
            .withDisplayLength(10) // Page size
            .withOption('aaSorting', [1, 'asc'])
            .withOption('autoWidth', false)
            .withOption('responsive', true)
            .withOption('dom', '<"top"f>rt<"bottom"<"left"<"length"l>><"right"<"info"i><"pagination"p>>>')
            .withDataProp('data')
            .withOption('fnRowCallback',
                    function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                        $compile(nRow)($scope);
                    });




    function actionsHtml(data, type, full, meta) {
        console.log(full);
        var action_btn = '';
//        var action_btn = '<md-fab-speed-dial style=" transform: translate(-55px, 0px);" md-open="demo' + data.id + '.isOpen" md-direction="left" ng-class="{ \'md-hover-full\': demo' + data.id + '.hover }" class="md-scale md-fab-top-right ng-isolate-scope md-left"  ng-mouseleave="demo' + data.id + '.isOpen=false" ng-mouseenter="demo' + data.id + '.isOpen=true">';
//        action_btn += '<md-fab-trigger>';
//        action_btn += '<md-button aria-label="menu" class="md-fab md-primary md-mini">';
//        action_btn += '<md-icon md-svg-src="assets/angular-material-assets/icons/ic_call_to_action_white_24px.svg"></md-icon>';
//        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">Action</md-tooltip>';
//
//        action_btn += '</md-button>';
//        action_btn += '</md-fab-trigger>';
//        action_btn += '<md-fab-actions>';
        action_btn += '<md-button aria-label="EDIT" ng-click="openScheduleEditDialog(' + full.seller_id + ',\''+ full.schedule_date +'\',\''+ full.schedule_time +'\');" class="md-fab md-accent md-raised md-mini">';
        action_btn += '<md-icon md-svg-src="assets/angular-material-assets/icons/ic_mode_edit_white_24px.svg" aria-label="Twitter"></md-icon>';
        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">EDIT</md-tooltip>';
        action_btn += '</md-button>';
        
        action_btn += '<md-button ng-click="showConfirm(' + full.seller_id + ',\''+ full.schedule_date +'\',\''+ full.schedule_time +'\')" aria-label="DELETE" class="md-fab md-warn md-raised md-mini">';
        action_btn += '<md-icon  md-font-icon="icon-delete" aria-label="Facebook"></md-icon>';
        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">DELETE</md-tooltip>';
        action_btn += '</md-button>';

//        action_btn += '</md-fab-actions></md-fab-speed-dial>';
        return action_btn;
//        $scope.persons[data.id] = data;
//        return '<md-button aria-label="Edit" ui-sref="edit_profile({id:\'' + full.id + '\'})" class="md-fab md-mini md-button  md-ink-ripple">  <md-icon md-svg-src="assets/img/edit.svg" class="icon"></md-icon></md-button><md-button aria-label="Delete" type="submit" class="md-fab md-mini md-button  md-ink-ripple md-warn">  <md-icon md-font-icon="icon-delete" class="icon" ></md-icon></md-button>';
    }
    function profileImage(data, type, full, meta) {

//        console.log('data');
        if (data != '' && data != undefined)
        {
            return '<img  style="width:40px;height:40px;border-radius:30px;" src="Uploads/profile/' + data + '">';
        }
        else
        {
            return '<img  style="width:40px;height:40px;border-radius:30px;" src="/assets/images/avatars/profile.jpg">';
        }
    }

    $scope.dtInstance = {};


    function reloadData() {

        var resetPaging = false;
        $scope.dtInstance.reloadData(callback, resetPaging);
    }

    $scope.getProductStatus = function () {
        $http.get(site_settings.api_url + 'get_all_product_approved_status')
                .then(function (response) {
                    $scope.product_status = response.data;
                }).catch(function (error) {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
        });
    };

    $scope.getProductStatus();

    function callback(json) {

    }

    $scope.dtColumns = [
//        DTColumnBuilder.newColumn('profile_image').withTitle('Image').notSortable().renderWith(profileImage),
//        DTColumnBuilder.newColumn('product_name').withTitle('Product Name'),
        DTColumnBuilder.newColumn('seller_name').withTitle('Seller Name'),
        DTColumnBuilder.newColumn('schedule_date').withTitle('Date'),
        DTColumnBuilder.newColumn('schedule_time').withTitle('Time'),
        DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable()
                .renderWith(actionsHtml),
    ];

    function schedule(data, type, full, meta) {

        if (full.images_from == 1)
        {
            return '<button aria-label="{{hover_' + full.id + ' ==true ? \'Schedule\' : \'Schedule\' }}"  class="md-primary md-raised md-button md-jd-theme md-ink-ripple"\n\
                     ng-class="hover==true ? md-warn : md-primary"    ng-mouseenter="hover_' + full.id + ' = true"    ng-mouseleave="hover_' + full.id + ' = false"\n\
                     type="button" ng-click="takeSchedule(\'' + full.id + '\')" >Schedule</button>';
        }
        else
        {
            return '<button ng-disabled="true" aria-label="{{hover_' + full.id + ' ==true ? \'Schedule\' : \'Schedule\' }}"  class="md-primary md-raised md-button md-jd-theme md-ink-ripple"\n\
                     ng-class="hover==true ? md-warn : md-primary"    ng-mouseenter="hover_' + full.id + ' = true"    ng-mouseleave="hover_' + full.id + ' = false"\n\
                     type="button" ng-click="takeSchedule(\'' + full.id + '\')" >Schedule</button>';
        }

    }

    function statusHtml(data, type, full, meta) {
//        console.log(full);
        if ($scope.authuser.roles[0].id == 1)
        {
            $scope.select[full.id] = full.status_id;

            var status_dropdown = '<md-select aria-label="orderstatus" style="margin:0;" ng-model="select[' + full.id + ']" ng-change="changeProductStatus(' + full.id + ')">';
            status_dropdown += '<md-option ng-repeat="ps in product_status" value="{{ps.id}}"><em>{{ps.value_text}}</em></md-option>';
            status_dropdown += '</md-select>';

            return status_dropdown;
        }
        else
        {
            return full.status_value;
        }
    }

    $scope.changeProductStatus = function (id)
    {
        $http.post(site_settings.api_url + 'product_approved/change_product_approved_status', {product_id: id, product_status_id: $scope.select[id]})
                .then(function (response) {
                    $rootScope.message = 'Status change successfully.';
                    $rootScope.$emit("notification");
                    $rootScope.$emit("reloadUserTable");
                }).catch(function (error) {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
        });
    }

    $scope.openScheduleEditDialog = function (sellerid, sdate, stime)
    {
        $mdDialog.show({
            controller: 'ScheduleEditController',
            templateUrl: 'app/modules/schedule/views/schedule_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                sellerid: sellerid,
                sdate: sdate,
                stime: stime
            }
        });
    }

    $rootScope.$on("reloadUserTable", function (event, args) {

        reloadData();
    });

    function reloadData() {

        var resetPaging = false;
        $scope.dtInstance.reloadData(callback, resetPaging);
    }

    function callback(json) {

    }


    $scope.showConfirm = function (sellerid, sdate, stime) {

        // Appending dialog to document.body to cover sidenav in docs app
        var confirm = $mdDialog.confirm()
                .title('Would you like to delete this Schedule?')
                .ok('Yes')
                .cancel('No');

        $mdDialog.show(confirm).then(function () {
            $http.post(site_settings.api_url + 'schedule/delete_schedule', {id: sellerid, date: sdate, time: stime})
                    .then(function (response) {

                        $rootScope.message = 'Schedule Deleted Successfully';
                        $rootScope.$emit("notification");
                        $rootScope.$emit("reloadUserTable");

                    }).catch(function (error) {
                $rootScope.message = 'Something Went Wrong';
                $rootScope.$emit("notification");
            });
        }, function () {

        });
    };
});

app.controller('ScheduleEditController', function (sellerid, sdate, stime, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder) {

    $scope.schedule = {};

    if (sellerid)
    {
        $scope.edit = true;
        $scope.action = 'Edit';
        $http.post(site_settings.api_url + 'schedule/get_schedule', {id: sellerid, date: sdate, time: stime})
                .then(function (response) {
                    $scope.schedule = response.data;
                    if ($scope.schedule.product_quot_id.id)
                    {
                        $scope.schedule.product_quot_id = $scope.schedule.product_quot_id.id;
                    }
//                    if ($scope.schedule.product_quotation_id)
//                    {
//                        $scope.schedule.product_quotation_id = $scope.schedule.product_quot_id.id;
//                    }
                    $scope.schedule.date = new Date($scope.schedule.date);
                    $scope.schedule.time = new Date($scope.schedule.time);
//                    $scope.schedule.time = new Date(moment.utc($scope.call.time_in.date).local());;

                }).catch(function (error) {
                    console.log(error)
        });
    }
    else
    {
        $scope.action = 'Add';
    }

    $scope.saveSchedule = function ()
    {

        var temp = $scope.schedule;
        temp.id = sellerid;
        temp.olddate = sdate;
        temp.oldtime = stime;
        temp.date = moment($scope.schedule.date).local().format("MM/DD/YYYY");
        temp.time = moment($scope.schedule.time).local().format("MM/DD/YYYY HH:mm:ss");

        $http.post(site_settings.api_url + 'schedule/save_schedule', temp)
                .then(function (response) {

                    $rootScope.message = response.data;
                    $rootScope.$emit("notification");
                    $rootScope.$emit("reloadUserTable");
                    $mdDialog.hide();
                }).catch(function (error) {
            $rootScope.message = error.data;
            $rootScope.$emit("notification");
        });
    };

    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };
})