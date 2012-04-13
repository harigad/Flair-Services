<?php

	$noun=$_POST['noun'];
	$nounName=$_POST['nounName'];
	$verb=$_POST['verb'];
	$verbName=$_POST['verbName'];

	
		$sql = "select sticker.id,food.name as foodname,food.type,place.name as placename from sticker ";
        $sql.=" inner join place on sticker.noun=place.pid ";
        $sql.=" inner join food on sticker.verb=food.fid ";
        $sql.=" where (sticker.noun='{$noun}' or sticker.verb='{$verb}') and sticker.status=1 ";
	

    $str="";
    $str="<div class='nominate_this_verb' >{$verbName}</div><div class='nominate_this_at' >@</div><div class='nominate_this_noun' >" + nounName + "</div>";
    $str.="<div onclick='nominate_this_confirm(\"" + noun + "\",\"" + nounName + "\",\"" + verb + "\",\"" + verbName + "\");' class='nominate_this_nominate_btn' >Flair</div>";
    $str.="<div onclick='nominate_this_cancel();' class='nominate_this_cancel_btn' >Cancel</div>";
	
	?>