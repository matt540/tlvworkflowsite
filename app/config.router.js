'use strict';
app.run(function ($rootScope, $window, $state, $stateParams, $http, $location, site_settings, $mdDialog) {
    $rootScope.$state = $state;
    $rootScope.$stateParams = $stateParams;
    $rootScope.$site_settings = site_settings;
    $rootScope.openAuthUserEditDialog = function () {
        $mdDialog.show({
            controller: 'UserAuthUpdateController',
            templateUrl: 'app/modules/users/views/user_auth_update.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            resolve: {
                dep: ['$ocLazyLoad',
                    function ($ocLazyLoad) {
                        return $ocLazyLoad.load(['app/modules/users/UsersController.js']);
                    }]
            }
        });
    }

    $rootScope.$on("$includeContentLoaded", function (event, templateName) {

        if (templateName == 'app/modules/order/views/order_third_tab.html') {
            $rootScope.loaderSecondTab = false;
        }
    });
//            $rootScope.$on('$stateChangeError',
//                    function (event, toState, toParams, fromState, fromParams, error) {
//                        console.log(error);
//                    });

    $rootScope
        .$on('$stateChangeStart',
            function (event, toState, toParams, fromState, fromParams) {

                $rootScope.current_state = toState.name;
            });
    $rootScope
        .$on('$stateChangeSuccess',
            function (event, toState, toParams, fromState, fromParams) {

                angular.element('#splash-screen').hide();
            });
})

    .config(function ($stateProvider, $urlRouterProvider, $locationProvider, $authProvider, site_settings, $mdThemingProvider, $mdIconProvider, $translateProvider, $translatePartialLoaderProvider, $mdDateLocaleProvider) {
        $locationProvider.html5Mode(true);
        $urlRouterProvider.otherwise(function ($injector) {
            //console.log($injector);
            var $state = $injector.get("$state");
            $state.go('dashboard');
        });
        $mdIconProvider.iconSet('social', 'assets/angular-material-assets/img/icons/sets/social-icons.svg', 24)
            .defaultIconSet('assets/angular-material-assets/img/icons/sets/core-icons.svg', 24);
        // angular-translate configuration
        $mdDateLocaleProvider.formatDate = function (date) {
            return moment(date).format('YYYY-MM-DD');
        };
        $mdDateLocaleProvider.parseDate = function (dateString) {
            var m = moment(dateString, 'YYYY-MM-DD', true);
            return m.isValid() ? m.toDate() : new Date(NaN);
        }
        $translateProvider.useLoader('$translatePartialLoader', {
            urlTemplate: '{part}/{lang}.json'
        });
        $translateProvider.preferredLanguage('en');
        $translateProvider.useSanitizeValueStrategy('sanitize');
        $translatePartialLoaderProvider.addPart('assets/translations');
//                    
        $authProvider.signinUrl = site_settings.api_url + 'authenticate';
        $authProvider.signinState = 'login';
        $authProvider.signinRoute = '/login';
        $authProvider.signinTemplateUrl = 'app/modules/authenticate/views/login.html';
        $authProvider.afterSigninRedirectTo = 'dashboard';
        $authProvider.afterSignoutRedirectTo = 'login';
        var timeStamp = Math.floor(Date.now());
        $stateProvider
            .state('index', {
                abstract: true,
                //url: '/',
                views: {
                    '@': {
                        templateUrl: 'app/modules/general/views/dashboard.html',
                        controller: 'DashBoardController'
                    },
                    'toolbar@index': {templateUrl: 'app/modules/general/views/toolbar.html',},
                    'navigation@index': {templateUrl: 'app/modules/general/views/navigation.html',},
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/general/DashBoardController.js?' + timeStamp]);
                        }]
                }
            })
            .state('home', {
                url: '/home',
                templateUrl: 'app/modules/authenticate/views/home.html',
                controller: 'HomeController',
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/authenticate/HomeController.js']);
                        }]
                },
                data:
                    {
                        authenticated: false
                    }
            })
            .state('notification_mail', {
                parent: 'index',
                url: '/notification_mail',
                views: {
                    'content': {
                        templateUrl: 'app/modules/notification_mail/views/notification_mail.html',
                        controller: 'NotificationMailController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/notification_mail/NotificationMailController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
                        permits: {
                            withAny: ['user.add', 'user.edit', 'user.view', 'user.delete']
                        }
                    }
            })
            .state('api', {
                url: '/api1/room',
//                        templateUrl: 'app/modules/authenticate/views/home.html',
                controller: 'ApiController',
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/api/ApiController.js']);
                        }]
                },
                data:
                    {
                        authenticated: false
                    }
            })
            .state('signup', {
                url: '/signup',
                templateUrl: 'app/modules/authenticate/views/signup.html',
                controller: 'SignupController',
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/authenticate/AuthController.js']);
                        }]
                },
                data:
                    {
                        authenticated: false
                    }
            })
            .state('dashboard', {
                parent: 'index',
                url: '/dashboard',
                views: {
                    'content': {
                        templateUrl: 'app/modules/general/views/userdashboard.html',
                        controller: 'UserDashboardController'
                    },
                },
                data:
                    {
                        authenticated: true
                    },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/product/ProductController.js', 'app/modules/general/DashBoardController.js']);
                        }]
                },
            })
            .state('products', {
                parent: 'index',
                url: '/products/:id',
                views: {
                    'content': {
                        templateUrl: 'app/modules/product/views/products.html',
                        controller: 'ProductController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/product/ProductController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
                        permits: {
                            withAny: ['product.add', 'product.edit', 'product.view', 'product.delete']
                        }

                    }
            })
            .state('sellers', {
                parent: 'index',
                url: '/sellers',
                views: {
                    'content': {
                        templateUrl: 'app/modules/seller/views/seller.html',
                        controller: 'SellerController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/seller/SellerController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
                        permits: {
                            withAny: ['user.add', 'user.edit', 'user.view', 'user.delete']
                        }
                    }
            })
            .state('sellerproduct', {
                parent: 'index',
                url: '/seller/:name',
                views: {
                    'content': {
                        templateUrl: 'app/modules/seller/views/seller_product.html',
                        controller: 'SellerProductController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/seller/SellerController.js', 'app/modules/product/ProductController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true
                    }
            })
            .state('product_quotations', {
                parent: 'index',
                url: '/product_quotations/:id',
                views: {
                    'content': {
                        templateUrl: 'app/modules/product_quotation/views/product_quotations.html',
                        controller: 'ProductQuotationController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/product_quotation/ProductQuotationController.js', 'app/modules/product/ProductController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true
                    }
            })
            .state('product_final', {
                parent: 'index',
                url: '/product_final/:id',
                views: {
                    'content': {
                        templateUrl: 'app/modules/product_final/views/product_final.html',
                        controller: 'ProductFinalController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/product_final/ProductFinalController.js', 'app/modules/product/ProductController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
                        permits: {
                            withAny: ['approval_product.add', 'approval_product.edit', 'approval_product.view', 'approval_product.delete']
                        }
                    }
            })
            .state('product_for_production', {
                parent: 'index',
                url: '/product_for_production/:id',
                views: {
                    'content': {
                        templateUrl: 'app/modules/product_for_production/views/product_for_production.html',
                        controller: 'ProductForProductionController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/product_for_production/ProductForProductionController.js', 'app/modules/product/ProductController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
                        permits: {
                            withAny: ['production.add', 'production.edit', 'production.view', 'production.delete']
                        }
                    }
            })
            .state('copyright', {
                parent: 'index',
                url: '/copyright/:id',
                views: {
                    'content': {
                        templateUrl: 'app/modules/product_final_copyright/views/copyright.html',
                        controller: 'CopyrightController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/product_final_copyright/CopyrightController.js', 'app/modules/product/ProductController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
                        permits: {
                            withAny: ['copyright.add', 'copyright.edit', 'copyright.view', 'copyright.delete']
                        }
                    }
            })
            .state('productapproved', {
                parent: 'index',
                url: '/products/approved',
                views: {
                    'content': {
                        templateUrl: 'app/modules/product/views/products_approve.html',
                        controller: 'ProductApprovedController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/product/ProductApprovedController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true
                    }
            })
            .state('syncproducts', {
                parent: 'index',
                url: '/sync-products',
                views: {
                    'content': {
                        templateUrl: 'app/modules/sync_products/views/list.html',
                        controller: 'SyncProductListController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/sync_products/SyncProductListController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true
                    }
            })
            .state('sync_product_order', {
                parent: 'index',
                url: '/sync_products_order/:id',
                views: {
                    'content': {
                        templateUrl: 'app/modules/sync_products/views/sync_product_order.html',
                        controller: 'SyncProductOrderController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/sync_products/SyncProductListController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
//                                    permits: {
//                                        withAny: ['production.add', 'production.edit', 'production.view', 'production.delete']
//                                    }
                }
            })
            .state('consignment_report', {
                parent: 'index',
                url: '/consignment-report',
                views: {
                    'content': {
                        templateUrl: 'app/modules/consignment_report/views/list.html',
                        controller: 'ConsignmentReportController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/consignment_report/ConsignmentReportController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true
                    }
            })

            .state('archivedseller', {
                parent: 'index',
                url: '/archived',
                views: {
                    'content': {
                        templateUrl: 'app/modules/archived/views/seller.html',
                        controller: 'ArchivedSellerController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/archived/ArchivedController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
                        permits: {
                            withAny: ['archived']
                        }
                    }
            })
            .state('archived', {
                parent: 'index',
                url: '/archived/products/:id',
                views: {
                    'content': {
                        templateUrl: 'app/modules/archived/views/archived.html',
                        controller: 'ArchivedController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/archived/ArchivedController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
                        permits: {
                            withAny: ['archived']
                        }
                    }
            })
            .state('product_report', {
                parent: 'index',
                url: '/product_report',
                views: {
                    'content': {
                        templateUrl: 'app/modules/product_report/views/product_report.html',
                        controller: 'ProductReportController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/product_report/ProductReportController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
//                                    permits: {
//                                        withAny: ['archived']
//                                    }
                }
            })
            .state('storage_proposal_report', {
                parent: 'index',
                url: '/storage_proposal_report',
                views: {
                    'content': {
                        templateUrl: 'app/modules/storage_agreement/views/storage_report.html',
                        controller: 'StorageAgreementReportController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/storage_agreement/StorageAgreementController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
//                                    permits: {
//                                        withAny: ['archived']
//                                    }
                }
            })
            .state('storage_report', {
                parent: 'index',
                url: '/storage_report',
                views: {
                    'content': {
                        templateUrl: 'app/modules/storage_report/views/storage_report.html',
                        controller: 'StorageReportController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/storage_report/StorageReportController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
//                                    permits: {
//                                        withAny: ['archived']
//                                    }
                }
            })
            .state('seller_product_status', {
                parent: 'index',
                url: '/seller_product_status/seller/:id',
                views: {
                    'content': {
                        templateUrl: 'app/modules/seller_product_status/views/products.html',
                        controller: 'SellerProductStatusController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/general/DashBoardController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true
                    }
            })
            .state('email_send_record', {
                parent: 'index',
                url: '/email_send_record',
                views: {
                    'content': {
                        templateUrl: 'app/modules/email_send_record/views/email_send_record.html',
                        controller: 'EmailSendRecordController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/email_send_record/EmailSendRecordController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true
                    }
            })
            .state('training_videos', {
                parent: 'index',
                url: '/training_videos',
                views: {
                    'content': {
                        templateUrl: 'app/modules/training_videos/views/training_videos.html',
                        controller: 'TrainingVideosController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/training_videos/TrainingVideosController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true
                    }
            })
            .state('schedule', {
                parent: 'index',
                url: '/schedules',
                views: {
                    'content': {
                        templateUrl: 'app/modules/schedule/views/schedule.html',
                        controller: 'ScheduleController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/schedule/ScheduleController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true
                    }
            })
            .state('users', {
                parent: 'index',
                url: '/users',
                views: {
                    'content': {
                        templateUrl: 'app/modules/users/views/users.html',
                        controller: 'UsersController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/users/UsersController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
                        permits: {
                            withAny: ['user.add', 'user.edit', 'user.view', 'user.delete']
                        }
                    }
            })
            .state('email_template', {
                parent: 'index',
                url: '/email/template/:id',
                views: {
                    'content': {
                        templateUrl: 'app/modules/email_template/views/email_template.html',
                        controller: 'EmailTemplateController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/email_template/EmailTemplateController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
                        permits: {
                            withAny: ['emailtemplate.add']
                        }
                    }
            })
            .state('export', {
                parent: 'index',
                url: '/export/products',
                views: {
                    'content': {
                        templateUrl: 'app/modules/settings/views/export_products.html',
                        controller: 'ExportController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/settings/ExportController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
                        permits: {
                            withAny: ['settings.ix']
                        }
                    }
            })
            .state('forgot-password', {
                url: '/forgot/password',
                templateUrl: 'app/modules/authenticate/views/forgot-password.html',
                controller: 'AuthController',
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/authenticate/AuthController.js']);
                        }]
                },
                data:
                    {
                        authenticated: false
                    }
            })
            .state('password_reset', {
                url: '/password/reset/:token',
                templateUrl: 'app/modules/authenticate/views/reset-password.html',
                controller: 'AuthController',
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/authenticate/AuthController.js']);
                        }]
                },
                data:
                    {
                        authenticated: false
                    }
            })
            .state('permissions', {
                parent: 'index',
                url: '/permissions',
                views: {
                    'content': {
                        templateUrl: 'app/modules/settings/views/permissions.html',
                        controller: 'PermsissionsController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/settings/PermsissionsController.js']);
                        }]
                },
                data:
                    {
                        permits: {
                            withAny: ['permission.add']
                        }
                    }
            })
            .state('permissionList', {
                parent: 'index',
                url: '/permissionList',
                views: {
                    'content': {
                        templateUrl: 'app/modules/settings/views/permissions_list.html',
                        controller: 'PermsissionsListController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/settings/PermsissionsListController.js']);
                        }]
                },
                data:
                    {
                        permits: {
                            withAny: ['permission.list', 'permission.add', 'permission.delete', 'permission.edit']
                        }
                    }
            })

            .state('proposal_for_production', {
                parent: 'index',
                url: '/proposal_for_production/:id',
                views: {
                    'content': {
                        templateUrl: 'app/modules/proposal_for_production/views/proposal_for_production.html',
                        controller: 'ProposalForProductionController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/proposal_for_production/ProposalForProductionController.js', 'app/modules/product/ProductController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
//                                    permits: {
//                                        withAny: ['production.add', 'production.edit', 'production.view', 'production.delete']
//                                    }
                }
            })
            .state('product_for_pricing', {
                parent: 'index',
                url: '/product_for_pricing/:id',
                views: {
                    'content': {
                        templateUrl: 'app/modules/product_for_pricing/views/product_for_pricing.html',
                        controller: 'ProductForPricingController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/product_for_pricing/ProductForPricingController.js', 'app/modules/product/ProductController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
//                                    permits: {
//                                        withAny: ['production.add', 'production.edit', 'production.view', 'production.delete']
//                                    }
                }
            })
            .state('product_for_pricing_pricer', {
                parent: 'index',
                url: '/product_for_pricing_pricer/:id',
                views: {
                    'content': {
                        templateUrl: 'app/modules/product_for_pricing_pricer/views/product_for_pricing_pricer.html',
                        controller: 'ProductForPricingPricerController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/product_for_pricing_pricer/ProductForPricingPricerController.js', 'app/modules/product/ProductController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
                    }
            })
            .state('awaiting_contract', {
                parent: 'index',
                url: '/awaiting_contract/:id',
                views: {
                    'content': {
                        templateUrl: 'app/modules/awaiting_contract/views/awaiting_contract.html',
                        controller: 'AwaitingContractController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/awaiting_contract/AwaitingContractController.js', 'app/modules/product/ProductController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
//                                    permits: {
//                                        withAny: ['production.add', 'production.edit', 'production.view', 'production.delete']
//                                    }
                }
            })
            .state('seller_agreement_completed', {
                url: '/seller_agreement/completed',
                templateUrl: 'app/modules/seller_agreement/views/seller_agreement_completed.html',
                controller: 'SellerAgreementCompletedController',
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/seller_agreement/SellerAgreementController.js']);
                        }]
                },
                params: {status: ''}
//                        data:
//                                {
//                                    authenticated: false
//                                }
            })
            .state('storage_agreement_completed', {
                url: '/storage_agreement/completed',
                templateUrl: 'app/modules/storage_agreement/views/storage_agreement_completed.html',
                controller: 'StorageAgreementCompletedController',
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/storage_agreement/StorageAgreementController.js']);
                        }]
                },
                params: {status: ''}
//                        data:
//                                {
//                                    authenticated: false
//                                }
            })
            .state('seller_agreement', {
                url: '/seller_agreement/:product_quote_agreement_id',
                templateUrl: 'app/modules/seller_agreement/views/seller_agreement.html',
                controller: 'SellerAgreementController',
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/seller_agreement/SellerAgreementController.js']);
                        }]
                },
//                        data:
//                                {
//                                    authenticated: false
//                                }
            })
            .state('storage_agreement', {
                url: '/storage_agreement/:product_storage_agreement_id',
                templateUrl: 'app/modules/storage_agreement/views/storage_agreement.html',
                controller: 'StorageAgreementController',
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/storage_agreement/StorageAgreementController.js']);
                        }]
                },
//                        data:
//                                {
//                                    authenticated: false
//                                }
            })
            .state('seller_agreement_new', {
                url: '/seller_agreement_new',
                templateUrl: 'app/modules/seller_agreement/views/seller_agreement_new.html',
                controller: 'SellerAgreementNewController',
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/seller_agreement/SellerAgreementController.js']);
                        }]
                },
//                        data:
//                                {
//                                    authenticated: false
//                                }
            })
            .state('auction_agreement_completed', {
                url: '/auction_agreement/completed',
                templateUrl: 'app/modules/auction_agreement/views/auction_agreement_completed.html',
                controller: 'AuctionAgreementCompletedController',
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/auction_agreement/AuctionAgreementController.js']);
                        }
                    ]
                },
                params: {status: ''}
            })
            .state('auction_agreement', {
                url: '/auction_agreement/:auction_agreement_id',
                templateUrl: 'app/modules/auction_agreement/views/auction_agreement.html',
                controller: 'AuctionAgreementController',
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/auction_agreement/AuctionAgreementController.js']);
                        }
                    ]
                }
            })
            .state('seller_agreement_with_storage', {
                url: '/seller_agreement_with_storage/:product_quote_agreement_id',
                templateUrl: 'app/modules/seller_agreement_with_storage/views/seller_agreement_with_storage.html',
                controller: 'SellerAgreementWithStorageController',
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/seller_agreement_with_storage/SellerAgreementWithStorageController.js']);
                        }]
                }
            })
            .state('seller_agreement_with_storage_completed', {
                url: '/seller_agreement_with_storage/completed',
                templateUrl: 'app/modules/seller_agreement_with_storage/views/seller_agreement_wih_storage_completed.html',
                controller: 'SellerAgreementWithStorageCompletedController',
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/seller_agreement_with_storage/SellerAgreementWithStorageCompletedController.js']);
                        }]
                },
                params: {status: ''}
            })
            .state('reject_to_auction_seller', {
                parent: 'index',
                url: '/reject_to_auction',
                views: {
                    'content': {
                        templateUrl: 'app/modules/reject_to_auction/views/seller.html',
                        controller: 'RejectToAuctionSellerController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/reject_to_auction/RejectToAuctionController.js']);
                        }
                    ]
                },
                data: {
                    authenticated: true,
                    permits: {
                        withAny: ['reject_to_auction.view']
                    }
                }
            })
            .state('reject_to_auction_product', {
                parent: 'index',
                url: '/reject_to_auction/products/:id',
                views: {
                    'content': {
                        templateUrl: 'app/modules/reject_to_auction/views/reject_to_auction.html',
                        controller: 'RejectToAuctionController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/reject_to_auction/RejectToAuctionController.js']);
                        }
                    ]
                },
                data: {
                    authenticated: true,
                    permits: {
                        withAny: ['reject_to_auction.view']
                    }
                }
            })
            .state('category_storage_price', {
                parent: 'index',
                url: '/category_storage_price',
                views: {
                    'content': {
                        templateUrl: 'app/modules/category_storage_price/views/category_storage_price.html',
                        controller: 'CategoryStorageController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/category_storage_price/CategoryStorageController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
//                                    permits: {
//                                        withAny: ['production.add', 'production.edit', 'production.view', 'production.delete']
//                                    }
                }
            }).state('assigned_agents', {
            parent: 'index',
            url: '/assigned_agents',
            views: {
                'content': {
                    templateUrl: 'app/modules/assigned_agents/views/assigned_agents.html',
                    controller: 'AssignedAgentsController'
                },
            },
            resolve: {
                dep: ['$ocLazyLoad',
                    function ($ocLazyLoad) {
                        return $ocLazyLoad.load(['app/modules/assigned_agents/AssignedAgentsController.js']);
                    }]
            },
            data:
                {
                    authenticated: true,
                    permits: {
                        withAny: ['assigned_agents']
                    }
                }
        })
            .state('agents_logs', {
                parent: 'index',
                url: '/agents_logs',
                views: {
                    'content': {
                        templateUrl: 'app/modules/agents_logs/views/agents_logs.html',
                        controller: 'AgentsLogsController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/agents_logs/AgentsLogsController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
                        permits: {
                            withAny: ['agents_logs']
                        }
                    }
            })
            .state('agents_logs_approved', {
                parent: 'index',
                url: '/agents_logs_approved',
                views: {
                    'content': {
                        templateUrl: 'app/modules/agents_logs/views/agents_logs_approved.html',
                        controller: 'AgentsLogsApprovalController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/agents_logs/AgentsLogsController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
                        permits: {
                            withAny: ['agents_logs']
                        }
                    }
            })
            .state('agents_logs_archive', {
                parent: 'index',
                url: '/agents_logs/archive',
                views: {
                    'content': {
                        templateUrl: 'app/modules/agents_logs/views/agents_logs_archive.html',
                        controller: 'AgentsLogsArchiveController'
                    },
                },
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/agents_logs/AgentsLogsController.js']);
                        }]
                },
                data:
                    {
                        authenticated: true,
                        permits: {
                            withAny: ['agents_logs.archive']
                        }
                    }
            })

            .state('storage_amendment_to_consignment_agreement_completed', {
                url: '/storage_amendment_to_consignment_agreement/completed',
                templateUrl: 'app/modules/storage_amendment_to_consignment_agreement/views/storage_amendment_to_consignment_agreement_completed.html',
                controller: 'StorageAmendmentToConsignmentAgreementCompletedController',
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/storage_amendment_to_consignment_agreement/StorageAmendmentToConsignmentAgreement.js']);
                        }]
                },
                params: {status: ''}
