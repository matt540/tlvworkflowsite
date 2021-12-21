<html>
    <head></head>
    <style>
         *{margin: 0px;padding: 0px;}
        .box-wrap{
            /*margin: 0px -10px;*/
        }
        .table-box{
            border-radius:10px;
            overflow: hidden;
        }
        table {
            border-collapse: collapse;
            font-size: 14px;
            border-radius: 10px;
            width: 100%;
        }

        table tr th , table tr td {
            border:none;
        }

        table.box tr  td{
            border: 1px solid #aaa;
            text-align: left;
            padding: 0px;
            border-radius: 10px;
        }
       .maxwidth {
            max-width: 214px;
            min-width: 214px;
            width: 214px;
        }
    
    </style>
    <body>
        <div class="box-wrap">
            <table cellspacing="10" cellpadding="0">
                @foreach($productsChunks as $key => $productsChunk)
                <tr >
                    @foreach($productsChunk as $product)
                    <td>
                        
                        <table style="font-size: 13px;"  cellpadding="10" >
                                <tr >
                                   <td style="border: 1px solid #aaa;text-align: left;width: 86px;height: 50px"><strong>Seller</strong></td>
                                    <td style="border: 1px solid #aaa;text-align: left;height: 50px"  class="maxWidth">{{$seller->getDisplayname()}}</td>
                                </tr>
                                <tr >
                                    <td style="border: 1px solid #aaa;text-align: left;width: 86px;height: 60px" ><strong>Product</strong></td>
                                    <td style="border: 1px solid #aaa;text-align: left;height: 60px"  class="maxWidth">{{$product['product_id']['name']}}</td>
                                </tr>
                                <tr >
                                    <td style="border: 1px solid #aaa;text-align: left;width: 86px;height: 50px"  ><strong>SKU</strong></td>
                                    <td style="border: 1px solid #aaa;text-align: left;height: 50px"  class="maxWidth">{{$product['product_id']['sku']}}</td>
                                </tr>
                               
                            </table>
                       
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </table>
        </div>
    </body>
</html>