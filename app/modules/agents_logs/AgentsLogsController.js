"use strict";

var app = angular.module('ng-app');
app.controller('AgentsLogsController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken, $window)
{
    $scope.isAdminUser = false;

    $auth.getProfile().then(function (profile) {

        for (var i = 0; i < profile.roles.length; i++) {
            if (profile.roles[i].id == 1) {
                $scope.isAdminUser = true;
            }
        }
    });

    $scope.authuser = $auth.getProfile().$$state.value;

    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
        dataSrc: "data",
        url: site_settings.api_url + 'agents_logs/get',
        type: "POST",
        headers: {Authorization: 'Bearer ' + ngAAToken.getToken()},
        data: {id: $stateParams.id}
    }).withOption('processing', true) //for show progress bar
            .withOption('serverSide', true) // for server side processing
            .withPaginationType('full_numbers') // for get full pagination options // first / last / prev / next and page numbers
            .withDisplayLength(10) // Page size
            .withOption('aaSorting', [0, 'asc'])
            .withOption('autoWidth', false)
            .withOption('responsive', true)
            .withOption('dom', '<"top"f>rt<"bottom"<"left"<"length"l>><"right"<"info"i><"pagination"p>>>')
            .withDataProp('data')
            .withOption('fnRowCallback', function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $compile(nRow)($scope);
            });

    $scope.dtColumns = [
        DTColumnBuilder.newColumn(null).withTitle('Agent First Name').renderWith(columnAgentFirstNameRender),
        DTColumnBuilder.newColumn(null).withTitle('Agent Last Name').renderWith(columnAgentLastNameRender),
        DTColumnBuilder.newColumn(null).withTitle('Seller').notSortable().renderWith(columnSellerRender),
        DTColumnBuilder.newColumn(null).withTitle('Photoshoot Location').renderWith(columnPhotoShootLocationRender),
        DTColumnBuilder.newColumn(null).withTitle('Total Products').renderWith(columnTotalProductsPhotographedRender),
        DTColumnBuilder.newColumn(null).withTitle('Payment Total').renderWith(columnPaymentTotalRender),
        DTColumnBuilder.newColumn(null).withTitle('Action').notSortable().renderWith(columnActionRender)
    ];

    $scope.dtInstance = {};

    function columnAgentFirstNameRender(data, type, full, meta) {
        return data.agent_id.firstname;
    }

    function columnAgentLastNameRender(data, type, full, meta) {
        return data.agent_id.lastname;
    }

    function columnPhotoShootLocationRender(data, type, full, meta) {
        return data.photo_shoot_location;
    }

    function columnPaymentTotalRender(data, type, full, meta) {
        return data.payment_total;
    }

    function columnSellerRender(data, type, full, meta) {
        if (data.seller_id.displayname.trim().length > 0) {
            return data.seller_id.displayname;
        }
        return data.seller_id.firstname + ' ' + data.seller_id.lastname;
    }

    function columnTotalProductsPhotographedRender(data, type, full, meta) {
        return data.total_products_photographed;
    }

    function columnActionRender(data, type, full, meta) {

        const editButton = '<md-button aria-label="EDIT" ng-click="editAgentLog(' + data.id + ');" class="md-fab md-accent md-raised md-mini">' +
                '<md-icon md-svg-src="assets/angular-material-assets/icons/ic_mode_edit_white_24px.svg" ></md-icon>' +
                '<md-tooltip md-direction="top" md-visible="tooltipVisible">EDIT</md-tooltip>' +
                '</md-button>';

        const deleteButton = '<md-button aria-label="DELETE" ng-click="deleteAgentLog(' + data.id + ');" class="md-fab md-warn md-raised md-mini">' +
                '<md-icon  md-font-icon="icon-delete"></md-icon>' +
                '<md-tooltip md-direction="top" md-visible="tooltipVisible">DELETE</md-tooltip>' +
                '</md-button>';

        var approvalButton = '<md-button aria-label="Submit for Approval" class="md-fab md-accent md-raised md-mini" disabled="disabled">' +
                '<md-icon  md-font-icon="icon-book-variant"></md-icon>' +
                '<md-tooltip md-direction="top" md-visible="tooltipVisible">Submit for Approval</md-tooltip>' +
                '</md-button>';

        var archiveButton = '';

        if ($scope.isAdminUser) {
            archiveButton = '<md-button aria-label="Archive" ng-click="archiveAgentLog(' + data.id + ');" class="md-fab md-accent md-raised md-mini">' +
                    '<md-icon  md-font-icon="icon-archive"></md-icon>' +
                    '<md-tooltip md-direction="top" md-visible="tooltipVisible">Archive</md-tooltip>' +
                    '</md-button>';
        }

        if (!$scope.isAdminUser && full.invoice) {
            approvalButton = '<md-button aria-label="Submit for Approval" ng-click="submitForApproval(' + data.id + ');" class="md-fab md-accent md-raised md-mini">' +
                    '<md-icon  md-font-icon="icon-book-variant"></md-icon>' +
                    '<md-tooltip md-direction="top" md-visible="tooltipVisible">Submit for Approval</md-tooltip>' +
                    '</md-button>';
        }

        return archiveButton + approvalButton + editButton + deleteButton;
    }

    $scope.openAgentLogAddDialog = function ()
    {
        $mdDialog.show({
            controller: 'AgentLogAddController',
            templateUrl: 'app/modules/agents_logs/views/agents_logs_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true
        });
    };

    $scope.editAgentLog = function (id)
    {
        $mdDialog.show({
            controller: 'AgentLogUpdateController',
            templateUrl: 'app/modules/agents_logs/views/agents_logs_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {id: id}
        });
    };

    $scope.deleteAgentLog = function (id) {

        var confirm = $mdDialog.confirm()
                .title('Are you sure you want to delete Agent Log?')
                .ok('Yes')
                .cancel('No');

        $mdDialog.show(confirm).then(function ()
        {
            $rootScope.loader = true;
            $http.delete(site_settings.api_url + 'agents_logs/delete/' + id)
                    .then(function (response)
                    {
                        $rootScope.loader = false;
                        $rootScope.$emit("reloadTable");
                    })
                    .catch(function (error) {
                        $rootScope.loader = false;
                    });
        }, function () { });
    };

    $scope.archiveAgentLog = function (id) {

        var confirm = $mdDialog.confirm()
                .title('Are you sure you want to archive Agent Log?')
                .ok('Yes')
                .cancel('No');

        $mdDialog.show(confirm).then(function ()
        {
            $rootScope.loader = true;
            $http.post(site_settings.api_url + 'agents_logs/archive', {id: id})
                    .then(function (response)
                    {
                        $rootScope.loader = false;
                        $rootScope.$emit("reloadTable");
                    })
                    .catch(function (error) {
                        $rootScope.loader = false;
                    });
        }, function () { });
    };

    $rootScope.$on("reloadTable", function (event, args)
    {
        $scope.dtInstance.rerender();
    });

    $scope.submitForApproval = function (id) {
        var confirm = $mdDialog.confirm()
                .title('Are you sure you want to submit for approval?')
                .ok('Yes')
                .cancel('No');

        $mdDialog.show(confirm).then(function ()
        {
            $rootScope.loader = true;
            $http.post(site_settings.api_url + 'agents_logs/submit_for_approval/' + id)
                    .then(function (response)
                    {
                        $rootScope.loader = false;
                        $rootScope.$emit("reloadTable");
                    })
                    .catch(function (error) {
                        $rootScope.loader = false;
                    });
        }, function () { });
    };

});

