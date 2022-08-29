"use strict";

var app = angular.module('ng-app');
app.controller('DashBoardController', function ($scope, $mdDialog, $document, $rootScope, $auth, $http, msNavigationService, site_settings)
{
    $scope.authuser = $auth.getProfile().$$state.value;
//    console.log($scope.user);

    $scope.site_settings = site_settings;
//    $scope.folded = true;

    $scope.hideDashboardItems = false;

    $auth.getProfile().then(function (profile) {
        for (var i = 0; i < profile.roles.length; i++) {
            if (profile.roles[i].id == 3 || profile.roles[i].id == 5 || profile.roles[i].id == 6 || profile.roles[i].id == 7) {
                $scope.hideDashboardItems = true;
            }
        }
    });

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

    $rootScope.goBack = function ()
    {
        history.back();
    };



    $scope.msScrollOptions = {
        suppressScrollX: true
    };

    $scope.navigation_menu = function ()
    {
        msNavigationService.deleteItem('dashboard');
        msNavigationService.deleteItem('users');
        msNavigationService.deleteItem('productapproved');
        msNavigationService.deleteItem('product_for_production');
        msNavigationService.deleteItem('proposal_for_production');
        msNavigationService.deleteItem('products');
        msNavigationService.deleteItem('pricing');
        msNavigationService.deleteItem('awaiting_contract');
        msNavigationService.deleteItem('copyright');
        msNavigationService.deleteItem('sellers');
        msNavigationService.deleteItem('proposals');
        msNavigationService.deleteItem('product_quotations');
        msNavigationService.deleteItem('product_final');
        msNavigationService.deleteItem('archived');
        msNavigationService.deleteItem('sync_products');
        msNavigationService.deleteItem('consignment_report');
        msNavigationService.deleteItem('training_videos');
        msNavigationService.deleteItem('schedule');
        msNavigationService.deleteItem('product_scheduling');
        msNavigationService.deleteItem('email_template');

        msNavigationService.deleteItem('settings');
        msNavigationService.deleteItem('permissions');
        msNavigationService.deleteItem('reporting');
        msNavigationService.deleteItem('notification_mail');
        msNavigationService.deleteItem('email_send_record');
        msNavigationService.deleteItem('assigned_agents');
        msNavigationService.deleteItem('agents_logs');
        msNavigationService.deleteItem('agents_logs_approved');
        msNavigationService.deleteItem('agents_logs_archive');
        msNavigationService.deleteItem('reject_to_auction');

        msNavigationService.saveItem('dashboard', {
            title: 'Dashboard',
            icon: 'icon-tile-four',
            class: 'navigation-dashboards',
            weight: 1,
            state: 'dashboard',
        });
        $auth.hasAnyPermission(['user.add', 'user.edit', 'user.view', 'user.delete'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {
                        msNavigationService.saveItem('users', {
                            title: 'User',
                            icon: 'icon-account',
                            class: 'navigation-users',
                            weight: 2,
                        });
                        msNavigationService.saveItem('users.users', {
                            title: 'Users',
                            icon: 'icon-people',
                            class: 'navigation-users',
                            weight: 1,
                            state: 'users',
                        });
                        msNavigationService.saveItem('users.sellers', {
                            title: 'Sellers',
                            icon: 'icon-people',
                            class: 'navigation-users',
                            weight: 2,
                            state: 'sellers',
                        });
                    } else
                    {
                        msNavigationService.deleteItem('users');
                    }
                });

        $auth.hasAnyPermission(['product.add', 'product.edit', 'product.view', 'product.delete'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {
                        msNavigationService.saveItem('products', {
                            title: 'Products for Review',
                            icon: 'icon-layers',
                            class: 'navigation-users',
                            weight: 3,
                            state: 'sellerproduct',
                            stateParams: {'name': 'product'}
                        });
                    } else
                    {
                        msNavigationService.deleteItem('products');
                    }
                });


        $auth.hasAnyPermission(['product.add', 'product.edit', 'product.view', 'product.delete', 'product.pricer'])
                .then(function (hasPermission)
                {
                    if (hasPermission)
                    {
                        msNavigationService.saveItem('awaiting_contract', {
                            title: 'Awaiting Contract',
                            icon: 'icon-layers',
                            class: 'navigation-users',
                            weight: 4,
                            state: 'sellerproduct',
                            stateParams: {'name': 'awaiting_contract'}
                        });
                    } else
                    {
                        msNavigationService.deleteItem('awaiting_contract');
                    }
                });


        $auth.hasAnyPermission(['production.add', 'production.edit', 'production.view', 'production.delete', 'product.pricer'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {
//                        msNavigationService.saveItem('proposals', {
//                            title: 'Proposals',
//                            icon: 'icon-layers',
//                            class: 'navigation-users',
//                            weight: 5,
//                        });
                        msNavigationService.saveItem('proposal_for_production', {
//                            title: 'Proposal/For Production',
                            title: 'For Production',
                            icon: 'icon-layers',
                            class: 'navigation-users',
                            weight: 5,
                            state: 'sellerproduct',
                            stateParams: {'name': 'proposal_for_production'}
                        });
                    } else
                    {
//                        msNavigationService.deleteItem('proposals');
                        msNavigationService.deleteItem('proposal_for_production');
//                        msNavigationService.deleteItem('product_final');
                    }
                });
        $auth.hasAnyPermission(['product.add', 'product.edit', 'product.view', 'product.delete', 'product.pricer'])
//        $auth.hasAnyPermission(['user.add'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {
                        msNavigationService.saveItem('pricing', {
                            title: 'Pricing',
                            icon: 'icon-layers',
                            class: 'navigation-users',
                            weight: 6,
                            state: 'sellerproduct',
                            stateParams: {'name': 'product_for_pricing'}
                        });
                    } else
                    {
                        msNavigationService.deleteItem('pricing');
                    }
                });

