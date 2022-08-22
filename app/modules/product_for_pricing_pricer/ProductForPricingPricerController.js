"use strict";

var app = angular.module('ng-app');
app.controller('ProductForPricingPricerController', function ($document, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder, ngAAToken, $window)
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
        url: site_settings.api_url + 'product_for_pricing/get_product_for_pricings',
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
                        $scope.storage_price = false;
                        $scope.Archive = false;
                        $scope.Reject = false;
                        $compile(nRow)($scope);
                    });

    function actionsHtml(data, type, full, meta)
    {
        var action_btn = '';

        if (full.is_product_for_production == 0)
        {
            action_btn += '<span ng-click="openProductQuotationPricerDialog(' + data.id + ');" class="text-boxed m-0 deep-blue-bg white-fg" style="cursor: pointer; background-color: #126491 !important;">VIEW</span>';
        }
        return action_btn;
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
        DTColumnBuilder.newColumn('tlv_price').withTitle('TLV Price').renderWith(skuRender).notSortable(),
        DTColumnBuilder.newColumn('storage_pricing').withTitle('Storage Price').notSortable(),
        DTColumnBuilder.newColumn('quote_created_at').withTitle('Date').renderWith(renderDate),
        DTColumnBuilder.newColumn(null).withTitle('Aging').renderWith(agingRender),
//        DTColumnBuilder.newColumn('agent_name').withTitle('Agent').notSortable().renderWith(skuRender),
        DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable()
                .renderWith(actionsHtml),
    ];

    $scope.openProductQuotationPricerDialog = function (product_quotation)
    {
        $mdDialog.show({
            controller: 'ProductForPricingPricerEditController',
            templateUrl: 'app/modules/product_for_pricing_pricer/views/product_for_pricing_edit_pricer.html',
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

    $rootScope.$on("reloadProductForProductionTable", function (event, args)
    {
        $scope.select = [];
        reloadData();
    });

    function reloadData()
    {
        var resetPaging = false;
        $scope.dtInstance.reloadData(callback, resetPaging);
    }
});

app.controller('ProductForPricingPricerEditController', function (product_quotation, $parse, $document, $timeout, $mdDialog, $rootScope, $state, $compile, $resource, $scope, $auth, $q, $stateParams, $http, site_settings, DTColumnDefBuilder, DTOptionsBuilder, DTColumnBuilder) {

    $scope.isPricerUser = false;
    $scope.isCopywriterUser = false;

    $auth.getProfile().then(function (profile) {

        for (var i = 0; i < profile.roles.length; i++) {
            if (profile.roles[i].id == 1 || profile.roles[i].id == 2 || profile.roles[i].id == 5) {
                $scope.isCopywriterUser = true;
            }
            if (profile.roles[i].id == 6) {
                $scope.isPricerUser = true;
            }
            if (profile.roles[i].id == 7) {
                $scope.isCopywriterUser = true;
                $scope.isPricerUser = true;
            }
        }
    });

    $scope.product_quotation = {};
    $scope.product_pending_images_name = [];
    $scope.product_pending_images = [];
    $scope.categorys = {};
    $scope.subcategorys = {};
    $scope.product_material_categorys = [];
    $scope.ship_sizes = {};
    $scope.sub_categorys = [];
    $scope.product = {};
    $scope.edit = false;
    $scope.action = 'Edit';
    $scope.searchTerm = '';
    $scope.commissions = [100, 80, 70, 60, 50, 40];
    $scope.product_quotation.commission = '60';
    $scope.product_quotation.cities = '';

    $scope.removeSub = function (category_name)
    {
        return category_name.replace("Sub", "");
    };

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

    $scope.getAllPickUpLocations = function (seller_id)
    {
        $scope.pick_up_locations = [];
        $http.get(site_settings.api_url + 'getPickUpLocationsBySelectIdSellerId/6/' + seller_id)
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
                            $scope.temp_categorys[v].category_name = 'Sub Category';
                            $scope.tempcategorys.push($scope.temp_categorys[v]);
                        } else
                        {
                            $scope.tempcategorys.push($scope.temp_categorys[v]);
                        }
                    }

                    $scope.categorys = $scope.tempcategorys;
                    $scope.getCat();
                })
                .catch(function (error)
                {
                    $rootScope.message = 'Something Went Wrong.';
                    $rootScope.$emit("notification");
                });
    };

    $scope.getAllSubCategorysOfCategory = function ()
    {
        $http.get(site_settings.api_url + 'get_all_subcategorys_of_product_materials/2')
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

    $scope.SubcategorySelected = function (sub_category) {}

    $scope.getSubCategorys = function ()
    {
        $scope.temp_subcategorys = [];
        $scope.tempsubcategorys = [];
        $http.get(site_settings.api_url + 'get_all_subcategorys')
                .then(function (response)
                {
                    $scope.subcategorys = response.data;

                })
                .catch(function (error)
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
                })
                .catch(function (error)
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
        $scope.product_quotation.product_id.cat = {};
        for (var i in $scope.categorys)
        {
            if ($scope.product_quotation.product_id[$scope.categorys[i].category_name.toLowerCase()] != null && $scope.categorys[i].category_name.toLowerCase() != 'condition' && $scope.categorys[i].category_name.toLowerCase() != 'category' && $scope.categorys[i].category_name.toLowerCase() != 'condition' && $scope.categorys[i].category_name.toLowerCase() != 'room' && $scope.categorys[i].category_name.toLowerCase() != 'color' && $scope.categorys[i].category_name.toLowerCase() != 'collection')
            {
                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = $scope.product_quotation.product_id[$scope.categorys[i].category_name.toLowerCase()].id;
            } else if ($scope.product_quotation.product_id['product_room'] != null && $scope.categorys[i].category_name.toLowerCase() == 'room')
            {
                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = [];
                for (var v in $scope.product_quotation.product_id['product_room'])
                {
                    $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name].push($scope.product_quotation.product_id['product_room'][v].id);
                }
            } else if ($scope.product_quotation.product_id['product_collection'] != null && $scope.categorys[i].category_name.toLowerCase() == 'collection')
            {
                $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name] = [];
                for (var v in $scope.product_quotation.product_id['product_collection'])
                {
                    $scope.product_quotation.product_id.cat[$scope.categorys[i].category_name].push($scope.product_quotation.product_id['product_collection'][v].id);
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
                        }
                    }
                }
                $scope.MaincategorySelected($scope.product_quotation.product_id.cat[$scope.categorys[i].category_name]);