app.controller('AgentsLogsApprovalController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken, $window)
{
    $scope.isAdminUser = false;

    $auth.getProfile().then(function (profile) {

        for (var i = 0; i < profile.roles.length; i++) {
            if (profile.roles[i].id == 1) {
                $scope.isAdminUser = true;
            }
        }
    });

    $scope.authuser = $auth.getProfile().$$state.value;

    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
        dataSrc: "data",
        url: site_settings.api_url + 'agents_logs/get',
        type: "POST",
        headers: {Authorization: 'Bearer ' + ngAAToken.getToken()},
        data: {paid: true}
    }).withOption('processing', true) //for show progress bar
            .withOption('serverSide', true) // for server side processing
            .withPaginationType('full_numbers') // for get full pagination options // first / last / prev / next and page numbers
            .withDisplayLength(10) // Page size
            .withOption('aaSorting', [0, 'asc'])
            .withOption('autoWidth', false)
            .withOption('responsive', true)
            .withOption('dom', '<"top"f>rt<"bottom"<"left"<"length"l>><"right"<"info"i><"pagination"p>>>')
            .withDataProp('data')
            .withOption('fnRowCallback', function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $compile(nRow)($scope);
            });

    $scope.dtColumns = [
        DTColumnBuilder.newColumn(null).withTitle('Agent First Name').renderWith(columnAgentFirstNameRender),
        DTColumnBuilder.newColumn(null).withTitle('Agent Last Name').renderWith(columnAgentLastNameRender),
        DTColumnBuilder.newColumn(null).withTitle('Seller').notSortable().renderWith(columnSellerRender),
        DTColumnBuilder.newColumn(null).withTitle('Photoshoot Location').renderWith(columnPhotoShootLocationRender),
        DTColumnBuilder.newColumn(null).withTitle('Total Products').renderWith(columnTotalProductsPhotographedRender),
        DTColumnBuilder.newColumn(null).withTitle('Payment Total').renderWith(columnPaymentTotalRender),
        DTColumnBuilder.newColumn(null).withTitle('Action').notSortable().renderWith(columnActionRender)
    ];

    $scope.dtInstance = {};

    function columnAgentFirstNameRender(data, type, full, meta) {
        return data.agent_id.firstname;
    }

    function columnAgentLastNameRender(data, type, full, meta) {
        return data.agent_id.lastname;
    }

    function columnPhotoShootLocationRender(data, type, full, meta) {
        return data.photo_shoot_location;
    }

    function columnPaymentTotalRender(data, type, full, meta) {
        return data.payment_total;
    }

    function columnSellerRender(data, type, full, meta) {
        if (data.seller_id.displayname.trim().length > 0) {
            return data.seller_id.displayname;
        }
        return data.seller_id.firstname + ' ' + data.seller_id.lastname;
    }

    function columnTotalProductsPhotographedRender(data, type, full, meta) {
        return data.total_products_photographed;
    }

    function columnActionRender(data, type, full, meta) {

        const editButton = '<md-button aria-label="EDIT" ng-click="editAgentLog(' + data.id + ');" class="md-fab md-accent md-raised md-mini">' +
                '<md-icon md-svg-src="assets/angular-material-assets/icons/ic_mode_edit_white_24px.svg" ></md-icon>' +
                '<md-tooltip md-direction="top" md-visible="tooltipVisible">EDIT</md-tooltip>' +
                '</md-button>';

        const deleteButton = '<md-button aria-label="DELETE" ng-click="deleteAgentLog(' + data.id + ');" class="md-fab md-warn md-raised md-mini">' +
                '<md-icon  md-font-icon="icon-delete"></md-icon>' +
                '<md-tooltip md-direction="top" md-visible="tooltipVisible">DELETE</md-tooltip>' +
                '</md-button>';

        var archiveButton = '';

        if ($scope.isAdminUser) {
            archiveButton = '<md-button aria-label="Archive" ng-click="archiveAgentLog(' + data.id + ');" class="md-fab md-accent md-raised md-mini">' +
                    '<md-icon  md-font-icon="icon-archive"></md-icon>' +
                    '<md-tooltip md-direction="top" md-visible="tooltipVisible">Archive</md-tooltip>' +
                    '</md-button>';
        }

        return archiveButton + editButton + deleteButton;
    }

    $scope.editAgentLog = function (id)
    {
        $mdDialog.show({
            controller: 'AgentLogUpdateController',
            templateUrl: 'app/modules/agents_logs/views/agents_logs_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {id: id}
        });
    };

    $scope.deleteAgentLog = function (id) {

        var confirm = $mdDialog.confirm()
                .title('Are you sure you want to delete Agent Log?')
                .ok('Yes')
                .cancel('No');

        $mdDialog.show(confirm).then(function ()
        {
            $rootScope.loader = true;
            $http.delete(site_settings.api_url + 'agents_logs/delete/' + id)
                    .then(function (response)
                    {
                        $rootScope.loader = false;
                        $rootScope.$emit("reloadTable");
                    })
                    .catch(function (error) {
                        $rootScope.loader = false;
                    });
        }, function () { });
    };

    $scope.archiveAgentLog = function (id) {

        var confirm = $mdDialog.confirm()
                .title('Are you sure you want to archive Agent Log?')
                .ok('Yes')
                .cancel('No');

        $mdDialog.show(confirm).then(function ()
        {
            $rootScope.loader = true;
            $http.post(site_settings.api_url + 'agents_logs/archive', {id: id})
                    .then(function (response)
                    {
                        $rootScope.loader = false;
                        $rootScope.$emit("reloadTable");
                    })
                    .catch(function (error) {
                        $rootScope.loader = false;
                    });
        }, function () { });
    };

    $rootScope.$on("reloadTable", function (event, args)
    {
        $scope.dtInstance.rerender();
    });

    $scope.submitForApproval = function (id) {
        var confirm = $mdDialog.confirm()
                .title('Are you sure you want to submit for approval?')
                .ok('Yes')
                .cancel('No');

        $mdDialog.show(confirm).then(function ()
        {
            $rootScope.loader = true;
            $http.post(site_settings.api_url + 'agents_logs/submit_for_approval/' + id)
                    .then(function (response)
                    {
                        $rootScope.loader = false;
                        $rootScope.$emit("reloadTable");
                    })
                    .catch(function (error) {
                        $rootScope.loader = false;
                    });
        }, function () { });
    };

});