//                        data:
//                                {
//                                    authenticated: false
//                                }
            })
            .state('storage_amendment_to_consignment_agreement', {
                url: '/storage_amendment_to_consignment_agreement/:product_quote_agreement_id',
                templateUrl: 'app/modules/storage_amendment_to_consignment_agreement/views/storage_amendment_to_consignment_agreement.html',
                controller: 'StorageAmendmentToConsignmentAgreementController',
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/storage_amendment_to_consignment_agreement/StorageAmendmentToConsignmentAgreement.js']);
                        }]
                }
            })
            .state('designer_consignment_agreement_completed', {
                url: '/designer_consignment_agreement/completed',
                templateUrl: 'app/modules/designer_consignment_agreement/views/designer_consignment_agreement_completed.html',
                controller: 'DesignerConsignmentAgreementCompletedController',
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/designer_consignment_agreement/DesignerConsignmentAgreementController.js']);
                        }]
                },
                params: {status: ''}
            })
            .state('designer_consignment_agreement', {
                url: '/designer_consignment_agreement/:product_quote_agreement_id',
                templateUrl: 'app/modules/designer_consignment_agreement/views/designer_consignment_agreement.html',
                controller: 'DesignerConsignmentAgreementController',
                resolve: {
                    dep: ['$ocLazyLoad',
                        function ($ocLazyLoad) {
                            return $ocLazyLoad.load(['app/modules/designer_consignment_agreement/DesignerConsignmentAgreementController.js']);
                        }]
                }
            })



    });