//        $auth.hasAnyPermission(['proposal.add', 'proposal.edit', 'proposal.view', 'proposal.delete'])
//                .then(function (hasPermission)
//                {
//
//                    if (hasPermission)
//                    {
//                        msNavigationService.saveItem('product_quotations', {
//                            title: 'Proposal',
//                            icon: 'icon-layers',
//                            class: 'navigation-users',
//                            weight: 5,
//                            state: 'sellerproduct',
//                            stateParams: {'name': 'proposal'}
//                        });
//                    } else
//                    {
//                        msNavigationService.deleteItem('product_quotations');
//                    }
//                });
//        $auth.hasAnyPermission(['production.add', 'production.edit', 'production.view', 'production.delete'])
//                .then(function (hasPermission)
//                {
//
//                    if (hasPermission)
//                    {
//
//                        msNavigationService.saveItem('product_for_production', {
//                            title: 'For Production',
//                            icon: 'icon-layers',
//                            class: 'navigation-users',
//                            weight: 7,
//                            state: 'sellerproduct',
//                            stateParams: {'name': 'product_for_production'}
//                        });
//                    } else
//                    {
//                        msNavigationService.deleteItem('product_for_production');
//                    }
//                });

        for (var i = 0; i < $scope.authuser.roles.length; i++) {
            if ($scope.authuser.roles[i].id == 1 || $scope.authuser.roles[i].id == 2 || $scope.authuser.roles[i].id == 5) {
                $auth.hasAnyPermission(['copyright.add', 'copyright.edit', 'copyright.view', 'copyright.delete'])
                        .then(function (hasPermission)
                        {

                            if (hasPermission)
                            {

                                msNavigationService.saveItem('copyright', {
                                    title: 'Copywriter',
                                    icon: 'icon-layers',
                                    class: 'navigation-users',
                                    weight: 7,
                                    state: 'sellerproduct',
                                    stateParams: {'name': 'copyright'}
                                });
                            } else
                            {
                                msNavigationService.deleteItem('copyright');
                            }
                        });
            }

            if ($scope.authuser.roles[i].id == 6) {
                $auth.hasAnyPermission(['product.pricer'])
                        .then(function (hasPermission)
                        {
                            if (hasPermission)
                            {
                                msNavigationService.saveItem('pricing', {
                                    title: 'Pricing',
                                    icon: 'icon-layers',
                                    class: 'navigation-users',
                                    weight: 6,
                                    state: 'sellerproduct',
                                    stateParams: {'name': 'product_for_only_pricing'}
                                });
                            } else
                            {
                                msNavigationService.deleteItem('pricing');
                            }
                        });
            }

            if ($scope.authuser.roles[i].id == 7) {
                $auth.hasPermissions(['copyright.add', 'copyright.edit', 'copyright.view', 'copyright.delete', 'product.pricer'])
                        .then(function (hasPermission)
                        {
                            if (hasPermission)
                            {
                                msNavigationService.deleteItem('pricing');
                                msNavigationService.deleteItem('copyright');

                                msNavigationService.saveItem('pricing_with_copyright', {
                                    title: 'Copywriter/Pricing',
                                    icon: 'icon-layers',
                                    class: 'navigation-users',
                                    weight: 6,
                                    state: 'sellerproduct',
                                    stateParams: {'name': 'product_for_only_pricing'}
                                });
                            } else
                            {
                                msNavigationService.deleteItem('pricing_with_copyright');
                            }
                        });
            }

        }


        $auth.hasAnyPermission(['approval_product.add', 'approval_product.edit', 'approval_product.view', 'approval_product.delete'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {

                        msNavigationService.saveItem('product_final', {
                            title: 'Approval',
                            icon: 'icon-layers',
                            class: 'navigation-users',
                            weight: 8,
                            state: 'sellerproduct',
                            stateParams: {'name': 'approvedproducts'}
                        });

                    } else
                    {
//                        msNavigationService.deleteItem('proposals');
//                        msNavigationService.deleteItem('product_quotations');
                        msNavigationService.deleteItem('product_final');
                    }
                });
           $auth.hasAnyPermission(['sync_product'])
                .then(function (hasPermission)
                {
                     
                    if (hasPermission)
                    {
                        
                        msNavigationService.saveItem('sync_products', {
                            title: 'Synced Products',
                            icon: 'icon-layers',
                            class: 'navigation-users',
                            weight: 8,
                            state: 'syncproducts',
                            stateParams: {'name': 'sync'}
                        });

                    } else
                    {
//                        msNavigationService.deleteItem('proposals');
//                        msNavigationService.deleteItem('product_quotations');
                        msNavigationService.deleteItem('sync_products');
                    }
                });
        $auth.hasAnyPermission(['consignment_report'])
            .then(function (hasPermission)
            {

                if (hasPermission)
                {

                    msNavigationService.saveItem('consignment_report', {
                        title: 'Consignment Report',
                        icon: 'icon-layers',
                        class: 'navigation-users',
                        weight: 8,
                        state: 'consignment_report',
                        stateParams: {'name': 'consignment_report'}
                    });

                } else
                {
//                        msNavigationService.deleteItem('proposals');
//                        msNavigationService.deleteItem('product_quotations');
                    msNavigationService.deleteItem('sync_products');
                }
            });

              
