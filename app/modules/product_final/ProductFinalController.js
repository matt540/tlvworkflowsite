"use strict";

var app = angular.module('ng-app');
app.controller('ProductFinalController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken, $window)
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
    $scope.productStatus = [];
    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('ajax', {
        dataSrc: "data",
        url: site_settings.api_url + 'product_final/get_product_finals',
        type: "POST",
        headers: {Authorization: 'Bearer ' + ngAAToken.getToken()},
        data: {id: $stateParams.id}
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
                    function (nRow, aData, iDisplayIndex, iDisplayIndexFull)
                    {
                        $scope.productStatus = [];
                        $scope.Reject = false;
                        $scope.Archive = false;
                        $scope.Delete = false;
                        $scope.Sync = false;
                        $scope.Queue = false;
                        $scope.Accept = false;
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

        if (full.status_id == 17)
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

//        action_btn += '<md-button aria-label="VIEW" ng-click="openProductFinalViewDialog(' + data.id + ');" class="md-fab md-accent md-raised md-mini">';
//        action_btn += '<md-icon  md-font-icon="icon-eye" aria-label="VIEW"></md-icon>';
//        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">VIEW PRODUCT</md-tooltip>';
//        action_btn += '</md-button>';

//        action_btn += '<span ng-click="openProductFinalViewDialog(' + data.id + ');" class="text-boxed m-0 deep-blue-bg white-fg" style="cursor: pointer; background-color: #1eaa36 !important; margin-left: 5px;">VIEW</span>';

//        action_btn += '</md-fab-actions></md-fab-speed-dial>';
        return action_btn;
//        $scope.persons[data.id] = data;
//        return '<md-button aria-label="Edit" ui-sref="edit_profile({id:\'' + full.id + '\'})" class="md-fab md-mini md-button  md-ink-ripple">  <md-icon md-svg-src="assets/img/edit.svg" class="icon"></md-icon></md-button><md-button aria-label="Delete" type="submit" class="md-fab md-mini md-button  md-ink-ripple md-warn">  <md-icon md-font-icon="icon-delete" class="icon" ></md-icon></md-button>';
    }

    $scope.dtInstance = {};


//    function reloadData()
//    {
//
//        var resetPaging = false;
//        $scope.dtInstance.reloadData(callback, resetPaging);
//    }

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
    function agingRender(data, type, full, meta)
    {
        if (full.status_id == 18 || full.status_id == 83)
        {
            if (full.approved_created_at != null)
            {

                var startDate = moment.utc(full.approved_created_at.date).local();
                var endDate = moment.utc(full.updated_at.data).local();

                var result = endDate.diff(startDate, 'days');
                return result + 1 + " Day";
            } else
            {
                return "1 Day";
            }

        } else
        {
            if (full.approved_created_at)
            {
                var startDate = moment.utc(full.approved_created_at.date).local();
                var endDate = moment.utc(new Date()).local();
                var result = endDate.diff(startDate, 'days');
                return result + 1 + " Day";
            } else
            {
                return   "1 Day";
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

        if (data != null && data != undefined)
        {
            var array = data.split(',');
            var priorities = full.priority.split(',');
            var i = 0;
            var max = 0;
            angular.forEach(priorities, function (value, key) {
                if (key == 0) {
                    max = key;
                } else {
                    if (value < priorities[max]) {
                        max = key;
                    }
                }
            });
            return '<a href="Uploads/product/' + array[max] + '" fancyboxable><img  style="width:40px;height:40px;border-radius:30px;" src="Uploads/product/' + array[max] + '"></a>';
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

    function renderTLVPrice(data, type, full, meta) {
        if (data.tlv_price) {
            return data.tlv_price;
        }
        return '-';
    }

    function renderStoragePrice(data, type, full, meta) {
        if (data.storage_pricing) {
            return data.storage_pricing;
        }
        return '-';
    }

    $scope.dtColumns = [
        DTColumnBuilder.newColumn('image').withTitle('Image').notSortable().renderWith(profileImage),
        DTColumnBuilder.newColumn('sku').withTitle('SKU').renderWith(skuRender),
        DTColumnBuilder.newColumn('name').withTitle('Product Name'),

//        DTColumnBuilder.newColumn('price').withTitle('Suggested Retail Price').renderWith(renderPrice),
//        DTColumnBuilder.newColumn('tlv_suggested_price_max').withTitle('Suggested Price'),
        DTColumnBuilder.newColumn(null).withTitle('Price').renderWith(renderPriceMaxMin).notSortable(),
        DTColumnBuilder.newColumn(null).withTitle('TLV Price').renderWith(renderTLVPrice).notSortable(),
        DTColumnBuilder.newColumn(null).withTitle('Storage Price').renderWith(renderStoragePrice).notSortable(),
        DTColumnBuilder.newColumn('approved_created_at').withTitle('Date').renderWith(renderDate),
        DTColumnBuilder.newColumn(null).withTitle('Aging').renderWith(agingRender),
//        DTColumnBuilder.newColumn('agent_name').withTitle('Agent').notSortable().renderWith(skuRender),
        DTColumnBuilder.newColumn('status_id').withTitle('Status').renderWith(statusHtml).withOption("width", "150px"),
//        DTColumnBuilder.newColumn('images_from').withTitle('Schedule').renderWith(schedule).notSortable(),
        DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable()
                .renderWith(actionsHtml),
    ];
    function schedule(data, type, full, meta)
    {

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

//        if (data.tlv_suggested_price_min != '' && data.tlv_suggested_price_min != '')
//        {
//            return data.tlv_suggested_price_max + ' / ' + data.tlv_suggested_price_min;
//
//        } else
//        if (data.tlv_suggested_price_min != '')
//        {
//            return '- - - / ' + data.tlv_suggested_price_min;
//
//        } else
        if (data.price != '')
        {
            return data.price;

        } else
        {
            return '- - -';
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
        if (full.status_id == 17)
        {
            var action_btn = '';

            action_btn += '<md-button aria-label="Approve" sglclick="changeProductFinalStatus(' + full.id + ',18);" class="md-fab md-accent md-raised md-mini">';
            action_btn += '<md-icon md-font-icon="icon-check-circle" class="" aria-hidden="true"></md-icon>';
            action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">APPROVE</md-tooltip>';
            action_btn += '</md-button>';

            action_btn += '<md-button aria-label="Reject" sglclick="changeProductFinalStatus(' + full.id + ',19);" class="md-fab md-warn md-raised md-mini">';
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
        var count = 0;
        for (var i = 0; i < $scope.productStatus.length; i++)
        {
            if ($scope.productStatus[i].product_quotation_id == product)
            {
                $scope.productStatus[i].product_final_status_id = status;
            } else
            {
                count++;
            }

        }
        if (count == $scope.productStatus.length)
        {
            $scope.productStatus.push({'product_final_status_id': status, 'product_quotation_id': product});
        }
//        $scope.productStatus.push({'product_final_status_id': status, 'product_quotation_id': product});
//        $scope.productStatus[product] = {'product_final_status_id': status, 'product_quotation_id': product};
    };

    function statusHtml(data, type, full, meta)
    {
        if (full.status_id == 17)
        {
            $scope.select[full.id] = 0;
            var action_btn = '';

            action_btn += '<md-radio-group ng-click="changeProductStatus(select[' + full.id + '], ' + full.id + ')" ng-model="select[' + full.id + ']" layout="row" layout-xs="column">';

            // choose 2 for uniqueness
            action_btn += '<md-radio-button value="2">Accept</md-radio-button>';
//            action_btn += '<md-radio-button value="1">SendProposal</md-radio-button>';
            action_btn += '<md-radio-button value="83">Queue</md-radio-button>';
            action_btn += '<md-radio-button value="18">Sync</md-radio-button>';
            action_btn += '<md-radio-button value="19">Reject</md-radio-button><br>';
            action_btn += '<md-radio-button value="archived">Archive</md-radio-button>';
            action_btn += '<md-radio-button value="85">Delete</md-radio-button>';
            action_btn += '</md-radio-group>';

            return action_btn;

        } else if (full.status_id == 83)
        {
            var action_btn = '';

            action_btn += "<span>Added In Queue</span>";
            return action_btn;

        } else
        {
            return full.status_value;
        }
    }

    $scope.changeProductFinalStatus = function ()
    {
        var rejected = [];
        for (var i in $scope.productStatus)
        {
            if ($scope.productStatus[i].product_final_status_id == 18)
            {
                rejected.push($scope.productStatus[i]);
            }

        }

        if (rejected.length > 0)
        {
            var confirm = $mdDialog.confirm()
                    .title('Are you sure you want to Sync product?')
                    .ok('Yes')
                    .cancel('No');

            $mdDialog.show(confirm).then(function ()
            {
                $scope.buttonDisabled = true;

                $rootScope.loader = true;
                $http.post(site_settings.api_url + 'product_final/send_mail_product_final_status', {product_status: rejected, seller: $scope.seller.id, send_sync_mail: 1})
                        .then(function (response)
                        {
                            $scope.buttonDisabled = false;
                            $scope.productStatus = [];
                            $rootScope.message = 'Product status has been saved.';
                            $rootScope.$emit("notification");
                            $rootScope.$emit("reloadUserTable");
                            $rootScope.getPendingProducts();
                            $rootScope.loader = false;
                        })
                        .catch(function (error)
                        {
                            $scope.buttonDisabled = false;
                            $rootScope.message = 'Something Went Wrong.';
                            $rootScope.$emit("notification");
                        });
            }, function ()
            {

            });
        } else
        {

            $rootScope.message = 'Please select at least one Sync';
            $rootScope.$emit("notification");

        }
    };


    $scope.sendPricingProposalMail = function ()
    {
        var approved = [];
        for (var i = 0; i < $scope.productStatus.length; i++)
        {
            if ($scope.productStatus[i].product_final_status_id == 2)
            {
                approved.push({id: $scope.productStatus[i].product_quotation_id, is_send_mail: 1});
            }

        }

        if (approved.length > 0)
        {

            var confirm = $mdDialog.confirm()
                    .title('Are you sure you want to send pricing proposal email?')
                    .ok('Yes')
                    .cancel('No');

            $mdDialog.show(confirm).then(function ()
            {
                $rootScope.loader = true;
                $http.post(site_settings.api_url + 'awaiting_contract/send_pricing_proposal', {products: approved, seller: $stateParams.id})
                        .then(function (response)
                        {

                            $rootScope.loader = false;
                            $scope.productStatus = [];
                            var b = response.data;
                            var a = document.createElement('a');
                            // a.download = b;
                            a.target = '_blank';
                            a.id = b;
                            // a.href = 'api/storage/exports/' + b;
                            a.href = b;
                            a.click();

                            $rootScope.message = 'Proposal Successfully Sent';
                            $rootScope.$emit("notification");
                            $rootScope.$emit("reloadUserTable");
                            $rootScope.getPendingProducts();
                            $rootScope.loader = false;
                        })
                        .catch(function (error)
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

    $scope.sendStoragePoposalMail = function ()
    {
        var rejected = [];
        for (var i = 0; i < $scope.productStatus.length; i++)
        {
            if ($scope.productStatus[i].product_final_status_id == 2)
            {
                rejected.push($scope.productStatus[i]);
            }

        }

        if (rejected.length > 0)
        {
            var confirm = $mdDialog.confirm()
                    .title('Are you sure you want to Send Storage proposal?')
                    .ok('Yes')
                    .cancel('No');

            $mdDialog.show(confirm).then(function ()
            {

                $rootScope.loader = true;
                $http.post(site_settings.api_url + 'product_for_pricing/save_product_storage_pricing', {product_status: rejected, seller: $stateParams.id})
                        .then(function (response)
                        {
                            $scope.productStatus = [];
                            $rootScope.message = 'Product storage proposal has been Send.';
                            $rootScope.$emit("notification");
                            $rootScope.$emit("reloadUserTable");
                            $rootScope.getPendingProducts();
                            $rootScope.loader = false;
                        })
                        .catch(function (error)
                        {
                            $rootScope.message = 'Something Went Wrong.';
                            $rootScope.$emit("notification");
                        });
            }, function ()
            {

            });
        } else
        {
            $rootScope.message = 'Please select at least one Accept';
            $rootScope.$emit("notification");
        }
    };

    $scope.changeProductFinalStatusToQueue = function ()
    {
        var rejected = [];
        for (var i in $scope.productStatus)
        {
            if ($scope.productStatus[i].product_final_status_id == 83)
            {
                rejected.push($scope.productStatus[i]);
            }

        }

        if (rejected.length > 0)
        {
            var confirm = $mdDialog.confirm()
                    .title('Are you sure you want to add in Queue product?')
                    .ok('Yes')
                    .cancel('No');

            $mdDialog.show(confirm).then(function ()
            {
                $scope.buttonDisabled = true;

                $rootScope.loader = true;
                $http.post(site_settings.api_url + 'product_final/change_product_final_status', {product_status: rejected, seller: $scope.seller.id})
                        .then(function (response)
                        {
                            $scope.buttonDisabled = false;
                            $scope.productStatus = [];
                            $rootScope.message = 'Product status has been saved.';
                            $rootScope.$emit("notification");
                            $rootScope.$emit("reloadUserTable");
                            $rootScope.getPendingProducts();
                            $rootScope.loader = false;
                        })
                        .catch(function (error)
                        {
                            $scope.buttonDisabled = false;
                            $rootScope.message = 'Something Went Wrong.';
                            $rootScope.$emit("notification");
                        });
            }, function ()
            {

            });
        } else
        {

            $rootScope.message = 'Please select at least one Queue';
            $rootScope.$emit("notification");

        }
    };


    $scope.changeArchiveProductFinalStatus = function ()
    {
        var rejected = [];
        for (var i in $scope.productStatus)
        {
            if ($scope.productStatus[i].product_final_status_id == 'archived')
            {
                rejected.push($scope.productStatus[i]);
            }

        }

        if (rejected.length > 0)
        {
            var confirm = $mdDialog.confirm()
                    .title('Are you sure you want to archive product?')
                    .ok('Yes')
                    .cancel('No');

            $mdDialog.show(confirm).then(function ()
            {
                $scope.buttonDisabled = true;

                $rootScope.loader = true;
                $http.post(site_settings.api_url + 'product_final/change_product_final_status', {product_status: rejected})
                        .then(function (response)
                        {
                            $scope.buttonDisabled = false;
                            $scope.productStatus = [];
                            $rootScope.message = 'Product status has been saved.';
                            $rootScope.$emit("notification");
                            $rootScope.$emit("reloadUserTable");
                            $rootScope.getPendingProducts();
                            $rootScope.loader = false;
                        })
                        .catch(function (error)
                        {
                            $scope.buttonDisabled = false;
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

    $scope.changeRejectProductFinalStatus = function ()
    {
        var rejected = [];
        for (var i in $scope.productStatus)
        {
            if ($scope.productStatus[i].product_final_status_id == 19)
            {
                rejected.push($scope.productStatus[i]);
            }

        }

        if (rejected.length > 0)
        {
            var confirm = $mdDialog.confirm()
                    .title('Are you sure you want to reject product?')
                    .ok('Yes')
                    .cancel('No');

            $mdDialog.show(confirm).then(function ()
            {
                $scope.buttonDisabled = true;

                $rootScope.loader = true;
                $http.post(site_settings.api_url + 'product_final/change_product_final_status', {product_status: rejected})
                        .then(function (response)
                        {
                            $scope.buttonDisabled = false;
                            $scope.productStatus = [];
                            $rootScope.message = 'Product status has been saved.';
                            $rootScope.$emit("notification");
                            $rootScope.$emit("reloadUserTable");
                            $rootScope.getPendingProducts();
                            $rootScope.loader = false;
                        })
                        .catch(function (error)
                        {
                            $scope.buttonDisabled = false;
                            $rootScope.message = 'Something Went Wrong.';
                            $rootScope.$emit("notification");
                        });
            }, function ()
            {

            });
        } else
        {

            $rootScope.message = 'Please select at least one reject';
            $rootScope.$emit("notification");

        }
    };

    $scope.DeleteSelectedProducts = function ()
    {
        var rejected = [];

        for (var i in $scope.productStatus)
        {
            if ($scope.productStatus[i].product_final_status_id == 85)
            {
                rejected.push($scope.productStatus[i]);
            }

        }

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

                            $rootScope.message = 'Product has been successfully Deleted';
                            $rootScope.$emit("notification");
                            $scope.productStatus = [];
                            $rootScope.$emit("reloadUserTable");
                            $rootScope.getPendingProducts();
//                        $scope.downloadDocument();
                            $rootScope.loader = false;
                        })
                        .catch(function (error)
                        {
                            $rootScope.message = 'Something Went Wrong';
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

    }

    $scope.SendProposalSelectedProducts = function ()
    {
        var selected = [];

        for (var i in $scope.productStatus)
        {
            if ($scope.productStatus[i].product_final_status_id == 1)
            {
                selected.push({id: $scope.productStatus[i].product_quotation_id, is_send_mail: 1});
            }

        }

        if (selected.length > 0)
        {

            var confirm = $mdDialog.confirm()
                    .title('Are you sure you want to Send Proposal?')
                    .ok('Yes')
                    .cancel('No');

            $mdDialog.show(confirm).then(function ()
            {


                $rootScope.loader = true;
                $http.post(site_settings.api_url + 'product_quotation/send_mail_approve', {products: selected, seller: $stateParams.id, pass_from: 'product_final'})
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
//                            $rootScope.getPendingProducts();
//                        $scope.downloadDocument();
                            $rootScope.loader = false;
                        })
                        .catch(function (error)
                        {
                            $rootScope.loader = false;
//                    $rootScope.message = 'Something Went Wrong';
//                    $rootScope.$emit("notification");
                        });

            }, function ()
            {

            });
        } else
        {
            $rootScope.message = 'Please select at least one to Send Proposal';
            $rootScope.$emit("notification");

        }

    }

    $scope.AllSyncProductSelected = function ()
    {
        $scope.productStatus = [];

        if ($scope.Sync)
        {
            $scope.Queue = false;
            $scope.Reject = false;
            $scope.Archive = false;
            $scope.Delete = false;
            $scope.Accept = false;
            for (var i in $scope.select)
            {
                $scope.select[i] = 18;
                $scope.productStatus.push({product_quotation_id: i, product_final_status_id: 18});
            }
        } else
        {
            $scope.Queue = false;
            $scope.Reject = false;
            $scope.Archive = false;
            $scope.Delete = false;
            $scope.Accept = false;
            for (var i in $scope.select)
            {
                $scope.productStatus = [];
                $scope.select[i] = 0;
            }

        }
    }
    $scope.AllQueueProductSelected = function ()
    {
        $scope.productStatus = [];

        if ($scope.Queue)
        {
            $scope.Sync = false;
            $scope.Reject = false;
            $scope.Archive = false;
            $scope.Delete = false;
            $scope.Accept = false;
            for (var i in $scope.select)
            {
                $scope.select[i] = 83;
                $scope.productStatus.push({product_quotation_id: i, product_final_status_id: 83});
            }
        } else
        {
            $scope.Sync = false;
            $scope.Reject = false;
            $scope.Archive = false;
            $scope.Delete = false;
            $scope.Accept = false;
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
            $scope.Queue = false;
            $scope.Sync = false;
            $scope.Archive = false;
            $scope.Delete = false;
            $scope.Accept = false;
            for (var i in $scope.select)
            {
                $scope.select[i] = 19;
                $scope.productStatus.push({product_quotation_id: i, product_final_status_id: 19});
            }
        } else
        {
            $scope.Queue = false;
            $scope.Sync = false;
            $scope.Archive = false;
            $scope.Delete = false;
            $scope.Accept = false;
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
            $scope.Queue = false;
            $scope.Sync = false;
            $scope.Reject = false;
            $scope.Delete = false;
            $scope.Accept = false;
            for (var i in $scope.select)
            {
                $scope.select[i] = 'archived';
                $scope.productStatus.push({product_quotation_id: i, product_final_status_id: 'archived'});
            }
        } else
        {
            $scope.Queue = false;
            $scope.Sync = false;
            $scope.Reject = false;
            $scope.Delete = false;
            $scope.Accept = false;
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
            $scope.Queue = false;
            $scope.Sync = false;
            $scope.Archive = false;
            $scope.Reject = false;
            $scope.Accept = false;
            for (var i in $scope.select)
            {
                $scope.select[i] = 85;
                $scope.productStatus.push({product_quotation_id: i, product_final_status_id: 85});
            }
        } else
        {
            $scope.Queue = false;
            $scope.Sync = false;
            $scope.Reject = false;
            $scope.Archive = false;
            $scope.Accept = false;
            for (var i in $scope.select)
            {
                $scope.productStatus = [];
                $scope.select[i] = 0;
            }

        }
    }
    $scope.AllAcceptProductSelected = function ()
    {
        $scope.productStatus = [];
        if ($scope.Accept)
        {
            $scope.Queue = false;
            $scope.Sync = false;
            $scope.Archive = false;
            $scope.Reject = false;
            $scope.Delete = false;
            for (var i in $scope.select)
            {
                $scope.select[i] = 2;
                $scope.productStatus.push({product_quotation_id: i, product_final_status_id: 2});
            }
        } else
        {
            $scope.Queue = false;
            $scope.Sync = false;
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

                    })
                    .catch(function (error)
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
            controller: 'ProductFinalAddController',
            templateUrl: 'app/modules/product_final/views/product_final_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                product_quotation: product_quotation
            }
        });
    };

    $scope.openProductFinalViewDialog = function (product_quotation)
    {
        $mdDialog.show({
            controller: 'ProductFinalViewController',
            templateUrl: 'app/modules/product_final/views/product_final_view.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                product_quotation: product_quotation
            }
        });
    };

    $rootScope.$on("reloadUserTable", function (event, args)
    {
        delete $scope.select;
        $scope.select = [];

        $window.location.reload();
        if ($scope.select.length == 0)
        {

            reloadData();
        }
    });
    $rootScope.$on("reloadProductFinalTable", function (event, args)
    {
//        delete $scope.select;
//        $scope.select = [];

//        $window.location.reload();
//        if ($scope.select.length == 0)
//        {
        $scope.select = [];
        reloadData();
//        }
    });

    function reloadData()
    {

        var resetPaging = true;
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
    
    $scope.generateApprovalReport = function () {
        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'export-sller-products-stage', {seller_id: $scope.seller.id, stage: 'approval'})
                .then(function (response)
                {
                    var fileName = response.data;
                    var a = document.createElement('a');
                    a.download = fileName.trim();
                    a.target = '_blank';
                    a.id = fileName;
                    a.href = 'Uploads/export-seller-products/' + fileName;

                    setTimeout(function () {
                        a.click();
                    }, 500);

                    $rootScope.loader = false;
                    $rootScope.message = 'File will be downloaded sortly!';
                    $rootScope.$emit("notification");
                })
                .catch(function (error)
                {
                    $rootScope.message = 'Something Went Wrong';
                    $rootScope.$emit("notification");
                });
    };
    
    $scope.generateProductLabelReport = function () {
        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'export-seller-products-labels', {seller_id: $scope.seller.id, stage: 'approval'})
                .then(function (response)
                {
                    var fileName = response.data;
                    var a = document.createElement('a');
                    a.download = fileName.trim();
                    a.target = '_blank';
                    a.id = fileName;
                    a.href = 'Uploads/export-seller-products/' + fileName;

                    setTimeout(function () {
                        a.click();
                    }, 500);

                    $rootScope.loader = false;
                    $rootScope.message = 'File will be downloaded sortly!';
                    $rootScope.$emit("notification");
                })
                .catch(function (error)
                {
                    $rootScope.message = 'Something Went Wrong';
                    $rootScope.$emit("notification");
});
    };
    
    
    $scope.sendStorageAmendmentToConsignmentAgreementMail = function ()
    {
        var approved = [];
        for (var i = 0; i < $scope.productStatus.length; i++)
        {
            if ($scope.productStatus[i].product_final_status_id == 2)
            {
                approved.push({id: $scope.productStatus[i].product_quotation_id, is_send_mail: 1});
            }

        }
        if (approved.length > 0)
        {
            var confirm = $mdDialog.confirm()
                    .title('Are you sure you want to Send Storage Amendment to Consignment Agreement?')
                    .ok('Yes')
                    .cancel('No');

            $mdDialog.show(confirm).then(function ()
            {
                $scope.buttonDisabled = true;

                $rootScope.loader = true;
                $http.post(site_settings.api_url + 'consignment_agreement_with_storage/send_storage_amendment_to_consignment_agreement_mail', {products: approved, seller: $stateParams.id})
                        .then(function (response)
                        {
                            $scope.buttonDisabled = false;
                            $rootScope.message = 'Storage Amendment to Consignment Agreement Successfully Sent';
                            $rootScope.$emit("notification");
                            $rootScope.$emit("reloadUserTable");
                            $rootScope.getPendingProducts();
                            $rootScope.loader = false;
                        })
                        .catch(function (error)
                        {
                            $scope.buttonDisabled = false;
                            $rootScope.message = 'Something Went Wrong.';
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
    };
    
});

app.controller('ProductFinalAddController', function (product_quotation, $parse, $document, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.product_quotation = {};
    $scope.categorys = {};
    $scope.subcategorys = {};
    $scope.product = {};
    $scope.ship_sizes = {};
    $scope.edit = false;
    $scope.action = 'Add';
    $scope.searchTerm = '';
    $scope.product_pending_images_name = [];
    $scope.product_pending_images = [];
    $scope.commissions = [80, 70, 60, 50, 40];
    $scope.product_quotation.commission = '60';
    $scope.product_quotation.cities = '';

    $scope.states = [
        {
            "name": "Alabama",
            "abbreviation": "AL"
        },
        {
            "name": "Alaska",
            "abbreviation": "AK"
        },
        {
            "name": "American Samoa",
            "abbreviation": "AS"
        },
        {
            "name": "Arizona",
            "abbreviation": "AZ"
        },
        {
            "name": "Arkansas",
            "abbreviation": "AR"
        },
        {
            "name": "California",
            "abbreviation": "CA"
        },
        {
            "name": "Colorado",
            "abbreviation": "CO"
        },
        {
            "name": "Connecticut",
            "abbreviation": "CT"
        },
        {
            "name": "Delaware",
            "abbreviation": "DE"
        },
        {
            "name": "District Of Columbia",
            "abbreviation": "DC"
        },
        {
            "name": "Federated States Of Micronesia",
            "abbreviation": "FM"
        },
        {
            "name": "Florida",
            "abbreviation": "FL"
        },
        {
            "name": "Georgia",
            "abbreviation": "GA"
        },
        {
            "name": "Guam",
            "abbreviation": "GU"
        },
        {
            "name": "Hawaii",
            "abbreviation": "HI"
        },
        {
            "name": "Idaho",
            "abbreviation": "ID"
        },
        {
            "name": "Illinois",
            "abbreviation": "IL"
        },
        {
            "name": "Indiana",
            "abbreviation": "IN"
        },
        {
            "name": "Iowa",
            "abbreviation": "IA"
        },
        {
            "name": "Kansas",
            "abbreviation": "KS"
        },
        {
            "name": "Kentucky",
            "abbreviation": "KY"
        },
        {
            "name": "Louisiana",
            "abbreviation": "LA"
        },
        {
            "name": "Maine",
            "abbreviation": "ME"
        },
        {
            "name": "Marshall Islands",
            "abbreviation": "MH"
        },
        {
            "name": "Maryland",
            "abbreviation": "MD"
        },
        {
            "name": "Massachusetts",
            "abbreviation": "MA"
        },
        {
            "name": "Michigan",
            "abbreviation": "MI"
        },
        {
            "name": "Minnesota",
            "abbreviation": "MN"
        },
        {
            "name": "Mississippi",
            "abbreviation": "MS"
        },
        {
            "name": "Missouri",
            "abbreviation": "MO"
        },
        {
            "name": "Montana",
            "abbreviation": "MT"
        },
        {
            "name": "Nebraska",
            "abbreviation": "NE"
        },
        {
            "name": "Nevada",
            "abbreviation": "NV"
        },
        {
            "name": "New Hampshire",
            "abbreviation": "NH"
        },
        {
            "name": "New Jersey",
            "abbreviation": "NJ"
        },
        {
            "name": "New Mexico",
            "abbreviation": "NM"
        },
        {
            "name": "New York",
            "abbreviation": "NY"
        },
        {
            "name": "North Carolina",
            "abbreviation": "NC"
        },
        {
            "name": "North Dakota",
            "abbreviation": "ND"
        },
        {
            "name": "Northern Mariana Islands",
            "abbreviation": "MP"
        },
        {
            "name": "Ohio",
            "abbreviation": "OH"
        },
        {
            "name": "Oklahoma",
            "abbreviation": "OK"
        },
        {
            "name": "Oregon",
            "abbreviation": "OR"
        },
        {
            "name": "Palau",
            "abbreviation": "PW"
        },
        {
            "name": "Pennsylvania",
            "abbreviation": "PA"
        },
        {
            "name": "Puerto Rico",
            "abbreviation": "PR"
        },
        {
            "name": "Rhode Island",
            "abbreviation": "RI"
        },
        {
            "name": "South Carolina",
            "abbreviation": "SC"
        },
        {
            "name": "South Dakota",
            "abbreviation": "SD"
        },
        {
            "name": "Tennessee",
            "abbreviation": "TN"
        },
        {
            "name": "Texas",
            "abbreviation": "TX"
        },
        {
            "name": "Utah",
            "abbreviation": "UT"
        },
        {
            "name": "Vermont",
            "abbreviation": "VT"
        },
        {
            "name": "Virgin Islands",
            "abbreviation": "VI"
        },
        {
            "name": "Virginia",
            "abbreviation": "VA"
        },
        {
            "name": "Washington",
            "abbreviation": "WA"
        },
        {
            "name": "West Virginia",
            "abbreviation": "WV"
        },
        {
            "name": "Wisconsin",
            "abbreviation": "WI"
        },
        {
            "name": "Wyoming",
            "abbreviation": "WY"
        }
    ];

    $scope.regions = [
        {
            "name": "Atlanta"
        },
        {
            "name": "Boston"
        },
        {
            "name": "CT/NY/NJ"
        },
        {
            "name": "Palm Beach"
        },
        {
            "name": "Other"
        }
    ]

    var count = 30;
    $scope.totalquantitys = [];
    for (var z = 1; z <= count; z++)
    {
        $scope.totalquantitys.push(z);
    }

    $scope.clearResult = function (cat_name) {
        $scope.product_quotation.product_id.cat[cat_name] = null;
    }
    $scope.removeSub = function (category_name) {
        return category_name.replace("Sub", "");

    }
    $rootScope.$on("newSeller", function (event, user)
    {
//        $scope.sellers.push(user);
//        $scope.product_quotation.product_id.seller_id = user.ID;
//        $rootScope.loader = true;
//        $http.get(site_settings.api_url + 'seller/get_all_seller')
//                .then(function (response)
//                {
//                    $scope.sellers = response.data;
//                });

    });
    $scope.removeImage = function (file) {
        if (!$scope.myDz)
        {
            $scope.myDz = $scope.dzMethods.getDropzone();

        }
        $scope.myDz.emit('removedfile', file);
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

    $rootScope.$on("newPickUpLocation", function (event, pick_up_location)
    {


//        $scope.pick_up_locations.push(pick_up_location);
        $scope.getAllPickUpLocations();
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

        $scope.pick_up_locations = [];
        $http.get(site_settings.api_url + 'getPickUpLocationsBySelectIdSellerId/6/' + $scope.product_quotation.product_id.seller_id)
                .then(function (response)
                {
                    $scope.pick_up_locations = response.data;
                })
                .catch(function (error)
                {
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                    $rootScope.loader = false;
                });
    };

    $scope.getShip_size = function ()
    {

        $http.get(site_settings.api_url + 'getOptionsBySelectId/9')
                .then(function (response)
                {
                    $scope.ship_sizes = response.data;
                })
                .catch(function (error)
                {
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                    $rootScope.loader = false;

                });
    };

    $scope.getShip_size();

    $scope.getStatus = function ()
    {

        $http.get(site_settings.api_url + 'select/get_status')
                .then(function (response)
                {
                    $scope.status = response.data;
                })
                .catch(function (error)
                {
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                    $rootScope.loader = false;

                });
    };

    $scope.getStatus();
    $scope.getCategorys = function ()
    {
        $scope.tempcategorys = [];
        $scope.temp_categorys = [];
        $http.get(site_settings.api_url + 'get_all_categorys')
                .then(function (response)
                {
                    $scope.temp_categorys = response.data;

                    for (var v in $scope.temp_categorys)
                    {
                        if ($scope.temp_categorys[v].id == 3)
                        {
                            var clonedCategory = Object.assign({}, $scope.temp_categorys[v]);
                            $scope.temp_categorys[v].category_name = 'Sub Category';
                            $scope.tempcategorys.push($scope.temp_categorys[v]);
                            $scope.tempcategorys.push(clonedCategory);
                        } else
                        {
                            $scope.tempcategorys.push($scope.temp_categorys[v]);
                        }
                    }

                    $scope.categorys = $scope.tempcategorys;
                    $scope.getCat();
//                    $rootScope.$emit("notification");
                })
                .catch(function (error)
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

                })
                .catch(function (error)
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

                })
                .catch(function (error)
                {
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                });
    };
    $scope.getAllSubCategorysOfCategory();

    $scope.getAllSubCategoriesOfProductMaterials = function () {
        $http.get(site_settings.api_url + 'get_all_subcategorys_of_product_materials/9')
                .then(function (response)
                {
                    $scope.product_material_categorys = response.data;
                })
                .catch(function (error)
                {
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                });
    };

    $scope.getAllSubCategoriesOfProductMaterials();


    $scope.MaincategorySelected = function (main_category)
    {
        $scope.product_sub_categorys = [];
        for (var v in $scope.sub_categorys)
        {
            if (main_category.indexOf($scope.sub_categorys[v].id.toString()) !== -1)
            {
                $scope.product_sub_categorys.push($scope.sub_categorys[v]);
            }
        }

    }

    $scope.getCat = function ()
    {
        $scope.product_quotation.product_id.cat = {};
        for (var i in $scope.categorys)
        {
            if ($scope.product_quotation.product_id[$scope.categorys[i].category_name.toLowerCase()] != null && $scope.categorys[i].category_name.toLowerCase() != 'category' && $scope.categorys[i].category_name.toLowerCase() != 'look' && $scope.categorys[i].category_name.toLowerCase() != 'condition' && $scope.categorys[i].category_name.toLowerCase() != 'room' && $scope.categorys[i].category_name.toLowerCase() != 'color' && $scope.categorys[i].category_name.toLowerCase() != 'collection')
            {
                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = $scope.product_quotation.product_id[$scope.categorys[i].category_name.toLowerCase()].id;
            } else if ($scope.product_quotation.product_id['product_collection'] != null && $scope.categorys[i].category_name.toLowerCase() == 'collection')
            {
                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = [];
                for (var v in $scope.product_quotation.product_id['product_collection'])
                {
                    $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name].push($scope.product_quotation.product_id['product_collection'][v].id);
                }
            } else if ($scope.product_quotation.product_id['product_room'] != null && $scope.categorys[i].category_name.toLowerCase() == 'room')
            {
                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = [];
                for (var v in $scope.product_quotation.product_id['product_room'])
                {
                    $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name].push($scope.product_quotation.product_id['product_room'][v].id);
                }
            } else if ($scope.product_quotation.product_id['product_look'] != null && $scope.categorys[i].category_name.toLowerCase() == 'look')
            {
                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = [];
                for (var v in $scope.product_quotation.product_id['product_look'])
                {
                    $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name].push($scope.product_quotation.product_id['product_look'][v].id);
                }
            } else if ($scope.product_quotation.product_id['product_color'] != null && $scope.categorys[i].category_name.toLowerCase() == 'color')
            {
                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = [];
                for (var v in $scope.product_quotation.product_id['product_color'])
                {
                    $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name].push($scope.product_quotation.product_id['product_color'][v].id);
                }
            } else if ($scope.product_quotation.product_id['product_category'] != null && $scope.categorys[i].category_name.toLowerCase() == 'category')
            {
                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = [];
                for (var v in $scope.product_quotation.product_id['product_category'])
                {
                    for (var va in $scope.sub_categorys)
                    {
                        if ($scope.sub_categorys[va].id == $scope.product_quotation.product_id['product_category'][v].id)
                        {
                            $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name].push($scope.product_quotation.product_id['product_category'][v].id);
                            break;
                        }
                    }
                }
                $scope.MaincategorySelected($scope.product_quotation.product_id.cat[$scope.categorys[i].category_name]);

            } else if ($scope.product_quotation.product_id['product_category'] != null && $scope.categorys[i].category_name.toLowerCase() == 'sub category')
            {
                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = [];
                for (var v in $scope.product_quotation.product_id['product_category'])
                {
                    if ($scope.product_quotation.product_id.cat['Category'].indexOf($scope.product_quotation.product_id['product_category'][v].id) !== -1)
                    {

                    } else
                    {
                        $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name].push($scope.product_quotation.product_id['product_category'][v].id);
                    }
                }
            } else if ($scope.product_quotation.product_id['product_con'] != null && $scope.categorys[i].category_name.toLowerCase() == 'condition')
            {
                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = [];
                for (var v in $scope.product_quotation.product_id['product_con'])
                {
                    $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name].push($scope.product_quotation.product_id['product_con'][v].id);
                }
            } else if ($scope.product_quotation.product_id['product_materials'] != null && $scope.categorys[i].category_name.toLowerCase() == 'materials') {
                // $scope.product_quotation.product_id.cat['product_material'] = $scope.product_quotation.product_id['product_material'].id;
                $scope.product_quotation.product_id.cat['product_materials'] = [];
                for (var v in $scope.product_quotation.product_id['product_materials'])
                {
                    $scope.product_quotation.product_id.cat['product_materials'].push($scope.product_quotation.product_id['product_materials'][v].id);
                }
            }
//            
//            else if ($scope.product_quotation.product_id['category'] != null && $scope.categorys[i].category_namelse if ($scope.product_quotation.product_id['con'] != null && $scope.categorys[i].category_name.toLowerCase() == 'condition')
//            {
//                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = $scope.product_quotation.product_id['con'].id;
//            } e == 'Sub Category')
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

                                })
                                .catch(function (error)
                                {
                                    $rootScope.loader = false;

                                });

                    } else
                    {
                        $scope.action = 'Add';
                    }


                })
                .catch(function (response)
                {
                    $rootScope.loader = false;
                });
    };



    $scope.getAllSubCategorysOfAgeCategory = function ()
    {


        $scope.ages = [];
        $http.post(site_settings.api_url + 'subcategory/getAllSubCategorysOfAgeCategory')
                .then(function (response)
                {
                    $scope.ages = response.data;
                })
                .catch(function (error)
                {
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                    $rootScope.loader = false;
                });
    };
    $scope.getAllSubCategorysOfAgeCategory();

    $scope.getProductQuatation = function () {
        if (product_quotation)
        {
            $rootScope.loader = true;
            $scope.action = 'Edit';
            $http.post(site_settings.api_url + 'product_quotation/get_product_quotation', {id: product_quotation})
                    .then(function (response)
                    {
                        $rootScope.loader = false;
                        $scope.product_quotation = response.data;

                        if ($scope.product_quotation.product_id.city == 'TLV Storage - Bridgeport' || $scope.product_quotation.product_id.city == 'TLV Storage - Cos Cob') {
                            $scope.product_quotation.product_id.cities = $scope.product_quotation.product_id.city;
                        } else if ($scope.product_quotation.product_id.city != '')
                        {
                            $scope.product_quotation.product_id.cities = 'Non - Storage Location';
                        } else {
                            $scope.product_quotation.product_id.cities = '';
                        }

                        $scope.sellers = [$scope.product_quotation.product_id.sellerid];

                        $scope.product_quotation.product_id.seller_id = $scope.product_quotation.product_id.sellerid.id;
                        $scope.getAllPickUpLocations();
                        $scope.product_pending_images = [];

                        if ($scope.product_quotation.product_id.age)
                        {
                            $scope.product_quotation.product_id.age = $scope.product_quotation.product_id.age.id;
                        }

                        if (!$scope.product_quotation.commission) {
                            $scope.product_quotation.commission = '60';
                        }

                        $scope.getCategorys();
                        $scope.getSubCategorys();
                        if ($scope.product_quotation.product_id.pick_up_location)
                        {
                            $scope.product_quotation.product_id.pick_up_location = $scope.product_quotation.product_id.pick_up_location.id;
                        }
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
                    })
                    .catch(function (error)
                    {
                        $rootScope.loader = false;
                    });

        } else
        {
            $scope.action = 'Add';
        }
    }

    $scope.getProductQuatation();

    $scope.getSellers = function ()
    {
        $rootScope.loader = true;
        $http.get(site_settings.api_url + 'seller/get_all_seller')
                .then(function (response)
                {
                    $scope.sellers = response.data;
                    $rootScope.loader = false;
                })
                .catch(function (error)
                {
                    $rootScope.loader = false;
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                });
    };

//    $scope.getSellersFromWP();
//    $scope.getSellers();

    $scope.getTaxClassFromWP = function ()
    {

        $http.jsonp(site_settings.wp_api_url + 'tlvotherinfo.php?skey=tlvesbyat&tax_class=true', {jsonpCallbackParam: 'callback'})
                .then(function (response)
                {
                    $scope.tax_class = response.data;
                })
                .catch(function (response)
                {
                });
    };
    $scope.getTaxClassFromWP();

    $scope.getShippingClassFromWP = function ()
    {

        $http.jsonp(site_settings.wp_api_url + 'tlvotherinfo.php?skey=tlvesbyat&shipping_class=true', {jsonpCallbackParam: 'callback'})
                .then(function (response)
                {
                    $scope.shipping_class = response.data;
                })
                .catch(function (response)
                {
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

    $scope.merge_array = function (array1, array2)
    {
        const result_array = [];
        const arr = array1.concat(array2);
        let len = arr.length;
        const assoc = {};

        while (len--)
        {
            const item = arr[len];

            if (!assoc[item])
            {
                result_array.unshift(item);
                assoc[item] = true;
            }
        }

        return result_array;
    }

    $scope.saveProductFinal = function ()
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



        if ($scope.product_quotation.product_id.cat['Category'] || $scope.product_quotation.product_id.cat['Sub Category'])
        {
            $scope.product_quotation.product_id.cat['Sub Category'] = $scope.merge_array($scope.product_quotation.product_id.cat['Category'], $scope.product_quotation.product_id.cat['Sub Category']);
        }

        $scope.product_quotation.images = [];
        $scope.product_quotation.images = $scope.product_pending_images;
        $scope.product_quotation.passfrom = 'product_final';
        if ($scope.product_quotation.product_id.cities == 'TLV Storage - Bridgeport' || $scope.product_quotation.product_id.cities == 'TLV Storage - Cos Cob') {
            $scope.product_quotation.product_id.city = $scope.product_quotation.product_id.cities;
            $scope.product_quotation.product_id.state = "CT";
        }
        $http.post(site_settings.api_url + 'product_final/save_product_quotation_final', $scope.product_quotation)
                .then(function (response)
                {

                    $rootScope.message = response.data;
                    $rootScope.$emit("notification");
//                    $rootScope.$emit("reloadUserTable");
                    $rootScope.$emit("reloadProductFinalTable");
                    $mdDialog.hide();
                })
                .catch(function (error)
                {
                    $rootScope.message = 'Something Went Wrong';
                    $rootScope.$emit("notification");
                });
    };

    $scope.setPriorities = function () {
        var temp_arr = [];
        for (var index in $scope.product_pending_images_name) {
            temp_arr[index] = $scope.product_pending_images_name[index].id;
        }
        $scope.product_pending_images = temp_arr;
        $http.post(site_settings.api_url + 'updateImagePriority', $scope.product_pending_images)
                .then(function (response) {

                })
                .catch(function (error) {

                });
    }

    $scope.sortableOptions = {

        update: function (e, ui) {
        },
        stop: function (e, ui) {
            $scope.setPriorities();

        }
    };

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
            // var jsonXhr = JSON.parse(xhr);
            var jsonXhr = xhr;

            $scope.product_pending_images.push(jsonXhr.id);


            jsonXhr.name = jsonXhr.filename;
            $scope.product_pending_images_name.push(jsonXhr);
            $scope.setPriorities();
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

                    })
                    .catch(function (error)
                    {
                    });
        },
    };

    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };

    $scope.addNewBrand = function () {
//        $scope.closeDialog();
        $mdDialog.show({
            controller: 'BrandAddController',
            templateUrl: 'app/modules/product_final/views/brand_add.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            skipHide: true,
            onRemoving: function (event, removePromise) {
                $scope.getSubCategorys();
            }
        });
    };
}).config(function (dropzoneOpsProvider)
{
    dropzoneOpsProvider.setOptions({
        url: '/api/product/uploadImages',
        maxFilesize: '20',
        paramName: 'photo',
        addRemoveLinks: true,
        maxFiles: 20,
        acceptedFiles: 'image/*',
        thumbnailWidth: '200',
        thumbnailHeight: '200',
        parallelUploads: 1,
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
    });
});

app.controller('ProductFinalViewController', function (product_quotation, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
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

                                })
                                .catch(function (error)
                                {
                                    $rootScope.loader = false;
                                });

                    } else
                    {
                        $scope.action = 'Add';
                    }
                })
                .catch(function (response)
                {
                    $rootScope.loader = false;
                });
    };

    $scope.getProductQuatation = function () {
        if (product_quotation)
        {
            $rootScope.loader = true;
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

                    })
                    .catch(function (error)
                    {
                        $rootScope.loader = false;
                    });
        } else
        {
            $scope.action = 'Add';
        }
    }

    $scope.getProductQuatation();

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
                })
                .catch(function (response)
                {
                });
    };
    $scope.getTaxClassFromWP();

    $scope.getShippingClassFromWP = function ()
    {

        $http.jsonp(site_settings.wp_api_url + 'tlvotherinfo.php?skey=tlvesbyat&shipping_class=true', {jsonpCallbackParam: 'callback'})
                .then(function (response)
                {
                    $scope.shipping_class = response.data;
                })
                .catch(function (response)
                {
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

app.controller('BrandAddController', function ($timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{

    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };


    $scope.brand_name = null;


    $scope.addBrand = function () {

        var postValues = {
            "tlv": "a1PYB21QaLV43LTlE786eEtUJBlhDti5yN",
            "name": $scope.brand_name
        };

        $http.post('https://tlvdev.wpengine.com/wp-json/tlv_create_brand/create_brand', postValues)
                .then(function (response)
                {
                    var responseData = response.data;
                    $rootScope.message = responseData.message;
                    $rootScope.$emit("notification");
                    $mdDialog.hide();
                })
                .catch(function (error)
                {
                    $rootScope.message = 'Something Went Wrong';
                    $rootScope.$emit("notification");
                    $mdDialog.hide();
                });
    };
});
