
<html>
    <head></head>
    <style>
        .border{
            border: 1px solid black;
        }
        .border-top{
            border-top: 1px solid black;
        }
        .border-bottom{
            border-bottom: 1px solid black;
        }
        .border-left{
            border-left: 1px solid black;
        }
        .border-right{
            border-right: 1px solid black;
        }
        .p-1{
            padding:1px;
        }
        th, td{
            padding: 5px;
            font-size: 13px;
        }
        .w-25{
            width: 25%;
        }
    </style>
    <body>
        <table style="margin-bottom:10px;">
            <thead>
                <tr>
                    <th class="w-25">Seller</th>
                    <th>{{$seller->getDisplayname()}}</th>
                </tr>
                <tr>
                    <th class="w-25">Email</th>
                    <th>{{$seller->getEmail()}}</th>
                </tr>
                <tr>
                    <th class="w-25">Phone</th>
                    <th>{{$seller->getPhone()}}</th>
                </tr>
            </thead>
        </table>
        <table cellpadding='5'>
            <thead>
                <tr>
                    <th class="border" style="width:10%;padding: 5px;font-size: 13px;">SKU</th>
                    <th class="border" style="width:10%;padding: 5px;font-size: 13px;">Name</th>
                    <th class="border" style="width:10%;padding: 5px;font-size: 13px;">TLV Price</th>
{{--                    <th class="border" style="width:10%;padding: 5px;font-size: 13px;">Location</th>--}}
                    <th class="border" style="width:30%;padding: 5px;font-size: 13px;">Description</th>
                    <th class="border" style="width:10%;padding: 5px;font-size: 13px;">Dimension</th>
                    <th class="border" style="width:15%;padding: 5px;font-size: 13px;">Condition Notes</th>
                    <th class="border" style="width:15%;padding: 5px;font-size: 13px;">Image</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td class="border" style="width:10%;padding: 5px;font-size: 13px;">{{$product['product_id']['sku']}}</td>
                    <td class="border" style="width:10%;padding: 5px;font-size: 13px;">{{$product['product_id']['name']}}</td>
                    <td class="border" style="width:10%;padding: 5px;font-size: 13px;">{{$product['product_id']['tlv_price']}}</td>
{{--                    <td class="border" style="width:10%;padding: 5px;font-size: 13px;">{{$product['product_id']['location']}}</td>--}}
                    <td class="border" style="width:30%;padding: 5px;font-size: 13px;padding: 5px;">{{$product['dimension_description']}}</td>
                    <td class="border" style="width:10%;padding: 5px;font-size: 13px;">
                        @if($product['units'])
                        <span><b>Units:</b>{{$product['units']}}</span><br/>
                        @endif
                        @if($product['width'])
                        <span><b>Width:</b>{{$product['width']}}</span><br/>
                        @endif
                        @if($product['depth'])
                        <span><b>Depth:</b>{{$product['depth']}}</span><br/>
                        @endif
                        @if($product['height'])
                        <span><b>Height:</b>{{$product['height']}}</span><br/>
                        @endif
                        @if($product['seat_height'])
                        <span><b>Seat Height:</b>{{$product['seat_height']}}</span><br/>
                        @endif
                        @if($product['arm_height'])
                        <span><b>Arm Height:</b>{{$product['arm_height']}}</span><br/>
                        @endif
                    </td>
                    <td class="border" style="width:15%;padding: 5px;font-size: 13px;padding: 5px;">{{$product['condition_note']}}</td>
                    <td class="border" style="width:15%">
                        @if(count($product['product_id']['product_pending_images'])>0)
                        <img width="100" height="100" src="{{config('app.url').'/Uploads/product/'.$product['product_id']['product_pending_images'][0]['name']}}" />
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
