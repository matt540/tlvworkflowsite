"use strict";

var app = angular.module('ng-app');

app.controller('SellerController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken)
{

    $scope.dtInstance = {};
    $scope.user = [];
    $scope.paramname = 2;
    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
        dataSrc: "data",
        url: site_settings.api_url + 'seller/get_sellers',
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

//        var action_btn = '<md-fab-speed-dial style=" transform: translate(-110px, 0px);" md-open="demo' + data.id + '.isOpen" md-direction="left" ng-class="{ \'md-hover-full\': demo' + data.id + '.hover }" class="md-scale md-fab-top-right ng-isolate-scope md-left"  ng-mouseleave="demo' + data.id + '.isOpen=false" ng-mouseenter="demo' + data.id + '.isOpen=true">';
//        action_btn += '<md-fab-trigger>';
//        action_btn += '<md-button aria-label="menu" class="md-fab md-primary md-mini">';
//        action_btn += '<md-icon md-svg-src="assets/angular-material-assets/icons/ic_call_to_action_white_24px.svg"></md-icon>';
//        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">Action</md-tooltip>';
//
//        action_btn += '</md-button>';
//        action_btn += '</md-fab-trigger>';
//        action_btn += '<md-fab-actions>';
        action_btn += '<md-button aria-label="EDIT" ng-click="openSellerAddDialog(' + data.id + ');" class="md-fab md-accent md-raised md-mini">';
        action_btn += '<md-icon md-svg-src="assets/angular-material-assets/icons/ic_mode_edit_white_24px.svg" aria-label="Twitter"></md-icon>';
        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">EDIT</md-tooltip>';
        action_btn += '</md-button>';

        action_btn += '<md-button aria-label="Assign Agent" ng-click="assignAgentDialog(' + data.id + ');" class="md-fab md-accent md-raised md-mini">';
        action_btn += '<md-icon md-font-icon="icon-plus" aria-label="assign agent"></md-icon>';
        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">Assign Agent</md-tooltip>';
        action_btn += '</md-button>';

        action_btn += '<md-button aria-label="AGREEMENTS" ng-click="openSellerProductQuoteAgreementsDialog(' + data.id + ');" class="md-fab md-accent md-raised md-mini">';
        action_btn += '<md-icon md-font-icon="icon-eye" aria-label="Twitter"></md-icon>';
        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">AGREEMENTS</md-tooltip>';
        action_btn += '</md-button>';


        action_btn += '<md-button aria-label="RENEWS" ng-click="openSellerProductQuoteRenewsDialog(' + data.id + ');" class="md-fab md-accent md-raised md-mini">';
        action_btn += '<md-icon md-font-icon="icon-book-variant" aria-label="Twitter"></md-icon>';
        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">RENEWS</md-tooltip>';
        action_btn += '</md-button>';


        action_btn += '<md-button ng-click="showConfirm(' + data.id + ')" aria-label="DELETE" class="md-fab md-warn md-raised md-mini">';
        action_btn += '<md-icon  md-font-icon="icon-delete" aria-label="Facebook"></md-icon>';
        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">DELETE</md-tooltip>';
        action_btn += '</md-button>';

//        action_btn += '</md-fab-actions></md-fab-speed-dial>';
        return action_btn;
//        $scope.persons[data.id] = data;
//        return '<md-button aria-label="Edit" ui-sref="edit_profile({id:\'' + full.id + '\'})" class="md-fab md-mini md-button  md-ink-ripple">  <md-icon md-svg-src="assets/img/edit.svg" class="icon"></md-icon></md-button><md-button aria-label="Delete" type="submit" class="md-fab md-mini md-button  md-ink-ripple md-warn">  <md-icon md-font-icon="icon-delete" class="icon" ></md-icon></md-button>';
    }
//    function profileImage(data, type, full, meta) {
//
//       // console.log('data');
//        if (data != '' && data != undefined)
//        {
//            return '<img  style="width:40px;height:40px;border-radius:30px;" src="Uploads/profile/' + data + '">';
//        }
//        else
//        {
//            return '<img  style="width:40px;height:40px;border-radius:30px;" src="/assets/images/avatars/profile.jpg">';
//        }
//    }

    $scope.dtInstance = {};


    function reloadData()
    {

        var resetPaging = false;
        $scope.dtInstance.reloadData(callback, resetPaging);
    }


    function callback(json)
    {

    }
    $scope.dtColumns = [
//        DTColumnBuilder.newColumn('profile_image').withTitle('Image').notSortable().renderWith(profileImage),
        DTColumnBuilder.newColumn('firstname').withTitle('First Name'),
        DTColumnBuilder.newColumn('lastname').withTitle('Last Name'),
        DTColumnBuilder.newColumn('email').withTitle('Email'),
        DTColumnBuilder.newColumn('displayname').withTitle('Dispaly Name'),
        DTColumnBuilder.newColumn('address').withTitle('Address'),
        DTColumnBuilder.newColumn('roles').withTitle('Roles').notSortable(),
        DTColumnBuilder.newColumn('agent_name').withTitle('Agent').notSortable(),
        //      DTColumnBuilder.newColumn('payment_type').withTitle('Payment Type'),
//        DTColumnBuilder.newColumn('status').withTitle('Status').renderWith(statusHtml),
//        DTColumnBuilder.newColumn('status').withTitle('Status').renderWith(statusHtml),
        DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable()
                .renderWith(actionsHtml),
    ];

//    function statusHtml(data, type, full, meta) {
//
//        if (data == 'Active')
//        {
//            return '<button aria-label="{{hover_' + full.id + ' ==true ? \'Inactive\' : \'Active\' }}"  class="md-primary md-raised md-button md-jd-theme md-ink-ripple"\n\
//                     ng-class="hover==true ? md-warn : md-primary"    ng-mouseenter="hover_' + full.id + ' = true"    ng-mouseleave="hover_' + full.id + ' = false"\n\
//                     type="button" ng-click="change_status(\'' + full.id + '\',\'' + 'InActive' + '\')" >{{hover_' + full.id + ' ==true ? \'Inactive\' : \'Active\' }}</button>';
//        }
//        else if (data == 'InActive')
//        {
//            return '<button aria-label="{{hover_' + full.id + ' ==true ? \'Active\' : \'InActive\' }}"  class="md-warn md-raised md-button md-default-theme md-ink-ripple"\n\
//                     ng-class="hover==true ? md-primary : md-warn"    ng-mouseenter="hover_' + full.id + ' = true"    ng-mouseleave="hover_' + full.id + ' = false"\n\
//                     type="button" ng-click="change_status(\'' + full.id + '\',\'' + 'Active' + '\')" >{{hover_' + full.id + ' ==true ? \'Active\' : \'InActive\' }}</button>';
//
//        }
//        else
//        {
//            return '<button aria-label="s" class="md-warm md-raised md-button md-default-theme md-ink-ripple" ng-class="hover==true ? md-warn : md-primary"    ng-mouseenter="hover = true"    ng-mouseleave="hover = false"\n\ type="button" ng-click="" >' + data + '</button>';
//
//        }
//    }
//
//    $scope.change_status = function (seller, status) {
//
//        var deferred = $q.defer();
//        var cred = {seller: seller, status: status}
//
//        $http.post(site_settings.api_url + 'seller/change_seller_status', cred)
//                .then(function (response) {
//                    $rootScope.message = 'Status change Successfully';
//                    $rootScope.$emit("notification");
//                    reloadData();
//                }).catch(function (error) {
//            $rootScope.message = 'Something Went Wrong.';
//            $rootScope.$emit("notification");
//            $rootScope.loader = false;
//
//        });
//    }
//
//
    $scope.assignAgentDialog = function (sellerId)
    {
        $mdDialog.show({
            controller: 'SellerAsignAgentController',
            templateUrl: 'app/modules/seller/views/assign_to_agent.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                seller_id: sellerId
            }
        });
    }

    $scope.openSellerAddDialog = function (user)
    {
        $mdDialog.show({
            controller: 'SellerAddEditController',
            templateUrl: 'app/modules/seller/views/seller_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                user: user
            }
        });
    }
    $scope.openSellerProductQuoteAgreementsDialog = function (seller_id)
    {
        $mdDialog.show({
            controller: 'SellerProductQuoteAgreementsController',
            templateUrl: 'app/modules/seller/views/product_quote_agreement.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                seller_id: seller_id
            }
        });
    }
    $scope.openSellerProductQuoteRenewsDialog = function (seller_id)
    {
        $mdDialog.show({
            controller: 'SellerProductQuoteRenewsController',
            templateUrl: 'app/modules/seller/views/product_quote_renew.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                seller_id: seller_id
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


    $scope.showConfirm = function (id)
    {

        // Appending dialog to document.body to cover sidenav in docs app
        var confirm = $mdDialog.confirm()
                .title('Would you like to delete this Seller?')
                .ok('Yes')
                .cancel('No');

        $mdDialog.show(confirm).then(function ()
        {
            $http.post(site_settings.api_url + 'seller/delete_seller', {id: id})
                    .then(function (response)
                    {
                        $scope.ids = response.data;

                        $rootScope.message = 'Seller Deleted Successfully';
                        $rootScope.$emit("notification");
                        $rootScope.$emit("reloadUserTable");

                    }).catch(function (error)
            {
                $rootScope.message = 'Something Went Wrong';
                $rootScope.$emit("notification");
            });
        }, function ()
        {

        });
    };
});

app.controller('SellerProductController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken)
{

    $scope.dtInstance = {};
    $scope.user = [];

    $scope.isPricerUser = false;

    $auth.getProfile().then(function (profile) {

        for (var i = 0; i < profile.roles.length; i++) {
            if (profile.roles[i].id == 6) {
                $scope.isPricerUser = true;
            }
        }
    });
//    $scope.paramname = 1;
    if ($stateParams.name == 'product')
    {
        $scope.paramname = 0;
    }
    $scope.title = 'SELLERS';


    if ($stateParams.name == 'product')
    {
        $auth.hasAnyPermission(['product.add', 'product.edit', 'product.view', 'product.delete'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {


                    } else
                    {
                        $rootScope.message = 'You Don\'t have Permission';
                        $rootScope.$emit("notification");
                        $state.go('dashboard');
                    }
                });
        $scope.title = 'SELLER REQUEST Dashboard';
    }
    if ($stateParams.name == 'proposal')
    {

        $auth.hasAnyPermission(['proposal.add', 'proposal.edit', 'proposal.view', 'proposal.delete'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {


                    } else
                    {
                        $rootScope.message = 'You Don\'t have Permission';
                        $rootScope.$emit("notification");
                        $state.go('dashboard');
                    }
                });
        $scope.title = 'SELLERS PROPOSAL Dashboard';
    }
    if ($stateParams.name == 'product_for_production')
    {
        $auth.hasAnyPermission(['production.add', 'production.edit', 'production.view', 'production.delete'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {


                    } else
                    {
                        $rootScope.message = 'You Don\'t have Permission';
                        $rootScope.$emit("notification");
                        $state.go('dashboard');
                    }
                });
        $scope.title = 'For Production Dashboard';
    }
    if ($stateParams.name == 'product_for_pricing')
    {
        $auth.hasAnyPermission(['production.add', 'production.edit', 'production.view', 'production.delete', 'product.pricer'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {


                    } else
                    {
                        $rootScope.message = 'You Don\'t have Permission';
                        $rootScope.$emit("notification");
                        $state.go('dashboard');
                    }
                });
//        $scope.title = 'Proposals / For Production Dashboard';
        $scope.title = 'For Pricing Dashboard';
    }
    if ($stateParams.name == 'awaiting_contract')
    {
        $auth.hasAnyPermission(['production.add', 'production.edit', 'production.view', 'production.delete', 'product.pricer'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {


                    } else
                    {
                        $rootScope.message = 'You Don\'t have Permission';
                        $rootScope.$emit("notification");
                        $state.go('dashboard');
                    }
                });
//        $scope.title = 'Proposals / For Production Dashboard';
        $scope.title = 'For Awaiting Contract Dashboard';
    }
    if ($stateParams.name == 'proposal_for_production')
    {
        $auth.hasAnyPermission(['production.add', 'production.edit', 'production.view', 'production.delete', 'product.pricer'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {


                    } else
                    {
                        $rootScope.message = 'You Don\'t have Permission';
                        $rootScope.$emit("notification");
                        $state.go('dashboard');
                    }
                });
//        $scope.title = 'Proposals / For Production Dashboard';
        $scope.title = 'For Production Dashboard';
    }
    if ($stateParams.name == 'copyright')
    {
        $auth.hasAnyPermission(['copyright.add', 'copyright.edit', 'copyright.view', 'copyright.delete'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {


                    } else
                    {
                        $rootScope.message = 'You Don\'t have Permission';
                        $rootScope.$emit("notification");
                        $state.go('dashboard');
                    }
                });
        $scope.title = 'SELLERS COPYWRITER Dashboard';
    }
    if ($stateParams.name == 'approvedproducts')
    {
        $auth.hasAnyPermission(['approval_product.add', 'approval_product.edit', 'approval_product.view', 'approval_product.delete'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {


                    } else
                    {
                        $rootScope.message = 'You Don\'t have Permission';
                        $rootScope.$emit("notification");
                        $state.go('dashboard');
                    }
                });
        $scope.title = 'Approval Dashboard';
    }
    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
        dataSrc: "data",
        url: site_settings.api_url + 'seller/get_seller_product',
        type: "POST",
        headers: {Authorization: 'Bearer ' + ngAAToken.getToken()},
        data: {name: $stateParams.name}
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

//        action_btn += '<md-button aria-label="EDIT" ng-click="openSellerAddDialog(' + data.id + ');" class="md-fab md-accent md-raised md-mini">';
//        action_btn += '<md-icon md-svg-src="assets/angular-material-assets/icons/ic_mode_edit_white_24px.svg" aria-label="Twitter"></md-icon>';
//        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">EDIT</md-tooltip>';
//        action_btn += '</md-button>';
//        action_btn += '<md-button ng-click="showConfirm(' + data.id + ')" aria-label="DELETE" class="md-fab md-warn md-raised md-mini">';
//        action_btn += '<md-icon  md-font-icon="icon-delete" aria-label="Facebook"></md-icon>';
//        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">DELETE</md-tooltip>';
//        action_btn += '</md-button>';
        action_btn += '<span ng-click="openSellerAddDialog(' + data.id + ');" class="text-boxed m-0 deep-blue-bg white-fg" style="cursor: pointer; background-color: #126491 !important;">EDIT</span>';
        action_btn += '<span ng-click="showConfirm(' + data.id + ')" class="text-boxed m-0 deep-red-bg white-fg" style="cursor: pointer; margin-left: 5px; background-color: red !important;"">DELETE</span>';

        return action_btn;
    }

    function productsHtml(data, type, full, meta)
    {

        if ($stateParams.name == 'product')
        {
            return '<span ui-sref="products({id: ' + full.id + '})" class="text-boxed m-0 deep-orange-bg white-fg" style="cursor: pointer">VIEW PRODUCTS (' + full.pending_count + ')</span>';
        } else if ($stateParams.name == 'proposal')
        {
            return '<span ui-sref="product_quotations({id: ' + full.id + '})" class="text-boxed m-0 deep-orange-bg white-fg" style="cursor: pointer">VIEW PRODUCTS (' + full.pending_count + ')</span>';

        } else if ($stateParams.name == 'product_for_production')
        {
            return '<span ui-sref="product_for_production({id: ' + full.id + '})" class="text-boxed m-0 deep-orange-bg white-fg" style="cursor: pointer">VIEW PRODUCTS (' + full.pending_count + ')</span>';
        } else if ($stateParams.name == 'product_for_pricing')
        {
            return '<span ui-sref="product_for_pricing({id: ' + full.id + '})" class="text-boxed m-0 deep-orange-bg white-fg" style="cursor: pointer">VIEW PRODUCTS (' + full.pending_count + ')</span>';

        } else if ($stateParams.name == 'awaiting_contract')
        {
            return '<span ui-sref="awaiting_contract({id: ' + full.id + '})" class="text-boxed m-0 deep-orange-bg white-fg" style="cursor: pointer">VIEW PRODUCTS (' + full.pending_count + ')</span>';

        } else if ($stateParams.name == 'proposal_for_production')
        {
            return '<span ui-sref="proposal_for_production({id: ' + full.id + '})" class="text-boxed m-0 deep-orange-bg white-fg" style="cursor: pointer">VIEW PRODUCTS (' + full.pending_count + ')</span>';

        } else if ($stateParams.name == 'copyright')
        {
            // old url for copywriter
            // return '<span ui-sref="copyright({id: ' + full.id + '})" class="text-boxed m-0 deep-orange-bg white-fg" style="cursor: pointer">VIEW PRODUCTS (' + full.pending_count + ')</span>';

            // sending same url as pricer
            return '<span ui-sref="product_for_pricing_pricer({id: ' + full.id + '})" class="text-boxed m-0 deep-orange-bg white-fg" style="cursor: pointer">VIEW PRODUCTS (' + full.pending_count + ')</span>';
        } else if ($stateParams.name == 'approvedproducts')
        {
            return '<span ui-sref="product_final({id: ' + full.id + '})" class="text-boxed m-0 deep-orange-bg white-fg" style="cursor: pointer">VIEW PRODUCTS (' + full.pending_count + ')</span>';
        } else if ($stateParams.name === 'product_for_only_pricing') {
            return '<span ui-sref="product_for_pricing_pricer({id: ' + full.id + '})" class="text-boxed m-0 deep-orange-bg white-fg" style="cursor: pointer">VIEW PRODUCTS (' + full.pending_count + ')</span>';
        }
    }

    function nullHtml(data, type, full, meta)
    {

        if (data == null || data == '')
        {
            return '---';
        } else
        {
            return data;
        }
    }

    $scope.dtInstance = {};


    function reloadData()
    {

        var resetPaging = false;
        $scope.dtInstance.reloadData(callback, resetPaging);
    }


    function callback(json)
    {

    }
    $scope.dtColumns = [
//        DTColumnBuilder.newColumn('profile_image').withTitle('Image').notSortable().renderWith(profileImage),
        DTColumnBuilder.newColumn('firstname').withTitle('First Name').renderWith(nullHtml),
        DTColumnBuilder.newColumn('lastname').withTitle('Last Name').renderWith(nullHtml),
        DTColumnBuilder.newColumn('email').withTitle('Email'),
        DTColumnBuilder.newColumn('agent_name').withTitle('Agent Name').notSortable(),
        DTColumnBuilder.newColumn(null).withTitle('Action').notSortable()
                .renderWith(productsHtml),
//        DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable()
//                .renderWith(actionsHtml)
    ];

//    function statusHtml(data, type, full, meta) {
//
//        if (data == 'Active')
//        {
//            return '<button aria-label="{{hover_' + full.id + ' ==true ? \'Inactive\' : \'Active\' }}"  class="md-primary md-raised md-button md-jd-theme md-ink-ripple"\n\
//                     ng-class="hover==true ? md-warn : md-primary"    ng-mouseenter="hover_' + full.id + ' = true"    ng-mouseleave="hover_' + full.id + ' = false"\n\
//                     type="button" ng-click="change_status(\'' + full.id + '\',\'' + 'InActive' + '\')" >{{hover_' + full.id + ' ==true ? \'Inactive\' : \'Active\' }}</button>';
//        }
//        else if (data == 'InActive')
//        {
//            return '<button aria-label="{{hover_' + full.id + ' ==true ? \'Active\' : \'InActive\' }}"  class="md-warn md-raised md-button md-default-theme md-ink-ripple"\n\
//                     ng-class="hover==true ? md-primary : md-warn"    ng-mouseenter="hover_' + full.id + ' = true"    ng-mouseleave="hover_' + full.id + ' = false"\n\
//                     type="button" ng-click="change_status(\'' + full.id + '\',\'' + 'Active' + '\')" >{{hover_' + full.id + ' ==true ? \'Active\' : \'InActive\' }}</button>';
//
//        }
//        else
//        {
//            return '<button aria-label="s" class="md-warm md-raised md-button md-default-theme md-ink-ripple" ng-class="hover==true ? md-warn : md-primary"    ng-mouseenter="hover = true"    ng-mouseleave="hover = false"\n\ type="button" ng-click="" >' + data + '</button>';
//
//        }
//    }
//
//    $scope.change_status = function (seller, status) {
//
//        var deferred = $q.defer();
//        var cred = {seller: seller, status: status}
//
//        $http.post(site_settings.api_url + 'seller/change_seller_status', cred)
//                .then(function (response) {
//                    $rootScope.message = 'Status change Successfully';
//                    $rootScope.$emit("notification");
//                    reloadData();
//                }).catch(function (error) {
//            $rootScope.message = 'Something Went Wrong.';
//            $rootScope.$emit("notification");
//            $rootScope.loader = false;
//
//        });
//    }
//
//
    $scope.openSellerAddDialog = function (user)
    {
        $mdDialog.show({
            controller: 'SellerAddEditController',
            templateUrl: 'app/modules/seller/views/seller_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                user: user
            }
        });
    };

    $scope.openProductAddDialog = function (product)
    {
        $mdDialog.show({
            controller: 'ProductAddController',
            templateUrl: 'app/modules/product/views/product_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                seller: null,
                product: product
            }
        });
    };

    $rootScope.$on("reloadUserTable", function (event, args)
    {
        console.log('asdasdsadsadadadad');

        reloadData();
    });
    $rootScope.$on("reloadUserTable1", function (event, args)
    {
        reloadData();
        $mdDialog.show({
            controller: 'CustomDialogController',
            templateUrl: 'app/modules/product/views/custom_dialog.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true
        });
    });

    function reloadData()
    {

        var resetPaging = false;
        $scope.dtInstance.reloadData(callback, resetPaging);
    }

    function callback(json)
    {

    }


    $scope.showConfirm = function (id)
    {

        // Appending dialog to document.body to cover sidenav in docs app
        var confirm = $mdDialog.confirm()
                .title('Would you like to delete this Seller?')
                .ok('Yes')
                .cancel('No');

        $mdDialog.show(confirm).then(function ()
        {
            $http.post(site_settings.api_url + 'seller/delete_seller', {id: id})
                    .then(function (response)
                    {
                        $scope.ids = response.data;

                        $rootScope.message = 'Seller Deleted Successfully';
                        $rootScope.$emit("notification");
                        $rootScope.$emit("reloadUserTable");

                    }).catch(function (error)
            {
                $rootScope.message = 'Something Went Wrong';
                $rootScope.$emit("notification");
            });
        }, function ()
        {

        });
    };
});

