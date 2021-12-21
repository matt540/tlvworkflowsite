<table style="border:1px black solid;">
    <thead>
        <tr style="font-weight: bold;border-bottom: 1px black solid;">
            <th>Seller Name</th>
            <th>Product Name</th>
            <th>SKU</th>
            <th>Storage Cost</th>
            <th>Storage Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td>{{$product['seller_name']}}</td>
            <td>{{$product['name']}}</td>
            <td>{{$product['sku']}}</td>
            <td>{{$product['storage_pricing']}}</td>
            <td>{{$product['storage_date']}}</td>
        </tr>
        @endforeach
    </tbody>
</table>