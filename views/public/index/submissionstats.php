<!DOCTYPE html>
<head>
  <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
  <link href="../omeka/plugins/Scriptus/views/public/css/font-awesome.min.css" rel="stylesheet">
  <link href="../omeka/plugins/Scriptus/views/public/css/component.css" rel="stylesheet">
</head>

<div id="primary">
<h1>Test of Disqus API </h1>
  <div id="content">
   
    <div id="recent-transcriptions" style="float: left; margin: 10px;">
       <h2>Most recent transcriptions</h2>
       <?php print_r($this->asd); ?>
      <?php foreach ($this->submissionStats as $submissionMonth): ?>
      enters foreach
        <p>Collection: <?php echo $submissionMonth["collection"] ?></p>
        <p>Date: <?php echo $submissionMonth["date"] ?></p>
        <p> Transcription count: <?php echo $submissionMonth["transcriptionCount"] ?></p>
      <?php endforeach; ?>
    </div>
  </div>
</div>
</html>