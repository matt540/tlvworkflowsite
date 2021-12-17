(function ()
{
    'use strict';

    angular
        .module('ng-app')
        .config(config);

    /** @ngInject */
//    function config($ariaProvider, $logProvider, msScrollConfigProvider, fuseConfigProvider)
    function config($ariaProvider,$logProvider,fuseConfigProvider,msScrollConfigProvider)
    {
        // Enable debug logging
        $logProvider.debugEnabled(true);
//
//        /*eslint-disable */
//
//        // ng-aria configuration
        $ariaProvider.config({
            tabindex: false
        });
//
//        // Fuse theme configurations
        fuseConfigProvider.config({
            'disableCustomScrollbars'        : false,
            'disableCustomScrollbarsOnMobile': true,
            'disableMdInkRippleOnMobile'     : true
        });
//
//        // msScroll configuration
        msScrollConfigProvider.config({
            wheelPropagation: true
        });

        /*eslint-enable */
    }
})();