app.controller('AgentsLogsArchiveController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken, $window)
{
    $scope.isAdminUser = false;

    $auth.getProfile().then(function (profile) {

        for (var i = 0; i < profile.roles.length; i++) {
            if (profile.roles[i].id == 1) {
                $scope.isAdminUser = true;
            }
        }
    });

    $scope.authuser = $auth.getProfile().$$state.value;

    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
        dataSrc: "data",
        url: site_settings.api_url + 'agents_logs/archive/get',
        type: "POST",
        headers: {Authorization: 'Bearer ' + ngAAToken.getToken()},
        data: {id: $stateParams.id}
    }).withOption('processing', true) //for show progress bar
            .withOption('serverSide', true) // for server side processing
            .withPaginationType('full_numbers') // for get full pagination options // first / last / prev / next and page numbers
            .withDisplayLength(10) // Page size
            .withOption('aaSorting', [0, 'asc'])
            .withOption('autoWidth', false)
            .withOption('responsive', true)
            .withOption('dom', '<"top"f>rt<"bottom"<"left"<"length"l>><"right"<"info"i><"pagination"p>>>')
            .withDataProp('data')
            .withOption('fnRowCallback', function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $compile(nRow)($scope);
            });

    $scope.dtColumns = [
        DTColumnBuilder.newColumn(null).withTitle('Agent First Name').renderWith(columnAgentFirstNameRender),
        DTColumnBuilder.newColumn(null).withTitle('Agent Last Name').renderWith(columnAgentLastNameRender),
        DTColumnBuilder.newColumn(null).withTitle('Seller').notSortable().renderWith(columnSellerRender),
        DTColumnBuilder.newColumn(null).withTitle('Photoshoot Location').renderWith(columnPhotoShootLocationRender),
        DTColumnBuilder.newColumn(null).withTitle('Total Products').renderWith(columnTotalProductsPhotographedRender),
        DTColumnBuilder.newColumn(null).withTitle('Payment Total').renderWith(columnPaymentTotalRender),
       
        DTColumnBuilder.newColumn(null).withTitle('Action').notSortable().renderWith(columnActionRender)
    ];

    $scope.dtInstance = {};

    function columnAgentFirstNameRender(data, type, full, meta) {
        return data.agent_id.firstname;
    }

    function columnAgentLastNameRender(data, type, full, meta) {
        return data.agent_id.lastname;
    }

    function columnPhotoShootLocationRender(data, type, full, meta) {
        return data.photo_shoot_location;
    }

    function columnPaymentTotalRender(data, type, full, meta) {
        return data.payment_total;
    }

    function columnSellerRender(data, type, full, meta) {
        if (data.seller_id.displayname.trim().length > 0) {
            return data.seller_id.displayname;
        }
        return data.seller_id.firstname + ' ' + data.seller_id.lastname;
    }

    function columnTotalProductsPhotographedRender(data, type, full, meta) {
        return data.total_products_photographed;
    }

    function columnActionRender(data, type, full, meta) {
        const restoreButton = '<span  class="text-boxed m-0 deep-orange-bg white-fg" ng-click="restoreAgentLog(' + data.id + ')" style="cursor: pointer">Restore</span>';
        return restoreButton;
    }

    $scope.restoreAgentLog = function (id) {

        var confirm = $mdDialog.confirm()
                .title('Are you sure you want to restore Agent Log?')
                .ok('Yes')
                .cancel('No');

        $mdDialog.show(confirm).then(function ()
        {
            $rootScope.loader = true;
            $http.post(site_settings.api_url + 'agents_logs/archive/restore', {id: id})
                    .then(function (response)
                    {
                        $rootScope.loader = false;
                        $rootScope.$emit("reloadArchiveTable");
                    })
                    .catch(function (error) {
                        $rootScope.loader = false;
                    });
        }, function () { });
    };

    $rootScope.$on("reloadArchiveTable", function (event, args)
    {
        $scope.dtInstance.rerender();
    });
});

