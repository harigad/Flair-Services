<?php
class dateObj {

    function __construct($val=null){
        if($val==null){
            $this->time=time();
        }else{
            $this->time=$this->strtotime($val);
        }
    }

    function strtotime($val){
        return strtotime($val);
    }


    function printDay(){
        return "<div class='day' ><div class='dayMonth' >" . date("M",$this->time) . "</div><div class='dayDay' >" . $this->getDay() . "</div>     </div>" ;
    }


    function getDay(){
        $date=date("d",$this->time);
        return $date;
    }

    function getMonth(){
        $date=date("m",$this->time);
        return $date;
    }

    function getYear(){
        $date=date("Y",$this->time);
        return $date;
    }

    function mysqlDate(){
        return date( 'Y-m-d H:i:s', $this->time );
    }

    function addDays($days){
        $this->time=$this->time + ($days * 24 * 60 * 60);
    }


    function getDate() {
        return date("d-m-Y",$this->time);
    }

    function getDateTime(){
        return date("l M jS, g:i a",$this->time);
    }


    function isFuture() {
        $now = time();
        $dateDiff    = $this->time - $now;
        if($dateDiff>0){
            return true;
        }else{
            return false;
        }
    }

    function numOfDays(){
        $now = time();
        $dateDiff = $this->time - $now;
        return ($dateDiff/(60*60*24));
    }

    function numOfMinutesNonGMT(){
        $now = time();
        $dateDiff    = $this->time - $now;
        return ($dateDiff/(60));
    }

    function numOfMinutes(){
        $tempDateStr=$this->mysqlDate();
        $tempDateStr=$tempDateStr . " UTC";
        $matchTime=strtotime($tempDateStr);
        $GMT=gmmktime();
        $dateDiff= $matchTime - $GMT;
        return ($dateDiff)/(60);
    }




}

?>