//                console.log($scope.product_quotation.product_id.cat['Category']);
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
//                console.log($scope.product_quotation.product_id.cat[$scope.categorys[i].category_name]);
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

    if (!product_quotation)
    {
        $scope.getSellers();
    }

    $scope.getProductQuatation = function () {
        if (product_quotation)
        {
            $rootScope.loader = true;

            if ($scope.isPricerUser) {
                $scope.action = 'Edit For Pricing';
                console.log('called');
            }

            if ($scope.isCopywriterUser) {
                $scope.action = 'Edit Copywriter';
                console.log('called');
            }

            $http.post(site_settings.api_url + 'product_quotation/get_product_quotation', {id: product_quotation})
                    .then(function (response)
                    {
                        $rootScope.loader = false;
                        $scope.product_quotation = response.data;

                        if ($scope.product_quotation.product_id.city == 'TLV Storage - Bridgeport' || $scope.product_quotation.product_id.city == 'TLV Storage - Cos Cob Office') {
                            $scope.product_quotation.product_id.cities = $scope.product_quotation.product_id.city;
                        } else if ($scope.product_quotation.product_id.city != '')
                        {
                            $scope.product_quotation.product_id.cities = 'Non - Storage Location';
                        } else {
                            $scope.product_quotation.product_id.cities = '';
                        }

                        $scope.product_quotation.product_id.seller_id = $scope.product_quotation.product_id.sellerid.id;

                        $scope.sellers = [$scope.product_quotation.product_id.sellerid];

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

                        if (!$scope.product_quotation.commission) {
                            $scope.product_quotation.commission = '60';
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
                        }
                    })
                    .catch(function (error)
                    {
                        $rootScope.loader = false;
                    });

        } else
        {
            $scope.product_quotation.quantity = 1;
            $scope.getAllPickUpLocations(null);
            $scope.getCategorys();
            $scope.getSubCategorys();
            $scope.action = 'Add';
        }
    };

    $scope.getProductQuatation();

    $scope.getTaxClassFromWP = function ()
    {

        $http.jsonp(site_settings.wp_api_url + 'tlvotherinfo.php?skey=tlvesbyat&tax_class=true', {jsonpCallbackParam: 'callback'})
                .then(function (response)
                {
                    $scope.tax_class = response.data;
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
                })
                .catch(function (response) {});
    };
    $scope.getShippingClassFromWP();

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

    $scope.saveProductForProduction = function ()
    {

        if ($scope.product_quotation.product_id.cat['Category'] || $scope.product_quotation.product_id.cat['Sub Category'])
        {
            $scope.product_quotation.product_id.cat['Sub Category'] = $scope.merge_array($scope.product_quotation.product_id.cat['Category'], $scope.product_quotation.product_id.cat['Sub Category']);
        }

        $scope.product_quotation.images = [];
        $scope.product_quotation.images = $scope.product_pending_images;
        $scope.product_quotation.passfrom = 'product_for_production';
        if ($scope.product_quotation.product_id.cities == 'TLV Storage - Bridgeport' || $scope.product_quotation.product_id.cities == 'TLV Storage - Cos Cob Office') {
            $scope.product_quotation.product_id.city = $scope.product_quotation.product_id.cities;
            $scope.product_quotation.product_id.state = "CT";
        }

        $http.post(site_settings.api_url + 'product_for_pricing/save_product_pricing_final', $scope.product_quotation)
                .then(function (response) {
                    $rootScope.message = response.data;
                    $rootScope.$emit("notification");
                    $rootScope.$emit("reloadProductForProductionTable");
                    $mdDialog.hide();
                })
                .catch(function (error)
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