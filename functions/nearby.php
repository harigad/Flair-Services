<?php
$output->feed = buildStickers(null,null,null,true);
$output->friends = buildStickers(null,null,$user->loadFriends());
echo json_encode($output);
 
 ?>