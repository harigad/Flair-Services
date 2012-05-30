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
        <script src="include.js" type="text/javascript"></script>
        <script>
            var matchid;
            var matches;

            function validatePlayer(player){
                l=0;
                for(p in user_match_data){l++;}
                if(l<11){
                    return true;
                }else{
                    return false;
                }
            }

            function allRulesSet(){
                err="";
                l=0;
                for(p in user_match_data){l++;}
                if(l<11){
                    err=err + "Your team must have 11 Players";
                                 $('#err').html(err);
                        $('#err').slideDown();
           
                    return false;
                }else {
                        bat=0;wic=0;bal=0;al=0;
                        for(p in user_match_data){
                            if(players[p].type==1){
                             bat++;
                            }else if (players[p].type==2){
                             bal++;
                            }else if (players[p].type==3){
                             al++;
                            }else if (players[p].type==4){
                             wic++;
                            }
                        }
                          
                        if(bat<3 || bal<3 || wic<1 || al<1){
                            err="You need atleast 3 batsmen, 3 bowlers, 1 wicket keeper and 1 all rounder.";

                        $('#err').html(err);
                        $('#err').slideDown();
                        return false;
                        }else{
                        return true;
                        }
                }


            }

            function save(){
                if(user==FB.getSession().uid){

                    if(allRulesSet()==true){


                        sending_data="";
                        sending_data=sending_data + "matchid=" + matchid + "&user=" + user;
                        for(p in user_match_data){
                            if(user_match_data[p]==true){
                                sending_data=sending_data + "&" + p + "=" + user_match_data[p];
                            }

                        }

                        $.ajax({
                            url: "save.php",
                            type: "POST",
                            dataType: 'html',
                            data: sending_data,
                            success: function(data){
                                $('#save').css('opacity', "0.2");
                            }
                        });

                    }
                }
            }


            function thumbRollOver(obj){
                if(user_match_data[obj.id]!=true){
                    $("#" + obj.id + " .thumb").addClass("rollOvers");
                    $("#" + obj.id + " .player_name").addClass("player_name_selected");
                    $("#" + obj.id + " .thumb_points").show();
                }
            }

            function thumbRollOut(obj){
                if(user_match_data[obj.id]!=true){
                    $("#" + obj.id + " .thumb_points").hide();
                    $("#" + obj.id + " .thumb").removeClass("rollOvers");
                    $("#" + obj.id + " .player_name").removeClass("player_name_selected");
                }
            }

            function showEditOptions(){
                if(currentMainPage!="all"){
                    $('#profile_content').html('');
                    i=0;
                    for(player in user_match_data){
                        i++;
                        $('#profile_content').append("<div class='match_title' >" + i + ". " + players[player].name + "</div>");
                    }
                    if(i<11){
                        while(i<11){
                            i++;
                            $('#profile_content').append("<div class='match_title' >" + i + ". "  + "&nbsp;</div>");
                        }
                    }
                    $('#profile_content').slideDown();
                }
            }


            function updateThumb(obj){
                alert( players[obj.id].editable);
                 $('#err').slideUp();
                if(user== FB.getSession().uid){
                    players[obj.id].points=parseInt(players[obj.id].points);

                    if(user_match_data[obj.id]==true){

                        availablePoints=availablePoints + players[obj.id].points;
                        // spentPoints=spentPoints - players[obj.id].points;
                        $("#" + obj.id + " .thumb").removeClass("selected");
                        $("#" + obj.id + " .player_name").removeClass("player_name_selected");
                        delete(user_match_data[obj.id]);
                        thumbRollOver(obj);
                        showEditOptions();
                        $('#save').css('opacity', "1.0");
                    }else{
                        if(availablePoints>=players[obj.id].points && validatePlayer(players[obj.id])){
                            thumbRollOut(obj);
                            availablePoints=availablePoints-players[obj.id].points;
                            //  spentPoints=spentPoints + players[obj.id].points;
                            $("#" + obj.id + " .thumb").addClass("selected");
                            $("#" + obj.id + " .player_name").addClass("player_name_selected");
                            $("#" + obj.id + " .thumb_points").show();
                            user_match_data[obj.id]=true;
                            showEditOptions();
                            $('#save').css('opacity', "1.0");
                        }else{
                            if(availablePoints>=players[obj.id].points){
                             $('#err').html('You do not have enough $$$!');
                            $('#err').slideDown();
                            }
                        }
                    }

                    updateBar();
                    updateCurrentCount();
                }
            }


            function updateBar(){
                percent=(availablePoints/totalPoints)*100;
                percent=percent + "%";
                $('#money_show').html("$" + availablePoints);
                $('#money').stop().animate({width:percent});
            }


            var currentPlayerType=1;
            function loadType(type,obj){
                $("#sub_" + currentPlayerType).removeClass('submenu_selected');
                $("#" + obj.id).addClass('submenu_selected');
                print(type);
            }

            function print(type){
                currentPlayerType=type;
                str="<table cellpadding=0 cellspacing=0 border=0 style='margin-left:20px;' ><tr>";
                temp=0;
                for (player in players){
                    if(players[player].type==type){
                        str=str + "<td style='vertical-align:top;' >";

                        thumbSelected="";
                        nameSelected="";
                        thumbShow="none";
                        if(user_match_data[player]==true){
                            thumbShow="block";
                            thumbSelected=" selected";
                            nameSelected=" player_name_selected";
                        }

                        str=str + "<div class='player' id='player_" + players[player].id + "' onclick='updateThumb(this);' onmouseover='thumbRollOver(this);' onmouseout='thumbRollOut(this);'  >";
                        str=str + "<div class='thumb_bg' ><div class='thumb" + thumbSelected + "' style='background-image:url(images/players/" + players[player].icon + ".jpeg);' ></div>";
                        str=str + "</div>";
                        str=str + "<div class='player_name" + nameSelected + "' >" + players[player].name + "</div>";
                        str=str + "<div class='thumb_points' style='display:" + thumbShow + ";' ><img src='images/flags/" +  players[player].team  + ".png' style='padding-right:5px;' >$" + players[player].points + "</div>";
                        str=str + "</div>";



                        str=str + "</td>";

                        temp=temp+1;
                        if(temp==4){
                            str=str + "</tr></tr>";
                            temp=0;
                        }
                    }

                }
                str=str + "</tr></table>";
                $('#thumbs').html(str);
            }

        </script>
    </head>
    <body style="padding:0px;margin:0px;" onload="init();">
        <div id="fb-root"></div>


        <table  cellpadding="0" cellspacing="0" width=100% >
            <tr>
                <td id="left" >
                    <div id="profile" ></div>
                    <div id="profile_content" ></div>
                    <div id="friends_list" ></div>
                </td>
                <td id="right" >
                    <div id="all_matches_page"></div>
                    <div id="details_page" >
                        <div style='padding-left:10px;' id='match_title' ></div>
                        <table width=100% cellspacing=0 cellpadding=0 >
                            <tr>
                        <td style='vertical-align:middle;' >
                            <div class="barContainer" style='margin-right:10px;' >
                                 <div class="money" id="money" ></div>
                            </div>
                        </td>
                         <td id='money_show' style='padding:0px;margin:0px;width:85px;vertical-align:bottom;' class='match_title' >

                            </td>
                        </tr></table>
                       
                        <div style='padding-left:30px;' id="submenu" >
                            <table cellpadding=0 cellspacing=0 border=0  >
                                <tr>
                                    <td><div id="sub_1" style='cursor:pointer;' class='match_title submenu submenu_selected' onclick="loadType(1,this);"   >Batsmen</div></td>
                                    <td><div id="sub_2"  style='cursor:pointer;' class='match_title submenu' onclick="loadType(2,this);" >Bowlers</div></td>
                                    <td><div id="sub_3"  style='cursor:pointer;' class='match_title submenu' onclick="loadType(3,this);" >All Rounders</div></td>
                                    <td><div id="sub_4"  style='cursor:pointer;' class='match_title submenu' onclick="loadType(4,this);" >Wicket Keepers</div></td>
                                    <td><div id="save"  style='cursor:pointer;color:#336699;opacity:0.2;font-weight:bold;' class='match_title submenu' onclick="save();" >Save Changes</div></td>
                                </tr>
                            </table>
                        </div>
                         <div id='err' ></div>

                        <div class="score" ></div>
                        <div id="thumbs" ></div>
                    </div>
                </td>
            </tr>
        </table>
        <script>
            var players;
            var user_match_data=new Array();
            var availablePoints=1000000;
            var spentPoints=0;
            var totalPoints=1000000;

            function load(dataStr){
                $.ajax({
                    url: "load.php",
                    type: "GET",
                    dataType: 'html',
                    data: dataStr,
                    success: function(data){
                        eval(data);
                        print(currentPlayerType);
                        updateBar();

                        if(user==FB.getSession().uid){
                            showEditOptions();
                        }else{
                            $('#profile_content').slideUp();
                        }

                    }
                });
            }



            function loadUser(id){
                user=id;
                if(currentMainPage=="all"){
                    loadUserAllMatchesData(matchid);
                }else{
                    load("func=loadUser&matchid=" + matchid + "&user=" + user);
                }
            }

            function loadMatch(id){
                matchid=id;
                load("matchid=" + matchid + "&user=" + user);
            }

            function loadPage(){
                user=FB.getSession().uid;
                //load("user=" + user + "&matchid=" + matchid);
                loadAllMatches();
            }

            function loadMatchScreen(matchid){
                currentMainPage="match";
                $('#all_matches_page').hide();
                $('#details_page').show();
                loadMatch(matchid);
            }
            function loadAllMatches(){
                $.ajax({
                    url: "loadallmatches.php",
                    type: "POST",
                    dataType: 'html',
                    success: function(data){
                        eval(data);
                        printAllMatches();
                    }
                });
            }

            var currentMainPage="";
            function printAllMatches(){
                currentMainPage="all";
                $('#profile_content').slideUp();
                $('#details_page').hide();
                $('#all_matches_page').show();
                for(m in allMatches){
                    match=allMatches[m];
                    str="";
                    str="<table cellpadding=0 cellspacing=0 border=0 onclick='loadMatchScreen(" + match.id + ");' style='cursor:pointer;' >";
                    str=str + "<tr>";
                    str=str + "<td style='text-align:left;' ><table><tr><td style='text-align:left;width:150px;' >";
                    str=str + "<div class='match_title' ><img src='images/flags/" + match.team_a + ".png' >" + match.team_a_name + "</div>";
                    str=str + "</td><td style='width:40px;text-align:center;' ><div class='match_title' >VS</div></td><td style='text-align:rights;width:150px;'>";
                    str=str + "<div class='match_title' ><img src='images/flags/" + match.team_b + ".png' >" + match.team_b_name + "</div>";
                    str=str + "</td></tr></table></td><td style='text-align:right;' >";
                    str=str + "</div></td></tr></table>";
                    str=str + "<div  class='barContainer' style='height:5px;' ><div class='money' id='" + m + "' ></div></div>";
                    $('#all_matches_page').append(str);

                }
                loadUserAllMatchesData();
            }


            var user_all_match_data;
            function loadUserAllMatchesData(){
                $.ajax({
                    url: "loadallusermatchdata.php",
                    type: "GET",
                    data: "user=" + user,
                    dataType: 'html',
                    success: function(data){
                        eval(data);
                        // alert(data);
                        //alert('ab' + user_all_match_data);
                        for(m in user_all_match_data){
                            percent=100-(parseInt(user_all_match_data[m])/totalPoints)*100;

                            $('#' + m).animate({width:percent + '%'});
                        }
                    }
                });





            }


            function loadCurrentUserPage(newUser){
                $('#fb_' + user).removeClass('friendSelected');
                $('#fb_' + user).addClass('friendUnSelected');


                $('#fb_' + newUser).removeClass('friendUnSelected');
                $('#fb_' + newUser).addClass('friendSelected');
                loadUser(newUser);

            }



        </script>


    </body>
</html>