<?php
class match{

    function __construct($id,$data=null){
        global $db;
        if($id>0){
            global $db;
            $row=$db->selectRow("select * from matches where id='$id'");
            if(is_array($row)){
                $this->init($row);
            }
        }else{
            $this->init($data);
        }
    }

  
    function loadAllPlayers(){
        global $db;
        $d=new dateObj($this->get('date'));
        $minutesLeft=$d->numOfMinutes();
    
        $sql="";
        $sql="select
player.id as id,
team,name,icon,type,points,
player,
IFNULL(runs,0) as runs,
IFNULL(runs,0) as sixes,
IFNULL(maidens,0) as maidens,
IFNULL(wickets,0) as wickets,
IFNULL(catches,0) as catches,
IFNULL(runouts,0) as runouts,
IFNULL(stumps,0) as stumps,
IFNULL(idrunouts,0) as idrunouts,
IFNULL(dismissed,0) as dismissed,
IFNULL(batting,0) as batting,
IFNULL(bowling,0) as bowling,
IFNULL(fielding,0) as fielding,
IFNULL(bonus,0) as bonus,
IFNULL(total,0) as total
from player
left outer join player_matches on
player.id=player_matches.player and player_matches.matchid='" . $this->get('id') . "'
where (team='" . $this->get('team_a') . "' or team='" . $this->get('team_b') . "') order by type,name";

//echo $sql;
        $allPlayers=$db->selectRows($sql);
            while($player=mysql_fetch_array($allPlayers)){
                if($minutesLeft>=30){
                    $player['editable']=true;
                }else{
                    $player['err']='Too Late! You may not change your team anymore for this match!';
                    $player['editable']=false;
                }
                $this->allPlayers['player_' . $player['id']]=$player;
            }
    }


    function load(){       
        $this->team_a=new team($this->get('team_a'));              
        $this->team_b=new team($this->get('team_b')); 
    }


    function loadBatsmen(){
        global $db;
        $this->batsmen=$db->selectRows("select * from player where type=1 and ( team='" . $this->get('team_a') . "' or team='" . $this->get('team_b') . "') order by name");
        
   }

    function loadBowlers(){
        global $db;
        $this->bowlers=$db->selectRows("select * from player where type=2 and (team='" . $this->get('team_a') . "' or team='" . $this->get('team_b') . "') order by name");
        return $this->bowlers;
    }

    function loadAllRounders(){
        global $db;
        $this->allRounders=$db->selectRows("select * from player where type=3 and (team='" . $this->get('team_a') . "' or team='" . $this->get('team_b') . "') order by name");
        return $this->allRounders;
    }
    
    function printName(){
       $d=new dateObj($this->get('date'));
       return  "<table cellpadding=0 cellspacing=0 border=0 width=100% ><tr><td style='vertical-align:middle;padding-left:10px;' >"   . $d->printDay() . "</td><td style='text-align:left;' ><table><tr><td style='text-align:left;' >" . $this->team_a->printName() . "</td>      <td style='width:40px;' ><div class='match_title' >VS</div></td>       <td>" . $this->team_b->printName() . "</td><td id='timer_display' >----</td></tr></table></td><td style='text-align:right;' ><div class='match_title' style='margin-right:20px;cursor:pointer;color:#3B5998;font-size:0.8em;font-weight:bold;' onclick='printAllMatches();'  >Show All</div></td></tr></table>";
    }
   
    function init($data){
        $this->data=$data;
    }

    function get($str){
        return $this->data[$str];
    }


    function set($str,$val){
        $this->data[$str]=$val;
    }


}
?>
