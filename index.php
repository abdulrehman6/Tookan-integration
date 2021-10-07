<?php
$tookanApiKey = 'add Tookan api key here';
$getSingleOrder = getSingleOrder($order_id);
$getOrderDetails = getOrderDetails($order_id);
$customerDetails = getCustomerProfileData($getSingleOrder->customer_id);
$payment_gateway = $getSingleOrder->payment_gateway;
$type ='Credit Card';

$shipAddress ='';
if(empty($getSingleOrder->shipping_address)){  $shipAddress = $getSingleOrder->billing_address; }else{  $shipAddress = $getSingleOrder->shipping_address; }

//adding 30 minutes for pick up time
$minutes_to_add = "+30 minutes";
$startTime = date("Y-m-d H:i:s");

$convertedTime = date('Y-m-d H:i:s', strtotime("$minutes_to_add", strtotime($startTime)));
//echo $convertedTime;
$data = array(
'api_key' => $tookanApiKey,
'order_id'=> $order_id,
'team_id'=> 'get team id from Tookan dashboard',
'auto_assignment'=> '1',
'job_description'=> 'groceries delivery',
'job_pickup_phone'=> $customerDetails->phone_number,
'job_pickup_name'=>  $getSingleOrder->customer_first_name." ".$getSingleOrder->customer_last_name,
'job_pickup_email'=> $getSingleOrder->customer_email,
'job_pickup_address'=>  $shipAddress,
'job_pickup_latitude'=> '',
'job_pickup_longitude'=> '',
'job_pickup_datetime'=> date("Y-m-d H:i:s"),
'customer_email'=>   $getSingleOrder->customer_email,
'customer_username'=> $getSingleOrder->customer_first_name." ".$getSingleOrder->customer_last_name,
'customer_phone'=> $customerDetails->phone_number,
'customer_address'=> $shipAddress,
'latitude'=> '',
'longitude'=> '',
'longitude'=> '',
'job_delivery_datetime'=> $convertedTime,
'has_pickup'=> '1',
'has_delivery'=> '1',
'layout_type'=> '0',
'tracking_link'=> '1',
'timezone'=> '-270',
'notify'=> 1,
'tags'=> '',
'geofence'=> '0',
'ride_type'=> '0'

);
$data_string = json_encode($data);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,            "https://api.tookanapp.com/v2/create_task");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST,           true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));


$res = curl_exec($ch);
$result=  json_decode($res);
if($result->status ==200){//success
//deliveryOrderId
//delivery_job_id
//print_r($result->data->order_id);
//print_r($result->data->delivery_tracing_link);
//$trackingUrlParts = explode('/', $result->data->delivery_tracing_link);
//$trackingUrlParts[3]; //tracking url id
return  $result;
}else{
//echo $convertedTime;

return  "API call failed: " . $result->message;
}



?>