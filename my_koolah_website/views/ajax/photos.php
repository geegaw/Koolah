<?php
	$photos = apiToolKit::getPages('slide'); 
	
	header('Content-Type: application/json');
	echo json_encode($photos->prepare());
