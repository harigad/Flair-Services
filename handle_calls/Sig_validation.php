<?php
        // This function calculates the HMAC hash of the data with the key passed in
        // Note: hash_hmac requires PHP 5 >= 5.1.2 or PECL hash:1.1-1.5
        // Or http://pear.php.net/package/Crypt_HMAC/
        function calculate_twilio_signature($key, $data) {
            $sig = base64_encode(hash_hmac("sha1", $data, $key, true));
            return $sig;
        }
 
 
        // this function assembles the data to sign from the $_SERVER and $_POST superglobals
        function build_twilio_data_string() {
 
            // Our data string starts with the full URL
 
            // Note, that if your URL uses an implied "index" document (index.php), then apache
            // often adds a slash to the SCRIPT_URI while Twilio's original request will not have a slash
            // Example: if Twilio requested http://mycompany.com/twilio
            //   and that url is handled by an index.php script
            //   Apache/PHP will report the URI as being: http://mycompany.com/twilio/ 
            //   But the hash should be calculated without the trailing slash
 
            // Also note, if you're using URL rewriting, then you should check to see that
            // PHP is reporting your SCRIPT_URI and QUERY_STRING correctly.  
 
           // $string_to_sign = $_SERVER['SCRIPT_URI'];
          $string_to_sign = "https://flair.me/handle_calls/welcome.php";
 
            // if there's a query string, add it here along with the question mark
            if(strlen($_SERVER['QUERY_STRING']))
                $string_to_sign .= "?{$_SERVER['QUERY_STRING']}";
 
            // Now, if it's a POST, then we need to add the POST parameters
            // alphabetized to the data string
            if(isset($_POST)) {
 
                // copy the post data
                $data = $_POST;
 
                // sort the array by keys
                ksort($data);
 
                // append them to the data string in order with no delimiters
                foreach($data AS $key=>$value)
                    $string_to_sign .= "$key$value";
 
            }
 
            return $string_to_sign;
 
        }
 
        // Use your Twilio AuthToken here.  Case matters.
        $MY_KEY = "fb8897381646eb3c81f27d9183d00497";
 
        // Get the signature sent by twilio in the HTTP Headers
        // PHP exposes HTTP headers in the $_SERVER superglobal array 
        //    in all upper case, with underscores instead of dashes, with the word "HTTP_" prefixed
        $expected_signature = $_SERVER["HTTP_X_TWILIO_SIGNATURE"];
 
        // Build the data string to sign
        $data_to_sign = build_twilio_data_string();
 
        // sign it
        $calculated_signature = calculate_twilio_signature($MY_KEY, $data_to_sign);
 
      
            ?>