//        $auth.hasAnyPermission(['product_scheduling.show'])
//                .then(function (hasPermission)
//                {
//                    if (hasPermission)
//                    {
//                        msNavigationService.saveItem('product_scheduling', {
//                            title: 'Scheduling',
//                            icon: 'icon-calendar-clock',
//                            class: 'navigation-users',
//                            weight: 8,
//                            path_to_external: 'https://thelocalvault.simplybook.me/v2/'
//                        });
//                    } else
//                    {
//                        msNavigationService.deleteItem('product_scheduling');
//                    }
//
//                });
        $auth.hasAnyPermission(['archived'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {

                        msNavigationService.saveItem('archived', {
                            title: 'Archived Products',
                            icon: 'icon-trash',
                            class: 'navigation-users',
                            weight: 9,
                            state: 'archivedseller',
                        });

                    } else
                    {
                        msNavigationService.deleteItem('archived');
                    }
                });

        // reject to auction
        // todo change permission
        $auth.hasAnyPermission(['reject_to_auction.view'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {
                        msNavigationService.saveItem('reject_to_auction', {
                            title: 'Reject To Auction',
                            icon: 'fa fa-gavel',
                            class: 'navigation-users',
                            weight: 9,
                            state: 'reject_to_auction_seller',
                        });

                    } else
                    {
                        msNavigationService.deleteItem('reject_to_auction');
                    }
                });

