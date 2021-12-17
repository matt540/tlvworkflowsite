"use strict";

var app = angular.module('ng-app');
app.controller('IndexController', function ($document, $mdDialog, $cookies, $scope, $rootScope, $auth, $http, msNavigationService, site_settings, fuseTheming, $translate, $mdToast, $mdSidenav) {

    $scope.site_settings = site_settings;
    $scope.active_theme = $cookies.get('selectedTheme');
    $scope.languages = {
        en: {
            'title': 'English',
            'translation': 'TOOLBAR.ENGLISH',
            'code': 'en',
            'flag': 'us'
        },
        es: {
            'title': 'Spanish',
            'translation': 'TOOLBAR.SPANISH',
            'code': 'es',
            'flag': 'es'
        },
        tr: {
            'title': 'Turkish',
            'translation': 'TOOLBAR.TURKISH',
            'code': 'tr',
            'flag': 'tr'
        }
    };
    $scope.toggleMsNavigationFolded = toggleMsNavigationFolded;
    function toggleMsNavigationFolded()
    {
        angular.element('body').toggleClass('ms-navigation-folded-open');
    }
    $scope.toggleMsNavigationEnter = toggleMsNavigationEnter;
    function toggleMsNavigationEnter()
    {
        angular.element('body').addClass('ms-navigation-folded-open');
    }

    $scope.toggleSidenav = toggleSidenav;
    $scope.msScrollOptions = {
        suppressScrollX: true
    };
    $rootScope.$on("sideNavToggle", function (event, args) {
        console.log('fdfd')
//        toggleSidenav('navigation');
//        $mdSidenav('navigation').toggle();
//        toggleMsNavigationFolded();
        angular.element('body').removeClass('ms-navigation-folded-open');

    });
    function toggleSidenav(sidenavId)
    {
        $mdSidenav(sidenavId).toggle();
    }
    $scope.selectedLanguage = $scope.languages[$translate.preferredLanguage()];
    $scope.closeToast = function () {
//        if (isDlgOpen)
//            return;

        $mdToast
                .hide()
                .then(function () {
//                    isDlgOpen = false;
                });
    };

    $rootScope.$on("notification", function () {
        var message = $rootScope.message;

        $mdToast.show({
            template: '<md-toast id="language-message" layout="column" layout-align="center start"><span class="md-toast-text" flex>' + message + '</span></md-toast>',
            hideDelay: 1000,
            position: 'top right',
        });
    });


    $scope.changeLanguage = function (lang)
    {
        $scope.selectedLanguage = lang;

        $rootScope.message = 'Your Language Has been Changed Successfully';
        $rootScope.$emit('notification');



        // Change the language
        $translate.use(lang.code);
    }
    $scope.openAuthUserEditDialog = function ()
    {
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
}).config(function ($mdThemingProvider) {


});
