<?php

$user->loadFriends();

$friendsStr = $user->friendsStr;

$stickers = buildStickers(0,0,$friendsStr);

echo json_encode($stickers);

?>