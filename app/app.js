/**
 * declare 'ng-laravel' module with dependencies
 */
'use strict';
Dropzone.autoDiscover = false;
var app = angular.module("ng-app", [
    'ui.router', // Powerful routing framework for AngularJS
    'ngSanitize', // it's require for some plugins
    'ngResource', // it's require for some plugins
    'ngAria', // it's require for some plugins
    'ngRoute', // it's require for some plugins
    'ngAnimate',
    'ngMaterial',
    'ngCookies', // it used to save some data in cookie like langKey and etc
    'ngAA',
    'oc.lazyLoad',
    'datatables',
    'pascalprecht.translate',
    'thatisuday.dropzone',
    'ngMessages',
    'mdPickers',
    'summernote',
    'fancyboxplus',
    'ui.sortable',
    'signature'
])
        .config(function ($httpProvider, jwtOptionsProvider, $sceDelegateProvider) {

            jwtOptionsProvider.config({
                whiteListedDomains: ['https://thelocalvault.com']
            });

            // deleget whitelist : https://docs.angularjs.org/error/$sce/insecurl?p0=http:%2F%2Flocalvault.staging.wpengine.com%2Fwp-content%2Fthemes%2Flocalvault%2Fseller-api.php%3Fcallback%3DJSON_CALLBACK

            $sceDelegateProvider.resourceUrlWhitelist([
                // Allow same origin resource loads.
                'self',
                // Allow loading from our assets domain.  Notice the difference between * and **.
                'https://thelocalvault.com/**'
            ]);

//            $httpProvider.defaults.useXDomain = true;
//            $httpProvider.defaults.headers.common["Accept"] = "application/json";
//            $httpProvider.defaults.headers.common["Content-Type"] = "application/json";
//            $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

//            $httpProvider.defaults.useXDomain = true;
//            delete $httpProvider.defaults.headers.common['X-Requested-With'];
//
        })
        .directive('dropzone', function () {
            return function (scope, element, attrs) {
                var config, dropzone;

                config = scope[attrs.dropzone];

                // create a Dropzone for the element with the given options
                dropzone = new Dropzone(element[0], config.options);

                // bind the given event handlers
                angular.forEach(config.eventHandlers, function (handler, event) {
                    dropzone.on(event, handler);
                });
            };
        });
app.directive('timeFormat', function () {

    return {
        link: link,
        restrict: 'A',
        scope: {
            sideHeight: '='
        },
    };
    function link(scope, element, attrs) {

        element.bind('keyup', function (e) {

            var keycode = e.keyCode;

            var isTextInputKey = (keycode > 95 && keycode < 106) || (keycode > 48 && keycode < 57); // number keys
//                        keycode == 32 || keycode == 8 || // spacebar or backspace
//                        (keycode > 64 && keycode < 91) || // letter keys
//                        (keycode > 95 && keycode < 112) || // numpad keys
//                        (keycode > 185 && keycode < 193) || // ;=,-./` (in order)
//                        (keycode > 218 && keycode < 223); // [\]' (in order)


            if (isTextInputKey) {
                applyFormatting();
            } else
            {
                var value = element.val();
                value = '';
                element.val(value);
                element.triggerHandler('input');

                return false;
            }
        });

        var applyFormatting = function () {
            var value = element.val();
            var original = value;
            if (!value || value.length < 2) {

            } else if (!value || value.length < 6) {
                var firsttwo = value.substring(0, 2);

                var lasttwo = value.substring(3, 5);

                if (firsttwo > 23) {
                    var value = value.slice(0, 1);
                } else if (lasttwo > 59) {
                    console.log('sdsd')
                    var value = value.slice(0, 2) + ":00";
                } else if (value.charAt(2) != ':')
                {
                    var value = value.slice(0, 2) + ":" + value.slice(2);
                }
            } else if (value.length > 5) {

                value = value.substring(0, 5);
            }
//                value = formatNumber(value);
            if (value != original) {
                element.val(value);
                element.triggerHandler('input')
            }
        };
    }


});
app.directive('scrollTopScreen', function ($timeout, $document, $rootScope, $window) {
    return {
        link: function (scope, element) {
            angular.element(element).bind('click', function () {

//                $timeout(function () {

//                        $("html, body").animate({scrollTop: -1000}, "slow");
//                        $("html, body").animate({scrollTop:angular.element('md-tabs').offset().top}, "slow");
                $("html, body").animate({scrollTop: 0}, "slow");

//                }, 2000);
            });
        }
    }
});

app.directive('sglclick', ['$parse', function ($parse) {
        return {
            restrict: 'A',
            link: function (scope, element, attr) {
                var fn = $parse(attr['sglclick']);
                var delay = 300, clicks = 0, timer = null;
                element.on('click', function (event) {
                    clicks++;  //count clicks
                    if (clicks === 1) {
                        timer = setTimeout(function () {
                            scope.$apply(function () {
                                fn(scope, {$event: event});
                            });
                            clicks = 0;             //after action performed, reset counter
                        }, delay);
                    } else {
                        clearTimeout(timer);    //prevent single-click action
                        clicks = 0;             //after action performed, reset counter
                    }
                });
            }
        };
    }]);
app.directive('slugCheck', ['$parse', function ($parse) {
        return {
            restrict: 'A',
            link: function (scope, element, attr) {
                element.bind('keyup', function (e) {
                    console.log('asdads');

                    var keycode = e.keyCode;
                    console.log(keycode);

//            var isTextInputKey = (keycode > 95 && keycode < 106) || (keycode > 48 && keycode < 57); // number keys
                    var isTextInputKey1 = !(keycode > 185 && keycode < 189); // // ;=,-./` (in order)
                    var isTextInputKey2 = !(keycode > 189 && keycode < 193); // // ;=,-./` (in order)
                    var isTextInputKey3 = !(keycode > 218 && keycode < 223); // [\]' (in order)
                    var isTextInputKey4 = !(keycode == 32); // number keys
//                        keycode == 32 || keycode == 8 || // spacebar or backspace
//                        (keycode > 64 && keycode < 91) || // letter keys
//                        (keycode > 95 && keycode < 112) || // numpad keys
//                        (keycode > 185 && keycode < 193) || // ;=,-./` (in order)
//                        (keycode > 218 && keycode < 223); // [\]' (in order)


                    if (isTextInputKey1 && isTextInputKey2 && isTextInputKey3 && isTextInputKey4)
                    {

                    } else
                    {
                        console.log('no');
                        var value = element.val();
                        value = '';
                        element.val(value);
                        element.triggerHandler('input');

                        return false;
                    }
                });
            }
        };
    }]);

app.directive('selectClear', function ($parse) {
    return {
        restrict: 'A',
        require: 'ngModel',

        link: function (scope, iElement, iAttrs) {
            iElement.bind('keydown', function (event) {
                if (event.keyCode === 46) {
                    event.preventDefault();
                    event.stopPropagation();

                    scope.$evalAsync(function () {
                        var modelGetter = $parse(iAttrs['ngModel']),
                                modelSetter = modelGetter.assign;
                        modelSetter(scope, '');
                    });
                }
            })
        }
    }
});
