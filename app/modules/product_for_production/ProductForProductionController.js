"use strict";

var app = angular.module('ng-app');
app.controller('ProductForProductionController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken,$window)
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
                }).catch(function (error)
        {

        });
    };
    $scope.getSellerById($stateParams.id);


    $scope.authuser = $auth.getProfile().$$state.value;
    $scope.dtInstance = {};
    $scope.users = [];
    $scope.select = [];
    $scope.productStatus = [];
    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
        dataSrc: "data",
        url: site_settings.api_url + 'product_for_production/get_product_for_productions',
        type: "POST",
        headers: {Authorization: 'Bearer ' + ngAAToken.getToken()},
        data: {id: $stateParams.id}
    }).withOption('processing', true) //for show progress bar
            .withOption('serverSide', true) // for server side processing
            .withPaginationType('full_numbers') // for get full pagination options // first / last / prev / next and page numbers
            .withDisplayLength(25) // Page size
            .withOption('aaSorting', [1, 'asc'])
            .withOption('autoWidth', false)
            .withOption('responsive', true)
            .withOption('dom', '<"top"f>rt<"bottom"<"left"<"length"l>><"right"<"info"i><"pagination"p>>>')
            .withDataProp('data')
            .withOption('fnRowCallback',
                    function (nRow, aData, iDisplayIndex, iDisplayIndexFull)
                    {
                        $scope.productStatus = [];
                        $scope.Archive = false;
                        $scope.Complete = false;
                        $scope.Delete = false;
                        $compile(nRow)($scope);
                    });




    function actionsHtml(data, type, full, meta)
    {

        var action_btn = '';

//        if (full.status_id == 17)
//        {
//            var action_btn = '<md-fab-speed-dial style=" transform: translate(-110px, 0px);" md-open="demo' + data.id + '.isOpen" md-direction="left" ng-class="{ \'md-hover-full\': demo' + data.id + '.hover }" class="md-scale md-fab-top-right ng-isolate-scope md-left"  ng-mouseleave="demo' + data.id + '.isOpen=false" ng-mouseenter="demo' + data.id + '.isOpen=true">';
//        }
//        else
//        {
//            var action_btn = '<md-fab-speed-dial style=" transform: translate(-55px, 0px);" md-open="demo' + data.id + '.isOpen" md-direction="left" ng-class="{ \'md-hover-full\': demo' + data.id + '.hover }" class="md-scale md-fab-top-right ng-isolate-scope md-left"  ng-mouseleave="demo' + data.id + '.isOpen=false" ng-mouseenter="demo' + data.id + '.isOpen=true">';
//        }
//
//        action_btn += '<md-fab-trigger>';
//        action_btn += '<md-button aria-label="menu" class="md-fab md-primary md-mini">';
//        action_btn += '<md-icon md-svg-src="assets/angular-material-assets/icons/ic_call_to_action_white_24px.svg"></md-icon>';
//        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">Action</md-tooltip>';
//
//        action_btn += '</md-button>';
//        action_btn += '</md-fab-trigger>';
//        action_btn += '<md-fab-actions>';

        if (full.is_product_for_production == 0)
        {
//            action_btn += '<md-button aria-label="EDIT" ng-click="openProductQuotationAddDialog(' + data.id + ');" class="md-fab md-accent md-raised md-mini">';
//            action_btn += '<md-icon md-svg-src="assets/angular-material-assets/icons/ic_mode_edit_white_24px.svg" aria-label="Twitter"></md-icon>';
//            action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">EDIT</md-tooltip>';
//            action_btn += '</md-button>';

            action_btn += '<span ng-click="openProductQuotationAddDialog(' + data.id + ');" class="text-boxed m-0 deep-blue-bg white-fg" style="cursor: pointer; background-color: #126491 !important;">VIEW</span>';


//            action_btn += '<md-button ng-click="showConfirm(' + data.id + ')" aria-label="DELETE" class="md-fab md-warn md-raised md-mini">';
//            action_btn += '<md-icon  md-font-icon="icon-delete" aria-label="Facebook"></md-icon>';
//            action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">DELETE</md-tooltip>';
//            action_btn += '</md-button>';
        } else
        {
            action_btn += '<span class="text-boxed m-0 deep-blue-bg white-fg" style="cursor: pointer; background-color: #A9A9A9 !important;">VIEW</span>';


        }

//        action_btn += '<md-button aria-label="VIEW" ng-click="openProductForProductionViewDialog(' + data.id + ');" class="md-fab md-accent md-raised md-mini">';
//        action_btn += '<md-icon  md-font-icon="icon-eye" aria-label="VIEW"></md-icon>';
//        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">VIEW PRODUCT</md-tooltip>';
//        action_btn += '</md-button>';

//        action_btn += '<span ng-click="openProductForProductionViewDialog(' + data.id + ');" class="text-boxed m-0 deep-blue-bg white-fg" style="cursor: pointer; background-color: #1eaa36 !important; margin-left: 5px;">VIEW</span>';

//        action_btn += '</md-fab-actions></md-fab-speed-dial>';
        return action_btn;
//        $scope.persons[data.id] = data;
//        return '<md-button aria-label="Edit" ui-sref="edit_profile({id:\'' + full.id + '\'})" class="md-fab md-mini md-button  md-ink-ripple">  <md-icon md-svg-src="assets/img/edit.svg" class="icon"></md-icon></md-button><md-button aria-label="Delete" type="submit" class="md-fab md-mini md-button  md-ink-ripple md-warn">  <md-icon md-font-icon="icon-delete" class="icon" ></md-icon></md-button>';
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
                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
        });
    };

    $scope.getProductStatus();

    function callback(json)
    {

    }
    function agingRender(data, type, full, meta)
    {
        if (full.copyright_created_at != null && full.for_production_created_at != null)
        {

            var startDate = moment.utc(full.for_production_created_at.date).local();
            var endDate = moment.utc(full.copyright_created_at.date).local();

            var result = endDate.diff(startDate, 'days');
            return result + 1 + " Day";

        } else
        {
            if (full.for_production_created_at != null)
            {
                var startDate = moment.utc(full.for_production_created_at.date).local();
                var endDate = moment.utc(new Date()).local();
                var result = endDate.diff(startDate, 'days');
                return result + 1 + " Day";
            } else
            {
                return "1 Day";
            }

        }
    }
    function renderDate(data, type, full, meta)
    {
        if (data)
        {
            return moment.utc(data.date).local().format('MM/DD/YYYY');
        } else
        {
            return '-----';
        }
    }
    function profileImage(data, type, full, meta)
    {

//        console.log('data');
        if (data != null && data != undefined)
        {
            var array = data.split(',');
            return '<a href="Uploads/product/' + array[0] + '" fancyboxable><img  style="width:40px;height:40px;border-radius:30px;" src="Uploads/product/' + array[0] + '"></a>';
        } else
        {
            return '<a href="/assets/images/avatars/profile.jpg" fancyboxable><img  style="width:40px;height:40px;border-radius:30px;" src="/assets/images/avatars/profile.jpg"></a>';
        }
    }
    function skuRender(data, type, full, meta)
    {
        if (data && data != '')
        {
            return data;
        } else
        {
            return '---';
        }

    }
    $scope.dtColumns = [
        DTColumnBuilder.newColumn('image').withTitle('Image').notSortable().renderWith(profileImage),
        DTColumnBuilder.newColumn('sku').withTitle('SKU').renderWith(skuRender),
        DTColumnBuilder.newColumn('name').withTitle('Product Name'),
//        DTColumnBuilder.newColumn('sell_name').withTitle('Sell Name'),
//        DTColumnBuilder.newColumn('price').withTitle('Suggested Retail Price').renderWith(renderPrice),
        DTColumnBuilder.newColumn(null).withTitle('Max / Min').renderWith(renderPriceMaxMin).notSortable(),
//        DTColumnBuilder.newColumn('tlv_suggested_price_max').withTitle('Suggested Price'),
        DTColumnBuilder.newColumn('for_production_created_at').withTitle('Date').renderWith(renderDate),
        DTColumnBuilder.newColumn(null).withTitle('Aging').renderWith(agingRender),
        DTColumnBuilder.newColumn('status_id').withTitle('Status').renderWith(statusHtml),
//        DTColumnBuilder.newColumn('images_from').withTitle('Schedule').renderWith(schedule).notSortable(),
        DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable()
                .renderWith(actionsHtml),
    ];
    function schedule(data, type, full, meta)
    {

        console.log(full.images_from);
        if (full.images_from == 1)
        {
            return '<button aria-label="{{hover_' + full.id + ' ==true ? \'Schedule\' : \'Schedule\' }}"  class="md-primary md-raised md-button md-jd-theme md-ink-ripple"\n\
                     ng-class="hover==true ? md-warn : md-primary"    ng-mouseenter="hover_' + full.id + ' = true"    ng-mouseleave="hover_' + full.id + ' = false"\n\
                     type="button" ng-click="takeSchedule(\'' + full.id + '\')" >Schedule</button>';
        } else
        {
            return '<button ng-disabled="true" aria-label="{{hover_' + full.id + ' ==true ? \'Schedule\' : \'Schedule\' }}"  class="md-primary md-raised md-button md-jd-theme md-ink-ripple"\n\
                     ng-class="hover==true ? md-warn : md-primary"    ng-mouseenter="hover_' + full.id + ' = true"    ng-mouseleave="hover_' + full.id + ' = false"\n\
                     type="button" ng-click="takeSchedule(\'' + full.id + '\')" >Schedule</button>';
        }

    }
    function renderPriceMaxMin(data, type, full, meta)
    {

        if (data.tlv_suggested_price_min != '' && data.tlv_suggested_price_min != '')
        {
            return data.tlv_suggested_price_max + ' / ' + data.tlv_suggested_price_min;

        } else
        if (data.tlv_suggested_price_min != '')
        {
            return '- - - / ' + data.tlv_suggested_price_min;

        } else
        if (data.tlv_suggested_price_max != '')
        {
            return data.tlv_suggested_price_max + ' / - - -';

        } else
        {
            return '- - - / - - -';
        }

    }
    function renderPrice(data, type, full, meta)
    {
        if (data != '')
        {
            return data;

        } else
        {
            return '- - -';
        }

    }

    function statusHtml1(data, type, full, meta)
    {
//        console.log(full);
        if (full.status_id == 17)
        {
            var action_btn = '';

            action_btn += '<md-button aria-label="Approve" sglclick="changeProductForProductionStatus(' + full.id + ',18);" class="md-fab md-accent md-raised md-mini">';
            action_btn += '<md-icon md-font-icon="icon-check-circle" class="" aria-hidden="true"></md-icon>';
            action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">APPROVE</md-tooltip>';
            action_btn += '</md-button>';

            action_btn += '<md-button aria-label="Reject" sglclick="changeProductForProductionStatus(' + full.id + ',19);" class="md-fab md-warn md-raised md-mini">';
            action_btn += '<md-icon md-font-icon="icon-cancel" class="" aria-hidden="true"></md-icon>';
            action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">REJECT</md-tooltip>';
            action_btn += '</md-button>';
            return action_btn;

        } else
        {
            return full.status_value;
        }
    }

    $scope.changeProductStatus = function (status, product)
    {
//        console.log(product);
        var count = 0;
        for (var i = 0; i < $scope.productStatus.length; i++)
        {
            if ($scope.productStatus[i].product_quotation_id == product)
            {
                $scope.productStatus[i].product_for_production_status_id = status;
            } else
            {
                count++;
            }

        }
        if (count == $scope.productStatus.length)
        {
            $scope.productStatus.push({'product_for_production_status_id': status, 'product_quotation_id': product});
        }
//        $scope.productStatus.push({'product_for_production_status_id': status, 'product_quotation_id': product});
//        $scope.productStatus[product] = {'product_for_production_status_id': status, 'product_quotation_id': product};
    };

    function statusHtml(data, type, full, meta)
    {
        if (full.is_product_for_production == 0)
        {
            $scope.select[full.id] = 0;
            var action_btn = '';

            action_btn += '<md-radio-group ng-click="changeProductStatus(select[' + full.id + '], ' + full.id + ')" ng-model="select[' + full.id + ']" layout="row" layout-xs="column">';
            action_btn += '<md-radio-button value="1">Complete</md-radio-button>';
            action_btn += '<md-radio-button value="3">Archive</md-radio-button>';
            action_btn += '<md-radio-button value="2">Delete</md-radio-button>';
            action_btn += '</md-radio-group>';

            return action_btn;

        } else if (full.is_product_for_production == 1)
        {
            return 'Completed';
        } else if (full.is_product_for_production == 2)
        {
            return 'Delete';
        }
    }



    $scope.sendMailApprove = function ()
    {
        var approved = [];
        for (var i = 0; i < $scope.productStatus.length; i++)
        {
            if ($scope.productStatus[i].product_for_production_status_id == 1)
            {
                approved.push({id: $scope.productStatus[i].product_quotation_id, is_send_mail: 1});
            }

        }
        console.log(approved);
        if (approved.length > 0)
        {

            var confirm = $mdDialog.confirm()
                    .title('Are you sure you want to advance to the next stage?')
                    .ok('Yes')
                    .cancel('No');

            $mdDialog.show(confirm).then(function ()
            {
                $rootScope.loader = true;
                $http.post(site_settings.api_url + 'product_quotation/send_mail_approve', {products: approved, seller: $stateParams.id})
                        .then(function (response)
                        {

                            $rootScope.loader = false;
                            $scope.productStatus = [];
                            var b = response.data;
                            var a = document.createElement('a');
//                            document.getElementById("content1").appendChild(a);
                            a.download = b;
                            a.target = '_blank';
                            a.id = b;
                            a.href = 'api/storage/exports/' + b;
                            a.click();


//                        $rootScope.message = 'Proposal has been successfully Accepted';
                            $rootScope.message = 'Proposal Successfully Sent';
                            $rootScope.$emit("notification");
                            $rootScope.$emit("reloadUserTable");
                            $rootScope.getPendingProducts();
//                        $scope.downloadDocument();
                            $rootScope.loader = false;
                        }).catch(function (error)
                {
                    $rootScope.message = 'Something Went Wrong';
                    $rootScope.$emit("notification");
                });
            }, function ()
            {

            });
        } else
        {
            $rootScope.message = 'Please select at least one Accept product';
            $rootScope.$emit("notification");

        }

    }





    $scope.changeProductForProductionStatus = function ()
    {

        var rejected = [];
        for (var i in $scope.productStatus)
        {
            if ($scope.productStatus[i].product_for_production_status_id == 1)
            {
                rejected.push($scope.productStatus[i]);
            }

        }
        console.log(rejected);
        if (rejected.length > 0)
        {
            var confirm = $mdDialog.confirm()
                    .title('Are you sure you want to advance to the next stage?')
                    .ok('Yes')
                    .cancel('No');

            $mdDialog.show(confirm).then(function ()
            {

                $rootScope.loader = true;
                $http.post(site_settings.api_url + 'product_for_production/change_product_for_production_status', {product_status: rejected, is_send_email: 1})
                        .then(function (response)
                        {
                            $scope.productStatus = [];
                            $rootScope.message = 'Product status has been saved.';
                            $rootScope.$emit("notification");
                            $rootScope.$emit("reloadUserTable");
                            $rootScope.getPendingProducts();
                            $rootScope.loader = false;
                        }).catch(function (error)
                {
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                });
            }, function ()
            {

            });
        } else
        {
            $rootScope.message = 'Please select at least one complete';
            $rootScope.$emit("notification");
        }
    };

    $scope.changeArchiveProductForProductionStatus = function ()
    {
        var rejected = [];
        for (var i in $scope.productStatus)
        {
            if ($scope.productStatus[i].product_for_production_status_id == 3)
            {
                rejected.push($scope.productStatus[i]);
            }

        }
        console.log(rejected);
        if (rejected.length > 0)
        {
            var confirm = $mdDialog.confirm()
                    .title('Are you sure you want to archive product?')
                    .ok('Yes')
                    .cancel('No');

            $mdDialog.show(confirm).then(function ()
            {

                $rootScope.loader = true;
                $http.post(site_settings.api_url + 'product_for_production/change_product_for_production_status', {product_status: rejected})
                        .then(function (response)
                        {
                            $scope.productStatus = [];
                            $rootScope.message = 'Product status has been saved.';
                            $rootScope.$emit("notification");
                            $rootScope.$emit("reloadUserTable");
                            $rootScope.getPendingProducts();
                            $rootScope.loader = false;
                        }).catch(function (error)
                {
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                });
            }, function ()
            {

            });
        } else
        {
            $rootScope.message = 'Please select at least one archive';
            $rootScope.$emit("notification");
        }
    };

    $scope.changeDeleteProductForProductionStatus = function ()
    {
        var rejected = [];
        for (var i in $scope.productStatus)
        {
            if ($scope.productStatus[i].product_for_production_status_id == 2)
            {
                rejected.push($scope.productStatus[i]);
            }

        }
        console.log(rejected);
        if (rejected.length > 0)
        {
            var confirm = $mdDialog.confirm()
                    .title('Are you sure you want to delete product?')
                    .ok('Yes')
                    .cancel('No');

            $mdDialog.show(confirm).then(function ()
            {

                $rootScope.loader = true;
                $http.post(site_settings.api_url + 'product_for_production/change_product_for_production_status', {product_status: rejected})
                        .then(function (response)
                        {
                            $scope.productStatus = [];
                            $rootScope.message = 'Product status has been saved.';
                            $rootScope.$emit("notification");
                            $rootScope.$emit("reloadUserTable");
                            $rootScope.getPendingProducts();
                            $rootScope.loader = false;
                        }).catch(function (error)
                {
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                });
            }, function ()
            {

            });
        } else
        {
            $rootScope.message = 'Please select at least one delete';
            $rootScope.$emit("notification");
        }
    };


    $scope.AllCompleteProductSelected = function ()
    {
        $scope.productStatus = [];

        if ($scope.Complete)
        {
            $scope.Archive = false;
            $scope.Delete = false;
            for (var i in $scope.select)
            {
                $scope.select[i] = 1;
                $scope.productStatus.push({'product_quotation_id': i, product_for_production_status_id: 1});
            }
        } else
        {
            $scope.Archive = false;
            $scope.Delete = false;
            for (var i in $scope.select)
            {
                $scope.productStatus = [];
                $scope.select[i] = 0;
            }

        }
    }

    $scope.AllArchiveProductSelected = function ()
    {
        $scope.productStatus = [];
        if ($scope.Archive)
        {
            $scope.Complete = false;
            $scope.Delete = false;
            for (var i in $scope.select)
            {
                $scope.select[i] = 3;
                $scope.productStatus.push({'product_quotation_id': i, product_for_production_status_id: 3});
            }
        } else
        {
            $scope.Complete = false;
            $scope.Delete = false;
            for (var i in $scope.select)
            {
                $scope.productStatus = [];
                $scope.select[i] = 0;
            }

        }

    }
    $scope.AllDeleteProductSelected = function ()
    {
        $scope.productStatus = [];
        if ($scope.Delete)
        {
            $scope.Archive = false;
            $scope.Complete = false;
            for (var i in $scope.select)
            {
                $scope.select[i] = 2;
                $scope.productStatus.push({'product_quotation_id': i, product_for_production_status_id: 2});
            }
        } else
        {
            $scope.Complete = false;
            $scope.Archive = false;
            for (var i in $scope.select)
            {
                $scope.productStatus = [];
                $scope.select[i] = 0;
            }

        }
    }



    $scope.takeSchedule = function (product)
    {
        $mdDialog.show({
            controller: 'ScheduleAddController',
            templateUrl: 'app/modules/schedule/views/schedule_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                product: product
            }
        });
    };

    $scope.sendMail = function (product_quotation, images_from)
    {
        if (images_from == 0)
        {
//            var confirm = $mdDialog.confirm()
//                    .title('Would you like to Send Proposal for this Product?')
//                    .ok('Yes')
//                    .cancel('No');
//
//            $mdDialog.show(confirm).then(function ()
//            {
            $http.post(site_settings.api_url + 'product_quotation/send_mail', {id: product_quotation})
                    .then(function (response)
                    {

                        $rootScope.message = 'Proposal send Successfully';
                        $rootScope.$emit("notification");
                        $rootScope.$emit("reloadUserTable");

                    }).catch(function (error)
            {
                $rootScope.message = 'Something Went Wrong';
                $rootScope.$emit("notification");
            });
//            }, function ()
//            {
//
//            });
        } else
        {
            $rootScope.message = 'Please Schedule first.';
            $rootScope.$emit("notification");

        }
    };

    $scope.openProductQuotationAddDialog = function (product_quotation)
    {
        $mdDialog.show({
            controller: 'ProductForProductionAddController',
            templateUrl: 'app/modules/product_for_production/views/product_for_production_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                product_quotation: product_quotation
            }
        });
    };

    $scope.openProductForProductionViewDialog = function (product_quotation)
    {
        $mdDialog.show({
            controller: 'ProductForProductionViewController',
            templateUrl: 'app/modules/product_for_production/views/product_for_production_view.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                product_quotation: product_quotation
            }
        });
    };

    $rootScope.$on("reloadUserTable", function (event, args)
    {
         $window.location.reload();
        $scope.select=[];

        reloadData();
    });
    $rootScope.$on("reloadProductForProductionTable", function (event, args)
    {
//         $window.location.reload();
        $scope.select=[];

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
                .title('Would you like to delete this Product?')
                .ok('Yes')
                .cancel('No');

        $mdDialog.show(confirm).then(function ()
        {
            $http.post(site_settings.api_url + 'users/delete_user', {id: id})
                    .then(function (response)
                    {

                        $rootScope.message = 'User Deleted Successfully';
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
app.controller('ProductForProductionAddController', function (product_quotation, $parse, $document, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.product_quotation = {};
    $scope.product_pending_images_name = [];
    $scope.product_pending_images = [];
    $scope.categorys = {};
    $scope.subcategorys = {};
    $scope.sub_categorys = [];
    $scope.product = {};
    $scope.edit = false;
    $scope.action = 'Add';
    $scope.searchTerm = '';
    $scope.commissions = [80, 70, 60, 50,40];
    $scope.removeSub = function (category_name) {
        return category_name.replace("Sub", "");

    }

    var count = 30;
    $scope.totalquantitys = [];
    for (var z = 1; z <= count; z++)
    {
        $scope.totalquantitys.push(z);
    }


    $rootScope.$on("newSeller", function (event, user)
    {
//        $scope.sellers.push(user);
//        $scope.product_quotation.product_id.seller_id = user.ID;
//        $rootScope.loader = true;
        $http.get(site_settings.api_url + 'seller/get_all_seller')
                .then(function (response)
                {
                    $scope.sellers = response.data;
                });

    });
    $scope.addNewSeller = function ()
    {
        $mdDialog.show({
            controller: 'SellerAddController',
            templateUrl: 'app/modules/product/views/seller_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            skipHide: true,
        });
    };

    $rootScope.$on("newPickUpLocation", function (event, pick_up_location)
    {

        console.log(pick_up_location);
        $scope.pick_up_locations.push(pick_up_location);
        $scope.product_quotation.product_id.pick_up_location = pick_up_location.id;

    });
    $scope.addNewPickUpLocation = function ()
    {
        if ($scope.product_quotation.product_id.seller_id)
        {
            $mdDialog.show({
                controller: 'PickUpLocationAddController',
                templateUrl: 'app/modules/product/views/pick_up_location_add.html',
                parent: angular.element($document.body),
                clickOutsideToClose: true,
                skipHide: true,
                locals: {
                    seller_id: $scope.product_quotation.product_id.seller_id
                }
            });
        } else
        {
            $rootScope.message = 'Please Select a Seller.';
            $rootScope.$emit("notification");

        }
    };
    $scope.getAllPickUpLocations = function (seller_id)
    {
//        console.log('hi');
//        console.log($scope.product_quotation.product_id.seller_id);

        $scope.pick_up_locations = [];
        $http.get(site_settings.api_url + 'getPickUpLocationsBySelectIdSellerId/6/' + seller_id)
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


    $scope.getCategorys = function ()
    {
        $http.get(site_settings.api_url + 'get_all_categorys')
                .then(function (response)
                {
                    $scope.categorys = response.data;
                    $scope.getCat();
//                    $rootScope.$emit("notification");
                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
        });
    };

    $scope.getAllSubCategorysOfCategory = function ()
    {
        $http.get(site_settings.api_url + 'get_all_subcategorys_of_category_id/2')
                .then(function (response)
                {
                    $scope.sub_categorys = response.data;

                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
        });
    };
    $scope.getAllSubCategorysOfCategory();
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


    $scope.getAllSubCategorysOfAgeCategory = function ()
    {


        $scope.ages = [];
        $http.post(site_settings.api_url + 'subcategory/getAllSubCategorysOfAgeCategory')
                .then(function (response)
                {
                    $scope.ages = response.data;
                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
            $rootScope.loader = false;
        });
    };
    $scope.getAllSubCategorysOfAgeCategory();


    $scope.getCat = function ()
    {
        if (!$scope.product_quotation.product_id)
        {
            $scope.product_quotation.product_id = {};
        }
        console.log($scope.product_quotation.product_id);
        $scope.product_quotation.product_id.cat = {};
        for (var i in $scope.categorys)
        {
            if ($scope.product_quotation.product_id[$scope.categorys[i].category_name.toLowerCase()] != null && $scope.categorys[i].category_name.toLowerCase() != 'condition' && $scope.categorys[i].category_name.toLowerCase() != 'sub category' && $scope.categorys[i].category_name.toLowerCase() != 'condition' && $scope.categorys[i].category_name.toLowerCase() != 'room' && $scope.categorys[i].category_name.toLowerCase() != 'color' && $scope.categorys[i].category_name.toLowerCase() != 'collection')
            {
                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = $scope.product_quotation.product_id[$scope.categorys[i].category_name.toLowerCase()].id;
            } else if ($scope.product_quotation.product_id['product_room'] != null && $scope.categorys[i].category_name.toLowerCase() == 'room')
            {
                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = [];
                for (var v in $scope.product_quotation.product_id['product_room'])
                {
                    $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name].push($scope.product_quotation.product_id['product_room'][v].id);
                }
            }
             else if ($scope.product_quotation.product_id['product_collection'] != null && $scope.categorys[i].category_name.toLowerCase() == 'collection')
            {
                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = [];
                for (var v in $scope.product_quotation.product_id['product_collection'])
                {
                    $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name].push($scope.product_quotation.product_id['product_collection'][v].id);
                }
            }
            else if ($scope.product_quotation.product_id['product_color'] != null && $scope.categorys[i].category_name.toLowerCase() == 'color')
            {
                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = [];
                for (var v in $scope.product_quotation.product_id['product_color'])
                {
                    $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name].push($scope.product_quotation.product_id['product_color'][v].id);
                }
            } else if ($scope.product_quotation.product_id['product_category'] != null && $scope.categorys[i].category_name.toLowerCase() == 'sub category')
            {
                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = [];
                for (var v in $scope.product_quotation.product_id['product_category'])
                {
                    $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name].push($scope.product_quotation.product_id['product_category'][v].id);
                }
            } else if ($scope.product_quotation.product_id['product_con'] != null && $scope.categorys[i].category_name.toLowerCase() == 'condition')
            {
                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = [];
                for (var v in $scope.product_quotation.product_id['product_con'])
                {
                    $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name].push($scope.product_quotation.product_id['product_con'][v].id);
                }
            }
//            else if ($scope.product_quotation.product_id['con'] != null && $scope.categorys[i].category_name.toLowerCase() == 'condition')
//            {
//                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = $scope.product_quotation.product_id['con'].id;
//
//            }
//            else if ($scope.product_quotation.product_id['category'] != null && $scope.categorys[i].category_name.toLowerCase() == 'sub category')
//            {
//                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = $scope.product_quotation.product_id['category'].id;
//            }
        }
    };

    $scope.getSellersFromWP = function ()
    {
        $rootScope.loader = true;

        $http.jsonp(site_settings.wp_api_url + 'seller-api.php', {jsonpCallbackParam: 'callback'})
                .then(function (response)
                {
                    $rootScope.loader = false;
                    $scope.sellers = response.data;

                    if (product_quotation)
                    {
                        $rootScope.loader = true;
                        $scope.action = 'Edit';
                        $http.post(site_settings.api_url + 'product_quotation/get_product_quotation', {id: product_quotation})
                                .then(function (response)
                                {
                                    $rootScope.loader = false;
                                    $scope.product_quotation = response.data;
                                    if ($scope.product_quotation.product_id.age)
                                    {
                                        $scope.product_quotation.product_id.age = $scope.product_quotation.product_id.age.id;
                                    }


                                    $scope.product_pending_images = [];

                                    $scope.getCategorys();
                                    $scope.getSubCategorys();
                                    for (var i in $scope.sellers)
                                    {

                                        if ($scope.product_quotation.product_id.seller_id == $scope.sellers[i].ID)
                                        {
                                            $scope.product_quotation.product_id.seller_name = $scope.sellers[i].data.display_name;
                                            $scope.product_quotation.product_id.seller_email = $scope.sellers[i].data.user_email;
                                        }
                                    }
                                    if ($scope.product_quotation.product_id.product_pending_images != '')
                                    {
                                        $scope.mockFiles = [];
                                        for (var i in $scope.product_quotation.product_id.product_pending_images)
                                        {
                                            $scope.product_pending_images.push($scope.product_quotation.product_id.product_pending_images[i].id);

                                            $scope.mockFiles.push(
                                                    {name: $scope.product_quotation.product_id.product_pending_images[i].name, id: $scope.product_quotation.product_id.product_pending_images[i].id, size: 5000, isMock: true, serverImgUrl: '/Uploads/product/' + $scope.product_quotation.product_id.product_pending_images[i].name}
                                            );
                                        }
                                        console.log($scope.mockFiles);

                                        $timeout(function ()
                                        {
                                            $scope.myDz = $scope.dzMethods.getDropzone();

                                            // emit `addedfile` event with mock files
                                            // emit `complete` event for mockfile as they are already uploaded
                                            // decrease `maxFiles` count by one as we keep adding mock file
                                            // push mock file dropzone
                                            $scope.mockFiles.forEach(function (mockFile)
                                            {
                                                $scope.myDz.emit('addedfile', mockFile);
                                                $scope.myDz.emit('complete', mockFile);
                                                $scope.myDz.options.maxFiles = $scope.myDz.options.maxFiles - 1;
                                                $scope.myDz.files.push(mockFile);
                                            });
                                        });

//                        console.log($scope.product_images);
                                    }

//                    if ($scope.user.profile_image != '')
//                    {
//                        $scope.mockFiles = [
//                            {name: $scope.user.profile_image, size: 5000, isMock: true, serverImgUrl: '/Uploads/profile/' + $scope.user.profile_image},
//                        ];
//
//                        $timeout(function () {
//                            $scope.myDz = $scope.dzMethods.getDropzone();
//
//                            // emit `addedfile` event with mock files
//                            // emit `complete` event for mockfile as they are already uploaded
//                            // decrease `maxFiles` count by one as we keep adding mock file
//                            // push mock file dropzone
//                            $scope.mockFiles.forEach(function (mockFile) {
//                                $scope.myDz.emit('addedfile', mockFile);
//                                $scope.myDz.emit('complete', mockFile);
//                                $scope.myDz.options.maxFiles = $scope.myDz.options.maxFiles - 1;
//                                $scope.myDz.files.push(mockFile);
//                            });
//                        });
//                    }

                                }).catch(function (error)
                        {
                            $rootScope.loader = false;
                            consoel.log(error);

                        });

                    } else
                    {
                        $scope.action = 'Add';
                    }


                })
                .catch(function (response)
                {
                    $rootScope.loader = false;
                    console.log(response);
                });
    };

    $scope.getSellers = function ()
    {

        $rootScope.loader = true;
        $http.get(site_settings.api_url + 'seller/get_all_seller')
                .then(function (response)
                {
                    $scope.sellers = response.data;

                    $rootScope.loader = false;

                    if (product_quotation)
                    {
                        $rootScope.loader = true;
                        $scope.action = 'Edit';
                        $http.post(site_settings.api_url + 'product_quotation/get_product_quotation', {id: product_quotation})
                                .then(function (response)
                                {
                                    $rootScope.loader = false;
                                    $scope.product_quotation = response.data;
                                    $scope.product_quotation.product_id.seller_id = $scope.product_quotation.product_id.sellerid.id;
                                    if ($scope.product_quotation.product_id.pick_up_location)
                                    {
                                        $scope.product_quotation.product_id.pick_up_location = $scope.product_quotation.product_id.pick_up_location.id;
                                    }
                                    $scope.getAllPickUpLocations($scope.product_quotation.product_id.seller_id);
                                    $scope.product_pending_images = [];

                                    if ($scope.product_quotation.product_id.age)
                                    {
                                        $scope.product_quotation.product_id.age = $scope.product_quotation.product_id.age.id;
                                    }
                                    $scope.getCategorys();
                                    $scope.getSubCategorys();


                                    for (var i in $scope.sellers)
                                    {

                                        if ($scope.product_quotation.product_id.seller_id == $scope.sellers[i].id)
                                        {
                                            $scope.product_quotation.product_id.seller_name = $scope.sellers[i].displayname;
                                            $scope.product_quotation.product_id.seller_email = $scope.sellers[i].email;
                                        }
                                    }
                                    if ($scope.product_quotation.product_id.product_pending_images != '')
                                    {
                                        $scope.mockFiles = [];
                                        for (var i in $scope.product_quotation.product_id.product_pending_images)
                                        {
                                            $scope.product_pending_images.push($scope.product_quotation.product_id.product_pending_images[i].id);
                                            $scope.product_pending_images_name.push({name: $scope.product_quotation.product_id.product_pending_images[i].name, filename: $scope.product_quotation.product_id.product_pending_images[i].name, id: $scope.product_quotation.product_id.product_pending_images[i].id});

                                            $scope.mockFiles.push(
                                                    {name: $scope.product_quotation.product_id.product_pending_images[i].name, id: $scope.product_quotation.product_id.product_pending_images[i].id, size: 5000, isMock: true, serverImgUrl: '/Uploads/product/' + $scope.product_quotation.product_id.product_pending_images[i].name}
                                            );
                                        }
                                        console.log($scope.mockFiles);

                                        $timeout(function ()
                                        {
                                            $scope.myDz = $scope.dzMethods.getDropzone();

                                            // emit `addedfile` event with mock files
                                            // emit `complete` event for mockfile as they are already uploaded
                                            // decrease `maxFiles` count by one as we keep adding mock file
                                            // push mock file dropzone
                                            $scope.mockFiles.forEach(function (mockFile)
                                            {
                                                $scope.myDz.emit('addedfile', mockFile);
                                                $scope.myDz.emit('complete', mockFile);
                                                $scope.myDz.options.maxFiles = $scope.myDz.options.maxFiles - 1;
                                                $scope.myDz.files.push(mockFile);
                                            });
                                        });

                                    }
                                }).catch(function (error)
                        {
                            $rootScope.loader = false;
                            consoel.log(error);

                        });

                    } else
                    {
//                        if (!$scope.product_quotation.product_id)
//                        {
//                            $scope.product_quotation.product_id = {};
//                        }
                        $scope.product_quotation.quantity = 1;
                        $scope.getAllPickUpLocations(null);
                        $scope.getCategorys();
                        $scope.getSubCategorys();
                        $scope.action = 'Add';
                    }

                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
        });
    };

//    $scope.getSellersFromWP();
    $scope.getSellers();

    $scope.getTaxClassFromWP = function ()
    {

        $http.jsonp(site_settings.wp_api_url + 'tlvotherinfo.php?skey=tlvesbyat&tax_class=true', {jsonpCallbackParam: 'callback'})
                .then(function (response)
                {
                    $scope.tax_class = response.data;
                    console.log(response);
                })
                .catch(function (response)
                {
                    console.log(response);
                });
    };
    $scope.getTaxClassFromWP();

    $scope.getShippingClassFromWP = function ()
    {

        $http.jsonp(site_settings.wp_api_url + 'tlvotherinfo.php?skey=tlvesbyat&shipping_class=true', {jsonpCallbackParam: 'callback'})
                .then(function (response)
                {
                    $scope.shipping_class = response.data;
                    console.log(response);
                })
                .catch(function (response)
                {
                    console.log(response);
                });
    };
    $scope.getShippingClassFromWP();


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



//    $scope.getCategorys();
//    $scope.getSubCategorys();





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
    $scope.dzMethods = {};
    $scope.removeNewFile = function ()
    {
        $scope.dzMethods.removeFile($scope.newFile); //We got $scope.newFile from 'addedfile' event callback
    }

    $scope.saveProductForProduction = function ()
    {

//        for (var i in $scope.sellers)
//        {
//            if ($scope.sellers[i].ID == $scope.product_quotation.product_id.seller_id)
//            {
//                $scope.product_quotation.product_id.seller_firstname = $scope.sellers[i].data.display_name.charAt(0);
//                var temp = $scope.sellers[i].data.display_name.split(" ");
//                $scope.product_quotation.product_id.seller_lastname = temp[1].substr(0, 3);
//            }
//        }
        $scope.product_quotation.images = [];
        $scope.product_quotation.images = $scope.product_pending_images;
        $scope.product_quotation.passfrom = 'product_for_production';
        $http.post(site_settings.api_url + 'product_final/save_product_quotation_final', $scope.product_quotation)
                .then(function (response)
                {

                    $rootScope.message = response.data;
                    $rootScope.$emit("notification");
//                    $rootScope.$emit("reloadUserTable");
                    $rootScope.$emit("reloadProductForProductionTable");
                    $mdDialog.hide();
                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong';
            $rootScope.$emit("notification");
        });
    };
    $scope.removeImage = function (file) {
        if (!$scope.myDz)
        {
            $scope.myDz = $scope.dzMethods.getDropzone();

        }
        $scope.myDz.emit('removedfile', file);
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

//            console.log($scope.current_dropzone_index);
            console.log(xhr.id);
            $scope.product_pending_images.push(xhr.id);

            xhr.name = xhr.filename;
            $scope.product_pending_images_name.push(xhr);
            return false;
        },
        'removedfile': function (file, response)
        {

            var data = {};
            data.folder = 'product';
//            data.product_id = product;

            if (file.id == undefined)
            {
                for (var i in $scope.product_pending_images)
                {
                    if ($scope.product_pending_images[i] == JSON.parse(file.xhr.responseText).id)
                    {
                        $scope.product_pending_images.splice(i, 1);
                        $scope.product_pending_images_name.splice(i, 1);
                    }
                }

                data.name = JSON.parse(file.xhr.responseText).filename;
                data.id = JSON.parse(file.xhr.responseText).id;
            } else
            {
                for (var i in $scope.product_pending_images)
                {
                    if ($scope.product_pending_images[i] == file.id)
                    {
                        $scope.product_pending_images.splice(i, 1);
                        $scope.product_pending_images_name.splice(i, 1);
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
                        console.log($scope.product_pending_images);
                    }).catch(function (error)
            {
            });
        },
    };

    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };
}).config(function (dropzoneOpsProvider)
{
    dropzoneOpsProvider.setOptions({
        url: '/api/product/uploadImages',
        maxFilesize: '10',
        paramName: 'photo',
        addRemoveLinks: true,
        maxFiles: 10,
        acceptedFiles: 'image/*',
        thumbnailWidth: '200',
        thumbnailHeight: '200',
        parallelUploads:1,
        dictDefaultMessage: 'Upload Product Images Here',
        maxfilesexceeded: function (file)
        {
//            this.removeAllFiles();
            this.addFile(file);
        },
        sending: function (file, xhr, formData)
        {
            console.log(formData)
            formData.append('folder', 'product');
        },
    });
});

app.controller('ProductForProductionViewController', function (product_quotation, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.product = {};
    $scope.products_count = 1;
    $scope.products_combo = [{name: '', price: '', description: ''}];
    $scope.product = {};
//    $scope.product.cat = [];
    $scope.edit = false;
    $scope.getSellersFromWP = function ()
    {
        $rootScope.loader = true;

        $http.jsonp(site_settings.wp_api_url + 'seller-api.php', {jsonpCallbackParam: 'callback'})
                .then(function (response)
                {
                    $scope.sellers = response.data;
                    if (product_quotation)
                    {
                        $scope.edit = true;
                        $scope.action = 'View';
                        $http.post(site_settings.api_url + 'product_quotation/get_product_quotation', {id: product_quotation})
                                .then(function (response)
                                {
                                    $scope.product_quotation = response.data;
                                    for (var i in $scope.sellers)
                                    {

                                        if ($scope.product_quotation.product_id.seller_id == $scope.sellers[i].ID)
                                        {
                                            $scope.product_quotation.product_id.seller_name = $scope.sellers[i].data.display_name;
                                            $scope.product_quotation.product_id.seller_email = $scope.sellers[i].data.user_email;
                                        }
                                    }
                                    $rootScope.loader = false;

                                }).catch(function (error)
                        {
                            $rootScope.loader = false;
                            console.log(error);

                        });

                    } else
                    {
                        $scope.action = 'Add';
                    }
                })
                .catch(function (response)
                {
                    console.log(response);
                    $rootScope.loader = false;
                });
    };

    $scope.getSellers = function ()
    {
        $rootScope.loader = true;
        $http.get(site_settings.api_url + 'seller/get_all_seller')
                .then(function (response)
                {
                    $scope.sellers = response.data;

                    if (product_quotation)
                    {
                        $scope.edit = true;
                        $scope.action = 'View';
                        $http.post(site_settings.api_url + 'product_quotation/get_product_quotation', {id: product_quotation})
                                .then(function (response)
                                {
                                    $rootScope.loader = false;
                                    $scope.product_quotation = response.data;
                                    for (var i in $scope.sellers)
                                    {

                                        if ($scope.product_quotation.product_id.sellerid.id == $scope.sellers[i].id)
                                        {
                                            $scope.product_quotation.product_id.seller_name = $scope.sellers[i].displayname;
                                            $scope.product_quotation.product_id.seller_email = $scope.sellers[i].email;
                                        }
                                    }
                                    $rootScope.loader = false;

                                }).catch(function (error)
                        {
                            $rootScope.loader = false;
                            console.log(error);

                        });

                    } else
                    {
                        $scope.action = 'Add';
                    }

                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
        });
    };

//    $scope.getSellersFromWP();
    $scope.getSellers();

    $scope.getShipingClass = function (slug)
    {
        for (var i in $scope.shipping_class)
        {
            if ($scope.shipping_class[i].slug == slug)
            {
                return $scope.shipping_class[i].name;
            }
        }

    };
    $scope.getTaxClassFromWP = function ()
    {

        $http.jsonp(site_settings.wp_api_url + 'tlvotherinfo.php?skey=tlvesbyat&tax_class=true', {jsonpCallbackParam: 'callback'})
                .then(function (response)
                {
                    $scope.tax_class = response.data;
                    console.log(response);
                })
                .catch(function (response)
                {
                    console.log(response);
                });
    };
    $scope.getTaxClassFromWP();

    $scope.getShippingClassFromWP = function ()
    {

        $http.jsonp(site_settings.wp_api_url + 'tlvotherinfo.php?skey=tlvesbyat&shipping_class=true', {jsonpCallbackParam: 'callback'})
                .then(function (response)
                {
                    $scope.shipping_class = response.data;
                    console.log(response);
                })
                .catch(function (response)
                {
                    console.log(response);
                });
    };
    $scope.getShippingClassFromWP();

    $scope.printDetails = function ()
    {
        var popupWin = window.open('', '_blank', 'width=300,height=300');
        popupWin.document.open();

        var action_html = '';

        action_html += '<html>';
        action_html += '<head></head><body onload="window.print()">';

        action_html += '<img style="height: 130px; width: 280px; margin-left:40%;" src="../../../../assets/images/site_logo.png">';

        // ########################### table #########################

        action_html += '<table cellpadding="10" border="1" style="border-collapse: collapse; width:100%; margin-top: 15px;">';

        action_html += '<tr style="text-align: center;">';
        action_html += '<th colspan="6">';
        action_html += 'Seller Information';
        action_html += '</th>';
        action_html += '</tr>';

        action_html += '<tr>';
        action_html += '<th>';
        action_html += 'Seller Name';
        action_html += '</th>';
        action_html += '<td>';
        action_html += $scope.product_quotation.product_id.seller_name;
        action_html += '</td>';
        action_html += '<th>';
        action_html += 'Seller Email';
        action_html += '</th>';
        action_html += '<td>';
        action_html += $scope.product_quotation.product_id.seller_email;
        action_html += '</td>';
        action_html += '<th>';
        action_html += 'Seller Phone';
        action_html += '</th>';
        action_html += '<td>';
        action_html += $scope.product_quotation.product_id.sellerid.phone;
        action_html += '</td>';
        action_html += '</tr>';

        action_html += '</table>';

        // ########################### table #########################

        // ########################### table #########################

        action_html += '<table cellpadding="10" border="1" style="border-collapse: collapse; width:100%; margin-top: 25px;">';

        action_html += '<tr style="text-align: center;">';
        action_html += '<th colspan="8">';
        action_html += 'Product Information';
        action_html += '</th>';
        action_html += '</tr>';

        action_html += '<tr>';
        action_html += '<th>';
        action_html += 'SKU';
        action_html += '</th>';
        action_html += '<td>';
        action_html += $scope.product_quotation.product_id.sku;
        action_html += '</td>';
        action_html += '<th>';
        action_html += 'Name';
        action_html += '</th>';
        action_html += '<td>';
        action_html += $scope.product_quotation.product_id.name;
        action_html += '</td>';
        action_html += '<th>';
        action_html += 'Estimated Retail Price';
        action_html += '</th>';
        action_html += '<td>';
        action_html += $scope.product_quotation.product_id.price;
        action_html += '</td>';
        action_html += '<th>';
        action_html += 'State';
        action_html += '</th>';
        action_html += '<td>';
        action_html += $scope.product_quotation.product_id.state;
        action_html += '</td>';
        action_html += '</tr>';

        action_html += '<tr>';
        action_html += '<th>';
        action_html += 'Quantity';
        action_html += '</th>';
        action_html += '<td>';
        action_html += $scope.product_quotation.quantity;
        action_html += '</td>';
        action_html += '<th>';
        action_html += 'TLV Suggested Price Max';
        action_html += '</th>';
        action_html += '<td>';
        action_html += $scope.product_quotation.tlv_suggested_price_max;
        action_html += '</td>';
        action_html += '<th>';
        action_html += 'Short Description';
        action_html += '</th>';
        action_html += '<td>';
        action_html += $scope.product_quotation.sort_description;
        action_html += '</td>';
        action_html += '<th>';
        action_html += 'City';
        action_html += '</th>';
        action_html += '<td>';
        action_html += $scope.product_quotation.product_id.city;
        action_html += '</td>';
        action_html += '</tr>';

        action_html += '</table>';

        // ########################### table #########################

        // ########################### table #########################

        action_html += '<table cellpadding="10" border="1" style="border-collapse: collapse; width:100%; margin-top: 25px;">';

        action_html += '<tr style="text-align: center;">';
        action_html += '<th colspan="6">';
        action_html += 'Stock Details';
        action_html += '</th>';
        action_html += '</tr>';

        action_html += '<tr>';
        action_html += '<th>';
        action_html += 'Stock Status';
        action_html += '</th>';
        action_html += '<td>';
        action_html += $scope.product_quotation.stock_status;
        action_html += '</td>';
        action_html += '<th>';
        action_html += 'Manage Stock';
        action_html += '</th>';
        action_html += '<td>';
        action_html += $scope.product_quotation.manage_stock;
        action_html += '</td>';
        action_html += '</tr>';

        action_html += '<tr>';
        action_html += '<th>';
        action_html += 'Tax Status';
        action_html += '</th>';
        action_html += '<td>';
        action_html += $scope.product_quotation.tax_status;
        action_html += '</td>';
        action_html += '<th>';
        action_html += 'Tax Class';
        action_html += '</th>';
        action_html += '<td>';
        if ($scope.product_quotation.tax_class != '' && $scope.product_quotation.tax_class != null)
        {
            action_html += $scope.tax_class[$scope.product_quotation.tax_class];
        } else
        {
            action_html += '---';
        }
        action_html += '</td>';
        action_html += '</tr>';

        action_html += '<tr>';
        action_html += '<th>';
        action_html += 'Shipping Class';
        action_html += '</th>';
        action_html += '<td colspan="3">';
        if ($scope.getShipingClass($scope.product_quotation.shipping_class))
        {
            action_html += $scope.getShipingClass($scope.product_quotation.shipping_class);
        } else
        {
            action_html += '---';
        }
        action_html += '</td>';
        action_html += '</tr>';

        action_html += '</table>';

        // ########################### table #########################

        // ########################### table #########################

        action_html += '<table cellpadding="10" border="1" style="border-collapse: collapse; width:100%; margin-top: 25px;">';

        action_html += '<tr style="text-align: center;">';
        action_html += '<th colspan="6">';
        action_html += 'Dimensions';
        action_html += '</th>';
        action_html += '</tr>';

        action_html += '<tr>';
        action_html += '<th>';
        action_html += 'Width';
        action_html += '</th>';
        action_html += '<td>';
        action_html += $scope.product_quotation.width;
        action_html += '</td>';
        action_html += '<th>';
        action_html += 'Height';
        action_html += '</th>';
        action_html += '<td>';
        action_html += $scope.product_quotation.height;
        action_html += '</td>';
        action_html += '</tr>';

        action_html += '<tr>';
        action_html += '<th>';
        action_html += 'Length';
        action_html += '</th>';
        action_html += '<td>';
        action_html += $scope.product_quotation.length;
        action_html += '</td>';
        action_html += '<th>';
        action_html += 'Weight';
        action_html += '</th>';
        action_html += '<td>';
        action_html += $scope.product_quotation.weight;
        action_html += '</td>';
        action_html += '</tr>';

        action_html += '</table>';

        // ########################### table #########################

        // ########################### table #########################

        action_html += '<table cellpadding="10" border="1" style="border-collapse: collapse; width:100%; margin-top: 25px;">';

        action_html += '<tr style="text-align: center;">';
        action_html += '<th colspan="6">';
        action_html += 'Category Information';
        action_html += '</th>';
        action_html += '</tr>';

        action_html += '<tr>';
        action_html += '<th>';
        action_html += 'Brand';
        action_html += '</th>';
        action_html += '<td>';
        if ($scope.product_quotation.product_id.brand)
        {
            action_html += $scope.product_quotation.product_id.brand.sub_category_name;
        } else
        {
            action_html += '---';
        }
        action_html += '</td>';
        action_html += '<th>';
        action_html += 'Category';
        action_html += '</th>';
        action_html += '<td>';
        if ($scope.product_quotation.product_id.category)
        {
            action_html += $scope.product_quotation.product_id.category.sub_category_name;
        } else
        {
            action_html += '---';
        }
        action_html += '</td>';
        action_html += '</tr>';

        action_html += '<tr>';
        action_html += '<th>';
        action_html += 'Room';
        action_html += '</th>';
        action_html += '<td>';
        if ($scope.product_quotation.product_id.room)
        {
            action_html += $scope.product_quotation.product_id.room.sub_category_name;
        } else
        {
            action_html += '---';
        }
        action_html += '</td>';
        action_html += '<th>';
        action_html += 'Color';
        action_html += '</th>';
        action_html += '<td>';
        if ($scope.product_quotation.product_id.color)
        {
            action_html += $scope.product_quotation.product_id.color.sub_category_name;
        } else
        {
            action_html += '---';
        }
        action_html += '</td>';
        action_html += '</tr>';

        action_html += '<tr>';
        action_html += '<th>';
        action_html += 'Condition';
        action_html += '</th>';
        action_html += '<td colspan="3">';
        if ($scope.product_quotation.product_id.color)
        {
            action_html += $scope.product_quotation.product_id.con.sub_category_name;
        } else
        {
            action_html += '---';
        }
        action_html += '</td>';
        action_html += '</tr>';

        action_html += '</table>';

        // ########################### table #########################

        action_html += '</body>';
        action_html += '</html>';

        popupWin.document.write(action_html);
        popupWin.document.close();
    };

    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };
})