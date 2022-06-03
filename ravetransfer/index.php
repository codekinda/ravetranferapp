<?php
$fullname = $bankCode = $accountNo = $amount = $error = "";
if(isset($_POST["pay"])){
 //echo"Transfer Initiated!";
$fullname = htmlspecialchars($_POST["fullname"]);
$bankCode = htmlspecialchars($_POST["bank_code"]);
$accountNo = htmlspecialchars($_POST["account_no"]);
$amount = htmlspecialchars($_POST["amount"]);

//Check for errors
if(empty($fullname) OR empty($bankCode) OR empty($accountNo) OR empty($amount)){
$error = "All fields are require!";
}else{
 //Integrate the Flutterwave Tranfer API
 $tranfer_data = [
    "full_name" => $fullname,
    "account_bank" => $bankCode,
    "account_number" => $accountNo,
    "amount" => $amount,
    "narration" => "Staff salary payment from CodeKinda",
    "currency" => "NGN",
    "debit_currency" => "NGN",
    "refenrnce" => uniqid() . "_PMCKDU_1",
    "redirect_url" => "http://localhost/ravetranfer/verify.php"
 ];

 $url = "https://api.flutterwave.com/v3/transfers";
    //Create cURL session
$curl = curl_init($url);

//Turn off Mandatory SSL Checker
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

//Configure the cURL  session based on the type of request
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

//Decide that this is a POST request
curl_setopt($curl, CURLOPT_POST, true);

//Convert the request data to a JSON data
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($tranfer_data));

//Set the API headers
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer YOUR_SECRET_KEY_HERE",
    "Content-type: Application/json"
]);

//Run the curl
$run = curl_exec($curl);

//Convert to obj
$response = json_decode($run);
//Error checker
$error = curl_error($curl);

if($error){
    die("Curl returned some errors: " . $error);
}
echo $status = $response->status;
$trans_id = $response->data->id;
//Close cURL session
curl_close($curl);

echo"<pre>" . $run . "</pre>";
//Integration ends here
if($status == "success"){
    //echo"Success, we move, very fast";
   header("Location: verify.php?transfer_id=" . $trans_id);
  exit;
}else{
   header("Location: fail.php");
  exit;
}
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rave Transfer App</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Rave Transfer App</h1>
        <span id="error"><?php echo $error; ?></span>
        <form action="" method="POST">
            <label>Full Name</label>
            <input type="text" name="fullname" value="<?php echo $fullname; ?>">
            <label>Bank Name</label>
            <select name="bank_code">
                <option>Choose your Bank</option>
                <option value="044">Access Bank</option>
                <option value="011">First Bank</option>
            </select>
             <label>Account No</label>
            <input type="number" name="account_no" value="<?php echo $accountNo; ?>">
             <label>Amount</label>
            <input type="number" name="amount" value="<?php echo $amount; ?>">
            <input type="submit" name="pay" value="Transfer">
        </form>
    </div>
</body>
</html>