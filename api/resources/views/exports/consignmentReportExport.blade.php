<table>
    <tr>
        <td>
            <table>
                <tr>
                    <td><b>Seller Name</b></td>
                    <td><b>Seller Email</b></td>
                    <td><b>Seller Address</b></td>
                    <td><b>Product Name</b></td>
                    <td><b>Category</b></td>
                    <td><b>Sub Category</b></td>
                    <td><b>Brand</b></td>
                    <td><b>Product SKU</b></td>
                    <td><b>Quantity</b></td>
                    <td><b>Retail Price</b></td>
                    <td><b>TLV Price</b></td>
                    <td><b>Storage Price</b></td>
                    <td><b>Commission</b></td>
                    <td><b>Shipping Size</b></td>
                    <td><b>Is this a pet free home?</b></td>
                    <td><b>Delivery Option</b></td>
                    <td><b>Flat Rate Packaging Fee</b></td>
                    <td><b>City</b></td>
                    <td><b>State</b></td>
                    <td><b>Curator or Referral Name</b></td>
                    <td><b>Curator or Referral Commission</b></td>
                    <td><b>Publish Date</b></td>
                    <td><b>Expiration Date</b></td>
                    <td><b>Product Location</b></td>
                    <td><b>Shipping Calculator on/off</b></td>
                </tr>
                @foreach($products as $key => $value)
                    <tr>
                        <td>
                            @if($value['product_id']['sellerid'] != '')
                                {{$value['product_id']['sellerid']['firstname'] }} {{ $value['product_id']['sellerid']['lastname'] }}
                            @endif
                        </td>
                        <td>
                            @if($value['product_id']['sellerid'] != '')
                                {{$value['product_id']['sellerid']['email'] }}
                            @endif
                        </td>
                        <td>
                            @if($value['product_id']['sellerid'] != '')
                                {{$value['product_id']['sellerid']['address'] }}
                            @endif
                        </td>
                        <td>{{$value['product_id']['name']}}</td>
                        <td>
                            <?php
                            foreach ($value['product_id']['product_category'] as $category) {
                                if ($category['is_enable'] == '1') {
                                    echo $category['sub_category_name'];
                                }
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            foreach ($value['product_id']['product_category'] as $category) {
                                if ($category['is_enable'] == '0') {
                                    echo $category['sub_category_name'];
                                }
                            }
                            ?>
                        </td>
                        <td>
                            @if(!empty($value['product_id']['brand']))
                                {{$value['product_id']['brand']['sub_category_name']}}
                            @endif
                        </td>
                        <td>{{$value['product_id']['sku']}}</td>
                        <td>{{$value['product_id']['quantity']}}</td>
                        <td>{{$value['price']}}</td>
                        <td>{{$value['tlv_price']}}</td>
                        <td>{{$value['storage_pricing']}}</td>
                        <td>{{$value['commission']}}</td>
                        <td>{{$value['product_id']['ship_size']}}</td>
                        <td>{{$value['product_id']['pet_free']}}</td>
                        <td>{{$value['delivery_option']}}</td>
                        <td>{{$value['product_id']['flat_rate_packaging_fee']}}</td>
                        <td>{{$value['product_id']['city']}}</td>
                        <td>{{$value['product_id']['state']}}</td>
                        <td>{{$value['curator_name']}}</td>
                        <td>{{$value['curator_commission']}}</td>
                        <td>
                            @if (!empty($value['wp_published_date']))
                                {{\Carbon\Carbon::parse($value['wp_published_date'])->format('Y-m-d H:i:s')}}
                            @endif
                        </td>
                        <td>{{$value['wp_product_expire_date']}}</td>
                        <td>
                            <?php
                            $seller_to_drop_off = 'False';
                            if (!empty($value['seller_to_drop_off'])) {
                                if ($value['seller_to_drop_off'] == true) {
                                    $seller_to_drop_off = 'True';
                                } else {
                                    $seller_to_drop_off = 'False';
                                }
                            }
                            echo $seller_to_drop_off;
                            ?>
                        </td>
                        <td>
                            <?php
                            $shipping_calculator = 'False';
                            if (!empty($value['shipping_calculator'])) {
                                if ($value['shipping_calculator'] == true) {
                                    $shipping_calculator = 'True';
                                } else {
                                    $shipping_calculator = 'False';
                                }
                            }
                            echo $shipping_calculator;
                            ?>
                        </td>


                    </tr>
                @endforeach
            </table>
        </td>
    </tr>
</table>
