<?php
if(isset($_GET["transfer_id"])){
$trans_id = htmlspecialchars($_GET["transfer_id"]);
$url = "https://api.flutterwave.com/v3/transfers/" . $trans_id;

//Create cURL session
$curl = curl_init($url);

//Turn off Mandatory SSL Checker
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

//Configure the cURL  session based on the type of request
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

//Decide that this is a GET request
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

//Set the API headers
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer YOUR_SECRET_KEY_HERE",
    "Content-type: Application/json"
]);

//Run the curl
$run = curl_exec($curl);

//Error checker
$error = curl_error($curl);

if($error){
    die("Curl returned some errors: " . $error);
}

//Convert to jSON object

$result = json_decode($run);
$status = $result->data->status;
$amount = $result->data->amount;
$account_number = $result->data->account_number;
$id = $result->data->id;
$ref = $result->data->reference;
$fullName = $result->data->full_name;
$date = $result->data->created_at;
$bank_name = $result->data->bank_name;
$narration = $result->data->narration;
//Close cURL session
curl_close($curl);

//var_dump($run);
}else{
    die("We could not find an ID to that transfer");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification | Rave Transfer App</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Verification | Rave Transfer App</h1>
        <hr><br>
     <p id="error">We could not verify this transfer this time.</p>
    </div>
</body>
</html>