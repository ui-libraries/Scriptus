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

  .transcriptionLink {
    text-decoration: none;
    color: black;
  }

  .transcriptionLink:hover {

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

  .comment-content {
    display: block;
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

  .collectionName {
    font-size: .8em;
    height: 2.85em;
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

          <a href="<?php echo $transcriptionItem["URL_changed"] ?>" class="transcriptionLink"><img src="<?php echo $transcriptionItem["image_url"] ?>" />

          <div class="transcription-snippet">
            <p> <?php echo snippet_by_word_count($transcriptionItem["transcription"], 10, '...') ?></p>
          </div></a>

          <p class="collectionName"><strong><?php echo $transcriptionItem["collection_name"] ?></strong></p>
          
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>


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

      collectionObject = {};
      collectionArray = ["Pioneer Lives", "Iowa Women’s Lives: Letters and Diaries", "Szathmary Culinary Manuscripts and Cookbooks", "Building the Transcontinental Railroad", "Nile Kinnick Collection", "Civil War Diaries and Letters"];

      for (var j = 0; j < collectionArray.length; j++){
        collectionObject[collectionArray[j]] = {commentData: [], noOfComments: 0};
      }

      if (result.response.length < numberOfPostsToDisplay){
         numberOfPostsToDisplay = result.response.length;
      }
      
      enoughComments = false;
      i = 0;

      while (enoughComments == false){
        aResponse = result.response[i];

        threadLink = aResponse.thread.link;
        threadTitle = aResponse.thread.title;

        
        if (threadTitle.split(" | ").length > 1){
          threadArray = threadTitle.split ( " | ");
          threadTitle = threadArray.pop() + " | " + threadArray.pop();
          collectionTitle = threadArray.pop();
        }
        
        if (collectionObject[collectionTitle]["noOfComments"] < 3){
          collectionObject[collectionTitle]["commentData"].push(aResponse);
          collectionObject[collectionTitle]["noOfComments"]++;
          if (collectionObject[collectionTitle]["noOfComments"] == 2){
            allCollectionsCompleted = true;
            for (k = 0; k < collectionArray.length; k++){
              collectionName = collectionArray[k];
              collection = collectionObject[collectionName];
              if (collection["noOfComments"] < 2){
                allCollectionsCompleted = false;

              }
            }
            console.log("ALL COLLECTIONS COMPLETED IS");
            console.log(allCollectionsCompleted);
            if (allCollectionsCompleted == true){

              enoughComments = true;
            }
          }
        }
      i++;  
      }

      bodyString = '';

      bodyString += '<div class="accordion" id="accordion1">';

      console.log("COLLECTION OBJECT IS");
      console.log(collectionObject);

      collectionNumberTracker = 0; 

      for (collectionName in collectionObject){

        collectionTitleString = '<h3>' + collectionName + '</h3>';

        bodyString += '<div class="accordion-group">';
        bodyString += '<div class="accordion-heading">';
        bodyString += '<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse' + collectionNumberTracker + '"">' + collectionTitleString + '</a></div>';

        bodyString += '<div id="collapse' + collectionNumberTracker + '" class="accordion-body collapse in">';

        collection = collectionObject[collectionName];
        for (propName in collection){

            collectionProp = collection[propName];
            for (m = 0; m < collectionProp.length; m++){
              comment = collectionProp[m];
              message = comment.message;
              message = message.replace('<p>', '');
              message = message.replace('</p>', '');

              author = comment.author.name;
              postDate = comment.createdAt;

              threadLink = aResponse.thread.link;
              threadTitle = aResponse.thread.title;

              if (threadTitle.split(" | ").length > 1){
                threadArray = threadTitle.split ( " | ");
                threadTitle = threadArray.pop() + " | " + threadArray.pop();
                collectionTitle = threadArray.pop();
              }

              postBody = "<a href='" + threadLink + "'>" + "<div class='recent-comment'>" + "<span class='comment-content'>" + message + "</span>"; 

              postLink =  "<strong class='collectionName'>" + threadTitle + "</strong>" + "</div>" + "</a>"  ;

              post = postBody + postLink; 

              bodyString += post;
          } 
        
        }
        bodyString += '</div>'; 
        collectionNumberTracker++;
      } 
      bodyString += '</div></div>';   
      $('#recent-comments').append(bodyString);
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