//        $auth.hasAnyPermission(['schedule.add'])
//                .then(function (hasPermission) {
//
//                    if (hasPermission)
//                    {
//                        msNavigationService.saveItem('schedule', {
//                            title: 'Schedule',
//                            icon: 'icon-clock',
//                            class: 'navigation-users',
//                            weight: 7,
//                            state: 'schedule',
//                        });
//                    } else
//                    {
//                        msNavigationService.deleteItem('schedule');
//                    }
//                });



// old permission emailtemplate.add
        $auth.hasAnyPermission(['emailtemplate1.add'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {
                        msNavigationService.saveItem('email_template', {
                            title: 'Email Templates',
                            icon: 'icon-email',
                            class: 'navigation-users',
                            weight: 10,
                        });
                        msNavigationService.saveItem('email_template.pending', {
                            title: 'Product Pending',
                            class: 'navigation-users',
                            weight: 1,
                            state: 'email_template',
                            stateParams: {'id': 1}
                        });
                        msNavigationService.saveItem('email_template.approved', {
                            title: 'Product Approved',
                            class: 'navigation-users',
                            weight: 2,
                            state: 'email_template',
                            stateParams: {'id': 2}
                        });
                        msNavigationService.saveItem('email_template.reject', {
                            title: 'Product Reject',
                            class: 'navigation-users',
                            weight: 3,
                            state: 'email_template',
                            stateParams: {'id': 3}
                        });
                        msNavigationService.saveItem('email_template.more', {
                            title: 'Product More Info',
                            class: 'navigation-users',
                            weight: 4,
                            state: 'email_template',
                            stateParams: {'id': 4}
                        });
//                        msNavigationService.saveItem('email_template.auction_house', {
//                            title: 'Referral to Auction House',
//                            class: 'navigation-users',
//                            weight: 4,
//                            state: 'email_template',
//                            stateParams: {'id': 5}
//                        });
                    } else
                    {
                        msNavigationService.deleteItem('email_template');
                    }
                });



        $auth.hasAnyPermission(['settings.ix'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {
                        msNavigationService.saveItem('reporting', {
                            title: 'Product Report',
                            icon: 'icon-cog',
                            class: 'navigation-users',
                            weight: 12,
                            state: 'product_report'
                        });
//                        msNavigationService.saveItem('storage_proposal_reporting', {
//                            title: 'Storage Proposal Report',
//                            icon: 'icon-cog',
//                            class: 'navigation-users',
//                            weight: 13,
//                            state:'storage_proposal_report'
//                        });
//                        msNavigationService.saveItem('storage_reporting', {
//                            title: 'Storage Report',
//                            icon: 'icon-cog',
//                            class: 'navigation-users',
//                            weight: 14,
//                            state:'storage_report'
//                        });
//                        msNavigationService.saveItem('settings', {
//                            title: 'Export',
//                            icon: 'icon-cog',
//                            class: 'navigation-users',
//                            weight: 8,
//                        });
//                        msNavigationService.saveItem('settings.import', {
//                            title: 'Import',
//                            class: 'navigation-users',
//                            weight: 1,
//                            state: 'import'
//                        });
//                        msNavigationService.saveItem('settings.export', {
//                            title: 'Export',
//                            class: 'navigation-users',
//                            weight: 2,
//                            state: 'export'
//                        });
                    } else
                    {
                        msNavigationService.deleteItem('settings');
                    }
                });

        $auth.hasAnyPermission(['agents_logs'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {
                        msNavigationService.saveItem('agents_logs', {
                            title: 'Agents Log',
                            icon: 'icon-cog',
                            class: 'navigation-users',
                            weight: 11,
                            state: 'agents_logs',
                        });

                        msNavigationService.saveItem('agents_logs_approved', {
                            title: 'Agents Log Approved',
                            icon: 'icon-cog',
                            class: 'navigation-users',
                            weight: 12,
                            state: 'agents_logs_approved',
                        });
                    } else
                    {
                        msNavigationService.deleteItem('agents_logs');
                        msNavigationService.deleteItem('agents_logs_approved');
                    }
                });

        $auth.hasAnyPermission(['agents_logs.archive'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {
                        msNavigationService.saveItem('agents_logs_archive', {
                            title: 'Agents Log Archive',
                            icon: 'icon-cog',
                            class: 'navigation-users',
                            weight: 13,
                            state: 'agents_logs_archive',
                        });
                    } else
                    {
                        msNavigationService.deleteItem('agents_logs_archive');
                    }
                });

        $auth.hasAnyPermission(['assigned_agents'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {
                        msNavigationService.saveItem('assigned_agents', {
                            title: 'Agent Report',
                            icon: 'icon-cog',
                            class: 'navigation-users',
                            weight: 14,
                            state: 'assigned_agents',
                        });
                    } else
                    {
                        msNavigationService.deleteItem('assigned_agents');
                    }
                });

        $auth.hasAnyPermission(['settings.ix'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {
                        msNavigationService.saveItem('storage_proposal_reporting', {
                            title: 'Storage Proposal Report',
                            icon: 'icon-cog',
                            class: 'navigation-users',
                            weight: 15,
                            state: 'storage_proposal_report'
                        });
                        msNavigationService.saveItem('storage_reporting', {
                            title: 'Storage Report',
                            icon: 'icon-cog',
                            class: 'navigation-users',
                            weight: 16,
                            state: 'storage_report'
                        });
                    } else {
                        msNavigationService.deleteItem('storage_proposal_reporting');
                        msNavigationService.deleteItem('storage_reporting');
                    }
                });

        $auth.hasAnyPermission(['permission.add', 'permission.edit', 'permission.view', 'permission.delete'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {
                        msNavigationService.saveItem('permissions', {
                            title: 'Permissions',
                            icon: 'icon-people',
                            class: 'navigation-users',
                            weight: 17,
                            state: 'permissions',
                        });
                    } else
                    {
                        msNavigationService.deleteItem('permissions');
                    }
                });
        $auth.hasAnyPermission(['user.add', 'user.edit', 'user.view', 'user.delete'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {
                        msNavigationService.saveItem('notification_mail', {
                            title: 'Notification',
                            icon: 'icon-people',
                            class: 'navigation-users',
                            weight: 18,
                            state: 'notification_mail',
                        });
                    } else
                    {
                        msNavigationService.deleteItem('notification_mail');
                    }
                });
        $auth.hasAnyPermission(['user.add', 'user.edit', 'user.view', 'user.delete'])
                .then(function (hasPermission)
                {

                    if (hasPermission)
                    {
                        msNavigationService.saveItem('email_send_record', {
                            title: 'Email Record',
                            icon: 'icon-email',
                            class: 'navigation-users',
                            weight: 19,
                            state: 'email_send_record',
                        });
                    } else
                    {
                        msNavigationService.deleteItem('notification_mail');
                    }
                });
        $auth.hasAnyPermission(['training_videos'])
            .then(function (hasPermission)
            {

                if (hasPermission)
                {

                    msNavigationService.saveItem('training_videos', {
                        title: 'Training Videos',
                        icon: 'icon-layers',
                        class: 'navigation-users',
                        weight: 8,
                        state: 'training_videos',
                        stateParams: {'name': 'training_videos'}
                    });

                } else
                {
//                        msNavigationService.deleteItem('proposals');
//                        msNavigationService.deleteItem('product_quotations');
                    msNavigationService.deleteItem('training_videos');
                }
            });
    }


    $rootScope.getPendingProducts = function ()
    {
        $http.get(site_settings.api_url + 'get_all_pending_product_quatation')
                .then(function (response)
                {
                    $rootScope.pending_products = response.data;
                    $rootScope.count = $rootScope.pending_products.length;
                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
        });
    };

    $rootScope.getPendingProducts();

});