app.controller('AgentLogAddController', function ($parse, $document, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.isAdminUser = false;

    $auth.getProfile().then(function (profile) {

        for (var i = 0; i < profile.roles.length; i++) {
            if (profile.roles[i].id == 1) {
                $scope.isAdminUser = true;
            }
        }
    });

    $scope.authuser = $auth.getProfile().$$state.value;

    $scope.action = 'Add';
    $scope.agents = [];
    $scope.sellers = [];
    $scope.agent_log = {};

    $scope.agent_log_images_obj = [];
    $scope.agent_log_images = [];

    if (!$scope.isAdminUser) {
        $scope.agent_log.agent_id = $scope.authuser.id;
    }

    $scope.dzMethods = {};
    $scope.dzCallbacks = {
        addedfile: function (file)
        {
            if (file.isMock) {
                $scope.myDz.createThumbnailFromUrl(file, file.serverImgUrl, null, true);
            }
        },
        success: function (file, xhr)
        {
            // var jsonXhr = JSON.parse(xhr);
            var jsonXhr = xhr;
            $scope.agent_log_images.push(jsonXhr.id);

            jsonXhr.name = jsonXhr.filename;
            $scope.agent_log_images_obj.push(jsonXhr);

            return true;
        },
        removedfile: function (file, response)
        {
            var data = {};

            if (file.id == undefined)
            {
                for (var i in $scope.agent_log_images)
                {
                    if ($scope.agent_log_images[i] == JSON.parse(file.xhr.responseText).id)
                    {
                        $scope.agent_log_images.splice(i, 1);
                        $scope.agent_log_images_obj.splice(i, 1);
                    }
                }
                data.name = JSON.parse(file.xhr.responseText).filename;
                data.id = JSON.parse(file.xhr.responseText).id;
            } else
            {
                for (var i in $scope.agent_log_images)
                {
                    if ($scope.agent_log_images[i] == file.id)
                    {
                        $scope.agent_log_images.splice(i, 1);
                        $scope.agent_log_images_obj.splice(i, 1);
                    }
                }

                data.name = file.name;
                data.id = file.id;
            }

            $http.post(site_settings.api_url + 'agents_logs/invoice/delete', data)
                    .then(function (response)
                    {
                        // nothing
                    })
                    .catch(function (error) {});
        }
    };

    $scope.dzOptionsAddAgentLogInvoice = {
        url: '/api/agents_logs/invoice/upload',
        maxFilesize: '10',
        paramName: 'photo',
        addRemoveLinks: true,
        maxFiles: 10,
        acceptedFiles: 'image/*',
        parallelUploads: 1,
        dictDefaultMessage: 'Upload Invoice'
    };

    $scope.getAllAgents = function ()
    {
        $http.post(site_settings.api_url + 'users/get_all_agents')
                .then(function (response)
                {
                    $scope.agents = response.data;
                }).catch(function (error) {});
    };

    $scope.getAllAgents();

    $scope.getAllSellers = function () {
        $rootScope.loader = true;
        $http.get(site_settings.api_url + 'seller/get_all_seller')
                .then(function (response)
                {
                    $scope.sellers = response.data;

                    $rootScope.loader = false;

                })
                .catch(function (error)
                {
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                });
    }

    $scope.getAllSellers();

    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };

    $scope.saveAgentLog = function () {
        $scope.agent_log.is_paid = $scope.agent_log.is_paid === true ? 1 : 0;
        $scope.agent_log.invoice_images = $scope.agent_log_images;

        $http.post(site_settings.api_url + 'agents_logs/create', $scope.agent_log)
                .then(function (response)
                {
                    $rootScope.message = response.data;
                    $rootScope.$emit("notification");
                    $mdDialog.hide();
                    $rootScope.$emit("reloadTable");
                })
                .catch(function (error) {
                    $rootScope.message = 'Something Went Wrong';
                    $rootScope.$emit("notification");
                });
    };

});

