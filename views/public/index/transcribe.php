
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<title>DIY History</title>
<style>	
	html {		
		height: 100%;	
	}
	body {
		width: 100%;
		height: 100%;
		margin: 0px;		
	}
	.smooth_zoom_preloader {
		background-image: url(../../plugins/Scriptus/views/public/index/img/preloader.gif);
	}	
	.smooth_zoom_icons {
		background-image: url(../../plugins/Scriptus/views/public/index/img/icons.png);
	}
</style>


<?php 
	echo js_tag('jquery-1.9.1'); 
	echo js_tag('jquery.smoothZoom.min'); 
	echo js_tag('PumaSideBar.min');
?>

<link href="../../plugins/Scriptus/views/public/css/font-awesome.min.css" rel="stylesheet">
<link href="../../plugins/Scriptus/views/public/css/PumaSideBar.min.css" rel="stylesheet">


<script>
	jQuery(function($){
		$('#ImageID').smoothZoom({
			width: '100%',
			height: '100%',
			responsive: true
		});
	});
</script>

<script>
	$(document).ready(function(){

		$.PumaSideBar({
			position: "right",  // Position
			label: "DIYH Transcription", // Initial label
			closeoutside: false,
			movebody: true, // if you want to push or not your page
			avatar: "http://s-lib018.lib.uiowa.edu/omeka/themes/diyh/images/mbutler.png", // Initial avatar.
			items: [{
						fa: "fa-text", 
						text: "Text Box"    
					
					}]
		});


	});
</script>
 
</head>

