"use strict";

var app = angular.module('ng-app');
app.controller('ProductController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken, $window)
{
    $scope.isAdminUser = false;

    $auth.getProfile().then(function (profile) {

        for (var i = 0; i < profile.roles.length; i++) {
            if (profile.roles[i].id == 1) {
                $scope.isAdminUser = true;
            }
        }
    });

    $scope.seller = {};
    $scope.seller.firstname = 'Default';
    $scope.seller.lastname = 'Default';
    $scope.seller_state_city = {};
    $scope.getSellerById = function (id)
    {
        $http.post(site_settings.api_url + 'seller/get_edit_seller', {id: id})
                .then(function (response)
                {
                    $scope.seller = response.data;
                    console.log(response.data);
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
        url: site_settings.api_url + 'product/get_products',
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

//        if (full.status_id != 7)
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

        if (full.status_id != 7 && full.status_id != 8 && full.status_id != 31)
        {
//            action_btn += '<md-button aria-label="EDIT" ng-click="openProductEditDialog(' + data.id + ');" class="md-fab md-accent md-raised md-mini">';
//            action_btn += '<md-icon md-svg-src="assets/angular-material-assets/icons/ic_mode_edit_white_24px.svg" aria-label="Twitter"></md-icon>';
//            action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">EDIT</md-tooltip>';
//            action_btn += '</md-button>';
            action_btn += '<span ng-click="openProductEditDialog(' + data.id + ');" class="text-boxed m-0 deep-blue-bg white-fg" style="cursor: pointer; background-color: #126491 !important;">VIEW</span>';

//            action_btn += '<md-button ng-click="showConfirm(' + data.id + ')" aria-label="DELETE" class="md-fab md-warn md-raised md-mini">';
//            action_btn += '<md-icon  md-font-icon="icon-delete" aria-label="Facebook"></md-icon>';
//            action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">DELETE</md-tooltip>';
//            action_btn += '</md-button>';
        } else
        {
            action_btn += '<span ng-disable="true"  class="text-boxed m-0 deep-blue-bg white-fg" style="cursor: pointer; background-color: #A9A9A9 !important;">VIEW</span>';

        }

//        action_btn += '<md-button aria-label="VIEW" ng-click="openProductViewDialog(' + data.id + ');" class="md-fab md-accent md-raised md-mini">';
//        action_btn += '<md-icon  md-font-icon="icon-eye" aria-label="VIEW"></md-icon>';
//        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">VIEW PRODUCT</md-tooltip>';
//        action_btn += '</md-button>';
//        action_btn += '<md-button aria-label="DELETE" ng-click="showConfirm(' + data.id + ');" class="md-fab md-warn md-raised md-mini">';
//        action_btn += '<md-icon  md-font-icon="icon-delete" aria-label="DELETE"></md-icon>';
//        action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">DELETE PRODUCT</md-tooltip>';
//        action_btn += '</md-button>';

//        action_btn += '<span ng-click="openProductViewDialog(' + data.id + ');" class="text-boxed m-0 deep-blue-bg white-fg" style="cursor: pointer;margin-left:5px; background-color: #1eaa36 !important;">VIEW</span>';
//        action_btn += '<span ng-click="showConfirm(' + data.id + ')" class="text-boxed m-0 deep-red-bg white-fg" style="cursor: pointer; margin-left: 5px; background-color: red !important;"">DELETE</span>';



//        action_btn += '</md-fab-actions></md-fab-speed-dial>';
        return action_btn;
//        $scope.persons[data.id] = data;
//        return '<md-button aria-label="Edit" ui-sref="edit_profile({id:\'' + full.id + '\'})" class="md-fab md-mini md-button  md-ink-ripple">  <md-icon md-svg-src="assets/img/edit.svg" class="icon"></md-icon></md-button><md-button aria-label="Delete" type="submit" class="md-fab md-mini md-button  md-ink-ripple md-warn">  <md-icon md-font-icon="icon-delete" class="icon" ></md-icon></md-button>';
    }
    function agingRender(data, type, full, meta)
    {
        if (full.approved_date != null)
        {
            var startDate = moment.utc(full.created_at.date).local();
            var endDate = moment.utc(full.approved_date.date).local();

            var result = endDate.diff(startDate, 'days');
            return result + 1 + " Day";

        } else
        {
            var startDate = moment.utc(full.created_at.date).local();
            var endDate = moment.utc(new Date()).local();
            var result = endDate.diff(startDate, 'days');
            return result + 1 + " Day";

        }
    }
    function dateRender(data, type, full, meta)
    {
//        if (full.approved_date != null)
//        {
//            var startDate = moment.utc(full.created_at.data).local();
//            var endDate = moment.utc(full.approved_date.data).local();
//
//            var result = endDate.diff(startDate, 'days');
//            return result + " Day";
//
//        } 
//        else
//        {
//            var startDate = moment.utc(full.created_at.data).local();
//            var endDate = moment.utc(new Date()).local();
//            var result = endDate.diff(startDate, 'days');
//            return result + " Day";
//
//        }
        if (data != null)
        {
            return moment.utc(data.date).local().format('MM/DD/YYYY')
        } else
        {
            return '---';
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

    $scope.dtInstance = {};
    $scope.sendProposal = function ()
    {
        $mdDialog.show({
            controller: 'MailAddController',
            templateUrl: 'app/modules/shared/views/compose-dialog.html',
            parent: angular.element($document.body),
            clickOutsideToClose: true,
            locals: {
                seller_id: $scope.seller.id
            }
        });


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
                    a.href = 'api/storage/exports/' + b;
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

    $scope.dtColumns = [
        DTColumnBuilder.newColumn('image').withTitle('Image').notSortable().renderWith(profileImage),
        DTColumnBuilder.newColumn('sku').withTitle('SKU').renderWith(skuRender),
//        DTColumnBuilder.newColumn(null).withTitle('').notVisible(),
        DTColumnBuilder.newColumn('name').withTitle('Product Name'),
//        DTColumnBuilder.newColumn('sell_name').withTitle('Sell Name'),
//        DTColumnBuilder.newColumn('price').withTitle('Suggested Retail Price').renderWith(renderPrice),
        DTColumnBuilder.newColumn(null).withTitle('Price').renderWith(renderPriceMaxMin).notSortable(),
        DTColumnBuilder.newColumn('created_at').withTitle('Date').renderWith(dateRender),
        DTColumnBuilder.newColumn(null).withTitle('Aging').renderWith(agingRender),
        DTColumnBuilder.newColumn('status_id').withTitle('Status').renderWith(statusHtml).notSortable(),
        DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable()
                .renderWith(actionsHtml),
    ];
    function renderPriceMaxMin(data, type, full, meta)
    {

//        if (data.tlv_suggested_price_min != '' && data.tlv_suggested_price_max != '')
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
            return '- - - ';
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
    $scope.changeProductStatus = function (status, product)
    {
        var count = 0;
        for (var i = 0; i < $scope.productStatus.length; i++)
        {
            if ($scope.productStatus[i].product_id == product)
            {
                $scope.productStatus[i].product_status_id = status;
            } else
            {
                count++;
            }

        }
        if (count == $scope.productStatus.length)
        {
            $scope.productStatus.push({'product_status_id': status, 'product_id': product});
        }
//        console.log($scope.productStatus);
    };

    function statusHtml1(data, type, full, meta)
    {
//        console.log(full);
        if (full.status_id != 7 && full.status_id != 8 && full.status_id != 20)
        {
            $scope.select[full.id] = full.status_id;
            var action_btn = '';

            action_btn += '<md-button aria-label="Approve" sglclick="changeProductStatus(' + full.id + ',7);" class="md-fab md-accent md-raised md-mini">';
            action_btn += '<md-icon md-font-icon="icon-check-circle" class="" aria-hidden="true"></md-icon>';
            action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">APPROVE</md-tooltip>';
            action_btn += '</md-button>';

            action_btn += '<md-button aria-label="Referral to Auction House" sglclick="changeProductStatus(' + full.id + ',20);" class="md-fab md-accent md-raised md-mini">';
            action_btn += '<md-icon md-font-icon="icon-redo-variant" class="" aria-hidden="true"></md-icon>';
            action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">Referral to Auction House</md-tooltip>';
            action_btn += '</md-button>';

            action_btn += '<md-button aria-label="Reject" sglclick="changeProductStatus(' + full.id + ',8);" class="md-fab md-warn md-raised md-mini">';
            action_btn += '<md-icon md-font-icon="icon-cancel" class="" aria-hidden="true"></md-icon>';
            action_btn += '<md-tooltip md-direction="top" md-visible="tooltipVisible">REJECT</md-tooltip>';
            action_btn += '</md-button>';








//            var status_dropdown = '<md-select aria-label="orderstatus" style="margin:0;" ng-model="select[' + full.id + ']" ng-change="changeProductStatus(' + full.id + ')">';
//            status_dropdown += '<md-option ng-repeat="ps in product_status" value="{{ps.id}}"><em>{{ps.value_text}}</em></md-option>';
//            status_dropdown += '</md-select>';

            return action_btn;
        } else if (full.status_id == 7 || full.status_id == 8 || full.status_id == 20)
        {
            return full.status_value;
        }
    }

    function statusHtml(data, type, full, meta)
    {
        console.log(full);
        if (full.status_id != 7 && full.status_id != 8 && full.status_id != 20 && full.status_id != 31)
        {
            $scope.select[full.id] = full.status_id;
            var action_btn = '';

            action_btn += '<md-radio-group ng-click="changeProductStatus(select[' + full.id + '], ' + full.id + ')" ng-model="select[' + full.id + ']" layout="row" layout-xs="column">';
            action_btn += '<md-radio-button value="7">Accept</md-radio-button>';
//            action_btn += '<md-radio-button value="20">Refer to Auction House</md-radio-button>';
            action_btn += '<md-radio-button value="31">Archive</md-radio-button>';
            action_btn += '<md-radio-button value="8">Reject</md-radio-button>';
            action_btn += '<md-radio-button value="85">Delete</md-radio-button>';

            action_btn += '</md-radio-group>';

            return action_btn;

        } else if (full.status_id == 7 || full.status_id == 8 || full.status_id == 20 || full.status_id == 31)
        {
            return full.status_value;
        }
    }

    $scope.saveProductStatusRejected = function (is_referral)
    {

        var rejected = [];
        for (var i = 0; i < $scope.productStatus.length; i++)
        {
            if ($scope.productStatus[i].product_status_id == 8)
            {
                rejected.push($scope.productStatus[i]);
            }

        }
        console.log(rejected);
        if (rejected.length > 0)
        {
            var confirm = $mdDialog.confirm()
                    .title('Are you sure you want to reject product with email?')
                    .ok('Yes')
                    .cancel('No');

            $mdDialog.show(confirm).then(function ()
            {
                $rootScope.loader = true;
                $http.post(site_settings.api_url + 'product/change_product_status_to_reject', {product_status: rejected, is_referral: is_referral})
                        .then(function (response)
                        {

                            $rootScope.message = 'Product status has been successfully saved.';
                            $scope.productStatus = [];
                            $rootScope.loader = false;
                            $rootScope.$emit("notification");
                            $rootScope.$emit("reloadProductTable");
                        }).catch(function (error)
                {
                    $rootScope.loader = false;
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                });
            }, function ()
            {

            });
        } else
        {
            $rootScope.message = 'Please select at least one Rejected.';
            $rootScope.$emit("notification");
        }

    }

    $scope.saveReferralProductStatusRejected = function (is_referral)
    {

        var rejected = [];
        for (var i = 0; i < $scope.productStatus.length; i++)
        {
            if ($scope.productStatus[i].product_status_id == 8)
            {
                rejected.push($scope.productStatus[i]);
            }

        }
        console.log(rejected);
        if (rejected.length > 0)
        {
            var confirm = $mdDialog.confirm()
                    .title('Are you sure you want to reject product with referral email?')
                    .ok('Yes')
                    .cancel('No');

            $mdDialog.show(confirm).then(function ()
            {
                $rootScope.loader = true;
                $http.post(site_settings.api_url + 'product/change_product_status_to_reject', {product_status: rejected, is_referral: is_referral})
                        .then(function (response)
                        {

                            $rootScope.message = 'Product status has been successfully saved.';
                            $scope.productStatus = [];
                            $rootScope.loader = false;
                            $rootScope.$emit("notification");
                            $rootScope.$emit("reloadProductTable");
                        }).catch(function (error)
                {
                    $rootScope.loader = false;
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                });
            }, function ()
            {

            });
        } else
        {
            $rootScope.message = 'Please select at least one Rejected.';
            $rootScope.$emit("notification");
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
                $scope.select[i] = 7;
                $scope.productStatus.push({'product_id': i, product_status_id: 7});
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
                $scope.select[i] = 8;
                $scope.productStatus.push({'product_id': i, product_status_id: 8});
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
                $scope.select[i] = 31;
                $scope.productStatus.push({'product_id': i, product_status_id: 31});
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
                $scope.productStatus.push({'product_id': i, product_status_id: 85});
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


    $scope.DeleteSelectedProducts = function ()
    {

        var rejected = [];
        for (var i = 0; i < $scope.productStatus.length; i++)
        {
            if ($scope.productStatus[i].product_status_id == 85)
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
                $http.post(site_settings.api_url + 'product/change_product_delete', {product_status: rejected})
                        .then(function (response)
                        {

                            $rootScope.message = 'Product Deleted has been successfully.';
                            $scope.productStatus = [];
                            $rootScope.loader = false;
                            $rootScope.$emit("notification");
                            $rootScope.$emit("reloadProductTable");
                        }).catch(function (error)
                {
                    $rootScope.loader = false;
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                });
            }, function ()
            {

            });
        } else
        {
            $rootScope.message = 'Please select at least one Delete.';
            $rootScope.$emit("notification");
        }

    }


    $scope.saveProductStatusApprove = function ()
    {
        var approved = [];
        for (var i = 0; i < $scope.productStatus.length; i++)
        {
            if ($scope.productStatus[i].product_status_id == 7)
            {
                approved.push($scope.productStatus[i]);
            }

        }
        console.log(approved);
        if (approved.length > 0)
        {
            var confirm = $mdDialog.confirm()
                    .title('Are you sure you want to approve product?')
                    .ok('Yes')
                    .cancel('No');

            $mdDialog.show(confirm).then(function ()
            {
                $http.post(site_settings.api_url + 'product/change_product_status_to_approve', {products: approved, seller: $stateParams.id})
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
//                        document.body.removeChild(a);

                            $rootScope.message = 'Product status has been successfully saved.';

                            $rootScope.$emit("notification");
                            $rootScope.$emit("reloadProductTable");
                        }).catch(function (error)
                {
                    console.log(error);
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                });
            }, function ()
            {

            });
        } else
        {
            $rootScope.message = 'Please select at least one Accept.';
            $rootScope.$emit("notification");
        }
    }
    $scope.saveProductStatusToArchive = function ()
    {


        var archived = [];
        for (var i = 0; i < $scope.productStatus.length; i++)
        {
            if ($scope.productStatus[i].product_status_id == 31)
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
                $http.post(site_settings.api_url + 'product/change_product_status_to_archive', {product_status: archived})
                        .then(function (response)
                        {

                            $rootScope.message = 'Product status has been successfully saved.';
                            $scope.productStatus = [];
                            $rootScope.loader = false;
                            $rootScope.$emit("notification");
                            $rootScope.$emit("reloadProductTable");
                        }).catch(function (error)
                {
                    $rootScope.loader = false;
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                });
            }, function ()
            {

            });
        } else
        {
            $rootScope.message = 'Please select at least one Archive.';
            $rootScope.$emit("notification");
        }

    }
    $scope.saveProductStatusWithoutEmail = function ()
    {

        var approved = [];
        for (var i = 0; i < $scope.productStatus.length; i++)
        {
            if ($scope.productStatus[i].product_status_id == 7)
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
                $http.post(site_settings.api_url + 'product/change_product_status', {product_status: approved, is_send_mail: 'no'})
                        .then(function (response)
                        {
                            $scope.productStatus = [];

                            $rootScope.message = 'Product status has been successfully saved.';
                            $rootScope.loader = false;
                            $rootScope.$emit("notification");
                            $rootScope.$emit("reloadProductTable");
                        }).catch(function (error)
                {
                    $rootScope.loader = false;
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                });
            }, function ()
            {

            });
        } else
        {
            $rootScope.message = 'Please select at least one Accept.';
            $rootScope.$emit("notification");
        }

    }
    $scope.saveProductStatus = function ()
    {

        var approved = [];
        for (var i = 0; i < $scope.productStatus.length; i++)
        {
            if ($scope.productStatus[i].product_status_id == 7)
            {
                approved.push($scope.productStatus[i]);
            }

        }
        console.log(approved);
        if (approved.length > 0)
        {
            var confirm = $mdDialog.confirm()
                    .title('Are you sure you want to Send Consigment Agreement email?')
                    .ok('Yes')
                    .cancel('No');

            $mdDialog.show(confirm).then(function ()
            {
                $rootScope.loader = true;
                $http.post(site_settings.api_url + 'product/change_product_status', {product_status: approved, is_send_mail: 'yes'})
                        .then(function (response)
                        {
                            $scope.productStatus = [];

                            $rootScope.message = 'Product status has been successfully saved.';
                            $rootScope.loader = false;
                            $rootScope.$emit("notification");
                            $rootScope.$emit("reloadProductTable");
                        }).catch(function (error)
                {
                    $rootScope.loader = false;
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                });
            }, function ()
            {

            });
        } else
        {
            $rootScope.message = 'Please select at least one Accept.';
            $rootScope.$emit("notification");
        }

    }

    $scope.SendConsignmentAgreementWithStorageProReview = function ()
    {

        var approved = [];



        for (var i = 0; i < $scope.productStatus.length; i++)
        {
            if ($scope.productStatus[i].product_status_id == 7)
            {
                approved.push({id: $scope.productStatus[i].product_id, product_status_id: $scope.productStatus[i].product_status_id});


            }

        }
        console.log($scope);
        if (approved.length > 0)
        {

            var confirm = $mdDialog.confirm()
                    .title('Are you sure you want to send Consignment agreement with storage?')
                    .ok('Yes')
                    .cancel('No');

            $mdDialog.show(confirm).then(function ()
            {
                $rootScope.loader = true;
                $http.post(site_settings.api_url + 'consignment_agreement_with_storage/send_consignment_agreement_with_storage_pro_review', {products: approved, seller: $stateParams.id, is_send_mail: 'yes'})
                        .then(function (response)
                        {

                            $rootScope.loader = false;
                            $scope.productStatus = [];

                            $rootScope.message = 'Proposal Successfully Sent';
                            $rootScope.$emit("notification");
                            $rootScope.$emit("reloadProductTable");
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

    $scope.sendPricingProposal = function ()
    {

        var approved = [];
        for (var i = 0; i < $scope.productStatus.length; i++)
        {
            if ($scope.productStatus[i].product_status_id == 7)
            {
                approved.push($scope.productStatus[i]);
            }

        }
        console.log(approved);
        if (approved.length > 0)
        {
            var confirm = $mdDialog.confirm()
                    .title('Are you sure you want to send pricing proposal email?')
                    .ok('Yes')
                    .cancel('No');

            $mdDialog.show(confirm).then(function ()
            {
                $rootScope.loader = true;
                $http.post(site_settings.api_url + 'product/send_products_pricing_proposal', {product_status: approved, seller: $stateParams.id})
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
                            $rootScope.$emit("reloadProductTable");
                            $rootScope.getPendingProducts();
//                        $scope.downloadDocument();
                            $rootScope.loader = false;
                        }).catch(function (error)
                {
                    $rootScope.loader = false;
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                });
            }, function ()
            {

            });
        } else
        {
            $rootScope.message = 'Please select at least one Accept.';
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
                product: product,
                seller: null,
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
        $window.location.reload();
        $scope.select = [];
        console.log('reload');

        reloadData();
    });
    $rootScope.$on("reloadProductTable1", function (event, args)
    {
        reloadData();

//        $mdDialog.show({
//            controller: 'CustomDialogController',
//            templateUrl: 'app/modules/product/views/custom_dialog.html',
//            parent: angular.element($document.body),
//            clickOutsideToClose: true
//        });
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
            $rootScope.loader = true;
            $http.post(site_settings.api_url + 'product/delete_product', {id: id})
                    .then(function (response)
                    {

                        $rootScope.message = 'Product Deleted Successfully';
                        $rootScope.$emit("notification");
                        $rootScope.$emit("reloadProductTable");
                        $rootScope.loader = false;
                    }).catch(function (error)
            {
                $rootScope.loader = false;
                $rootScope.message = 'Something Went Wrong';
                $rootScope.$emit("notification");
            });
        }, function ()
        {

        });
    };

    $scope.generateReport = function () {
        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'product-review-report', {seller_id: $scope.seller.id})
                .then(function (response)
                {
                    var fileName = response.data;
                    var a = document.createElement('a');
                    a.download = fileName.trim();
                    a.target = '_blank';
                    a.id = fileName;
                    a.href = 'Uploads/export-products-review/' + fileName;

                    setTimeout(function () {
                        a.click();
                    }, 500);

                    $rootScope.loader = false;
                    $rootScope.message = 'File will be downloaded sortly!';
                    $rootScope.$emit("notification");
                })
                .catch(function (error)
                {
                    $rootScope.loader = false;
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                });
    };
});

app.controller('MailAddController', function (seller_id, $parse, $document, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
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
                    $scope.form.attachment = $scope.seller.last_product_file_name;
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
        $http.post(site_settings.api_url + 'product/send_proposal', $scope.form)
                .then(function (response)
                {
                    $rootScope.message = 'Mail Sent Successfully';
                    $rootScope.$emit("notification");
                    $mdDialog.hide();
                });
    }

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
                seller: null,
                product: product
            }
        });
    };

    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };
});