app.controller('UserDashboardController', function ($timeout, ngAAToken, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
//dashboard Seller with Products
    $scope.dtInstance = {};
    $scope.user = $auth.getProfile().$$state.value;
    $scope.paramname = 2;
    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
        dataSrc: "data",
        url: site_settings.api_url + 'seller/get_product_in_state_sellers',
        type: "POST",
        headers: {Authorization: 'Bearer ' + ngAAToken.getToken()},
    }).withOption('processing', true) //for show progress bar
            .withOption('serverSide', true) // for server side processing
            .withPaginationType('full_numbers') // for get full pagination options // first / last / prev / next and page numbers
            .withDisplayLength(10) // Page size
            .withOption('aaSorting', [0, 'desc'])
            .withOption('autoWidth', false)
            .withOption('responsive', true)
            .withOption('dom', '<"top"f>rt<"bottom"<"left"<"length"l>><"right"<"info"i><"pagination"p>>>')
            .withDataProp('data')
            .withOption('fnRowCallback',
                    function (nRow, aData, iDisplayIndex, iDisplayIndexFull)
                    {
                        $compile(nRow)($scope);
                    });


    function productsHtml(data, type, full, meta)
    {
        return '<span ui-sref="seller_product_status({id: ' + full.id + '})" class="text-boxed m-0 deep-orange-bg white-fg" style="cursor: pointer">VIEW PRODUCTS</span>';
    }

    function dateRender(data, type, full, meta)
    {
        if (data != null)
        {
            return moment.utc(data.date).local().format('MM/DD/YYYY');
        } else
        {
            return '';
        }
    }

    function statusRender(data, type, full, meta)
    {
        console.log(data);
        if (data == 1)
        {
            return 'In Review';
        } else
        {
            return 'Open';
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

    function reloadData()
    {
        var resetPaging = false;
        $scope.dtInstance.reloadData(callback, resetPaging);
    }

    function callback(json)
    {

    }

    $scope.dtColumns = [
        DTColumnBuilder.newColumn('firstname').withTitle('First Name').renderWith(nullHtml),
        DTColumnBuilder.newColumn('lastname').withTitle('Last Name').renderWith(nullHtml),
        DTColumnBuilder.newColumn('email').withTitle('Email'),
        DTColumnBuilder.newColumn('product_created_at').withTitle('Date of request').notSortable().renderWith(dateRender),
        DTColumnBuilder.newColumn('wp_seller_id').withTitle('Sales Request #'),
        DTColumnBuilder.newColumn('is_touched').withTitle('Status').notSortable().renderWith(statusRender),
//        DTColumnBuilder.newColumn('displayname').withTitle('Dispaly Name'),
        DTColumnBuilder.newColumn(null).withTitle('Action').notSortable()
                .renderWith(productsHtml),
    ];
    $scope.openSellerAddDialog = function (user)
    {
        $mdDialog.show({
            controller: 'SellerAddController',
            templateUrl: 'app/modules/seller/views/seller_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                user: user
            }
        });
    }

    $rootScope.$on("reloadUserTable", function (event, args)
    {
        reloadData();
    });

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

                    })
                    .catch(function (error)
                    {
                        $rootScope.message = 'Something Went Wrong';
                        $rootScope.$emit("notification");
                    });
        }, function ()
        {

        });
    };

    $scope.product_for_review_stage_count = 0;
    $scope.awaiting_contract_stage_count = 0;
    $scope.for_production_stage_count = 0;
    $scope.pricing_stage_count = 0;
    $scope.copywriter_stage_count = 0;
    $scope.pricing_proposal_sent_count = 0;
    $scope.pricing_proposal_approved_count = 0;
    $scope.storage_propsal_report_count = 0;

    $scope.getDashboardCounts = function () {
        $http.post(site_settings.api_url + 'dashboard/count')
                .then(function (response)
                {
                    $scope.awaiting_contract_stage_count = response.data.awaiting_contract_stage_count;
                    $scope.product_for_review_stage_count = response.data.product_for_review_stage_count;
                    $scope.for_production_stage_count = response.data.for_production_stage_count;
                    $scope.pricing_stage_count = response.data.pricing_stage_count;
                    $scope.copywriter_stage_count = response.data.pricing_stage_count;
                    $scope.pricing_proposal_sent_count = response.data.pricing_proposal_sent_count;
                    $scope.pricing_proposal_approved_count = response.data.pricing_proposal_approved_count;
                })
                .catch(function (error)
                {
                    console.log(error);
                });
    };

    $scope.getDashboardCounts();
});



