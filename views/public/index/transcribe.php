
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
	echo js_tag('jquery.smoothZoom'); 	
	echo js_tag('modernizr.custom');
?>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
<link href="../../plugins/Scriptus/views/public/css/font-awesome.min.css" rel="stylesheet">
<link href="../../plugins/Scriptus/views/public/css/component.css" rel="stylesheet">

 
</head>

	<body class="cbp-spmenu-push">		

		<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="cbp-spmenu-s2">
			<h3>Transcription</h3>
			<?php echo $this->form; ?>	
			
		</nav>

		<img id="ImageID" src=""/>

		<!-- Classie - class helper functions by @desandro https://github.com/desandro/classie -->
		<script src="../../plugins/Scriptus/views/public/javascripts/classie.js"></script>
		<script>
			var 
				menuRight = document.getElementById( 'cbp-spmenu-s2' ),							
				body = document.body;

			window.onload = function() {				
				classie.toggle( body, 'cbp-spmenu-push-toleft' );
				classie.toggle( menuRight, 'cbp-spmenu-open' );			
			};
		</script>

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
		$('form').submit(function(event) {

				// get the form data				
				var formData = {
					'transcription'	: $('#transcribebox').val()
				};

				// process the form
				$.ajax({
					type 		: 'POST', // define the type of HTTP verb we want to use (POST for our form)
					url 		: '<?php echo Zend_Controller_Front::getInstance()->getRequest()->getRequestUri(); ?>/save', // the url where we want to POST
					data 		: formData, // our data object
					dataType 	: 'json', // what type of data do we expect back from the server
		            encode          : true
				})
					// using the done promise callback
					.done(function(data) {

						// log data to the console so we can see
						//console.log(data); 

						// here we will handle errors and validation messages
					});

				// stop the form from submitting the normal way and refreshing the page
				event.preventDefault();
			});
		</script>		

	</body>
</html>

