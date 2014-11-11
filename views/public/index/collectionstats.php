<!DOCTYPE html>
<head>
  <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
  <link href="../omeka/plugins/Scriptus/views/public/css/font-awesome.min.css" rel="stylesheet">
  <link href="../omeka/plugins/Scriptus/views/public/css/component.css" rel="stylesheet">
  <?php echo head(array('title'=> 'recentComments')); ?>
</head>
<br><br><br><br><br><br>
<div id="primary">
<h1>New Collection Stats</h1>
  <div id="content">
   
    <div id="recent-transcriptions" style="float: left; margin: 10px;">

      <?php foreach ($this->collectionStats as $collection): ?>
        
        <p>Collection: <b><?php echo $collection["title"] ?></b></p>
        <p>Number Of Files: <?php echo $collection["noOfFiles"] ?></p>

      <?php endforeach; ?>
       
    </div>
  </div>
</div>
</html>