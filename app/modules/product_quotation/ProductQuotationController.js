"use strict";

var app = angular.module('ng-app');
app.controller('ProductQuotationController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken, $window)
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
        url: site_settings.api_url + 'product_quotation/get_product_quotations',
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
                        $scope.Accept = false;
                        $scope.Reject = false;
                        $scope.Archive = false;
                        $scope.Delete = false;
                        $compile(nRow)($scope);
                    });




    function actionsHtml(data, type, full, meta)
    {

        var action_btn = '';

//        if (full.is_send_mail != 1)
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

//        if (full.status_id != 7)
        if (full.is_send_mail != 1)
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
            action_btn += '<span class="text-boxed m-0 deep-blue-bg white-fg" style="cursor: pointer; background-color: #A9A9A9  !important;">VIEW</span>';

        }

//        action_btn += '<md-button aria-label="VIEW" ng-click="openProductQuotationViewDialog(' + data.id + ');" class="md-fab md-accent md-raised md-mini">';
//        action_btn += '<md-icon  md-font-icon="icon-eye" aria-label="VIEW"></md-icon>';
//        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">VIEW PRODUCT</md-tooltip>';
//        action_btn += '</md-button>';

//        action_btn += '<span ng-click="openProductQuotationViewDialog(' + data.id + ');" class="text-boxed m-0 deep-blue-bg white-fg" style="cursor: pointer; background-color: #1eaa36 !important; margin-left: 5px;">VIEW</span>';


//        action_btn += '</md-fab-actions></md-fab-speed-dial>';
        return action_btn;
