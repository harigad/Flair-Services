<?php
class devices{
    
    public $phones;

    function __construct(){
            $this->init();
    }

    function isMobile(){        
        $browser=new Browser();
        if( $browser->isMobile()){
           // echo "--1--";
        }else{
            //echo "-----2-------->" . $browser->isMobile();
        }
        return $browser->isMobile();
    }

    function isLive($acitvated_devices){
        $browser=new Browser();
        foreach ($acitvated_devices as $device => $active){
            if($browser->isBrowser($device)){
                $date=new dateObj($active);
                if($date->isFuture()){
                        return true;break;
                }
            }
        }
        return false;
    }


 
    function get($phone){
        return $this->phones[$phone];
    }



    function init(){
        $apple["title"]="Apple";
        $apple["phones"]["iphone"]["title"]="iPhone";
        $apple["phones"]["iphone"]["make"]="Apple";
        $apple["phones"]["iphone"]["carrier"]="AT&T";
        $apple["phones"]["iphone"]["image"]="iPhone_large.jpg";

        $apple["phones"]["ipod"]["title"]="iPod Touch";
        $apple["phones"]["ipod"]["make"]="Apple";
        $apple["phones"]["ipod"]["carrier"]="AT&T";
        $apple["phones"]["ipod"]["image"]="iPod.jpg";

        $android["title"]="Android";
        $android["phones"]["nexusone"]["title"]="Nexus One";
        $android["phones"]["nexusone"]["make"]="Google";
        $android["phones"]["nexusone"]["carrier"]="T-Mobile";
        $android["phones"]["nexusone"]["image"]="nexusone.jpg";

        $android["phones"]["evo"]["title"]="HTC EVO";
        $android["phones"]["evo"]["make"]="HTC";
        $android["phones"]["evo"]["carrier"]="Sprint";
        $android["phones"]["evo"]["image"]="evo_htc.jpg";

        $android["phones"]["droid"]["title"]="Droid";
        $android["phones"]["droid"]["make"]="Motorola";
        $android["phones"]["droid"]["carrier"]="Verizon";
        $android["phones"]["droid"]["image"]="droid.jpg";

        $android["phones"]["opus"]["title"]="Opus One";
        $android["phones"]["opus"]["make"]="Motorola";
        $android["phones"]["opus"]["carrier"]="T-Mobile";
        $android["phones"]["opus"]["image"]="opusone_moto.jpg";

        $blackberry["title"]="Black Berry";
        $blackberry["phones"]["torch"]["title"]="Black Berry Torch";
        $blackberry["phones"]["torch"]["make"]="BlackBerry";
        $blackberry["phones"]["torch"]["carrier"]="AT&T";
        $blackberry["phones"]["torch"]["image"]="bb_torch.jpg";

        $blackberry["phones"]["storm"]["title"]="Black Berry Storm";
        $blackberry["phones"]["storm"]["make"]="BlackBerry";
        $blackberry["phones"]["storm"]["carrier"]="AT&T";
        $blackberry["phones"]["storm"]["image"]="bb_storm.jpg";

        $palm["title"]="Palm";
        $palm["phones"]["pre"]["title"]="Palm Pre";
        $palm["phones"]["pre"]["make"]="Palm";
        $palm["phones"]["pre"]["carrier"]="Sprint";
        $palm["phones"]["pre"]["image"]="bb_torch.jpg";

        $windows["title"]="Windows";
        $windows["phones"]["focus"]["title"]="Samsung Focus";
        $windows["phones"]["focus"]["make"]="Samsung";
        $windows["phones"]["focus"]["carrier"]="AT&T";
        $windows["phones"]["focus"]["image"]="focus.jpg";

        $windows["phones"]["quantum"]["title"]="LG Quantum";
        $windows["phones"]["quantum"]["make"]="LG";
        $windows["phones"]["quantum"]["carrier"]="AT&T";
        $windows["phones"]["quantum"]["image"]="quantum.jpg";

        $windows["phones"]["surround"]["title"]="HTC Surround";
        $windows["phones"]["surround"]["make"]="HTC";
        $windows["phones"]["surround"]["carrier"]="AT&T";
        $windows["phones"]["surround"]["image"]="surround.jpg";

        $windows["phones"]["venue"]["title"]="Dell Venue Pro";
        $windows["phones"]["venue"]["make"]="Dell";
        $windows["phones"]["venue"]["carrier"]="T - Mobile";
        $windows["phones"]["venue"]["image"]="venue.jpg";





        $this->phones["iphone"]=$apple;
        $this->phones["android"]=$android;
        $this->phones["blackberry"]=$blackberry;
        $this->phones["palm"]=$palm;
        $this->phones["windows"]=$windows;
    
    }


    function update_stats($redirected=false){
            global $app;
            global $db;
            $browser=new Browser();

            $arr['appid']=$app->id;
            $arr['device']=$browser->getBrowser();
            $arr['raw']=$_SERVER['HTTP_USER_AGENT'];
            $arr['sessionid']=session_id();
            $arr['remote_addr']=$_SERVER["REMOTE_ADDR"];
            $arr['remote_host']=$_SERVER["REMOTE_HOST"];
            $arr['redirected']=$redirected;            
            $db->insert("quick_mobile_stats",$arr);
        }

  }

?>