app.controller('SellerAddController', function ($timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.action = 'Add';
    $scope.user = {};
    $scope.regex = '^[a-zA-Z0-9._-]+$';
    $scope.user.not_available = false;
    $scope.user.not_available_email = true;


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
    };
    $scope.wp_key = 'AbcBsdsa1';
    $scope.IsShopUrlAvailable = function (shop_url, is_valid)
    {
        if (shop_url && is_valid)
        {
            $rootScope.loader = true;
            $http.post(site_settings.api_url + 'is_shop_url_available_WP', {shop_url: shop_url})
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
    $scope.saveUser = function ()
    {
//        var url = 'seller-api.php';
//        url += '?email=' + $scope.user.email;
//        url += '&firstname=' + $scope.user.firstname;
//        url += '&lastname=' + $scope.user.lastname;
//        url += '&password=' + $scope.user.password;
//        url += '&shop_name=' + $scope.user.shop_name;
//        url += '&shop_url=' + $scope.user.shop_url;
//        url += '&address=' + $scope.user.address;
//        url += '&phone=' + $scope.user.phone;
//        url += '&key=' + $scope.wp_key;
//        console.log(url);


//        $http.jsonp(site_settings.wp_api_url + url, {jsonpCallbackParam: 'callback'})
//                .then(function (response) {
//                    $scope.sellers = response.data;
////            console.log($scope.sellers);
//                })
//                .catch(function (response) {
//                    console.log(response);
//                });

        $http.post(site_settings.api_url + 'seller/add_seller', $scope.user)
                .then(function (response)
                {
                    console.log(response);
                    console.log(response.data);
//                    response.data.data.displayname = response.data.data.display_name;
                    $rootScope.$emit("newSeller", response.data);

                    $rootScope.message = 'User Saved Successfully';
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
//        .config(function (dropzoneOpsProvider) {
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
//        maxfilesexceeded: function (file) {
//            this.removeAllFiles();
//            this.addFile(file);
//
//        },
//        sending: function (file, xhr, formData) {
//
//            formData.append('folder', 'profile');
//        },
//    });
//});
app.controller('PickUpLocationAddController', function (seller_id, $parse, $document, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.pick_up_location = {};
    $scope.pick_up_location.select_id = 6;
    $scope.pick_up_location.seller_id = seller_id;
    $scope.pick_up_location.key_text = [{city: '', state: ''}];
    $scope.states = ['CT', 'NY', 'NJ', 'MA', 'FL'];
    $scope.savePickUpLocation = function ()
    {
        $scope.pick_up_location.value_text;

//        $scope.pick_up_location.key_text = $scope.pick_up_location.key_text;
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
app.controller('ProductAddController', function (seller, product, $parse, $document, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.search = [];
    $scope.product = {};
    $scope.categorys = {};
    $scope.subcategorys = {};
    $scope.product.cities = '';

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

    var count = 30;
    $scope.totalquantitys = [];
    for (var z = 1; z <= count; z++)
    {
        $scope.totalquantitys.push(z);
    }

    $rootScope.$on("newSeller", function (event, user)
    {
    });

    $rootScope.$on("newPickUpLocation", function (event, pick_up_location)
    {

        console.log(pick_up_location);
//        $scope.pick_up_locations.push(pick_up_location);
        $scope.getAllPickUpLocations();
        $scope.products_combo[0].pick_up_location = pick_up_location.id;

    });
//    $scope.sellers = {};
    $scope.products_count = 1;
    $scope.products_combo = [{name: '', price: '', description: '', quantity: '1', state: '', city: '', cat: {}, cities: ''}];
    $scope.product = {};
    $scope.edit = false;
    $scope.action = 'Add';
    $scope.searchTerm = '';

    if (seller) {
        $scope.sellers = [seller];
        $scope.product.sellerid = seller.id;
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

    $scope.getStatus();
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
    };
    $scope.getAllPickUpLocations = function ()
    {
        console.log($scope.product.sellerid);

        $scope.pick_up_locations = [];
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
//    $scope.getSellers();

    $scope.searchSellers = function () {
        $http.post(site_settings.api_url + 'search-sellers', {q: $scope.searchTerm})
                .then(function (response) {
                    $scope.sellers = response.data;
                })
                .catch(function () {
                    $scope.sellers = [];
                });
    };

    $scope.getPickUpLocationsWithState = function (sellerid)
    {
        $scope.getAllPickUpLocations();
        if (sellerid != 'Add New')
        {
            $rootScope.loader = true;
            $scope.seller_state_city = {};
            $http.post(site_settings.api_url + 'seller/get_seller_city_state', {sellerid: sellerid})
                    .then(function (response)
                    {
                        $rootScope.loader = false;
                        if (response.data.er == '0')
                        {
                            $scope.seller_state_city = response.data;
                        }
                        console.log(response.data);
                    }).catch(function (error)
            {
                $rootScope.loader = false;
            });
        }
    };

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
        if ($scope.product.products[0].cities == 'TLV Storage - Bridgeport' || $scope.product.products[0].cities == 'TLV Storage - Cos Cob Office') {
            $scope.product.products[0].city = $scope.product.products[0].cities;
            $scope.product.products[0].state = "CT";
        }
        if ($scope.product.products[0].local_drop_off == false) {
            $scope.product.products[0].local_drop_off_city = "";
        }
        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'product/save_product', $scope.product)
                .then(function (response)
                {

                    $rootScope.message = response.data;
                    $rootScope.loader = false;
                    $rootScope.$emit("notification");
                    $rootScope.$emit("reloadProductTable1");
                    $rootScope.$emit("reloadUserTable");

                    var confirm = $mdDialog.confirm()
                            .title('Would you like to add another product?')
                            .ok('Yes')
                            .cancel('No');

                    $mdDialog.show(confirm).then(function () {

                        var selectedSeller = null;
                        for (var i = 0; i < $scope.sellers.length; i++) {
                            if ($scope.product.sellerid == $scope.sellers[i].id) {
                                selectedSeller = $scope.sellers[i];
                            }
                        }

                        $mdDialog.show({
                            controller: 'ProductAddController',
                            templateUrl: 'app/modules/product/views/product_add.html',
                            parent: angular.element($document.body),
                            clickOutsideToClose: true,
                            locals: {
                                product: null,
                                seller: selectedSeller
                            }
                        });

                    }, function () {

                    });

                    $mdDialog.hide();

                })
                .catch(function (error)
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
//    $scope.myDz = $scope.dzMethods.getDropzone();
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

            console.log($scope.current_dropzone_index);
            // var xhrJson = JSON.parse(xhr);
            var xhrJson = xhr;

            if ($scope.products_combo[$scope.current_dropzone_index]['images'] != undefined)
            {

            } else
            {
                $scope.products_combo[$scope.current_dropzone_index]['images'] = [];
            }

            if (xhrJson != null)
            {
                $scope.products_combo[$scope.current_dropzone_index]['images'].push(xhrJson.id);
            }

            //show preview
            console.log($scope.current_dropzone_index);
            if ($scope.products_combo[$scope.current_dropzone_index]['images_name'] != undefined)
            {

            } else
            {
                $scope.products_combo[$scope.current_dropzone_index]['images_name'] = [];
            }
            console.log($scope.current_dropzone_index);
            console.log(xhrJson.id);
            if (xhrJson != null)
            {
                $scope.products_combo[$scope.current_dropzone_index]['images_name'].push(xhrJson);
            }

//            console.log($scope.products_combo[$scope.current_dropzone_index]['images_name']);

            //end show preview


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
                            $scope.products_combo[i]['images_name'].splice(k, 1);
                        }
                    }
                }
                //change
                if (!file.name)
                {
                    data.name = file.filename;
                } else
                {
                    data.name = file.name;
                }
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

    $scope.sortableOptions = {

        update: function (e, ui) {
        },
        stop: function (e, ui) {
            var temp_arr = [];
            for (var index in $scope.products_combo[$scope.current_dropzone_index]['images_name']) {
                temp_arr[index] = $scope.products_combo[$scope.current_dropzone_index]['images_name'][index].id;
            }
            $scope.products_combo[$scope.current_dropzone_index]['images'] = temp_arr;
            $http.post(site_settings.api_url + 'updateImagePriority', $scope.products_combo[$scope.current_dropzone_index]['images'])
                    .then(function (response) {
                        console.log(response);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
        }
    };

    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };
    $scope.dzOptionsAddProduct = {
        url: '/api/product/uploadImages',
        maxFilesize: '20',
        paramName: 'photo',
        addRemoveLinks: true,
        maxFiles: 20,
        acceptedFiles: 'image/*',
        thumbnailWidth: '0',
        thumbnailHeight: '0',
        parallelUploads: 1,
//        dictDefaultMessage: '<img width="140" src="/assets/images/clip-512.png">',
//        dictDefaultMessage: 'Upload Product Images Here',
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
})
app.controller('ProductAddWithSellerController', function (seller, $parse, $document, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.search = [];
    $scope.product = {};
    $scope.product.sellerid = seller;
    console.log($scope.product);
    $scope.categorys = {};
    $scope.subcategorys = {};

    var count = 30;
    $scope.totalquantitys = [];
    for (var z = 1; z <= count; z++)
    {
        $scope.totalquantitys.push(z);
    }


    $rootScope.$on("newSeller", function (event, user)
    {
        console.log(user);
//        console.log($scope.sellers.length);
//        $scope.sellers.push(user);
//        console.log($scope.sellers);
//        console.log($scope.sellers.length);
//        console.log(user.ID);
//        $scope.product.sellerid = user.ID;
//        $scope.getSellers();

    });
    $rootScope.$on("newPickUpLocation", function (event, pick_up_location)
    {

        console.log(pick_up_location);
        $scope.pick_up_locations.push(pick_up_location);
        $scope.products_combo[0].pick_up_location = pick_up_location.id;

    });
//    $scope.sellers = {};
    $scope.products_count = 1;
    $scope.products_combo = [{name: '', price: '', description: '', quantity: '1', state: '', city: '', cat: {}}];
//    $scope.product = {};
    $scope.edit = false;
    $scope.action = 'Add';
    $scope.searchTerm = '';

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

    $scope.getStatus();
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
    };

    $scope.getAllPickUpLocations = function ()
    {
        console.log($scope.product.sellerid);

        $scope.pick_up_locations = [];
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

    $scope.searchSellers = function () {
        $http.post(site_settings.api_url + 'search-sellers', {q: $scope.searchTerm})
                .then(function (response) {
                    $scope.sellers = response.data;
                })
                .catch(function () {
                    $scope.sellers = [];
                });
    };

//    $scope.getSellersFromWP();
//    $scope.getSellers();

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
        // Appending dialog to document.body to cover sidenav in docs app

//        for (var i in $scope.sellers)
//        {
//            if ($scope.sellers[i].ID == $scope.product.seller_id)
//            {
////                $scope.product.seller_firstname = $scope.sellers[i].data.display_name.charAt(0);
////                var temp = $scope.sellers[i].data.display_name.split(" ");
////                $scope.product.seller_lastname = temp[1].substr(0, 3);
//            }
//        }

        if ($scope.product.local_drop_off == false) {
            $scope.product.local_drop_off_city = "";
        }
        $rootScope.loader = true;
        $http.post(site_settings.api_url + 'product/save_product', $scope.product)
                .then(function (response)
                {

                    $rootScope.message = response.data;
                    $rootScope.loader = false;
                    $rootScope.$emit("notification");
                    $rootScope.$emit("reloadProductTable1");
                    $rootScope.$emit("reloadUserTable");

                    var confirm = $mdDialog.confirm()
                            .title('Would you like to add another product?')
                            .ok('Yes')
                            .cancel('No');

                    $mdDialog.show(confirm).then(function () {

                        $mdDialog.show({
                            controller: 'ProductAddWithSellerController',
                            templateUrl: 'app/modules/product/views/product_add.html',
                            parent: angular.element($document.body),
                            clickOutsideToClose: true,
                            locals: {
                                seller: $scope.product.sellerid
                            }
                        });

                    }, function () {

                    });

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
//    $scope.myDz = $scope.dzMethods.getDropzone();
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

            var xhrJson = JSON.parse(xhr);

            console.log($scope.current_dropzone_index);
            if ($scope.products_combo[$scope.current_dropzone_index]['images'] != undefined)
            {

            } else
            {
                $scope.products_combo[$scope.current_dropzone_index]['images'] = [];
            }

            if (xhrJson != null)
            {
                $scope.products_combo[$scope.current_dropzone_index]['images'].push(xhrJson.id);
            }

            if ($scope.products_combo[$scope.current_dropzone_index]['images_name'] != undefined)
            {

            } else
            {
                $scope.products_combo[$scope.current_dropzone_index]['images_name'] = [];
            }

            if (xhrJson != null)
            {
                $scope.products_combo[$scope.current_dropzone_index]['images_name'].push(xhrJson);
            }

//            console.log($scope.products_combo[$scope.current_dropzone_index]['images_name']);

            //end show preview


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
                            $scope.products_combo[i]['images_name'].splice(k, 1);
                        }
                    }
                }
                //change
                if (!file.name)
                {
                    data.name = file.filename;
                } else
                {
                    data.name = file.name;
                }
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

    $scope.sortableOptions = {

        update: function (e, ui) {
        },
        stop: function (e, ui) {
            var temp_arr = [];
            for (var index in $scope.products_combo[$scope.current_dropzone_index]['images_name']) {
                temp_arr[index] = $scope.products_combo[$scope.current_dropzone_index]['images_name'][index].id;
            }
            $scope.products_combo[$scope.current_dropzone_index]['images'] = temp_arr;
            $http.post(site_settings.api_url + 'updateImagePriority', $scope.products_combo[$scope.current_dropzone_index]['images'])
                    .then(function (response) {
                        console.log(response);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
        }
    };
    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };
    $scope.dzOptionsAddProduct = {
        url: '/api/product/uploadImages',
        maxFilesize: '20',
        paramName: 'photo',
        addRemoveLinks: true,
        maxFiles: 20,
        acceptedFiles: 'image/*',
        thumbnailWidth: '0',
        thumbnailHeight: '0',
        parallelUploads: 1,
//        dictDefaultMessage: '<img width="140" src="/assets/images/clip-512.png">',
//        dictDefaultMessage: 'Upload Product Images Here',
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
//        .config(function (dropzoneOpsProvider) {
//    dropzoneOpsProvider.setOptions({
//        url: '/api/product/uploadImages',
//        maxFilesize: '10',
//        paramName: 'photo',
//        addRemoveLinks: true,
//        maxFiles: 10,
//        acceptedFiles: 'image/*',
//        thumbnailWidth: '100',
//        thumbnailHeight: '100',
//        dictDefaultMessage: '',
//        maxfilesexceeded: function (file) {
////            this.removeAllFiles();
//            this.addFile(file);
//        },
//        sending: function (file, xhr, formData) {
//            console.log(formData)
//            formData.append('folder', 'product');
//        },
//    });
//});
//angular.module("ng-app", [])
//app.filter("filterMine", function () {
//    return function (allOptions, searchval) {
//        var newOptions = [];
//
//        angular.forEach(allOptions, function (currentOption) {
//            console.log(currentOption.data.display_name.indexOf(searchval));
//
//            if (currentOption.data.display_name.indexOf(searchval) != (-1))
//            {
//                newOptions.push(currentOption);
//            }
//        });
//
//        return newOptions;
//    };
//})


app.controller('ProductEditController', function (product, $document, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
{
    $scope.search = [];
    $scope.product = {};
    $scope.products_count = 1;
    $scope.products_combo = [{name: '', price: '', description: ''}];
    $scope.product = {};
    $scope.seller_state_city = {};
//    $scope.product.cat = [];
    $scope.edit = false;
    $scope.product.cities = '';

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

    var count = 30;
    $scope.totalquantitys = [];
    for (var z = 1; z <= count; z++)
    {
        $scope.totalquantitys.push(z);
    }

    $rootScope.$on("newSeller", function (user)
    {
//        console.log($scope.sellers.length);
//        $scope.sellers.push(user);
//        console.log($scope.sellers);
//        console.log($scope.sellers.length);
//        $scope.product.seller_id = user.ID;
//        $scope.getSellers();

    });


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




    $rootScope.$on("newPickUpLocation", function (event, pick_up_location)
    {

        console.log(pick_up_location);
//        $scope.pick_up_locations.push(pick_up_location);
        $scope.getAllPickUpLocations();
        $scope.product.pick_up_location = pick_up_location.id;

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
                })
                .catch(function (error)
                {
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                });
    };

    $scope.searchSellers = function () {
        $http.post(site_settings.api_url + 'search-sellers', {q: $scope.searchTerm})
                .then(function (response) {
                    $scope.sellers = response.data;
                })
                .catch(function () {
                    $scope.sellers = [];
                });
    };


    $scope.getPickUpLocationsWithState = function (sellerid)
    {
        if (sellerid != 'Add New')
        {
            $rootScope.loader = true;
            $scope.seller_state_city = {};
            $http.post(site_settings.api_url + 'seller/get_seller_city_state', {sellerid: sellerid})
                    .then(function (response)
                    {
                        $rootScope.loader = false;
                        if (response.data.er == '0')
                        {
                            $scope.seller_state_city = response.data;
                        }
                        console.log(response.data);
                    }).catch(function (error)
            {
                $rootScope.loader = false;
            });
        }
    };
//    $scope.getSellersFromWP();
//    $scope.getSellers();

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
            $rootScope.message = 'Something Went Wrong';
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
            $rootScope.message = 'Something Went Wrong';
            $rootScope.$emit("notification");
        });
    };

    $scope.changeSelect = function ()
    {
        $scope.company_temp = 1;
        $scope.user.companyname1 = '';
    };

    $scope.getCat = function ()
    {
        $scope.product.cat = {};
        for (var i in $scope.categorys)
        {
            if ($scope.product[$scope.categorys[i].category_name.toLowerCase()] != null && $scope.categorys[i].category_name.toLowerCase() != 'condition' && $scope.categorys[i].category_name.toLowerCase() != 'room' && $scope.categorys[i].category_name.toLowerCase() != 'color')
            {
                $scope.product.cat[$scope.categorys[i].category_name] = $scope.product[$scope.categorys[i].category_name.toLowerCase()].id;
            } else if ($scope.product['product_room'] != null && $scope.categorys[i].category_name.toLowerCase() == 'room')
            {
                $scope.product.cat[$scope.categorys[i].category_name] = [];
                for (var v in $scope.product['product_room'])
                {
                    $scope.product.cat[$scope.categorys[i].category_name].push($scope.product['product_room'][v].id);
                }
            } else if ($scope.product['product_color'] != null && $scope.categorys[i].category_name.toLowerCase() == 'color')
            {
                $scope.product.cat[$scope.categorys[i].category_name] = [];
                for (var v in $scope.product['product_color'])
                {
                    $scope.product.cat[$scope.categorys[i].category_name].push($scope.product['product_color'][v].id);
                }
            } else if ($scope.product['con'] != null && $scope.categorys[i].category_name.toLowerCase() == 'condition')
            {
                $scope.product.cat[$scope.categorys[i].category_name] = $scope.product['con'].id;
            }
        }
    };
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
//    $scope.product_pending_images_name = [];
    if (product)
    {
        $scope.edit = true;
        $scope.action = 'Edit';

        $http.post(site_settings.api_url + 'product/get_product', {id: product})
                .then(function (response)
                {
                    $rootScope.loader = false;

                    $scope.product = response.data;

                    $scope.sellers = [$scope.product.sellerid];
                    if ($scope.product.city == 'TLV Storage - Bridgeport' || $scope.product.city == 'TLV Storage - Cos Cob Office') {
                        $scope.product.cities = $scope.product.city;
                    }
                    else if($scope.product.city != '')
                    {
                        $scope.product.cities = 'Non - Storage Location';
                    }else{
                        $scope.product.cities = '';
                    }


                    $scope.product.sellerid = $scope.product.sellerid.id + "";
//                                $scope.getPickUpLocationsWithState($scope.product.sellerid)
                    $scope.product.status = $scope.product.status.id;
                    if ($scope.product.pick_up_location)
                    {
                        $scope.product.pick_up_location = $scope.product.pick_up_location.id;
                    }
                    if ($scope.product.age)
                    {
                        $scope.product.age = $scope.product.age.id;
                    }
                    if ($scope.product.brand)
                    {
                        $scope.product.brand = $scope.product.brand.id;
                    }
//                    console.log($scope.product);
//                    $scope.product.sell_name = response.data.sell_id.name;
                    $scope.product_pending_images = [];
                    $scope.product_pending_images_name = [];

                    $scope.getCategorys();
                    $scope.getSubCategorys();
                    if ($scope.product.product_pending_images != '')
                    {
                        $scope.mockFiles = [];
                        for (var i in $scope.product.product_pending_images)
                        {
                            $scope.product_pending_images.push($scope.product.product_pending_images[i].id);
                            $scope.product_pending_images_name.push({name: $scope.product.product_pending_images[i].name, filename: $scope.product.product_pending_images[i].name, id: $scope.product.product_pending_images[i].id});

                            $scope.mockFiles.push(
                                    {name: $scope.product.product_pending_images[i].name, id: $scope.product.product_pending_images[i].id, size: 5000, isMock: true, serverImgUrl: '/Uploads/product/' + $scope.product.product_pending_images[i].name}
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
                            console.log($scope.myDz.files);
                        });



                    }
                    $scope.getAllPickUpLocations();

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
                    console.log(error);

                });


    } else
    {
        $scope.action = 'Add';
    }

    $scope.setPriorities = function () {
        var temp_arr = [];
        for (var index in $scope.product_pending_images_name) {
            temp_arr[index] = $scope.product_pending_images_name[index].id;
        }
        $scope.product_pending_images = temp_arr;
        $http.post(site_settings.api_url + 'updateImagePriority', $scope.product_pending_images)
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
    }

    $scope.sortableOptions = {

        update: function (e, ui) {
        },
        stop: function (e, ui) {
            $scope.setPriorities();

        }
    };

    $scope.removeImage = function (file) {
        if (!$scope.myDz)
        {
            $scope.myDz = $scope.dzMethods.getDropzone();

        }
        $scope.myDz.emit('removedfile', file);
    }
    $scope.dzCallbacks = {
        'removedfile': function (file, response)
        {

            var data = {};
            data.folder = 'product';
            data.product_id = product;

            if (file.id == undefined)
            {
                for (var k in $scope.product_pending_images)
                {
                    if ($scope.product_pending_images[k] == JSON.parse(file.xhr.responseText).id)
                    {
                        $scope.product_pending_images.splice(k, 1);
                    }
                }
//                if (JSON.parse(file.xhr.responseText).filename)
//                {
                data.name = JSON.parse(file.xhr.responseText).filename;
//                }
                data.id = JSON.parse(file.xhr.responseText).id;
            } else
            {
                for (var k in $scope.product_pending_images)
                {
                    if ($scope.product_pending_images[k] == file.id)
                    {
                        $scope.product_pending_images.splice(k, 1);
                        $scope.product_pending_images_name.splice(k, 1);
                    }
                }
                data.name = file.name;
                data.name = file.filename;
                data.id = file.id;
            }



            data.imgs = $scope.product_pending_images;

            $http.post('/api/product/deleteImagePending', data)
                    .then(function (response)
                    {
                        console.log('deleted');
                        console.log($scope.product_pending_images);
                    }).catch(function (error)
            {
            });
        },
        'sending': function (file, xhr, formData)
        {

            formData.append('folder', 'product');
        },
        'addedfile': function (file)
        {
            console.log($scope.myDz);

            if (file.isMock)
            {
                $scope.myDz.createThumbnailFromUrl(file, file.serverImgUrl, null, true);
            }
        },
        'success': function (file, xhr)
        {
            // var xhrjson = JSON.parse(xhr);
            var xhrjson = xhr;
            $scope.product_pending_images.push(xhrjson.id);
            $scope.product_pending_images_name.push(xhrjson);
            $scope.setPriorities();
//            console.log($scope.product_images);
            return false;
        }
    };
    $scope.dzMethods = {};
    $scope.removeNewFile = function ()
    {
        $scope.dzMethods.removeFile($scope.newFile); //We got $scope.newFile from 'addedfile' event callback
    }
    $scope.dzMethods = {};
    $scope.saveProduct = function ()
    {
        $scope.product.products = $scope.products_combo;
        $scope.product.product_pending_images = $scope.product_pending_images;
        if ($scope.product.cities == 'TLV Storage - Bridgeport' || $scope.product.cities == 'TLV Storage - Cos Cob Office') {
            $scope.product.city = $scope.product.cities;
            $scope.product.state = "CT";

        }

        $http.post(site_settings.api_url + 'product/edit_product', $scope.product)
                .then(function (response)
                {

                    $rootScope.message = response.data;
                    $rootScope.$emit("notification");
//                    $rootScope.$emit("reloadProductTable");
                    $rootScope.$emit("reloadProductTable1");
                    $mdDialog.hide();
                }).catch(function (error)
        {
            $rootScope.message = error.data;
            $rootScope.$emit("notification");
        });
    };


    $scope.addProductField = function ()
    {
        $scope.products_count++;
        $scope.products_combo.push({name: '', price: '', description: ''})
    }
    $scope.removeProductField = function (c)
    {
        $scope.products_count--;
        $scope.products_combo.splice(c, 1);

    }



    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };
    $scope.dzOptionsEditProduct = {
        url: '/api/product/uploadImages',
        maxFilesize: '20',
        paramName: 'photo',
        addRemoveLinks: true,
        maxFiles: 20,
        acceptedFiles: 'image/*',
        thumbnailWidth: '200',
        thumbnailHeight: '200',
//        dictDefaultMessage: '<img width="140" src="/assets/images/clip-512.png">',
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
})
//        .config(function (dropzoneOpsProvider) {
//    dropzoneOpsProvider.setOptions({
//        url: '/api/product/uploadImages',
//        maxFilesize: '10',
//        paramName: 'photo',
//        addRemoveLinks: true,
//        maxFiles: 10,
//        acceptedFiles: 'image/*',
//        thumbnailWidth: '100',
//        thumbnailHeight: '100',
//        dictDefaultMessage: '',
//        maxfilesexceeded: function (file) {
////            this.removeAllFiles();
//            this.addFile(file);
//        },
//        sending: function (file, xhr, formData) {
//            formData.append('folder', 'product');
//        },
//    });
//});

app.controller('ProductViewController', function (product, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder)
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
                    if (product)
                    {
                        $scope.edit = true;
                        $scope.action = 'View';
                        $http.post(site_settings.api_url + 'product/get_product', {id: product})
                                .then(function (response)
                                {
                                    $scope.product = response.data;
                                    console.log($scope.product);
//                                    $scope.product.sell_name = response.data.sell_id.name;
                                    for (var i in $scope.sellers)
                                    {

                                        if ($scope.product.seller_id == $scope.sellers[i].ID)
                                        {
                                            $scope.product.seller_name = $scope.sellers[i].data.display_name;
                                            $scope.product.seller_email = $scope.sellers[i].data.user_email;
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

                    if (product)
                    {
                        $scope.edit = true;
                        $scope.action = 'View';
                        $http.post(site_settings.api_url + 'product/get_product', {id: product})
                                .then(function (response)
                                {
                                    $rootScope.loader = false;
                                    $scope.product = response.data;
                                    for (var i in $scope.sellers)
                                    {

                                        if ($scope.product.sellerid.id == $scope.sellers[i].id)
                                        {
                                            $scope.product.seller_name = $scope.sellers[i].displayname;
                                            $scope.product.seller_email = $scope.sellers[i].email;
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
//    $scope.getSellers();


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

    $scope.closeDialog = function ()
    {
        $mdDialog.hide();
    };


});