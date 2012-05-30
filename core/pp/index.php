<?php
require("processpaypal.php");

if(isset($_POST['transaction_type']))
{
switch($_POST['transaction_type'])
{
    case 'single_charge':
		$amount = $_POST['totl'] * $_POST['quantity'];
        $process_t = single_charge($_POST['mode'], 'Sale', $_POST['fname'], $_POST['lname'], $_POST['cardtype'], $_POST['cardnumber'], $_POST['expmonth'], $_POST['expyear'], $_POST['cvv2'], $_POST['address1'], $_POST['address2'], $_POST['city'], $_POST['state'], $_POST['zip'], $_POST['country'], $amount);
		echo $process_t;
		exit();
        break;
    case 'recurring_charge':
        $process_t = recurring_charge($_POST['mode'], 'Sale', $_POST['billperiod'], $_POST['frequency'], '12/18/2010', $_POST['fname'], $_POST['lname'], $_POST['cardtype'], $_POST['cardnumber'], $_POST['expmonth'], $_POST['expyear'], $_POST['cvv2'], $_POST['address1'], $_POST['address2'], $_POST['city'], $_POST['state'], $_POST['zip'], $_POST['country'], $_POST['totl'], $_POST['profile_desc']);
		echo $process_t;
		exit();
        break;
}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <p>
          <?php
        // put your code here
        //
        ?>
        </p>
        <form method="post" action="">
        <table width="300" border="0" cellspacing="1" cellpadding="4">
          <tr>
            <td bgcolor="#cccccc"><p>Single Charge</p>
            <p>Apple - $1.00
              <input name="totl" type="hidden" id="totl" value="1.00">
              <br>
            Quantity: 
              <label for="quantity"></label>
              <select name="quantity" id="quantity">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
              </select>
           <br>
            Mode: 
              <label for="mode"></label>
              <select name="mode" id="mode">
                <option value="sandbox">Sandbox</option>
                <option value="live" selected>Live</option>
              </select>
            </p>
            <p>
              <label for="fname">First Name</label>
              <input type="text" name="fname" id="fname">
            </p>
            <p>
              <label for="fname">Last Name</label>
              <input type="text" name="lname" id="lname">
</p>
            <p>
              <label for="fname">Address1</label>
              <input type="text" name="address1" id="address1">
</p>
            <p>
              <label for="fname">Address2</label>
              <input type="text" name="address2" id="address2">
</p>
            <p>
              <label for="fname">City</label>
              <input type="text" name="city" id="city">
</p>
            <p>
              <label for="fname">State</label>
              <input type="text" name="state" id="state">
</p>
            <p>
              <label for="fname">Zip</label>
              <input type="text" name="zip" id="zip">
</p>
            <p>
              <label for="fname">Country</label>
              <input name="country" type="text" id="country" value="US">
            </p>
            <p>
              <label for="fname">Card Number</label>
              <input name="cardnumber" type="text" id="cardnumber">
</p>
            <p>
              <label for="fname">Card Exp Month</label>
              <input name="expmonth" type="text" id="expmonth">
            </p>
            <p>
              <label for="fname">CVV2</label>
              <input name="cvv2" type="text" id="cvv2">
            </p>
            <p>
              <label for="fname">Card Exp Year</label>
              <input name="expyear" type="text" id="expyear">
            </p>
            <p>
              <label for="cardtype">Card Type</label>
              <select name="cardtype" id="cardtype">
                <option value="Visa">Visa</option>
                <option value="Mastercard">Mastercard</option>
                <option value="Discover">Discover</option>
                <option value="Amex">Amex</option>
              </select>
            </p>
            <p>
              <input type="submit" name="button" id="button" value="Submit">
            </p></td>
          </tr>
        </table>
        <input name="transaction_type" type="hidden" id="transaction_type" value="single_charge">
    </form>
    <br><br>
    <form method="post" action="">
      <table width="300" border="0" cellspacing="1" cellpadding="4">
          <tr>
            <td bgcolor="#cccccc"><p>Recurring Charge</p>
            <p>Iphone App - $1.00
              <input name="totl" type="hidden" id="totl" value="1.00">
              <br>
Billing Frequency:
<label for="frequency"></label>
<select name="frequency" id="frequency">
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3">3</option>
</select><br>
Billing Period:
              <label for="frequency"></label>
              <select name="billperiod" id="frequency">
                <option value="Day">Day</option>
                <option value="Week">Week</option>
                <option value="Month">Month</option>
                <option value="Year">Year</option>
              </select>
<br>
              Mode:
              <label for="mode"></label>
              <select name="mode" id="mode">
                <option value="sandbox">Sandbox</option>
                <option value="live" selected>Live</option>
              </select>
            </p>
            <p>
              <label for="fname">First Name</label>
              <input type="text" name="fname" id="fname">
            </p>
            <p>
              <label for="fname">Last Name</label>
              <input type="text" name="lname" id="lname">
            </p>
            <p>
              <label for="fname">Address1</label>
              <input type="text" name="address1" id="address1">
            </p>
            <p>
              <label for="fname">Address2</label>
              <input type="text" name="address2" id="address2">
            </p>
            <p>
              <label for="fname">City</label>
              <input type="text" name="city" id="city">
            </p>
            <p>
              <label for="fname">State</label>
              <input type="text" name="state" id="state">
            </p>
            <p>
              <label for="fname">Zip</label>
              <input type="text" name="zip" id="zip">
            </p>
            <p>
              <label for="fname">Country</label>
              <input name="country" type="text" id="country" value="US">
            </p>
            <p>
              <label for="fname">Card Number</label>
              <input name="cardnumber" type="text" id="cardnumber">
            </p>
            <p>
              <label for="fname">Card Exp Month</label>
              <input name="expmonth" type="text" id="expmonth">
            </p>
            <p>
              <label for="fname">CVV2</label>
              <input name="cvv2" type="text" id="cvv2">
            </p>
            <p>
              <label for="fname">Card Exp Year</label>
              <input name="expyear" type="text" id="expyear">
            </p>
            <p>
              <label for="cardtype">Card Type</label>
              <select name="cardtype" id="cardtype">
                <option value="Visa">Visa</option>
                <option value="Mastercard">Mastercard</option>
                <option value="Discover">Discover</option>
                <option value="Amex">Amex</option>
              </select>
            </p>
            <p>
              <input type="submit" name="button2" id="button2" value="Submit">
            </p></td>
          </tr>
      </table>
        <input name="transaction_type" type="hidden" id="transaction_type" value="recurring_charge">
    </form>
        <p>&nbsp;</p>
    </body>
</html>
