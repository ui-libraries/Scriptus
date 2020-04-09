<?php echo head(array('title'=> 'recentComments')); ?>
<style>

.menu-link:hover {
	text-decoration: underline;
	color: white;
}

.menu-link {
	color: white;
}

.menu-item a {
	color: white;
}

.menu-item a:hover {
	color: white;
}

a {
	color: black;
}

a:hover {
	text-decoration: underline;
	color: black;
}

.recent-comment {
	padding: 10px;
	background-color: #FFFFED;
	border-radius: 10px;
	border-style: solid;
	margin-bottom: 20px;
	margin-left: 10px;
	border-color: #C6A971;
	border-width: 1px;
	border-style: solid;
	box-shadow: 2px 1px rgba(0, 0, 0, 0.3)
}

.transcriptionLink {
	text-decoration: none;
	color: black;
}

.transcriptionLink:hover {}

#recent-comments a {
	color: black;
	text-decoration: none;
}

#recent-comments,
#update-account {
	float: left;
	width: 35%;
	margin-right: 5px;
	padding: 5px;
}

#recent-comments h2 {
	padding: 0 8px 0 8px;
	display: block;
}

.recent-transcriptions {
	float: left;
	width: 60%;
	margin-left: 5px;
	padding: 5px;
}

#user-transcriptions {
   margin-left: 20%;
}

.transcription-item {
	position: relative;
	overflow: hidden;
	background-color: #f7f7f7;
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.10);
	width: 200px;
	float: left;
	margin-right: 10px;
	margin-bottom: 20px;
	padding: 10px;
	height: 340px;
}

.comment-content {
	display: block;
	font-size: 1.2em;
}

.page-title {
	text-align: center;
}

.transcription-snippet {
	background-color: white;
	padding: 5px;
	margin-top: 10px;
	margin-bottom: 10px;
	border-radius: 5px;
	height: 7.15em;
}

.transcription-breadcrumbs a {
	font-weight: bold;
}

.transcription-item a {
	color: black;
}

#accordion0 .accordion-toggle {
	padding: 5px;
}

.accordion-toggle h3 {
	padding-left: 25px;
	margin-bottom: 0px;
}

.accordion-group {
	margin-bottom: 10px;
}

.accordion-body {
	padding: 0 8px 0 8px;
}

#accordion0 {
	padding-top: 0px;
}

.accordion-group {
	border: none;
	background-color: rgba(0, 0, 0, 0.03);
}

.expanded {
	background: url(themes/diyh/images/minusIcon.png) 5px 10px no-repeat;
	background-size: 10px 10px;
}

.notExpanded {
	background: url(themes/diyh/images/plusIcon.png) 5px 10px no-repeat;
	background-size: 10px 10px;
}

.author {
	background-color: #854a16;
	color: white;
	float: right;
	padding: 3px;
	border-radius: 5px;
}

.postDate {
	background-color: #854a16;
	color: white;
	float: left;
	margin-right: 10px;
	padding: 3px;
	border-radius: 5px;
}

.commentContext {
	margin-top: 5px;
	margin-bottom: 5px;
}

.header-clear {
	height: 40px;
}

.clearfix {
	clear: both;
}

.section-title h1 {
	font-size: 30pt;
}

.section-title,
.login-link {
	text-align: center;
	margin-bottom: 5px;
}

.login-link {
	font-size: 1.2em;
	margin-bottom: 10px;
	border-width: 2px;
	border-style: ridge;
	padding: 3px;
	border-color: rgba(0, 0, 0, 0.03);
}

.login-link a {
	color: blue;
}

@media (max-width: 959px) {
	#user-transcriptions,
	#update-account,
	#recent-comments,
	#recent-transcriptions {
		width: 95%;
		margin: auto;
		margin-bottom: 10px;
	}
}

@media (max-width: 480px) {
	.section-title h1 {
		font-size: 28pt;
	}
}


}
</style>
<?php $user = current_user(); ?>
    <div id="primary">
        <div class="content">
            <div class="section-title">
                <h1>Your dashboard</h1>
                <?php if ($user): ?>
                    <div id="update-account">
                        <h2>Update account</h2>
                        <a href="<?php echo WEB_ROOT;?>/guest-user/user/update-account">Update account information and password</a>
                        <?php endif; ?>
                    </div>
            </div>
                <?php if (!$user): ?>
                    <div class="login-link"><a href="<?php echo WEB_ROOT;?>/guest-user/user/login">Login </a>to see your recent transcriptions and view account options.</div>
                    <?php else: ?>
                        <div class="recent-transcriptions">
                            <h2>Most recent transcriptions</h2>
                              <?php foreach ($this->recentTranscriptions as $transcriptionItem): ?>
                                <div class="transcription-item">
                                        <a href="<?php echo $transcriptionItem["URL_changed"] ?>" class="transcriptionLink">
                                          <img src="<?php echo $transcriptionItem["image_url"] ?>" alt="<?php echo $transcriptionItem["file_title"] ?>,a part of <?php echo $transcriptionItem["item_title"] ?>" /> 
                                          <div class="transcription-snippet">
                                             <p> <?php echo snippet_by_word_count($transcriptionItem["transcription"], 10, '...') ?></p>
                                          </div>
                                       </a>
                                </div>
                              <?php endforeach; ?>
                        
                        <div class="recent-transcriptions">
                            <h2>Your recent transcriptions</h2>
                            <ul>
                                <?php foreach ($this->recentUserTranscriptions as $transcriptionItem): ?>
                                    <li>
                                        <a href="<?php echo $transcriptionItem['last_transcribed'] ?>">
                                            <?php echo $transcriptionItem['display_title'] ?>
                                        </a>
                                        <br />
                                        <?php echo $transcriptionItem['time_changed'] ?>
                                            <br />
                                            <br />
                                    </li>
                                 <?php endforeach; ?>
                            </ul>
                           </div>
                        
                            
               <?php endif; ?>
                        
        </div>
    </div>

    </html>