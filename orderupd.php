<?php

$servername = "localhost";
$username = "matth803_1";
$password = "tlvworkflow123";
$dbname = "matth803_1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM orders where flag='0' limit 100";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://thelocalvault.com/wp-json/tlv_get_order_user/order_user',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('order_id' => $row['order_id']),
          CURLOPT_HTTPHEADER => array(
            ': '
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

        if($response != '0'){

        // $sql = "UPDATE orders SET date_created='" . $response . "',flag='1' WHERE id ='" . $row['id'] . "' and order_id='" . $row['order_id'] . "' ";
            $sql = "UPDATE orders SET customer_username='" . $response . "',flag='1' WHERE id ='" . $row['id'] . "' and order_id='" . $row['order_id'] . "' ";

            $conn->query($sql);
        }else{
            $sql = "UPDATE orders SET flag='2' WHERE id ='" . $row['id'] . "' and order_id='" . $row['order_id'] . "' ";

            $conn->query($sql);
        }
    }
} else {
    echo "0 results";
}
$conn->close();
?>