app.controller('SellerProductStatusController', function ($timeout, ngAAToken, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{

    $scope.seller = {};
    $scope.seller.firstname = 'Default';
    $scope.seller.lastname = 'Default';
    $scope.getSellerById = function (id)
    {
        $http.post(site_settings.api_url + 'seller/get_edit_seller', {id: id})
                .then(function (response)
                {
                    $scope.seller = response.data;
//                    $scope.user.password = '********';
//                    console.log($scope.user);
                })
                .catch(function (error)
                {

                });
    };
    $scope.getSellerById($stateParams.id);


    $scope.authuser = $auth.getProfile().$$state.value;
    $scope.dtInstance = {};
    $scope.users = [];
    $scope.select = [];
    $scope.products = [];
    $scope.productStatus = [];
    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
        dataSrc: "data",
        url: site_settings.api_url + 'product/get_all_products_with_status',
        type: "POST",
        headers: {Authorization: 'Bearer ' + ngAAToken.getToken()},
        data: {id: $stateParams.id}
    }).withOption('processing', true) //for show progress bar
            .withOption('serverSide', true) // for server side processing
            .withPaginationType('full_numbers') // for get full pagination options // first / last / prev / next and page numbers
            .withDisplayLength(10) // Page size
            .withOption('aaSorting', [0, 'desc'])
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
        action_btn += '<span ng-click="openProductViewDialog(' + data.id + ');" class="text-boxed m-0 deep-blue-bg white-fg" style="cursor: pointer;margin-left:5px; background-color: #1eaa36 !important;">VIEW</span>';
        return action_btn;
    }

    $scope.dtInstance = {};


    function reloadData()
    {

        var resetPaging = false;
        $scope.dtInstance.reloadData(callback, resetPaging);
    }

    $scope.getProductStatus = function ()
    {
        $http.get(site_settings.api_url + 'get_all_product_status')
                .then(function (response)
                {
                    $scope.product_status = response.data;
                })
                .catch(function (error)
                {
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                });
    };

    $scope.getProductStatus();

    function callback(json)
    {

    }

    $scope.dtColumns = [
//        DTColumnBuilder.newColumn('profile_image').withTitle('Image').notSortable().renderWith(profileImage),
        DTColumnBuilder.newColumn(null).withTitle('').notVisible(),
        DTColumnBuilder.newColumn('name').withTitle('Product Name'),
//        DTColumnBuilder.newColumn('sell_name').withTitle('Sell Name'),
        DTColumnBuilder.newColumn('created_at').withTitle('Date').renderWith(dateRender),
//        DTColumnBuilder.newColumn('aging').withTitle('Aging').renderWith(agingRender),
        DTColumnBuilder.newColumn(null).withTitle('Product In').renderWith(productFromHtml).notSortable(),
//        DTColumnBuilder.newColumn('status_id').withTitle('Status').renderWith(statusHtml),
//        DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable()
//                .renderWith(actionsHtml),
    ];

    $scope.changeProductStatus = function (status, product)
    {
        $scope.productStatus.push({'product_status_id': status, 'product_id': product});
    };

    function agingRender(data, type, full, meta)
    {
        if (data)
        {
            return data;
        } else
        {
            return '---';
        }
    }
    function dateRender(data, type, full, meta)
    {
        if (data != null)
        {
            return moment.utc(data.date).local().format('MM/DD/YYYY')
        } else
        {
            return '---';
        }
    }
    function statusHtml(data, type, full, meta)
    {
        $scope.select[full.id] = false;
        $scope.products[full.id] = full;
        var action_btn = '';

        action_btn += '<md-checkbox ng-model="select[' + full.id + ']" style="margin-top: 10px;" layout="row" layout-xs="column">';
        action_btn += 'Re-open';
        action_btn += '</md-checkbox>';

        return action_btn;
    }

    function productFromHtml(data, type, full, meta)
    {
        if (full.status_id == 6)
        {
            return 'Product for Review';
        }
        if (full.status_id == 31)
        {
//            return 'Archived From (Product for review)';
            return 'Archived';

        } else if (full.is_send_mail == 0)
        {
            if (full.is_archived == 1)
            {
//                return 'Archived From (Proposal)';
                return 'Archived';
            } else
            {
//                return 'Proposal';
//                return 'Proposal / For Production';
                return 'For Production';
            }
        } else if (full.is_send_mail == 1 && full.is_product_for_production == 0)
        {
            return 'For Production';
//            return 'Proposal / For Production';
        } else if (full.is_send_mail == 1 && full.is_product_for_production == 1 && full.is_copyright == 0)
        {
//            if (full.is_archived == 1)
//            {
//                return 'Archived From (Copyright)';
//            } else
//            {
            return 'Copywriter';
//            }
        } else if (full.is_send_mail == 1 && full.is_product_for_production == 1 && full.is_copyright == 1 && full.status_quot_id == 17)
        {
            if (full.is_archived == 1)
            {
                return 'Archived From (For Production)';
            } else
            {
                return 'Approval';
            }
        } else if (full.is_send_mail == 1 && full.is_product_for_production == 1 && full.is_copyright == 1 && full.status_quot_id == 18)
        {
            if (full.is_archived == 1)
            {
                return 'Archived From (Full Approved)';
            } else
            {
                return 'Full Approved';
            }
        } else
        {
            return 'Default';
        }
    }

    $scope.reopen = function ()
    {
        var temp = [];

        for (var i in $scope.select)
        {
            if ($scope.select[i])
            {
                temp.push($scope.products[i]);
            }
        }

        if (temp.length)
        {
            $http.post(site_settings.api_url + 'product/reopen_product', temp)
                    .then(function (response)
                    {

                        $rootScope.message = 'Product status has been successfully saved.';

                        $rootScope.$emit("notification");
                        $rootScope.$emit("reloadProductTable");
                    })
                    .catch(function (error)
                    {
                        $rootScope.message = 'Something Went Wrong.';
                        $rootScope.$emit("notification");
                    });
        } else
        {
            $rootScope.message = 'Please select at least one product.';
            $rootScope.$emit("notification");
        }
    }

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
    }

    $scope.openProductEditDialog = function (product)
    {
        $mdDialog.show({
            controller: 'ProductEditController',
            templateUrl: 'app/modules/product/views/product_edit.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                product: product
            }
        });
    }

    $scope.openProductViewDialog = function (product)
    {
        $mdDialog.show({
            controller: 'ProductViewController',
            templateUrl: 'app/modules/product/views/product_view.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                product: product
            }
        });
    }

    $rootScope.$on("reloadProductTable", function (event, args)
    {

        reloadData();
    });
    $rootScope.$on("reloadProductTable1", function (event, args)
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
                .title('Would you like to delete this Product?')
                .ok('Yes')
                .cancel('No');

        $mdDialog.show(confirm).then(function ()
        {
            $http.post(site_settings.api_url + 'product/delete_product', {id: id})
                    .then(function (response)
                    {

                        $rootScope.message = 'Product Deleted Successfully';
                        $rootScope.$emit("notification");
                        $rootScope.$emit("reloadProductTable");

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