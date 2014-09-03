<!DOCTYPE html>
<head>
  <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
  <link href="../omeka/plugins/Scriptus/views/public/css/font-awesome.min.css" rel="stylesheet">
  <link href="../omeka/plugins/Scriptus/views/public/css/component.css" rel="stylesheet">
</head>

<div id="primary">
<h1>Test of Disqus API </h1>
  <div id="content">
    <div id="recent-comments" style="float: left; margin: 10px;">

    <h2>Most recent comments</h2>
    </div>
    <div id="recent-transcriptions" style="float: left; margin: 10px;">
       <h2>Most recent transcriptions</h2>
      <?php //print_r($this->recentTranscriptions);
      foreach ($this->recentTranscriptions as $transcriptionItem): ?>
        <p>Link to page: <?php echo $transcriptionItem["URL_changed"] ?></p>
        <p> Username: <?php echo $transcriptionItem["username"] ?></p>
        <p> Changed: <?php echo $transcriptionItem["time_changed"] ?></p> 
      <?php endforeach; ?>
    </div>
  </div>
</div>

<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script type="text/javascript">

$(document).ready(function () {


   var disqusPublicKey = "jmgI7Iex4CKNqXPAVCq6d7gI8HISZRjx442VnEdhl0GDHgJJ20aheCkcmygqHwXX";
  var disqusShortname = "diyh"; // Replace with your own shortname

  $.ajax({
    type: 'GET',
    //the related=thread parameter below is necessary for the query to return links to the posts
    url: "http://disqus.com/api/3.0/forums/listPosts.jsonp?related=thread",
    data: { api_key: disqusPublicKey, forum : disqusShortname},
    dataType: 'jsonp',
    success: function (result) {

      numberOfPostsToDisplay = 3;

      if (result.response.length < numberOfPostsToDisplay){
         numberOfPostsToDisplay = result.response.length;
      }
      
      
      for (var i = 0; i < numberOfPostsToDisplay; i++){
        aResponse = result.response[i];

        message = aResponse.message;
        author = aResponse.author.name;
        postDate = aResponse.createdAt;
        threadLink = aResponse.thread.link;
     

        post = "<p>author: " + author + "</p>message: " + message + "<p>post date: " + postDate + "</p><p>URL for thread: " + threadLink + "</p>";
        //document.write("author: " + author + "<br>");
        //document.write("message: " + message);
        //document.write("post date: " + postDate + "<br>");
         //document.write("URL for thread: " + threadLink + "<br>");

        $('#recent-comments').append(post);
        console.log(post);
        //document.write("<br><br><br>")

        console.log(aResponse);
      }
    },
    error: function(parsedjson, textStatus, errorThrown){
      console.log("ERRORRRR");
      console.log(parsedjson.response);
      console.log(textStatus);
      console.log(errorThrown);
    }
  });
  
});
</script>

</html>
