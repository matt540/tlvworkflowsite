<table>
    <tr>
        <td>
            <table>
                <tr>
                    <td></td>
                </tr>
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
                    <td><b>Sort Description</b></td>
                    <td><b>Dimensions/Product Details</b></td>
                    <td><b>Condition Notes</b></td>
                    <td><b>Internal Note</b></td>
                    <td><b>Retail Price</b></td>
                    <td><b>TLV Price</b></td>
                    <td><b>Storage Price</b></td>
                    <td><b>Sale Price</b></td>
                    <td><b>Commission</b></td>
                    <td><b>Units</b></td>
                    <td><b>Width</b></td>
                    <td><b>Depth</b></td>
                    <td><b>Height</b></td>
                    <td><b>Seat Height</b></td>
                    <td><b>Arm Height</b></td>
                    <td><b>Inside Seat Depth</b></td>
                    <td><b>Shipping Size</b></td>
                    <td><b>Is this a pet free home?</b></td>
                    <td><b>Delivery Option</b></td>
                    <td><b>Flat Rate Packaging Fee</b></td>
                    <td><b>Item Location</b></td>
                    <td><b>City</b></td>
                    <td><b>State</b></td>
                    <td><b>Curator or Referral Name</b></td>
                    <td><b>Curator or Referral Commission</b></td>
                    <td><b>Stock Status</b></td>
                    <td><b>Publish Date</b></td>
                    <td><b>Expiration Date</b></td>
                    <td><b>Product Location</b></td>
                    <td><b>Order Number</b></td>
                    <td><b>Date Created</b></td>
                    <td><b>Status</b></td>
                    <td><b>Payment Method</b></td>
                    <td><b>Payment Method Title</b></td>
                    <td><b>Transaction Id</b></td>
                    <td><b>Sale Price</b></td>
                    <td><b>Quantity</b></td>
                    <td><b>Sub Total</b></td>
                    <td><b>Commission</b></td>
                    <td><b>Commission Total</b></td>
                    <td><b>Total</b></td>
                    <td><b>User Name</b></td>
                    <td><b>Buyer Name</b></td>
                    <td><b>Buyer Email</b></td>
                    <td><b>Billing</b></td>
                    <td><b>Shipping</b></td>
                    <td><b>Buyer Type</b></td>
                    <td><b>Make an Offer</b></td>
                    <td><b>Shipping Category</b></td>
                    <td><b>Shipping Charge</b></td>

                </tr>
                @foreach($products as $key => $value)
                    <tr>
                        <td>{{$value[0]}}</td>
                        <td>{{$value[1]}}</td>
                        <td>{{$value[2]}}</td>
                        <td>{{$value[3]}}</td>
                        <td>{{$value[4]}}</td>
                        <td>{{$value[5]}}</td>
                        <td>{{$value[6]}}</td>
                        <td>{{$value[7]}}</td>
                        <td>{{$value[8]}}</td>
                        <td>{{$value[9]}}</td>
                        <td>{!! $value[10] !!}</td>
                        <td>{{$value[11]}}</td>
                        <td>{{$value[12]}}</td>
                        <td>{{$value[13]}}</td>
                        <td>{{$value[14]}}</td>
                        <td>{{$value[15]}}</td>
                        <td>{{$value[16]}}</td>
                        <td>{{$value[17]}}</td>
                        <td>{{$value[18]}}</td>
                        <td>{{$value[19]}}</td>
                        <td>{{$value[20]}}</td>
                        <td>{{$value[21]}}</td>
                        <td>{{$value[22]}}</td>
                        <td>{{$value[23]}}</td>
                        <td>{{$value[24]}}</td>
                        <td>{{$value[25]}}</td>
                        <td>{{$value[26]}}</td>
                        <td>{{$value[27]}}</td>
                        <td>{{$value[28]}}</td>
                        <td>
                            @if($value[29] == 'TLV Storage - Bridgeport' || $value[29] == 'TLV Storage - Cos Cob Office')
                                {{$value[29]}}
                            @elseif($value[29] != '')
                                Non - Storage Location
                            @endif
                        </td>
                        <td>
                            @if($value[29] != 'TLV Storage - Bridgeport' && $value[29] != 'TLV Storage - Cos Cob Office' && $value[29] != '')
                                {{$value[29]}}
                            @endif
                        </td>
                        <td>
                            @if($value[29] != 'TLV Storage - Bridgeport' && $value[29] != 'TLV Storage - Cos Cob Office')
                                {{$value[30]}}
                            @endif
                        </td>
                        <td>{{$value[31]}}</td>
                        <td>{{$value[32]}}</td>
                        <td>{{$value[33]}}</td>
                        <td>@if(isset($value[34]) && $value[34] !== '') {{ $value[34]->format('Y-m-d H:i:s') }} @endif</td>
                        <td>@if(isset($value[35]) && $value[35] !== '') {{ $value[35] }} @endif</td>
                        <td>@if(isset($value[36]) && $value[36] !== '') {{ $value[36] }} @endif</td>
                        <td>@if(isset($value[37]) && $value[37] !== '') {{ $value[37] }} @endif</td>
                        <td>@if(isset($value[38]) && $value[38] !== '') {{ $value[38]->format('Y-m-d H:i:s') }} @endif</td>
                        <td>@if(isset($value[39]) && $value[39] !== '') {{ $value[39] }} @endif</td>
                        <td>@if(isset($value[40]) && $value[40] !== '') {{ $value[40] }} @endif</td>
                        <td>@if(isset($value[41]) && $value[41] !== '') {{ $value[41] }} @endif</td>
                        <td>@if(isset($value[42]) && $value[42] !== '') {{ $value[42] }} @endif</td>
                        <td>@if(isset($value[43]) && $value[43] !== '') {{ $value[43] }} @endif</td>
                        <td>@if(isset($value[44]) && $value[44] !== '') {{ $value[44] }} @endif</td>
                        <td>@if(isset($value[45]) && $value[45] !== '') {{ $value[45] }} @endif</td>
                        <td>@if(isset($value[46]) && $value[46] !== '') {{ $value[46] }} @endif</td>
                        <td>@if(isset($value[47]) && $value[47] !== '') {{ $value[47] }} @endif</td>
                        <td>@if(isset($value[48]) && $value[48] !== '') {{ $value[48] }} @endif</td>
                        <td>@if(isset($value[49]) && $value[49] !== '') {{ $value[49] }} @endif</td>
                        <td>@if(isset($value[50]) && $value[50] !== '') {{ $value[50] }} @endif</td>
                        <td>@if(isset($value[51]) && $value[51] !== '') {{ $value[51] }} @endif</td>
                        <td>@if(isset($value[52]) && $value[52] !== '') {{ $value[52] }} @endif</td>
                        <td>@if(isset($value[53]) && $value[53] !== '') {{ $value[53] }} @endif</td>
                        <td>@if(isset($value[54]) && $value[54] !== '') {{ $value[54] }} @endif</td>
                        <td>@if(isset($value[55]) && $value[55] !== '') {{ $value[55] }} @endif</td>
                        <td>@if(isset($value[56]) && $value[56] !== '') {{ $value[56] }} @endif</td>
                        <td>@if(isset($value[57]) && $value[57] !== '') {{ $value[57] }} @endif</td>


                    </tr>
                @endforeach
            </table>
        </td>
    </tr>
</table>
