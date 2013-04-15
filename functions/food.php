 <?php
 $fid = $_POST['id'];
 $food = $db->selectRow("select parent,name,type from food where fid='{$fid}'");
 
		$parent=$food['parent'];
 		$flairs=$db->selectRow("select * from sticker where verb='{$fid}'");
?>
 <div style="text-align:center;" >
        <?php
        $stickers= $db->selectRows("select 
				sticker.id as sid , sticker.user as user,
				place.name as name ,place.pid as pid ,
				user.name as username,user.photo as userphoto 
				from sticker 
				inner join place on sticker.noun = place.pid  
				inner join user on sticker.user = user.id 
				where verb={$fid} and sticker.status=1 order by sticker.created desc limit 20");
        while ($sticker = mysql_fetch_object($stickers)) {  
            sticker_place($sticker);
        } ?>   
</div>
<?php
 function sticker_place($sticker) {  
 ?>
                               
							   
							   
							   
<?php } ?>