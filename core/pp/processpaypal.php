<?php

function single_charge($live, $transaction_type, $fname, $lname, $cardtype, $cardnumber, $expmo, $expyr, $cvv2, $address1, $address2, $city, $state, $zip, $country, $amount)
{

$paymentType = urlencode($transaction_type); //Sale
$firstName = urlencode($fname);
$lastName = urlencode($lname);
$creditCardType = urlencode($cardtype);
$creditCardNumber = urlencode($cardnumber);
$expDateMonth = $expmo;
$expDateYear = urlencode($expyr);
$cvv2Number = urlencode($cvv2);
$address1 = urlencode($address1);
$address2 = urlencode($address2);
$city = urlencode($city);
$state = urlencode($state);
$zip = urlencode($zip);
$country = urlencode($country);
$amount = urlencode($amount);
$currencyID = 'USD';
$padDateMonth = str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);
$environment = $live; //sanxbox

function PPHttpPost($methodName_, $nvpStr_, $environment_) {
	global $environment_;

	// Set up your API credentials, PayPal end point, and API version.
        $pp_api_username = urlencode("techd82_api1.yahoo.com");
        $pp_api_password = urlencode("8T9KXNFZUX54WBBF");
        $pp_api_signature = urlencode("AuRA8YAFrqaa8el6GaeJD4DBTrntAHLbWMjYm-2vS545N94euoW86fm5");
	$API_UserName = $pp_api_username;
	$API_Password = $pp_api_password;
	$API_Signature = $pp_api_signature;
	$API_Endpoint = "https://api-3t.paypal.com/nvp";
	if("sandbox" === $environment_ || "beta-sandbox" === $environment_) {
		$API_Endpoint = "https://api-3t.$environment_.paypal.com/nvp";
	}

    exit($API_Endpoint);
	$version = urlencode('56.0');

	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the API operation, version, and API signature in the request.
	$nvpreq = "USER=$API_UserName&PWD=$API_Password&SIGNATURE=$API_Signature&METHOD=$methodName_&VERSION=$version$nvpStr_";
        echo $nvpreq;

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

	return $httpParsedResponseAr;
}

$nvpStr =	"&PAYMENTACTION=$paymentType&IPADDRESS={$_SERVER['REMOTE_ADDR']}&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber".
			"&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName".
			"&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID";

// Execute the API operation; see the PPHttpPost function above.
$httpParsedResponseAr = PPHttpPost('DoDirectPayment', $nvpStr, $environment);
$result = strtoupper($httpParsedResponseAr["ACK"]);
print_r($httpParsedResponseAr);
return $result;
}


function recurring_charge($live, $transaction_type, $bill_period, $bill_frequency, $start_date, $fname, $lname, $cardtype, $cardnumber, $expmo, $expyr, $cvv2, $address1, $address2, $city, $state, $zip, $country, $amount, $profileDesc)
{

$paymentType = urlencode($transaction_type);
$firstName = urlencode($fname);
$lastName = urlencode($lname);
$creditCardType = urlencode($cardtype);
$creditCardNumber = urlencode($cardnumber);
$expDateMonth = $expmo;
$expDateYear = urlencode($expyr);
$cvv2Number = urlencode($cvv2);
$address1 = urlencode($address1);
$address2 = urlencode($address2);
$city = urlencode($city);
$state = urlencode($state);
$zip = urlencode($zip);
$country = urlencode($country);
$amount = urlencode($amount);
$currencyID = 'USD';
$padDateMonth = str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);
$environment = $live;

$startDay="18";$startMonth="10";$startYear="2010";
$profileStartDateDay = $startDay;
// Day must be padded with leading zero
$padprofileStartDateDay = str_pad($profileStartDateDay, 2, '0', STR_PAD_LEFT);
$profileStartDateMonth = $startMonth;
// Month must be padded with leading zero
$padprofileStartDateMonth = str_pad($profileStartDateMonth, 2, '0', STR_PAD_LEFT);
$profileStartDateYear = $startYear;
$profileStartDate = urlencode($profileStartDateYear . '-' . $padprofileStartDateMonth . '-' . $padprofileStartDateDay . 'T00:00:00Z');
$startDate=$profileStartDate;
$profileDesc="test desc";


$billingPeriod = urlencode($bill_period);
$billingFreq = urlencode($bill_frequency);
$environment = $live; //sanxbox

function PPHttpPost($methodName_, $nvpStr_, $environment_) {
	global $environment_;

	// Set up your API credentials, PayPal end point, and API version.
        $pp_api_username = urlencode("techd82_api1.yahoo.com");
        $pp_api_password = urlencode("8T9KXNFZUX54WBBF");
        $pp_api_signature = urlencode("AuRA8YAFrqaa8el6GaeJD4DBTrntAHLbWMjYm-2vS545N94euoW86fm5");
	$API_UserName = $pp_api_username;
	$API_Password = $pp_api_password;
	$API_Signature = $pp_api_signature;
	$API_Endpoint = "https://api-3t.paypal.com/nvp";
	if("sandbox" === $environment_ || "beta-sandbox" === $environment_) {
		$API_Endpoint = "https://api-3t.$environment_.paypal.com/nvp";
	}
	$version = urlencode('56.0');

    	


	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

    echo $nvpreq;

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

	return $httpParsedResponseAr;
}

$nvpStr =	"&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber".
		"&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName".
                "&PROFILESTARTDATE=$startDate&BILLINGPERIOD=$billingPeriod&BILLINGFREQUENCY=$billingFreq".
                "&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID&DESC=$profileDesc";


// Execute the API operation; see the PPHttpPost function above.
$httpParsedResponseAr = PPHttpPost('CreateRecurringPaymentsProfile', $nvpStr, 'sandbox');
$result = strtoupper($httpParsedResponseAr["ACK"]);
print_r($httpParsedResponseAr);
return $result;
}



?>
