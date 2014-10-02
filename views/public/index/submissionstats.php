<!DOCTYPE html>
<head>
  <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
  <link href="../omeka/plugins/Scriptus/views/public/css/font-awesome.min.css" rel="stylesheet">
  <link href="../omeka/plugins/Scriptus/views/public/css/component.css" rel="stylesheet">
  <?php echo head(array('title'=> 'recentComments')); ?>
</head>

<div id="primary">
<h1>New Submission Stats</h1>
  <div id="content">
   
    <div id="recent-transcriptions" style="float: left; margin: 10px;">
       <h2>Most recent transcriptions</h2>
      
      <?php foreach ($this->submissionStats as $submissionMonth): ?>
     
        <p>Collection: <b><?php echo $submissionMonth["collection"] ?></b></p>
        <p>Date: <?php echo $submissionMonth["date"] ?></p>
        <p> Transcription count:<b> <?php echo $submissionMonth["transcriptionCount"] ?></b></p>
      <?php endforeach; ?>
    </div>
  </div>
</div>
</html>