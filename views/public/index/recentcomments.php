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
    text-decoration: none;

  }

  .recent-transcription {
    padding: 10px; background-color: #FFFFE0; border-radius: 20px; border-style: solid; margin-bottom: 10px;
  }

  #recent-comments {
    float: left; margin: 10px; width: 35%;
  }

  #recent-comments h2 {
    padding: 0 8px 0 8px;

  }

   .accordion-body {
  padding: 0 8px 0 8px;
}

  #accordion0 {
    padding-top: 0px;
  }

  .accordion-group {
    border: none;
  }
  .accordion-group h3 {
    background-color: rgba(0, 0, 0, 0.03);
    padding: 5px;
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
    height: 450px;
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

  .transcription-breadcrumbs a {
    font-size: .8em;
    font-weight: bold;
  }

  .transcription-item a {
    color: black;
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

           <a href="<?php echo $transcriptionItem["URL_changed"] ?>" class="transcriptionLink">
            <img src="<?php echo $transcriptionItem["image_url"] ?>" /> 

            <div class="transcription-snippet">
              <p> <?php echo snippet_by_word_count($transcriptionItem["transcription"], 10, '...') ?></p>
            </div>
           </a>

          <div class="transcription-breadcrumbs">
            <a href="<?php echo $transcriptionItem["URL_changed"] ?>"><?php echo $transcriptionItem["file_title"] ?></a> |
            <?php echo $transcriptionItem["item_link"] ?> | 
            <?php echo $transcriptionItem["collection_link"] ?>
          </div>
          
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>


<script type="text/javascript">

$(document).ready(function () {

  //This JavaScript queries Disqus for the latest comments in order to display the number desired from each collection.  If the number desired from a given collection aren't present in the latest 100 comments, then only the number of comments that are present will appear.


  var disqusPublicKey = "jmgI7Iex4CKNqXPAVCq6d7gI8HISZRjx442VnEdhl0GDHgJJ20aheCkcmygqHwXX";
  var disqusShortname = "diyh"; 

  $.ajax({
    type: 'GET',
    /*the related=thread URL parameter below is necessary for the query to return links to the posts.  The second URL paramter, limit 100, is the max we can get back.  */
    url: "http://disqus.com/api/3.0/forums/listPosts.jsonp?related=thread&limit=100",
    data: { api_key: disqusPublicKey, forum : disqusShortname},
    dataType: 'jsonp',
    success: function (result) {
    console.log("RESULT IS");
    console.log(result);

      //The collection object will have the collections and then the associated comments stored in it
      collectionObject = {};

      //All the collections currently tracked.  Update as necessary.
      collectionArray = ["Pioneer Lives", "Iowa Women’s Lives: Letters and Diaries", "Szathmary Culinary Manuscripts and Cookbooks", "Building the Transcontinental Railroad", "Nile Kinnick Collection", "Civil War Diaries and Letters"];

      //The number of comments we're displaying for each collection
      commentsPerCollection = 3;

      //When we complete a collection, increment this variable
      collectionsComplete = 0;

      //Each time we complete a collection, we check to see if we've completed all the collections by comparing collectionsComplete to collectionsToBeCompleted
      collectionsToBeCompleted = collectionArray.length;

      //Create structure for collection object that we'll use to display collections
      for (var j = 0; j < collectionArray.length; j++){
        collectionObject[collectionArray[j]] = {commentData: [], noOfComments: 0};
      }

      //The number of comments we're displaying for each collection
      commentsPerCollection = 3;

      //When each collection has the above number of comments per collection, enoughComments should be true
      enoughComments = false;

      //Used to track iterating through array of results
      i = 0;

      //Iterate through API results until we have the desired number of comments.  The results are found in an object with a property response that is just an array of comments.
      while ((enoughComments == false) && (i < result.response.length)){
        
        //The next comment in the array
        aResponse = result.response[i];


        threadTitle = aResponse.thread.title;

        //To identify the collection, item and file names associated with a comment, we pull that information out of the HTML title.  DON'T CHANGE THE FORMAT OF THE HTML TITLE.
        //The format of a title is DIY History | Transcribe | Collection Name | Item Name | File Name.
        //If the title fits that format, split the string on the '|' character and then pop the last two items off of the resulting array to format a title for the user.
        //If the title doesn't fit that format, then we avoid performing array operations on a non-array with the if statement.  
        if (threadTitle.split(" | ").length > 1){
          threadArray = threadTitle.split ( " | ");

          //Title we display to the user
          displayTitle = threadArray.pop() + " | " + threadArray.pop();

          //TODO: Needed?
          collectionTitle = threadArray.pop();
        }
        
        //Add comment to what we're going to display as long as we haven't reached the max number of comments for a collection
        if (collectionObject[collectionTitle]["noOfComments"] < commentsPerCollection){

          //Add comment data to object that holds what we're going to display
          collectionObject[collectionTitle]["commentData"].push(aResponse);

          //Add display title to comment data 
          collectionObject[collectionTitle]["commentData"]["displayTitle"] = displayTitle;

          //Track number of comments added for each collection
          collectionObject[collectionTitle]["noOfComments"]++;

          //If the collection we just updated has the max number of comments, we recheck to see if we have the desired comments for each collection.  If so, we exit the loop
          if (collectionObject[collectionTitle]["noOfComments"] == commentsPerCollection){
            collectionsComplete++;
            if (collectionsComplete == collectionsToBeCompleted){
              enoughComments = true;
            }
          }
        }
      i++;  
      }
      console.log("COLLECTION OBJECT IS");
      console.log(collectionObject);

      //We will build the DOM to append to the recent-comments page with this string
      bodyString = '';

      bodyString += '<div class="accordion" id="accordion0">';

      //Needed to uniquely label the different collapsible parts of the accordion (collapse0, collapse1 and so forth).  Incremented each time through the loop.
      collectionNumberTracker = 0; 

      //Each high-level prop of collectionObject is the name of a collection
      for (collectionName in collectionObject){

        //The title of collection that will be displayed
        collectionTitleString = '<h3>' + collectionName + '</h3>';

        //Below is the Bootstrap markup for the accordion we display.  Most of it is taken from the documentation
        bodyString += '<div class="accordion-group">';
        bodyString += '<div class="accordion-heading">';
        bodyString += '<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion0" href="#collapse' + collectionNumberTracker + '">' + collectionTitleString + '</a></div>';

        bodyString += '<div id="collapse' + collectionNumberTracker + '" class="accordion-body collapse in">';

        //The value of each high-level property in collectionObject is the comments for each collection and the number of comments.  Here, we get all the comments to iterate through them
        collectionComments = collectionObject[collectionName]["commentData"];
        displayTitle = collectionObject[collectionName]["commentData"]["displayTitle"];

            //Iterate through the comments for each collection
            for (m = 0; m < collectionComments.length; m++){

              //Get an individual comment
              comment = collectionComments[m];

              //Get the message (content of the comment)
              message = comment.message;

              //Strip HTML markup out of message
              message = message.replace('<p>', '');
              message = message.replace('</p>', '');

              //Get other information out of the comment
              author = comment.author.name;
              postDate = comment.createdAt;
              threadLink = comment.thread.link;
              threadTitle = comment.thread.title;

              //The comment and a link to where it occurred
              postBody = "<a href='" + threadLink + "'>" + "<div class='recent-comment accordion-inner'>" + "<span class='comment-content'>" + message + "</span>"; 

              //Information about where the comment occurred
              postContext =  "<strong class='commentContext'>" + displayTitle + "</strong>" + "</div>" + "</a>"  ;

              post = postBody + postContext; 

              bodyString += post;
          }
        
        bodyString += '</div></div>'; 
        collectionNumberTracker++;
      } 
      bodyString += '</div>'; 
      
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