//        $scope.persons[data.id] = data;
//        return '<md-button aria-label="Edit" ui-sref="edit_profile({id:\'' + full.id + '\'})" class="md-fab md-mini md-button  md-ink-ripple">  <md-icon md-svg-src="assets/img/edit.svg" class="icon"></md-icon></md-button><md-button aria-label="Delete" type="submit" class="md-fab md-mini md-button  md-ink-ripple md-warn">  <md-icon md-font-icon="icon-delete" class="icon" ></md-icon></md-button>';
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
    function agingRender(data, type, full, meta)
    {
        if (full.for_production_created_at != null)
        {

            var startDate = moment.utc(full.quote_created_at.date).local();
            var endDate = moment.utc(full.for_production_created_at.date).local();

            var result = endDate.diff(startDate, 'days');
            return result + 1 + " Day";

        } else
        {
            var startDate = moment.utc(full.quote_created_at.date).local();
            var endDate = moment.utc(new Date()).local();
            var result = endDate.diff(startDate, 'days');
            return result + 1 + " Day";

        }
    }

    $scope.dtColumns = [
        DTColumnBuilder.newColumn('image').withTitle('Image').notSortable().renderWith(profileImage),
        DTColumnBuilder.newColumn('sku').withTitle('SKU').renderWith(skuRender),
        DTColumnBuilder.newColumn('name').withTitle('Product Name'),
//        DTColumnBuilder.newColumn('sell_name').withTitle('Sell Name'),
//        DTColumnBuilder.newColumn('price').withTitle('Suggested Retail Price').renderWith(renderPrice),
        DTColumnBuilder.newColumn(null).withTitle('Max / Min').renderWith(renderPriceMaxMin).notSortable(),
        DTColumnBuilder.newColumn(null).withTitle('Aging').renderWith(agingRender).notSortable(),
//        DTColumnBuilder.newColumn('price').withTitle('Price'),
//        DTColumnBuilder.newColumn('tlv_suggested_price_max').withTitle('Suggested Max Price'),
//        DTColumnBuilder.newColumn('tlv_suggested_price_min').withTitle('Suggested Min Price'),

        DTColumnBuilder.newColumn('is_send_mail').withTitle('Status').renderWith(statusHtml).notSortable(),
//        DTColumnBuilder.newColumn('images_from').withTitle('Schedule').renderWith(schedule).notSortable(),
        DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable()
                .renderWith(actionsHtml),
    ];
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
    function schedule(data, type, full, meta)
    {

        console.log(full.is_scheduled);
//        if (full.images_from == 1)
//        {
        if (full.is_scheduled == '1')
        {
            return '<button ng-disabled="true" aria-label="{{hover_' + full.id + ' ==true ? \'Schedule\' : \'Schedule\' }}"  class="md-primary md-raised md-button md-jd-theme md-ink-ripple"\n\
                     ng-class="hover==true ? md-warn : md-primary"    ng-mouseenter="hover_' + full.id + ' = true"    ng-mouseleave="hover_' + full.id + ' = false"\n\
                     type="button" ng-click="takeSchedule(\'' + full.id + '\')" >Schedule</button>';

        } else if (full.images_from == 1)
        {
            return '<button aria-label="{{hover_' + full.id + ' ==true ? \'Schedule\' : \'Schedule\' }}"  class="md-primary md-raised md-button md-jd-theme md-ink-ripple"\n\
                     ng-class="hover==true ? md-warn : md-primary"    ng-mouseenter="hover_' + full.id + ' = true"    ng-mouseleave="hover_' + full.id + ' = false"\n\
                     type="button" ng-click="takeSchedule(\'' + full.id + '\')" >Schedule</button>';
        } else if (full.images_from == 0)
        {
            return '<button ng-disabled="true" aria-label="{{hover_' + full.id + ' ==true ? \'Schedule\' : \'Schedule\' }}"  class="md-primary md-raised md-button md-jd-theme md-ink-ripple"\n\
                     ng-class="hover==true ? md-warn : md-primary"    ng-mouseenter="hover_' + full.id + ' = true"    ng-mouseleave="hover_' + full.id + ' = false"\n\
                     type="button" ng-click="takeSchedule(\'' + full.id + '\')" >Schedule</button>';

        }
//        }
//        else
//        {
//            return '<button ng-disabled="true" aria-label="{{hover_' + full.id + ' ==true ? \'Schedule\' : \'Schedule\' }}"  class="md-primary md-raised md-button md-jd-theme md-ink-ripple"\n\
//                     ng-class="hover==true ? md-warn : md-primary"    ng-mouseenter="hover_' + full.id + ' = true"    ng-mouseleave="hover_' + full.id + ' = false"\n\
//                     type="button" ng-click="takeSchedule(\'' + full.id + '\')" >Schedule</button>';
//        }

    }



    function statusHtml00(data, type, full, meta)
    {
        console.log(full);
        if (full.is_send_mail == 0)
        {
            return '<button aria-label="{{hover_' + full.id + ' ==true ? \'Schedule\' : \'Schedule\' }}"  class="md-primary md-raised md-button md-jd-theme md-ink-ripple"\n\
                     ng-class="hover==true ? md-warn : md-primary"    ng-mouseenter="hover_' + full.id + ' = true"    ng-mouseleave="hover_' + full.id + ' = false"\n\
                     type="button" ng-click="sendMail(\'' + full.id + '\',' + full.is_updated_details + ')" >Send Proposal</button>';

        } else if (full.is_send_mail == 1)
        {
            return '<button ng-disabled="true" aria-label="{{hover_' + full.id + ' ==true ? \'Schedule\' : \'Schedule\' }}"  class="md-primary md-raised md-button md-jd-theme md-ink-ripple"\n\
                     ng-class="hover==true ? md-warn : md-primary"    ng-mouseenter="hover_' + full.id + ' = true"    ng-mouseleave="hover_' + full.id + ' = false"\n\
                     type="button" ng-click="sendMail(\'' + full.id + '\')" >Send Proposal</button>';
        }
    }

    function statusHtml(data, type, full, meta)
    {
//        console.log(full);
        $scope.select[full.id] = 0;
        var action_btn = '';

        if (full.is_send_mail == 0)
        {
            if (full.is_updated_details == 1)
            {
                action_btn += '<md-radio-group ng-click="changeProductStatus(' + full.id + ',select[' + full.id + '])" ng-model="select[' + full.id + ']" layout="row" layout-xs="column">';
                action_btn += '<md-radio-button value="1">Accept</md-radio-button>';
                action_btn += '<md-radio-button value="3">Archive</md-radio-button>';
                action_btn += '<md-radio-button value="2">Reject</md-radio-button>';
                action_btn += '<md-radio-button value="85">Delete</md-radio-button>';
                action_btn += '</md-radio-group>';

                return action_btn;
            } else if (full.is_updated_details == 0)
            {
                return '<span style="color: red;">Please Update Product Details</span>';
            }
        } else if (full.is_send_mail == 1)
        {
            return 'Accepted';
        } else if (full.is_send_mail == 2)
        {
            return 'Rejected';
        }

    }


    $scope.AllAcceptProductSelected = function ()
    {
        $scope.productStatus = [];
        if ($scope.Accept)
        {
            $scope.Reject = false;
            $scope.Archive = false;
            $scope.Delete = false;
            for (var i in $scope.select)
            {
                $scope.select[i] = 1;
                $scope.productStatus.push({'id': i, is_send_mail: 1});
            }
        } else
        {
            $scope.Reject = false;
            $scope.Archive = false;
            $scope.Delete = false;
            for (var i in $scope.select)
            {
                $scope.productStatus = [];
                $scope.select[i] = 0;
            }

        }

    }
    $scope.AllRejectProductSelected = function ()
    {
        $scope.productStatus = [];
        if ($scope.Reject)
        {
            $scope.Accept = false;
            $scope.Archive = false;
            $scope.Delete = false;
            for (var i in $scope.select)
            {
                $scope.select[i] = 2;
                $scope.productStatus.push({'id': i, is_send_mail: 2});
            }
        } else
        {
            $scope.Accept = false;
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
            $scope.Accept = false;
            $scope.Reject = false;
            $scope.Delete = false;
            for (var i in $scope.select)
            {
                $scope.select[i] = 3;
                $scope.productStatus.push({'id': i, is_send_mail: 3});
            }
        } else
        {
            $scope.Accept = false;
            $scope.Reject = false;
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
            $scope.Accept = false;
            $scope.Archive = false;
            $scope.Reject = false;
            for (var i in $scope.select)
            {
                $scope.select[i] = 85;
                $scope.productStatus.push({'id': i, is_send_mail: 85});
            }
        } else
        {
            $scope.Accept = false;
            $scope.Reject = false;
            $scope.Archive = false;
            for (var i in $scope.select)
            {
                $scope.productStatus = [];
                $scope.select[i] = 0;
            }

        }
    }

    $scope.changeProductStatus = function (product, is_send_mail)
    {
        var count = 0;
        for (var i = 0; i < $scope.productStatus.length; i++)
        {
            if ($scope.productStatus[i].id == product)
            {
                $scope.productStatus[i].is_send_mail = is_send_mail;
            } else
            {
                count++;
            }

        }
        if (count == $scope.productStatus.length)
        {
            $scope.productStatus.push({'id': product, is_send_mail: is_send_mail});
        }

    };

    $scope.changeProductQuotationStatus = function (id, status_id)
    {
        $http.post(site_settings.api_url + 'product_quotation/change_product_quotation_status', {product_quotation_id: id, product_quotation_status_id: status_id})
                .then(function (response)
                {
                    $rootScope.message = 'Status change successfully.';
                    $scope.productStatus = [];
                    $rootScope.$emit("notification");
                    $rootScope.$emit("reloadUserTable");
                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
        });
    };
    $scope.takeSchedule = function (product_quotation_id)
    {
        $mdDialog.show({
            controller: 'ScheduleAddController',
            templateUrl: 'app/modules/product_quotation/views/schedule_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                product_quotation_id: product_quotation_id
            }
        });
    };
