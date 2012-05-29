<?php
	$sid = $_POST['id'];
	
				$flair = $db->selectRow("select 
				sticker.id as sid , place.name as placename,			
				food.name as foodname,food.fid as fid ,food.type as type,
				user.name as username,user.photo_big as userphoto 
				from sticker 				
				inner join place on sticker.noun = place.pid
				inner join food on sticker.verb = food.fid  
				inner join user on sticker.user = user.id 
				where sticker.id={$sid} ");


	echo "<div style=\"text-align:center;\" >";
		echo "<div class=\"flair_thumb\" style=\"background-image:url(/images/icons/{$flair['type']}.png);\" ></div>";
		echo "<div class=\"flair_thumb\" style=\"width:182px;background-color:#fff;line-height:1.4em;\" >{$flair['foodname']}<br>@<br>{$flair['placename']}</div>";
		echo "<div style='color:#fff;padding:10px;text-align:left;padding-left:117px;' >The cake here is the best.. try it with raspberry juice.</div>";
	echo "</div>";

?>