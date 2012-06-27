<?php

$SEARCHSERVER = "http://www4.publicdata.com/pdquery.php?";
$ACCOUNT = "019124235";
$ACCOUNT_ID = "TX"; #or 'CORP' or whatever state
$PASSWORD = "25MCUN";
$SESSION_ID = "";
// make sure you are using the correct DPPA restriction
$DPPARESTRICTION = "DPPA-01";

function samplelogon() {

    global $ACCOUNT,$ACCOUNT_ID,$PASSWORD,$SESSION_ID,$SEARCHSERVER;

    $loginInfo = pdlogon($ACCOUNT,$ACCOUNT_ID,$PASSWORD);
    $SESSION_ID = (string) $loginInfo->user->id;
    $SEARCHSERVER  = (string) $loginInfo->servers->searchserver;
  //  print "sessionId: $SESSION_ID <br>";
  //  print "serverId:  $SEARCHSERVER<br><br>";
    
}

function pdlogon ($inAccount, $inAccountId, $inPassword) {
   $inAccount = strtoupper($inAccount);
   $inAccountId = strtoupper($inAccountId);
   $inPassword = strtoupper($inPassword);

   $request = "http://login.publicdata.com/pdmain.php/logon/checkAccess?disp=XML&login_id=$inAccount&state_id=$inAccountId&password=$inPassword";
   $detailstring = file_get_contents($request);

   $detailXML = simplexml_load_string($detailstring);

   return $detailXML;


}

function lookUpPlate($inPlate,$stateDMV) {
   global $SEARCHSERVER,$ACCOUNT,$ACCOUNT_ID,$SESSION_ID,$DPPARESTRICTION;


   $theXML = getSearchResults($SEARCHSERVER, "$inPlate", "PLATE", $stateDMV, $ACCOUNT, $ACCOUNT_ID, $SESSION_ID, $DPPARESTRICTION);
   $numberOfRecords = $theXML->results["numrecords"];
   $recordNumber = $theXML->results->record["rec"];
   $dbEdition = $theXML->results->record["ed"];


   if ($numberOfRecords < 1) {
       print "$inPlate,NOTFOUND<br>\n";
       return "NOTFOUND";
   } 

   if ($numberOfRecords > 1) {
       print "$inPlate,TOOMANYFOUND<br>\n";
       return "TOOMANYFOUND";
   } 


   $theXML = getDetail($SEARCHSERVER, "TXDMV", $recordNumber, $dbEdition, $ACCOUNT, $ACCOUNT_ID, $SESSION_ID,$DPPARESTRICTION);

   $i = 0;
   $allResults['info'] = "This is an array to hold all the results";
   
   while ("" != $theXML->dataset->dataitem->textdata->field[$i]['label']) {
       $theLabel = (string) ($theXML->dataset->dataitem->textdata->field[$i]['label']);
       $theValue = (string) ($theXML->dataset->dataitem->textdata->field[$i][0]);
       // Do what it is you would like to do here. for example:
       $allResults["$theLabel"] = $theValue;
       //print "$i $theLabel: $theValue <br>";
       $i++;
   }

   return $allResults;

}

function getDetail($server, $db, $rec, $ed, $dlnumber, $dlstate, $id, $tacInfo)
   {
       $request = "http://".$server."/pddetails.php?".
                                       "db=".$db."&".
                                       "rec=".$rec."&".
                                       "ed=".$ed."&".
                                       "tacDMV=".$tacInfo."&".
                                       "dlnumber=".$dlnumber."&".
                                       "dlstate=".$dlstate."&".
                                       "id=".$id."&".
                                       "disp=xml";


       $detailstring = file_get_contents($request);
       $detailXML = simplexml_load_string($detailstring);
       return $detailXML;
   }

   function getSearchResults($searchserver, $searchstring, $type, $legacyname, $dlnumber, $dlstate, $id, $tacInfo)
   {
       $request = "http://".$searchserver."/pdsearch.php?".
                           "p1=".$searchstring."&".
                           "input=".$legacyname."&".
                           "tacDMV=".$tacInfo."&".
                           "type=".$type."&".
                           "dlnumber=".$dlnumber."&".
                           "dlstate=".$dlstate."&".
                           "id=".$id."&".
                           "disp=xml";



       $searchstring = file_get_contents($request);

       $searchXML = simplexml_load_string($searchstring);
       return $searchXML;
   }


?>