//    $scope.sendMail = function (product_quotation, images_from, is_scheduled)
    $scope.downloadDocumentInWordProposal = function ()
    {
        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'export_products_document_in_word_proposal', {products: $scope.productStatus, seller: $stateParams.id})
                .then(function (response)
                {
                    $rootScope.loader = false;
                    $scope.productStatus = [];
                    var b = response.data;
                    var a = document.createElement('a');
                    document.getElementById("content").appendChild(a);
                    a.download = b;
                    a.target = '_blank';
                    a.id = b;
                    a.href = '../Uploads/word/' + b;
                    a.click();
                    document.body.removeChild(a);
                }).catch(function (error)
        {
            $rootScope.loader = false;
//                $rootScope.message = 'Something Went Wrong';
//                $rootScope.$emit("notification");
            console.log(error);
        });

    }
    $scope.sendMailReject = function ()
    {
        var rejected = [];
        for (var i = 0; i < $scope.productStatus.length; i++)
        {
            if ($scope.productStatus[i].is_send_mail == 2)
            {
                rejected.push($scope.productStatus[i]);
            }

        }
        console.log(rejected);
        if (rejected.length > 0)
        {

            var confirm = $mdDialog.confirm()
                    .title('Are you sure you want to reject product?')
                    .ok('Yes')
                    .cancel('No');

            $mdDialog.show(confirm).then(function ()
            {
                $rootScope.loader = true;
                $http.post(site_settings.api_url + 'product_quotation/send_mail_reject', {products: rejected})
                        .then(function (response)
                        {

                            $rootScope.message = 'Proposal has been successfully Rejected';
                            $rootScope.$emit("notification");
                            $scope.productStatus = [];
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
            $rootScope.message = 'Please select at least one product';
            $rootScope.$emit("notification");

        }

    }

    $scope.DeleteSelectedProducts = function ()
    {
        var rejected = [];
        for (var i = 0; i < $scope.productStatus.length; i++)
        {
            if ($scope.productStatus[i].is_send_mail == 85)
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
                $http.post(site_settings.api_url + 'product_quotation/delete_product_quotation', {products: rejected})
                        .then(function (response)
                        {

                            $rootScope.message = 'Proposal has been successfully Deleted';
                            $rootScope.$emit("notification");
                            $scope.productStatus = [];
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
            $rootScope.message = 'Please select at least one product';
            $rootScope.$emit("notification");

        }

    }

    $scope.sendToNextStep = function ()
    {
        var approved = [];
        for (var i = 0; i < $scope.productStatus.length; i++)
        {
            if ($scope.productStatus[i].is_send_mail == 1)
            {
                approved.push($scope.productStatus[i]);
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
                $http.post(site_settings.api_url + 'product_quotation/send_mail_approve_status_change', {products: approved, seller: $stateParams.id})
                        .then(function (response)
                        {
                            $rootScope.message = 'Proposal Successfully Accepted';
                            $scope.productStatus = [];
                            $rootScope.$emit("notification");
                            $rootScope.loader = false;
                            $rootScope.$emit("reloadUserTable");
                        }).catch(function (error)
                {
                    $rootScope.loader = false;
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
    $scope.sendMailApprove = function ()
    {
        var approved = [];
        for (var i = 0; i < $scope.productStatus.length; i++)
        {
            if ($scope.productStatus[i].is_send_mail == 1)
            {
                approved.push($scope.productStatus[i]);
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
                            document.getElementById("content1").appendChild(a);
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

    $scope.sendMail = function ()
    {
        var archived = [];
        for (var i = 0; i < $scope.productStatus.length; i++)
        {
            if ($scope.productStatus[i].is_send_mail == 3)
            {
                archived.push($scope.productStatus[i]);
            }

        }
        console.log(archived);
        if (archived.length > 0)
        {

            var confirm = $mdDialog.confirm()
                    .title('Are you sure you want to archive product?')
                    .ok('Yes')
                    .cancel('No');

            $mdDialog.show(confirm).then(function ()
            {
                $rootScope.loader = true;
//            $http.post(site_settings.api_url + 'product_quotation/send_mail', {products: $scope.archived})
                $http.post(site_settings.api_url + 'product_quotation/send_mail_archive', {products: archived})
                        .then(function (response)
                        {
                            $scope.productStatus = [];
                            $rootScope.message = 'Proposal has been archived.';
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
            $rootScope.message = 'Please select at least one Archive Product';
            $rootScope.$emit("notification");

        }
    };

    $scope.downloadDocumentInWord = function ()
    {
        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'export_products_document_in_word', {products: $scope.productStatus, seller: $stateParams.id})
                .then(function (response)
                {
                    $rootScope.loader = false;
                    $scope.productStatus = [];
                    var b = response.data;
                    var a = document.createElement('a');
                    document.getElementById("content").appendChild(a);
                    a.download = b;
                    a.target = '_blank';
                    a.id = b;
                    a.href = '../Uploads/word/' + b;
                    a.click();
                    document.body.removeChild(a);
                }).catch(function (error)
        {
            $rootScope.loader = false;
//                $rootScope.message = 'Something Went Wrong';
//                $rootScope.$emit("notification");
            console.log(error);
        });

    }
    $scope.sendProposal = function ()
    {
        $mdDialog.show({
            controller: 'MailProposalAddController',
            templateUrl: 'app/modules/shared/views/compose-dialog.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                seller_id: $scope.seller.id
            }
        });


    };
    $scope.downloadDocument = function ()
    {
        var confirm = $mdDialog.confirm()
                .title('Would you like to download pdf for these Products?')
                .ok('Yes')
                .cancel('No');

        $mdDialog.show(confirm).then(function ()
        {
            $rootScope.loader = true;
            $http.post(site_settings.api_url + 'export_products_document', {products: $scope.productStatus, seller: $stateParams.id})
                    .then(function (response)
                    {
                        $rootScope.loader = false;
                        $scope.productStatus = [];
                        var b = response.data;
                        var a = document.createElement('a');
                        document.getElementById("content").appendChild(a);
                        a.download = b;
                        a.target = '_blank';
                        a.id = b;
                        a.href = '../Uploads/pdf/' + b;
                        a.click();
                        document.body.removeChild(a);
                    }).catch(function (error)
            {
                $rootScope.loader = false;
//                $rootScope.message = 'Something Went Wrong';
//                $rootScope.$emit("notification");
                console.log(error);
            });
        }, function ()
        {

        });
    };

    $scope.openProductQuotationAddDialog = function (product_quotation)
    {
        $mdDialog.show({
            controller: 'ProductQuotationAddController',
            templateUrl: 'app/modules/product_quotation/views/product_quotation_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                product_quotation: product_quotation
            }
        });
    };

    $scope.openProductQuotationViewDialog = function (product_quotation)
    {
        $mdDialog.show({
            controller: 'ProductQuotationViewController',
            templateUrl: 'app/modules/product_quotation/views/product_quotation_view.html',
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
        $scope.select = [];

        reloadData();
    });
    $rootScope.$on("reloadProductQuotationTable", function (event, args)
    {
//        $window.location.reload();
        $scope.select = [];

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
app.controller('ScheduleAddController', function (product_quotation_id, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{

    $scope.schedule = {};
    $scope.schedule.product_quot_id = product_quotation_id;
    $scope.action = 'Add';
    $scope.getSellersFromWP = function ()
    {
        $rootScope.loader = true;
        $http.jsonp(site_settings.wp_api_url + 'seller-api.php', {jsonpCallbackParam: 'callback'})
                .then(function (response)
                {
                    $scope.sellers = response.data;
                    $scope.getSellerIdOfProductQuot();

                    $rootScope.loader = false;
                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong';
            $rootScope.$emit("notification");
        });


        $http.post(site_settings.api_url + 'schedule/get_schedule_by_product_quotation_id', $scope.schedule)
                .then(function (response)
                {
//                console.log(response);
                    if (response.data[0] != null)
                    {
                        $scope.schedule = response.data[0];
                        $scope.schedule.product_quot_id = $scope.schedule.product_quotation.id;
                        $scope.schedule.date = new Date($scope.schedule.date);
                        $scope.schedule.time = new Date($scope.schedule.time);
//                    $scope.schedule.time = moment($scope.schedule.time).local().format("MM/DD/YYYY HH:mm:ss");
                    }
                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong';
            $rootScope.$emit("notification");
        });
    };

    $scope.getSellers = function ()
    {

        $http.get(site_settings.api_url + 'seller/get_all_seller')
                .then(function (response)
                {
                    $scope.sellers = response.data;
                    $scope.getSellerIdOfProductQuot();
                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong.';
            $rootScope.$emit("notification");
        });

        $http.post(site_settings.api_url + 'schedule/get_schedule_by_product_quotation_id', $scope.schedule)
                .then(function (response)
                {
                    console.log('dskjdskjdskj');
                    console.log(response);
                    if (response.data[0] != null)
                    {
                        $scope.schedule = response.data[0];
                        $scope.schedule.product_quot_id = $scope.schedule.product_quotation.id;
                        $scope.schedule.date = new Date($scope.schedule.date);
                        $scope.schedule.time = new Date($scope.schedule.time);
//                    $scope.schedule.time = moment($scope.schedule.time).local().format("MM/DD/YYYY HH:mm:ss");
                    }
                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong';
            $rootScope.$emit("notification");
        });
    };

//    $scope.getSellersFromWP();
    $scope.getSellers();

    $scope.getSellerIdOfProductQuot = function ()
    {
        $http.post(site_settings.api_url + 'schedule/get_seller_of_prduct_quot', $scope.schedule)
                .then(function (response)
                {
                    $scope.seller_id = response.data;
                    for (var i in $scope.sellers)
                    {
                        if ($scope.sellers[i].id == $scope.seller_id)
                        {
                            $scope.seller_name = $scope.sellers[i].displayname;
                        }
                    }

                }).catch(function (error)
        {
            $rootScope.message = 'Something Went Wrong';
            $rootScope.$emit("notification");
        });
    };
    $scope.saveSchedule = function ()
    {
        var temp = $scope.schedule;
        console.log(temp);

        temp.date = moment($scope.schedule.date).local().format("MM/DD/YYYY");
        temp.time = moment($scope.schedule.time).local().format("MM/DD/YYYY HH:mm:ss");

        $http.post(site_settings.api_url + 'schedule/save_all_schedule_of_seller', temp)
                .then(function (response)
                {

                    $rootScope.message = 'Product has been successfully scheduled';
                    $rootScope.$emit("notification");
                    $rootScope.$emit("reloadUserTable");
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

//app.controller('SellerAddController', function ($timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
//{
//    $scope.user = {};
//    $scope.dzCallbacks = {
//        'addedfile': function (file)
//        {
//
//            if (file.isMock)
//            {
//                $scope.myDz.createThumbnailFromUrl(file, file.serverImgUrl, null, true);
//            }
//        },
//        'success': function (file, xhr)
//        {
//            $scope.user.profile_image = xhr.filename
//            return false;
//        }
//
//    };
//    $scope.dzMethods = {};
//    $scope.removeNewFile = function ()
//    {
//        $scope.dzMethods.removeFile($scope.newFile); //We got $scope.newFile from 'addedfile' event callback
//    };
//    $scope.wp_key = 'AbcBsdsa1';
//    $scope.saveUser = function ()
//    {
////        var url = 'seller-api.php';
////        url += '?email=' + $scope.user.email;
////        url += '&firstname=' + $scope.user.firstname;
////        url += '&lastname=' + $scope.user.lastname;
////        url += '&password=' + $scope.user.password;
////        url += '&shop_name=' + $scope.user.shop_name;
////        url += '&shop_url=' + $scope.user.shop_url;
////        url += '&address=' + $scope.user.address;
////        url += '&phone=' + $scope.user.phone;
////        url += '&key=' + $scope.wp_key;
////        console.log(url);
//
//        $http.post(site_settings.api_url + 'save_seller_WP', $scope.user)
//                .then(function (response)
//                {
//                    console.log(response);
//                    console.log(response.data);
//                    $rootScope.$emit("newSeller", response.data);
//
//                    $rootScope.message = 'User Saved Successfully';
//                    $rootScope.$emit("notification");
//                    $mdDialog.hide();
//                }).catch(function (error)
//        {
//            $rootScope.message = 'Something Went Wrong';
//            $rootScope.$emit("notification");
//        });
//
//
////        $http.jsonp(site_settings.wp_api_url + url, {jsonpCallbackParam: 'callback'})
////                .then(function (response) {
////                    $scope.sellers = response.data;
//////            console.log($scope.sellers);
////                })
////                .catch(function (response) {
////                    console.log(response);
////                });
//
////        $http.post(site_settings.api_url + '/signup', $scope.user)
////                .then(function (response) {
////
////                    $rootScope.message = 'User Saved Successfully';
////                    $rootScope.$emit("notification");
////                    $rootScope.$emit("reloadUserTable");
////                    $mdDialog.hide();
////                }).catch(function (error) {
////            $rootScope.message = 'Something Went Wrong';
////            $rootScope.$emit("notification");
////        });
//    };
//    $scope.closeDialog = function ()
//    {
//        $mdDialog.hide();
//    };
//}).config(function (dropzoneOpsProvider)
//{
//    dropzoneOpsProvider.setOptions({
//        url: '/api/uploadImages',
//        maxFilesize: '10',
//        paramName: 'photo',
//        addRemoveLinks: true,
//        maxFiles: 2,
//        acceptedFiles: 'image/*',
//        thumbnailWidth: '150',
//        thumbnailHeight: '150',
//        dictDefaultMessage: '<img width="200" src="/assets/images/avatars/profile.jpg">',
//        maxfilesexceeded: function (file)
//        {
//            this.removeAllFiles();
//            this.addFile(file);
//
//        },
//        sending: function (file, xhr, formData)
//        {
//
//            formData.append('folder', 'profile');
//        },
//    });
//});
app.controller('ProductQuotationAddController', function (product_quotation, $parse, $document, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.product_quotation = {};
    $scope.product_quotation.product_id = {};
    $scope.product_pending_images_name = [];
    $scope.product_pending_images = [];
    $scope.categorys = {};
    $scope.subcategorys = {};
    $scope.product = {};
    $scope.tax_class = {};
    $scope.shipping_class = {};
    $scope.edit = false;
//    $scope.action = 'ADD';
    $scope.searchTerm = '';
    $scope.product_quotation.quantity = '1';
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
    $scope.getAllPickUpLocations = function ()
    {
        console.log($scope.product_quotation.product_id.seller_id);

        $scope.pick_up_locations = [];
        $http.get(site_settings.api_url + 'getPickUpLocationsBySelectIdSellerId/6/' + $scope.product_quotation.product_id.seller_id)
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
    if ($scope.product_quotation.id)
    {
        $scope.getAllPickUpLocations();
    }

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

//    $scope.getCategorys();
//    $scope.getSubCategorys();

    $scope.getStatus();
    $scope.getCat = function ()
    {
        console.log($scope.product_quotation.product_id);
        $scope.product_quotation.product_id.cat = {};
        for (var i in $scope.categorys)
        {
            if ($scope.product_quotation.product_id[$scope.categorys[i].category_name.toLowerCase()] != null && $scope.categorys[i].category_name.toLowerCase() != 'condition')
            {
                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = $scope.product_quotation.product_id[$scope.categorys[i].category_name.toLowerCase()].id;
            } else if ($scope.product_quotation.product_id['product_room'] != null && $scope.categorys[i].category_name.toLowerCase() == 'room')
            {
                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = [];
                for (var v in $scope.product_quotation.product_id['product_room'])
                {
                    $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name].push($scope.product_quotation.product_id['product_room'][v].id);
                }
            } else if ($scope.product_quotation.product_id['product_color'] != null && $scope.categorys[i].category_name.toLowerCase() == 'color')
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
            } else if ($scope.product_quotation.product_id['con'] != null && $scope.categorys[i].category_name.toLowerCase() == 'condition')
            {
                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = $scope.product_quotation.product_id['con'].id;
            }
        }
    };

    $scope.getSellersFromWP = function ()
    {
        $rootScope.loader = true;

        $http.jsonp(site_settings.wp_api_url + 'seller-api.php', {jsonpCallbackParam: 'callback'})
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
                                    if ($scope.product_quotation.product_id.pick_up_location)
                                    {
                                        $scope.product_quotation.product_id.pick_up_location = $scope.product_quotation.product_id.pick_up_location.id;
                                    }
                                    if ($scope.product_quotation.product_id.age)
                                    {
                                        $scope.product_quotation.product_id.age = $scope.product_quotation.product_id.age.id;
                                    }

                                    $scope.product_quotation.product_id.seller_id = $scope.product_quotation.product_id.sellerid.id;
                                    $scope.getAllPickUpLocations();
                                    $scope.product_pending_images = [];


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

    $scope.saveProductQuotation = function (with_send_mail)
    {
        if (with_send_mail == 1)
        {
            if ($scope.product_quotation.is_send_mail == 1 || $scope.product_quotation.is_send_mail == 2)
            {

            } else
            {
                $rootScope.message = 'Please select Accept or reject.';
                $rootScope.$emit("notification");
                return false;
            }

        }

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
        $scope.product_quotation.with_send_mail = with_send_mail;
        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'product_quotation/save_product_quotation', $scope.product_quotation)
                .then(function (response)
                {

                    $rootScope.loader = false;
                    if ($scope.product_quotation.with_send_mail == 1)
                    {
                        if ($scope.product_quotation.is_send_mail == 1)
                        {
                            var b = response.data;
                            var a = document.createElement('a');
                            document.getElementById("content1").appendChild(a);
                            a.download = b;
                            a.target = '_blank';
                            a.id = b;
                            a.href = 'api/storage/exports/' + b;
                            a.click();
                            $rootScope.message = "Proposal has been send successfully.";
                        } else if ($scope.product_quotation.is_send_mail == 2)
                        {
                            $rootScope.message = "Proposal has been rejected";
                        }

                    } else
                    {

                        $rootScope.message = response.data;

                    }
                    $rootScope.$emit("notification");
//                    $rootScope.$emit("reloadUserTable");
                    $rootScope.$emit("reloadProductQuotationTable");
                    $mdDialog.hide();
                }).catch(function (error)
        {
            $rootScope.loader = false;
            $rootScope.message = 'Something Went Wrong';
            $rootScope.$emit("notification");
        });
//        } else
//        {
//            $rootScope.message = 'Please select Accept or reject.';
//            $rootScope.$emit("notification");
//        }
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
//            console.log(xhr);
//            console.log(file);
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

app.controller('ProductQuotationViewController', function (product_quotation, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
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

//                    $scope.getCategorys();
//                    $scope.getSubCategorys();

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
            $scope.user.profile_image = xhr.filename
            return false;
        }

    };
    $scope.dzMethods = {};
    $scope.removeNewFile = function ()
    {
        $scope.dzMethods.removeFile($scope.newFile); //We got $scope.newFile from 'addedfile' event callback
    }

    $scope.saveProduct = function ()
    {
        $scope.product.products = $scope.products_combo;

        $http.post(site_settings.api_url + 'product/edit_product', $scope.product)
                .then(function (response)
                {

                    $rootScope.message = response.data;
                    $rootScope.$emit("notification");
                    $rootScope.$emit("reloadUserTable");
                    $mdDialog.hide();
                }).catch(function (error)
        {
            $rootScope.message = error.data;
            $rootScope.$emit("notification");
        });
    };




    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };
});

app.controller('MailProposalAddController', function (seller_id, $parse, $document, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.form = {};
    $scope.seller = {};
    $scope.closeDialog = function ()
    {
        $mdDialog.hide();

    }
    $scope.getSellerById = function (id)
    {
        $http.post(site_settings.api_url + 'seller/get_edit_seller', {id: seller_id})
                .then(function (response)
                {
                    $scope.seller = response.data;
                    $scope.form.attachment = $scope.seller.last_proposal_file_name;
                    $scope.form.seller_id = $scope.seller.id;
//                    $scope.user.password = '********';
//                    console.log($scope.user);
                }).catch(function (error)
        {

        });
    };

    $scope.getSellerById($stateParams.id);
    $scope.sendMail = function ()
    {
        $http.post(site_settings.api_url + 'product_quote/send_proposal', $scope.form)
                .then(function (response)
                {
                    $rootScope.message = 'Mail Sent Successfully';
                    $rootScope.$emit("notification");
                    $mdDialog.hide();
                });
    }

});