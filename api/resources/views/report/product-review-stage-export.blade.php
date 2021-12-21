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
            padding: 10px;
            border-radius: 10px;
        }

        .maxwidth {
            max-width: 146px;
            min-width: 146px;
            width: 146px;
        }

    </style>
    <body>
        <div class="box-wrap">
            <table>
                @foreach($productsChunks as $productsChunk)
                <tr>
                    @foreach($productsChunk as $product)
                    <td style="width:50%">
                        <div class="table-box">
                            <table class="box">
                                <tr>
                                    <td style="padding: 5px;" class="maxWidth">Product</td>
                                    <td style="padding: 5px;" >{{ $product['name'] }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px;">SKU</td>
                                    <td style="padding: 5px;">{{ $product['sku'] }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px;">Description</td>
                                    <td style="padding: 5px;">{{ $product['description'] }}</td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </table>
        </div>
    </body>
</html>