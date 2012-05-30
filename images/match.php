<?php
include_once 'core/dateClass.php';
include_once 'core/db.php';
include_once 'core/Browser.php';
include_once 'core/devices.php';
include_once 'core/user.php';
include_once 'core/player.php';
include_once 'core/team.php';
include_once 'core/match.php';



session_start();
$db = new db();

$user = new user($_SESSION['user']);
$devices = new devices();
$js = "";
?>
<html>
    <head>
        <link rel="stylesheet" href="cric.css" type="text/css" />
        <script src="/templates/js/jquery-1.3.2.js" type="text/javascript"></script>
        <script>
            var players=new Array();
            var points=new Array();
            var availablePoints=10000;
            var totalPoints=10000;

            function thumbRollOver(obj){              
                if(players[obj.id]!=true){
                    $("#" + obj.id + " .player_name").addClass("player_name_selected");
                    $("#" + obj.id + " .thumb_points").show();
                }
            }

            function thumbRollOut(obj){
                $("#" + obj.id + " .thumb_points").hide();
                if(players[obj.id]!=true){
                    $("#" + obj.id + " .player_name").removeClass("player_name_selected");
                }
            }

            function updateThumb(obj){
                if(players[obj.id]==true){
                    availablePoints=availablePoints+points[obj.id];
                    $("#" + obj.id + " .thumb").removeClass("selected");
                    $("#" + obj.id + " .player_name").removeClass("player_name_selected");
                    players[obj.id]=false;
                    thumbRollOver(obj);
                }else{
                    if(availablePoints>=points[obj.id]){
                    thumbRollOut(obj);    
                    availablePoints=availablePoints-points[obj.id];
                    $("#" + obj.id + " .thumb").addClass("selected");
                    $("#" + obj.id + " .player_name").addClass("player_name_selected");
                    players[obj.id]=true;                   
                    }
                }
                
                percent=((availablePoints/totalPoints)*100);
                percent=percent + "%";
                $('#money').animate({width:percent});
                
            }
        </script>
    </head>
    <body style="padding:0px;margin:0px;">


        <div id="fb-root"></div>
        <script>
            window.fbAsyncInit = function() {
                //  FB.init({appId: 'your app id', status: true, cookie: true,
                  //  xfbml: true});
                   FB.Canvas.setAutoResize();

            };
            (function() {
                var e = document.createElement('script'); e.async = true;
                e.src = document.location.protocol +
                    '//connect.facebook.net/en_US/all.js';
                document.getElementById('fb-root').appendChild(e);
            }());
        </script>












        <table  cellpadding="" cellspacing="0" >
            <tr>
                <td style="width:168px;background-color:#fff;border:0px solid #ccc;border-right-width:1px;">
                    <div style="padding:10px;" >
                        aaa

                    </div>
                </td>

                <td style="background-color:#fff;" >
                    <div style="padding:10px;" class='list' >

                        <?php
                        $match = new match($_GET['id']);
                        $match->load();
                        echo "<div>" . $match->printName() . "</div>";
                        ?>


                        <div class="barContainer" >

                            <div class="money" id="money" ></div>

                        </div>
                        <div>
                            <span style="float:left;" >$0.00</span>
                            <span style="float:right;" >$1 Million</span>
                        </div>


                        <div class="score" ></div>
                        <table style="width:100%;" ><tr>
                                <?php
                                $userRow = $db->selectRow("select id,data from user_matches where user='" . $user->get('id') . "' and matchid='" . $match->get('id') . "'");
                                if (is_array($userRow) != true) {
                                    $match->loadAllPlayers();

                                    while ($player = mysql_fetch_array($match->allPlayers)) {
                                        $temp['selected'] = false;
                                        $temp['data'] = $player;
                                        $user_match_data[$player['id']] = $temp;
                                    }

                                    $d = new dateObj();
                                    $thisData['user'] = $user->get('id');
                                    $thisData['matchid'] = $match->get('id');
                                    $thisData['created'] = $d->mysqlDate();
                                    $thisData['data'] = serialize($user_match_data);
                                    $user_match_id = $db->insert("user_matches", $thisData);
                                    $user_match_data = $user_match_data;
                                } else {
                                    $user_match_data = unserialize($userRow['data']);
                                    $user_match_id = $userRow['id'];
                                }

                                $type = 0;
                                $temp = 1;


                                foreach ($user_match_data as $player_id => $player_a_obj) {
                                    $player_a = $player_a_obj['data'];

                                    $js = $js . "points['player_$player_id']=" . $player_a['points'] . ";";
                                    if ($player_a_obj['selected'] == true) {
                                        $js = $js . "players['player_$player_id']=true;";
                                    } else {
                                        $js = $js . "players['player_$player_id']=false;";
                                    }


                                    if ($temp > 5 && $player_a['type'] == $type) {
                                        $temp = 1;
                                        echo "</tr><tr>";
                                    } else if ($player_a['type'] != $type) {
                                        switch ($player_a['type']) {
                                            case 1:
                                                $title = "Batsmen";
                                                break;
                                            case 2:
                                                $title = "Bowlers";
                                                break;
                                            case 3:
                                                $title = "All Rounders";
                                                break;
                                            case 4:
                                                $title = "Wicket Keepers";
                                                break;
                                        }
                                        echo "</tr><tr><td colspan='5'><div class='match_title' >$title</div></td></tr><tr>";
                                        $temp = 1;
                                        $type = $player_a['type'];
                                    }

                                    $player = new player(0, $player_a);
                                    echo "<td>";
                                    $player->thumb();
                                    echo "</td>";
                                    $temp++;
                                }
                                ?>
                            </tr>
                        </table>
                    </div>
                </td>

            </tr>



        </table>
        <script>
<?php echo $js; ?>
        </script>
    </body>
</html>