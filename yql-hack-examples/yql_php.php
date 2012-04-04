<!DOCTYPE html>
<html>
    <head>
        <title>YQL PHP Example</title>
	<link rel="stylesheet" href="http://hackyourworld.org/~jaisanth/css/docstyles.css" type="text/css"> 
    </head>
    <body>
<div id="container">
<h1>Using YQL to Access Youtube API</h1>
<form name='search_form'>
Search Term: <input name='term' id='term' type='text' size='20'/>
<button id='search_term'>Search Term</button>
</form>

<script>
  // Attach event handler to button
  document.getElementById("search_term").addEventListener("click",find_event,false);
  // Get user input and submit form
  function find_event(){
    document.search_form.term.value = document.getElementById('term').value || "rhok";
    document.search_form.submit();
  } 
</script>
<?php
  $BASE_URL = "https://query.yahooapis.com/v1/public/yql";

  if(isset($_GET['term']))
  {
    $term = $_GET['term'];
     
    // Form YQL query and build URI to YQL Web service
    $yql_query = "select id,title,thumbnails from youtube.search where query='" . $term . "' limit 10";
    $yql_query_url = $BASE_URL . "?q=" . rawurlencode($yql_query) . "&format=json&env=store://datatables.org/alltableswithkeys";

    // Make call with cURL
    $session = curl_init($yql_query_url);
    curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
    $json = curl_exec($session);
    // Convert JSON to PHP object 
    $phpObj =  json_decode($json);

    $markup = '';
    // Confirm that results were returned before parsing
    if(!is_null($phpObj->query->results)){
      // Parse results and extract data to display
      foreach($phpObj->query->results->video as $video){
	$markup .= <<<HTML
<li class="video">
<cite>{$video->title}</cite>
<iframe width="315" height="236" src="http://www.youtube.com/embed/{$video->id}" frameborder="0" allowfullscreen></iframe>
</li>
HTML;
      }
    }
    // No results were returned
    if(empty($markup)){
       echo "Sorry, no videos matching for $term on Youtube";
    }
    // Display results and unset the global array $_GET
    else
    {
    	echo <<<HTML
<h3>Search results for "{$term}"</h3>
<ul class="video-list">$markup</ul>
HTML;
    }
    unset($_GET);
  }
?>
</div>
</body>
</html>