app.controller('CustomDialogController', function ($parse, $document, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.openProductAddDialog = function (product)
    {
        $mdDialog.show({
            controller: 'ProductAddController',
            templateUrl: 'app/modules/product/views/product_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                product: product
            }
        });
    };

    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };
});
app.controller('ProductAddController_stop', function (product, $parse, $document, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.product = {};
    $scope.categorys = {};
    $scope.subcategorys = {};
    $rootScope.$on("newSeller", function (event, user)
    {
        console.log($scope.sellers.length);
        $scope.sellers.push(user);
        console.log($scope.sellers);
        console.log($scope.sellers.length);
        $scope.product.seller_id = user.ID;

    });
    $rootScope.$on("newPickUpLocation", function (event, pick_up_location)
    {

        console.log(pick_up_location);
        $scope.pick_up_locations.push(pick_up_location);
        $scope.products_combo[0].pick_up_location = pick_up_location.id;

    });
    $scope.addNewPickUpLocation = function ()
    {
        if ($scope.product.sellerid)
        {
            $mdDialog.show({
                controller: 'PickUpLocationAddController',
                templateUrl: 'app/modules/product/views/pick_up_location_add.html',
                parent: angular.element($document.body),
                clickOutsideToClose: true,
                skipHide: true,
                locals: {
                    seller_id: $scope.product.sellerid
                }
            });
        } else
        {
            $rootScope.message = 'Please Select a Seller.';
            $rootScope.$emit("notification");

        }
//        $mdDialog.show({
//            controller: 'PickUpLocationAddController',
//            templateUrl: 'app/modules/product/views/pick_up_location_add.html',
//            parent: angular.element($document.body),
//            clickOutsideToClose: true,
//            skipHide: true,
//        });
    };
    $scope.getAllPickUpLocations = function ()
    {

        $http.get(site_settings.api_url + 'getPickUpLocationsBySelectIdSellerId/6/' + $scope.product.sellerid)
                .then(function (response)
                {
                    $scope.pick_up_locations = response.data;
                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
            $rootScope.loader = false;
        });
    };
    $scope.getAllPickUpLocations();

//    $scope.sellers = {};
    $scope.products_count = 1;
    $scope.products_combo = [{name: '', price: '', description: '', quantity: '', state: '', city: '', cat: {}}];
    $scope.product = {};
    $scope.edit = false;
    $scope.action = 'Add';
    $scope.searchTerm = '';

    $scope.addNewSeller = function ()
    {
        $mdDialog.show({
            controller: 'SellerAddEditController',
            templateUrl: 'app/modules/product/views/seller_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            skipHide: true,
        });
    };
    $scope.getStatus = function ()
    {

        $http.get(site_settings.api_url + 'select/get_status')
                .then(function (response)
                {
                    $scope.status = response.data;
                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
            $rootScope.loader = false;

        });
    };

    $scope.getStatus();

    $scope.getSellersFromWP = function ()
    {

        $http.jsonp(site_settings.wp_api_url + 'seller-api.php', {jsonpCallbackParam: 'callback'})
                .then(function (response)
                {
                    $scope.sellers = response.data;
//            console.log($scope.sellers);
                })
                .catch(function (response)
                {
                    console.log(response);
                });
    };

    $scope.getSellers = function ()
    {

        $http.get(site_settings.api_url + 'seller/get_all_seller')
                .then(function (response)
                {
                    $scope.sellers = response.data;
                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
        });
    };

//    $scope.getSellersFromWP();
    $scope.getSellers();

//    $scope.getSubCategoriesByCategory = function () {
//
//        $scope.subcategorys_temp = [];
//
//        for (var i in $scope.subcategorys)
//        {
//            if ($scope.subcategorys[i].category_id == $scope.menu_item.category_id)
//            {
//                $scope.subcategorys_temp[$scope.subcategorys_temp.length] = $scope.subcategorys[i];
//            }
//        }
//
//    };

    $scope.getCategorys = function ()
    {
        $http.get(site_settings.api_url + 'get_all_categorys')
                .then(function (response)
                {
                    $scope.categorys = response.data;
//                    $rootScope.$emit("notification");
                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
        });
    };

    $scope.getSubCategorys = function ()
    {
        $http.get(site_settings.api_url + 'get_all_subcategorys')
                .then(function (response)
                {
                    $scope.subcategorys = $scope.subcategorys_temp = response.data;

                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
        });
    };

    $scope.getCategorys();
    $scope.getSubCategorys();




//    $scope.dzCallbacks = {
//        'addedfile': function (file) {
//
//            if (file.isMock) {
//                $scope.myDz.createThumbnailFromUrl(file, file.serverImgUrl, null, true);
//            }
//        },
//        'success': function (file, xhr) {
//            $scope.user.profile_image = xhr.filename
//            return false;
//        }
//
//    };
    $scope.current_dropzone = '';
    $scope.dzMethods = {};
    $scope.removeNewFile = function ()
    {
        $scope.dzMethods.removeFile($scope.newFile); //We got $scope.newFile from 'addedfile' event callback
    }

    $scope.saveProduct = function ()
    {
        $scope.product.products = $scope.products_combo;

//        for (var i in $scope.sellers)
//        {
//            if ($scope.sellers[i].ID == $scope.product.seller_id)
//            {
////                $scope.product.seller_firstname = $scope.sellers[i].data.display_name.charAt(0);
////                var temp = $scope.sellers[i].data.display_name.split(" ");
////                $scope.product.seller_lastname = temp[1].substr(0, 3);
//            }
//        }
        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'product/save_product', $scope.product)
                .then(function (response)
                {

                    $rootScope.message = response.data;
                    $rootScope.loader = false;
                    $rootScope.$emit("notification");
                    $rootScope.$emit("reloadUserTable");
//                    $rootScope.$emit("reloadUserTable1");
                    $mdDialog.hide();
                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong';
            $rootScope.$emit("notification");
        });
    };


    $scope.set_current_dropzone = function (prod_key)
    {
        $scope.current_dropzone_index = prod_key;
//        $scope.products_combo[$scope.current_dropzone_index]['images'] = [];
    }
    $scope.addProductField = function ()
    {
        $scope.products_count++;
        $scope.products_combo.push({name: '', price: '', description: '', quantity: '', cat: {}});
//        var the_string = $scope.products_count - 1;
//        var model = $parse('dzCallbacks_' + the_string);
//
//        model.assign($scope, {
//            'addedfile': function (file) {
//
//                if (file.isMock) {
//                    $scope.myDz.createThumbnailFromUrl(file, file.serverImgUrl, null, true);
//                }
//            },
//            'success': function (file, xhr) {
//                $scope.user.profile_image = xhr.filename;
//                console.log(the_string);
//                console.log(xhr.filename);
//                return false;
//            }
//
//        });
//        $scope.dzCallbacks_ = {
//            'addedfile': function (file) {
//
//                if (file.isMock) {
//                    $scope.myDz.createThumbnailFromUrl(file, file.serverImgUrl, null, true);
//                }
//            },
//            'success': function (file, xhr) {
//                $scope.user.profile_image = xhr.filename
//                return false;
//            }
//
//        };
    }
    $scope.removeProductField = function (c)
    {
        $scope.products_count--;
        $scope.products_combo.splice(c, 1);

    }
    $scope.dzCallbacks = {
        'addedfile': function (file)
        {

            if (file.isMock)
            {
                $scope.myDz.createThumbnailFromUrl(file, file.serverImgUrl, null, true);
            }
        },
        'success': function (file, xhr)
        {
            console.log($scope.current_dropzone_index);
            if ($scope.products_combo[$scope.current_dropzone_index]['images'] != undefined)
            {

            } else
            {
                $scope.products_combo[$scope.current_dropzone_index]['images'] = [];
            }
            console.log($scope.current_dropzone_index);
            console.log(xhr.id);
            if (xhr != null)
            {
                $scope.products_combo[$scope.current_dropzone_index]['images'].push(xhr.id);
            }
            return false;
        },
        'sending': function (file, xhr, formData)
        {

            formData.append('folder', 'product');
        },
        'removedfile': function (file, response)
        {

            var data = {};
            data.folder = 'product';
//            data.product_id = product;

            if (file.id == undefined)
            {
                for (var i in $scope.products_combo)
                {
                    for (var k in $scope.products_combo[i]['images'])
                    {
                        if ($scope.products_combo[i]['images'][k] == JSON.parse(file.xhr.responseText).id)
                        {

                            $scope.products_combo[i]['images'].splice(k, 1);
                        }
                    }
                }
                data.name = JSON.parse(file.xhr.responseText).filename;
                data.id = JSON.parse(file.xhr.responseText).id;
            } else
            {
                for (var i in $scope.products_combo)
                {
                    for (var k in $scope.products_combo[i]['images'])
                    {
                        if ($scope.products_combo[i]['images'][k] == file.id)
                        {
                            $scope.products_combo[i]['images'].splice(k, 1);
                        }
                    }
                }
                data.name = file.name;
                data.id = file.id;
            }

//            data.imgs = $scope.product_images;

            $http.post('/api/product/deleteImageForFirstAdd', data)
                    .then(function (response)
                    {
                        console.log('deleted');
                        console.log($scope.products_combo);
                    }).catch(function (error)
            {
            });
        },
    };

    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };
    $scope.dzOptionsAddProduct = {
        url: '/api/product/uploadImages',
        maxFilesize: '10',
        paramName: 'photo',
        addRemoveLinks: true,
        maxFiles: 10,
        acceptedFiles: 'image/*',
        thumbnailWidth: '200',
        thumbnailHeight: '200',
//        dictDefaultMessage: '<img width="80" src="/assets/images/clip-512.png">',
        dictDefaultMessage: 'Upload Product Images Here',
        maxfilesexceeded: function (file)
        {
//            this.removeAllFiles();
            this.addFile(file);
        },
        sending: function (file, xhr, formData)
        {
            formData.append('folder', 'product');
        },
    };
});

