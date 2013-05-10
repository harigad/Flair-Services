<?php

	$_lat = $_POST['lat'];
	$_lng = $_POST['lng'];

	$stickers = buildStickers(null,0,"",true);
	echo json_encode($stickers);

?>