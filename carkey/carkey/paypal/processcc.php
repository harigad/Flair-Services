<?php
include_once 'country.php';

//$countrycode=array_search(strtoupper($_POST['countrycode']),$country);
$countrycode="US";
$expdate=$_POST['month'].$_POST['year'];
if(strlen($expdate)==5) $expdate="0".$expdate;
// Set PayPal API version and credentials.
$api_version = '85.0';
$api_endpoint = 'https://api-3t.paypal.com/nvp';
$api_username = 'techd82_api1.yahoo.com';
$api_password =  '8T9KXNFZUX54WBBF';
$api_signature = 'AuRA8YAFrqaa8el6GaeJD4DBTrntAHLbWMjYm-2vS545N94euoW86fm5';

// Store request params in an array
$request_params = array
					(
					'METHOD' => 'DoDirectPayment', 
					'USER' => $api_username, 
					'PWD' => $api_password, 
					'SIGNATURE' => $api_signature, 
					'VERSION' => $api_version, 
					'PAYMENTACTION' => 'Sale', 					
					'IPADDRESS' => $_SERVER['REMOTE_ADDR'],
					'CREDITCARDTYPE' => $_POST['creditcardtype'], 
					'ACCT' => $_POST['acct'], 						
					'EXPDATE' => $expdate, 			
					'CVV2' => $_POST['cvv2'], 
					'FIRSTNAME' =>$_POST['firstname'], 
					'LASTNAME' => $_POST['lastname'], 
					'STREET' => $_POST['street'], 
					'CITY' => $_POST['city'], 
					'STATE' => $_POST['state'], 					
					'COUNTRYCODE' => $countrycode, 
					'ZIP' => $_POST['zip'], 
					'AMT' => '1.00', 
					'CURRENCYCODE' => 'USD', 
					'DESC' => 'Direct Payment' 
					);
					
// Loop through $request_params array to generate the NVP string.
$nvp_string = '';
foreach($request_params as $var=>$val)
{
	$nvp_string .= '&'.$var.'='.urlencode($val);	
}

// Send NVP string to PayPal and store response
$curl = curl_init();
		curl_setopt($curl, CURLOPT_VERBOSE, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_URL, $api_endpoint);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $nvp_string);

$result = curl_exec($curl);
curl_close($curl);

// Parse the API response
$result_array = NVPToArray($result);

echo '<pre />';
if($result_array["ACK"]=="Failure")
{
	//print_r($result_array);
	echo 'Transaction Failed :-  <br/><br/>Error code :- '.$result_array["L_ERRORCODE0"].'<br/>Error message :-  '.$result_array["L_SHORTMESSAGE0"].'<br/>Error Description :-  '.$result_array["L_LONGMESSAGE0"];
	if($result_array["L_ERRORCODE0"]!="")
		echo '<br/><br/>Error code :- '.$result_array["L_ERRORCODE1"].'<br/>Error message :-  '.$result_array["L_SHORTMESSAGE1"].'<br/>Error Description :-  '.$result_array["L_LONGMESSAGE1"];
}
else if($result_array["ACK"]=="Success")
	echo 'Transaction Succeeded';

echo "<br/><br/><br/>"."User entered details:-<br/><br/>";
$i=0;
foreach($request_params as $key => $val)
{
	if($i>6 && $i<18)
			echo $key." :- ".$val."<br/>";
	$i++;
}
// Function to convert NTP string to an array
function NVPToArray($NVPString)
{
	$proArray = array();
	while(strlen($NVPString))
	{
		// name
		$keypos= strpos($NVPString,'=');
		$keyval = substr($NVPString,0,$keypos);
		// value
		$valuepos = strpos($NVPString,'&') ? strpos($NVPString,'&'): strlen($NVPString);
		$valval = substr($NVPString,$keypos+1,$valuepos-$keypos-1);
		// decoding the respose
		$proArray[$keyval] = urldecode($valval);
		$NVPString = substr($NVPString,$valuepos+1,strlen($NVPString));
	}
	return $proArray;
}