app.controller('SellerProductQuoteAgreementsController', function (seller_id, $document, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.product_quote_agreements = [];
    $scope.product_storage_agreements = [];
    $scope.consignment_with_storage_agreements = [];

    $scope.renderDate = function (date)
    {
        return moment.utc(date.date).local().format('MM/DD/YYYY');
    };

    $scope.getAllMyProductQuoteAgreements = function ()
    {
        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'agreements/filled-agreements', {seller_id: seller_id})
                .then(function (response)
                {
                    $rootScope.loader = false;
                    $scope.product_quote_agreements = response.data.product_quote_agreements;
                    $scope.product_storage_agreements = response.data.product_storage_agreements;
                    $scope.consignment_with_storage_agreements = response.data.consignment_with_storage_agreements;
                })
                .catch(function (error)
                {
                    $rootScope.loader = false;
                    $scope.closeDialog();
                });
    };

    if (seller_id)
    {
        $scope.getAllMyProductQuoteAgreements();
    } else
    {
        $scope.closeDialog();
    }

    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };

    $scope.openAddAgreementDialog = function (agreementType) {
        $mdDialog.hide();
        $mdDialog.show({
            controller: 'AddSellerAgreementController',
            templateUrl: 'app/modules/seller/views/upload_agreement.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                seller_id: seller_id,
                agreement_type: agreementType
            }
        });
    };

});
app.controller('SellerProductQuoteRenewsController', function (seller_id, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.product_quote_renews = [];
    $scope.filter = {};
    $scope.filter.length = 5;
    $scope.filter.last = 0;
    $scope.total = 0;


    $scope.renderDate = function (date)
    {
        return moment.utc(date.date).local().format('MM/DD/YYYY');
    }
    $scope.getAllMyProductQuoteRenews = function ()
    {
        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'seller_agreement/getAllMyProductQuoteRenews', {id: seller_id, filter: $scope.filter})
                .then(function (response)
                {
                    $rootScope.loader = false;

                    $scope.product_quote_renews = $scope.product_quote_renews.concat(response.data.data);

                    if (response.data.data.length > 0)
                    {
                        $scope.filter.last = response.data.data[response.data.data.length - 1].id;
                    }
                    $scope.total = response.data.total;
                }).catch(function (error)
        {
            $rootScope.loader = false;
            $scope.closeDialog();
//            $rootScope.message = 'Seller Not Found';
//            $rootScope.$emit("notification");

        });
    }
    if (seller_id)
    {
        $scope.getAllMyProductQuoteRenews();
    } else
    {

        $scope.closeDialog();
    }




    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };

});
app.controller('SellerAddEditController', function (user, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.user = {};
    $scope.onlyNumbers = /^\d+$/;
    $scope.regex = '^[a-zA-Z0-9._-]+$';
    $scope.user.not_available = false;
    $scope.user.not_available_email = true;
    $scope.agents = [];

    $scope.seller_roles = [];
    $scope.getAllSellerRoles = function ()
    {
        $http.get(site_settings.api_url + 'getOptionsBySelectId/8')
                .then(function (response)
                {
                    $scope.seller_roles = response.data;
                }).catch(function (error)
        {

        });


    }
    $scope.getAllSellerRoles();


    $scope.IsShopUrlAvailable = function (shopurl, is_valid)
    {
        if (shopurl && is_valid)
        {
            $rootScope.loader = true;
            $http.post(site_settings.api_url + 'is_shop_url_available_WP', {shop_url: shopurl})
                    .then(function (response)
                    {
                        if (response.data == 1)
                        {
                            $scope.user.not_available = true;

                        } else
                        {
                            $scope.user.not_available = false;
                        }
                        $rootScope.loader = false;
                        console.log(response);
//                    $rootScope.$emit("newSeller", response);
//
//                    $rootScope.message = 'User Saved Successfully';
//                    $rootScope.$emit("notification");
//                    $mdDialog.hide();
                    }).catch(function (error)
            {
                $rootScope.loader = false;
                console.log(error)
                $rootScope.message = 'Something Went Wrong';
                $rootScope.$emit("notification");
            });
        }

    }
    $scope.IsEmailAvailable = function (selleremail, is_valid)
    {
        if (selleremail && is_valid)
        {
            $rootScope.loader = true;
            $http.post(site_settings.api_url + 'is_seller_email_available_WP', {useremail: selleremail})
                    .then(function (response)
                    {
                        $rootScope.loader = false;
                        if (response.data == 1)
                        {
                            $scope.user.not_available_email = true;

                        } else
                        {
                            $scope.user.not_available_email = false;
                        }
                        console.log(response);
//                    $rootScope.$emit("newSeller", response);
//
//                    $rootScope.message = 'User Saved Successfully';
//                    $rootScope.$emit("notification");
//                    $mdDialog.hide();
                    }).catch(function (error)
            {
                $rootScope.loader = false;
                console.log(error)
                $rootScope.message = 'Something Went Wrong';
                $rootScope.$emit("notification");
            });
        }

    }
    if (user)
    {
        $scope.action = 'EDIT';
        $http.post(site_settings.api_url + 'seller/get_edit_seller', {id: user})
                .then(function (response)
                {
                    $scope.user = response.data;
                    $scope.user.password = '********';
                })
                .catch(function (error)
                {
                    console.log(error);
                });

    } else
    {
        $scope.action = 'ADD';
    }


    $scope.saveUser = function ()
    {
        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'seller/add_seller', $scope.user)
                .then(function (response)
                {

                    $rootScope.message = 'Seller Saved Successfully';
                    $rootScope.$emit("notification");
                    $rootScope.$emit("reloadUserTable");
                    $rootScope.loader = false;
                    $mdDialog.hide();
                }).catch(function (error)
        {
            $rootScope.message = error;
            $rootScope.$emit("notification");
        });

    };

    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };
});