app.controller('AgentLogUpdateController', function (id, $parse, $document, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.isAdminUser = false;

    $auth.getProfile().then(function (profile) {

        for (var i = 0; i < profile.roles.length; i++) {
            if (profile.roles[i].id == 1) {
                $scope.isAdminUser = true;
            }
        }
    });

    $scope.action = 'Update ';
    $scope.agents = [];
    $scope.sellers = [];
    $scope.agent_log = {};

    $scope.agent_log_images_obj = [];
    $scope.agent_log_images = [];

    $scope.mockFiles = [];
    $scope.dzMethods = {};
    $scope.dzCallbacks = {
        addedfile: function (file)
        {
            if (file.isMock) {
                $scope.myDz.createThumbnailFromUrl(file, file.serverImgUrl, null, true);
            }
        },
        success: function (file, xhr)
        {
            // $scope.agent_log.invoice = JSON.parse(xhr).filename;
            $scope.agent_log.invoice = xhr.filename;

            // var jsonXhr = JSON.parse(xhr);
            var jsonXhr = xhr;
            $scope.agent_log_images.push(jsonXhr.id);

            jsonXhr.name = jsonXhr.filename;
            $scope.agent_log_images_obj.push(jsonXhr);

            return true;
        },
        removedfile: function (file, response)
        {
            var data = {};

            if (file.id == undefined)
            {
                for (var i in $scope.agent_log_images)
                {
                    if ($scope.agent_log_images[i] == JSON.parse(file.xhr.responseText).id)
                    {
                        $scope.agent_log_images.splice(i, 1);
                        $scope.agent_log_images_obj.splice(i, 1);
                    }
                }
                data.name = JSON.parse(file.xhr.responseText).filename;
                data.id = JSON.parse(file.xhr.responseText).id;
            } else
            {
                for (var i in $scope.agent_log_images)
                {
                    if ($scope.agent_log_images[i] == file.id)
                    {
                        $scope.agent_log_images.splice(i, 1);
                        $scope.agent_log_images_obj.splice(i, 1);
                    }
                }

                data.name = file.name;
                data.id = file.id;
            }

            $http.post(site_settings.api_url + 'agents_logs/invoice/delete', data)
                    .then(function (response)
                    {
                        // nothing
                    })
                    .catch(function (error) {});
        }
    };

    $scope.dzOptionsAddAgentLogInvoice = {
        url: '/api/agents_logs/invoice/upload',
        maxFilesize: '10',
        paramName: 'photo',
        addRemoveLinks: true,
        maxFiles: 10,
        acceptedFiles: 'image/*',
        parallelUploads: 1,
        dictDefaultMessage: 'Upload Invoice'
    };

    $scope.getAllAgents = function ()
    {
        $http.post(site_settings.api_url + 'users/get_all_agents')
                .then(function (response)
                {
                    $scope.agents = response.data;
                }).catch(function (error) {});
    };

    $scope.getAllAgents();

    $scope.setValues = function (values) {
        $scope.agent_log = {};
        $scope.agent_log.agent_id = values.agent_id.id;
        $scope.agent_log.seller_id = values.seller_id.id;
        $scope.agent_log.photo_shoot_location = values.photo_shoot_location;
        $scope.agent_log.payment_total = parseFloat(values.payment_total);
        $scope.agent_log.invoice = values.invoice;
        $scope.agent_log.additional_details = values.additional_details;
        $scope.agent_log.total_products_photographed = values.total_products_photographed;

        if (values.photo_shoot_date) {
            $scope.agent_log.photo_shoot_date = new Date(values.photo_shoot_date.date);
        }

        $scope.agent_log.is_paid = values.is_paid == 1 ? true : false;
        $scope.agent_log.vignettes = values.vignettes;
        $scope.agent_log.vignettes = values.vignettes;
        if (values.payment_date) {
            $scope.agent_log.payment_date = new Date(values.payment_date.date);
        }
        $scope.agent_log.payment_made_by = values.payment_made_by;



        if (values.agent_log_invoice_images)
        {
            $scope.mockFiles = [];
            for (var i in values.agent_log_invoice_images)
            {
                $scope.agent_log_images.push(values.agent_log_invoice_images[i].id);
                $scope.agent_log_images_obj.push({
                    name: values.agent_log_invoice_images[i].name,
                    filename: values.agent_log_invoice_images[i].name,
                    id: values.agent_log_invoice_images[i].id
                });

                $scope.mockFiles.push({
                    name: values.agent_log_invoice_images[i].name,
                    id: values.agent_log_invoice_images[i].id,
                    size: 5000,
                    isMock: true,
                    serverImgUrl: '/Uploads/agents_logs/' + values.agent_log_invoice_images[i].name
                });
            }

            $timeout(function ()
            {
                $scope.myDz = $scope.dzMethods.getDropzone();
                $scope.mockFiles.forEach(function (mockFile)
                {
                    $scope.myDz.emit('addedfile', mockFile);
                    $scope.myDz.emit('complete', mockFile);
                    $scope.myDz.options.maxFiles = $scope.myDz.options.maxFiles - 1;
                    $scope.myDz.files.push(mockFile);
                });
            });

        }
    };

    $scope.getAllSellers = function () {
        $rootScope.loader = true;
        $http.get(site_settings.api_url + 'seller/get_all_seller')
                .then(function (response)
                {
                    $scope.sellers = response.data;
                    $rootScope.loader = false;
                    $scope.getAgentLogByID();
                })
                .catch(function (error)
                {
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                });
    }

    $scope.getAllSellers();

    $scope.getAgentLogByID = function () {
        $rootScope.loader = true;
        $http.get(site_settings.api_url + 'agents_logs/get/' + id)
                .then(function (response)
                {
                    if (response.data.length > 0) {
                        $scope.setValues(response.data[0]);
                    } else {
                        $scope.closeDialog();
                    }
                    $rootScope.loader = false;
                })
                .catch(function (error) {
                    $rootScope.loader = false;
                    $scope.closeDialog();
                    console.log(error);
                });
    };

    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };

    $scope.saveAgentLog = function () {

        $scope.agent_log.is_paid = $scope.agent_log.is_paid === true ? 1 : 0;
        $scope.agent_log.invoice_images = $scope.agent_log_images;

        $http.post(site_settings.api_url + 'agents_logs/update/' + id, $scope.agent_log)
                .then(function (response)
                {
                    $mdDialog.hide();
                    $rootScope.$emit("reloadTable");
                })
                .catch(function (error)
                {
                    $rootScope.message = 'Something Went Wrong';
                    $rootScope.$emit("notification");
                });
    };
});