
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<title>DIY History | Transcribe | <?php echo $this->collection_title; ?> | <?php echo $this->item_title; ?> | <?php echo $this->file_title; ?></title>

<?php 
	echo js_tag('jquery-1.9.1'); 
	echo js_tag('jquery-ui.min');
	echo js_tag('jquery.smoothZoom'); 	
	echo js_tag('modernizr.custom');
?>

<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-hover-dropdown/2.0.10/bootstrap-hover-dropdown.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">

<link href="../../plugins/Scriptus/views/public/css/component.css" rel="stylesheet"> 
<style>
.tab-content label {
	visibility: hidden;
	position: absolute;
}

#egrip {
	background-image: url('../../themes/diyh/images/resize.png');
	width: 21px;
	height: 54px;
	right: 0px;
}

</style>
</head>

	<body class="menu-push">	

		<img id="ImageID" src="<?php echo $this->imageUrl; ?>" alt=''/>	
		
		<nav class="menu menu-vertical menu-left" id="menu-s2">
			<div class="ui-resizable-handle ui-resizable-e" id="egrip"></div>
			
	   		<a href="<?php echo WEB_ROOT; ?>"><span class="glyphicon glyphicon-home"></span>home</a>
	   		<br /><br />

	   		<ul class="nav nav-tabs" role="tablist">
	   		  <li class="active"><a href="#transcribe" role="tab" data-toggle="tab">Transcribe</a></li>
	   		  <li id="discussTab"><a href="#discuss" id="discussLink" role="tab" data-toggle="tab">Discuss</a></li>
	   		</ul>

	   		<div class="tab-content">

		   		<div class="tab-pane active" id="transcribe">
			   		<ul>
			   			<li><h5><span class="fa fa-archive fa-lg"></span><?php echo $this->collection_link; ?> </h5></li>			   			
			   			<li><p><span class="fa fa-book fa-lg"></span><?php echo $this->item_link; ?> </p></li>
			   			<li><p><span class="fa fa-file-text fa-lg"></span><strong><?php echo $this->file_title; ?> </strong></p></li>
			   			
		   			</ul>

		   			<div class="dropdown">
		   				<a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">More information<span class="caret"></span></a>
		   			    <ul class="dropdown-menu" role="menu">
		   			      <li><a href="<?php echo $this->idl_link; ?>">digital collection</a></li>
		   			      <li><a href="<?php echo $this->collguide_link; ?>">archival collection guide</a></li>
		   			      <li><a href="<?php echo WEB_ROOT; ?>/tips">transcription tips</a></li>
		   			    </ul>
		   			</div>   			
					<?php echo $this->form; 				
					?>
					<label for="save-button">Save transcription</label> 
					<label for="transcribebox">Enter transcription here</label>

					<?php if (isset($this->paginationUrls['prev'])): ?>
						<a><button type="submit" class="btn btn-xs" onClick="parent.location='<?php echo html_escape($this->paginationUrls['prev']); ?>'">prev</button></a>
					<?php else: ?>
						<button type="submit" class="btn btn-xs">prev</button>
					<?php endif; ?>

					<?php if (isset($this->paginationUrls['next'])): ?>
						<a><button type="submit" class="btn btn-xs" onClick="parent.location='<?php echo html_escape($this->paginationUrls['next']); ?>'">next</button></a>
					<?php else: ?>
						<button type="submit" class="btn btn-xs">next</button>
					<?php endif; ?>

				</div>

				<div data-toggle="tab" class="tab-pane" id="discuss">
					<div id="disqus_thread"></div>
					<script type="text/javascript">
						var url = window.location.href;   
						var temp = new Array();  
						temp = url.split('?');                            
						disqus_url = temp[0];

					    /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
					    var disqus_shortname = 'diyh'; // required: replace example with your forum shortname

					    /* * * DON'T EDIT BELOW THIS LINE * * */
					    (function() {
					        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
					        dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
					        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
					    })();
					</script>
					<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
					<a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
					
				</div>

			</div>
		</nav>	

	


		<!-- Classie - class helper functions by @desandro https://github.com/desandro/classie -->
		<script src="../../plugins/Scriptus/views/public/javascripts/classie.js"></script>
		<script>

			//Loads discuss tab if user navigated from recent comments page. discussOpen is the URL parameter used for this purpose
			$(document).ready(function(){
				var URL = document.URL;
				var URLArray = URL.split("?");
				if (URLArray){
					var endOfURL = URLArray.pop();
					if (endOfURL == 'discussOpen=true'){ 
						$('.active').removeClass('active');
						$('#discuss').addClass('active');
						$('#discussTab').addClass('active');
					}
				}
			});

			jQuery(function($){
				$('#ImageID').smoothZoom({
					width: '100%',
					height: '100%',
					responsive: true
				});

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

								//log data to the console so we can see
								//console.log(data); 
								//console.log("DONE HIT");

								// here we will handle errors and validation messages
							})
							.fail(function(request, error) {
								//console.log("FAIL HIT");
								//console.log("ERROR IS:");
								//console.log(error);
								//console.log(request.responseText)
							});

							

						// stop the form from submitting the normal way and refreshing the page
						event.preventDefault();
					});

					

					/* simulates async login activity */
					var doLogin = function(ms,cb) {
					  setTimeout(function() {
					    if(typeof cb == 'function')
					    cb();
					  }, ms);
					};

					$('#save-button').click(function(){
					  var btn = $(this);
					  
					  btn.button("loading");
					  btn.children().each(function(idx,ele){
					    var icon = $(ele);
					    icon.animate({},2000, 'linear', function() {
					        icon.hide().fadeIn(300*idx).addClass('big');
					     });
					  });
					  
					  // perform login / async callback here
					  doLogin(3000,function(){
					  	btn.button("reset"); // reset button after login callback returns
					  });	  
					})				
			});			

					
			$('#menu-s2').resizable({
			    handles: {			        
			        'e': '#egrip',

			    }
			});

		</script>

	</body>
</html>

