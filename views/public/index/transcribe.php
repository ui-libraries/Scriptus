<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

		<title>DIY History | Transcribe | <?php echo $this->collection_title; ?> | <?php echo $this->item_title; ?> | <?php echo $this->file_title; ?></title>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
		<script src='https://cdn.rawgit.com/anvaka/panzoom/v6.1.3/dist/panzoom.min.js'></script>
		
		<?php
			//echo js_tag('jquery.panzoom.min'); 	
			echo js_tag('modernizr.custom');
		?>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <!-- Our Custom CSS -->
    <link href="../../plugins/Scriptus/views/public/css/transcribe-style.css" rel="stylesheet"> 

</head>

<body>
  <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">					
					<div class="sidebar-header">
							<a href="<?php echo WEB_ROOT; ?>"><img src="../../plugins/Scriptus/views/public/index/img/diyh-logo-white.svg"></a>
					</div>
							<nav class="navbar menu menu-vertical menu-left" id="menu-s2">
								<ul class="nav nav-tabs" role="tablist">
									<li id="transcribeTab" class="toggleTab active"><a href="#transcribe" role="tab" data-toggle="tab">Transcribe</a></li>
									<li id="translateTab" class="toggleTab"><a href="#translate" role="tab" data-toggle="tab">Translate</a></li>
								</ul>

								<div class="tab-content">

								<div class="tab-pane active" id="transcribe">
							
									<p class="titles"><?php echo $this->item_link; ?></p>
									<p class="titles"><?php echo $this->file_title; ?></p>									

									<div class="dropdown dropdown-titles">
											<a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">More information</a>
											<ul class="dropdown-menu" role="menu">
												<li><a href="<?php echo $this->idl_link; ?>">digital collection</a></li>
												<li><a href="<?php echo $this->collguide_link; ?>">archival collection guide</a></li>
												<li><a href="<?php echo WEB_ROOT; ?>/tips-for-transcribing">transcription tips</a></li>
											</ul>
									</div>
									<!-- <span id="transcribe-label">Enter transcription:</span> --> 			
									<?php echo $this->form; ?>
									<div id="flash-message">Saving...</div>

									<div id="prev-next">
										<?php if (isset($this->paginationUrls['prev'])): ?>
											<img class="triangle" src="../../plugins/Scriptus/views/public/index/img/triangle-left.svg"><a><button type="submit" class="btn btn-xs" onClick="parent.location='<?php echo html_escape($this->paginationUrls['prev']); ?>'">prev</button></a>
										<?php else: ?>
											<img class="triangle" src="../../plugins/Scriptus/views/public/index/img/triangle-left.svg"><button type="submit" class="btn btn-xs">prev</button>
										<?php endif; ?>

										<?php if (isset($this->paginationUrls['next'])): ?>
											<a><button type="submit" class="btn btn-xs" onClick="parent.location='<?php echo html_escape($this->paginationUrls['next']); ?>'">next</button></a><img class="triangle" src="../../plugins/Scriptus/views/public/index/img/triangle-right.svg">
										<?php else: ?>
											<button type="submit" class="btn btn-xs">next</button><img class="triangle" src="../../plugins/Scriptus/views/public/index/img/triangle-right.svg">
										<?php endif; ?>
									</div>
								</div>

								<div data-toggle="tab" class="tab-pane" id="translate">
									<?php
										echo '<div id="translate-text">';
										echo $this->transcription;
										echo '</div>';
										echo '<div id="translate-textarea">';
										echo $this->translateform;
										echo '</div>';						
									?>					
										</div>
									</div>
								</nav>
								</nav>

        <!-- Page Content  -->
        <div id="content">
					<nav class="navbar navbar-expand-lg navbar-light bg-light">
							<div class="container-fluid">
									<span class="collection-title"><?php echo $this->collection_link; ?></span>
									<div class="collapse navbar-collapse" id="navbarSupportedContent"></div>
									<!--
									<button type="button" id="zoomIn" class="btn btn-sm btn-info zoom-in">
											<i class="fas fa-align-left"></i>
											<span>zoom in</span>
									</button>
-->
									<button type="button" id="sidebarCollapse" class="btn btn-sm btn-info zoom-in">
											<i class="fas fa-align-left"></i>
											<span>sidebar</span>
									</button>
									<!--
									<button type="button" id="zoomOut" class="btn btn-sm btn-info zoom-out">
											<i class="fas fa-align-left"></i>
											<span>zoom out</span>
									</button>
-->
							</div>
					</nav>
					<div id="scene">
						<?php //show the small version for Scholarhsip because of TIFFS ?>
						<img id="ImageID" src="<?php if ($this->collection_title == 'Scholarship at Iowa') {echo $this->smallerImageUrl;} else {echo $this->imageUrl;} ?>" alt=''/>	
					</div>          
        </div>
  </div> <!-- wrapper -->

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js"></script>

		<script type="text/javascript">
			var scene = document.getElementById('scene')
			panzoom(scene)
			panzoom(scene, {
				onTouch: function(e) {
					return false
				}
			});

			panzoom(scene, {
				maxZoom: 1,
				minZoom: 0.1
				}).zoomAbs(
				100, // initial x position
				100, // initial y position
				0.4  // initial zoom 
			);

			/*
			/* toggle the tabs
			*/
		
			$('.toggleTab').on('touchstart click', function () {
					$('#transcribeTab').toggleClass('active');
					$('#translateTab').toggleClass('active');
			});

			/*
			/* saving animation
			*/
			
			$('.save').on('touchstart click', function () {
				$('#prev-next').hide();
				$('#flash-message').delay(0).fadeIn('normal', function() {
					$(this).delay(800).fadeOut('slow', function() {
						$('#prev-next').show();
					});
   			});
			});

			/*
			/* collapse sidebar
			*/

			$('#sidebarCollapse').on('touchstart click', function () {
					$('#sidebar').toggleClass('active');
			});

			$('.titles').on('touchstart click', function () {
					$('#sidebar').toggleClass('active');
			});

			/*
			/* login button
			*/

			// https://diyhistory.lib.uiowa.edu/users/login
			$('.login').on('touchstart click', function () {				
				window.location.href = "https://diyhistory.lib.uiowa.edu/users/login";
			});

			/*
			/* submit transcription
			*/

			$("#save-button").on('touchstart click', function(event) {

				var formData = {
					'transcription'	: $('#transcribebox').val()
				};

				$.ajax({
					type 			: 'POST',
					url 			: '<?php echo Zend_Controller_Front::getInstance()->getRequest()->getRequestUri(); ?>/save',
					data 			: formData,
					dataType 	: 'json',
					encode    : true
				}).done(function(data) {}).fail(function(request, error) {});

				event.preventDefault();
			});
			
			/*
			/* submit translation
			*/

			$('#save-translation-button').on('touchstart click', function(event) {

				var formData = {
					'translation'	: $('#translatebox').val()
				};

				$.ajax({
					type 			: 'POST',
					url 			: '<?php echo Zend_Controller_Front::getInstance()->getRequest()->getRequestUri(); ?>/translate',
					data 			: formData,
					dataType 	: 'json',
					encode    : true
				}).done(function(data) {}).fail(function(request, error) {});

				event.preventDefault();				
			});			
    </script>
</body>
</html>