app.controller('PickUpLocationAddController', function (seller_id, $parse, $document, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.pick_up_location = {};
    $scope.pick_up_location.select_id = 6;
    $scope.pick_up_location.seller_id = seller_id;
    $scope.pick_up_location.key_text = [{city: '', state: ''}];
    $scope.states = ['CT', 'NY', 'NJ', 'MA', 'FL'];
    $scope.savePickUpLocation = function ()
    {
//        $scope.pick_up_location.key_text = $scope.pick_up_location.value_text;
        $http.post(site_settings.api_url + 'option/saveOption', $scope.pick_up_location)
                .then(function (response)
                {
                    console.log(response);
                    console.log(response.data);
                    $rootScope.$emit("newPickUpLocation", response.data);

                    $rootScope.message = 'Pick Up Location Saved Successfully';
                    $rootScope.$emit("notification");
                    $mdDialog.hide();
                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong';
            $rootScope.$emit("notification");
        });
    };
    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };

});

app.controller('AddSellerAgreementController', function (seller_id, agreement_type, $parse, $document, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.file = null;

    $scope.addAgreement = function ()
    {
        $rootScope.loader = true;

        const data = {seller_id: seller_id, agreement: $scope.file, type: agreement_type};

        var formData = new FormData();

        angular.forEach(data, function (value, key) {
            formData.append(key, value);
        });

        const headers = {'Accept': 'application/json', 'Content-Type': undefined};

        $http.post(site_settings.api_url + 'agreement/save_external_agreement', formData, {transformRequest: angular.identity, headers: headers})
                .then(function (response) {
                    $rootScope.loader = false;

                    if (response.data.status) {
                        $rootScope.message = 'Agreement Added';
                        $rootScope.$emit("notification");
                        $mdDialog.hide();
                    } else {
                        $rootScope.message = 'Something Went Wrong!';
                        $rootScope.$emit("notification");
                    }

                })
                .catch(function (data, status) {
                    $rootScope.message = 'Something Went Wrong';
                    $rootScope.$emit("notification");
                    $rootScope.loader = false;
                });
    };


    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };

}).directive("selectNgFiles", function () {
    return {
        require: "ngModel",
        link: function postLink(scope, elem, attrs, ngModel) {
            elem.on("change", function (e) {
                var files = elem[0].files;
                ngModel.$setViewValue(files[0]);
            });
        }
    }
});


app.controller('SellerAsignAgentController', function (seller_id, $parse, $document, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.agent_id = null;
    $scope.agents = [];

    $scope.getAllAgents = function ()
    {
        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'users/get_all_agents')
                .then(function (response)
                {
                    $rootScope.loader = false;
                    $scope.agents = response.data;
                })
                .catch(function (error)
                {
                    $rootScope.loader = false;
                });
    };

    $scope.getAllAgents();

    $scope.assignAgent = function () {
        $rootScope.loader = true;

        var data = {
            seller_id: seller_id,
            agent_id: $scope.agent_id
        };

        $http.post(site_settings.api_url + 'seller/assign_agent', data)
                .then(function (response)
                {
                    $rootScope.loader = false;
                    $mdDialog.hide();
                })
                .catch(function (error)
                {
                    $rootScope.loader = false;
                });
    };

    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };

});
