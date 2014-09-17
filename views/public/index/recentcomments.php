<!DOCTYPE html>

<?php echo head(array('title'=> 'recentComments')); ?>

<style>
  .recent-comment{
  padding: 10px;
  background-color: #FFFFED;
  border-radius: 10px;
  border-style: solid;
  margin-bottom: 10px;
  border-color: #C6A971;
  border-width: 1px;
  border-style: solid;
  box-shadow: 2px 1px rgba(0, 0, 0, 0.3)
  }

  #recent-comments a {
    color: black;

  }

  .recent-transcription {
    padding: 10px; background-color: #FFFFE0; border-radius: 20px; border-style: solid; margin-bottom: 10px;
  }

  #recent-comments {
    float: left; margin: 10px; width: 35%;
  }

  #recent-transcriptions {
    float: right; margin: 10px; width: 60%;
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
  }

  .page-title {
    text-align: center;
  }

  .transcription-snippet {
    margin-top: 10px;
    margin-bottom: 10px;
  }

  .collectionName {
    font-size: .8em;
  }

</style>

<div id="primary">
<h1 class="page-title">Site Activity </h1>
  <div id="content">
    <div id="recent-comments">
    <h2>Most recent comments</h2>
      
    <!--Most recent comments inserted via JavaScript-->
    
    </div>
    <div id="recent-transcriptions">
       <h2>Most recent transcriptions</h2>
      <?php //print_r($this->recentTranscriptions);
      foreach ($this->recentTranscriptions as $transcriptionItem): ?>
        <div class="transcription-item">
          <?php /* <p>Link to page: <?php echo $transcriptionItem["URL_changed"] ?></p> 
          <p>Collection name: <?php echo $transcriptionItem["collection_name"] ?></p>
          <p> Username: <?php echo $transcriptionItem["username"] ?></p>
          <p> Changed: <?php echo $transcriptionItem["time_changed"] ?></p> 
          <p> Image URL: <?php echo $transcriptionItem["image_url"] ?></p> 
          <p> Transcription: <?php echo snippet_by_word_count($transcriptionItem["transcription"], 10, '...') ?></p> */ ?>

          <a href="<?php echo $transcriptionItem["URL_changed"] ?>"><img src="<?php echo $transcriptionItem["image_url"] ?>" /></a>

          <div class="transcription-snippet">
            <p> <?php echo snippet_by_word_count($transcriptionItem["transcription"], 10, '...') ?></p>
          </div>

          <p class="collectionName"><strong><?php echo $transcriptionItem["collection_name"] ?></strong></p>
          
        </div>
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

        postDate = new Date(postDate);
        console.log("NEW DATE FORMAT");
        console.log(postDate);
        console.log("END NEW DATE FORMAT");
        //TODO: Remove below if (it's there for testing)
        if (i < 0){
          //console.log(aResponse.thread);
        }
        threadLink = aResponse.thread.link;
        threadTitle = aResponse.thread.title;

        
        if (threadTitle.split(" | ").length > 1){
          threadArray = threadTitle.split ( " | ");
          threadTitle = threadArray.pop() + " | " + threadArray.pop();
        }
        
     

        postBody = "<a href='" + threadLink + "'>" + "<div class='recent-comment'>" + "<p>" + message + "</p>"; 

        postLink = "<p> " + "<strong class='collectionName'>" + threadTitle + "</strong>" +"</p>" + "</div>" + "</a>"  ;

        post = postBody + postLink; 
       
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
