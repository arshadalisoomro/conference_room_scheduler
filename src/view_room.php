<?php
error_reporting(E_ALL);
    include_once('../AutoLoader.php');
    AutoLoader::registerDirectory('../src/classes');

    require("config.php");

    if(empty($_SESSION['user'])) {
        header("Location: ../index.php");
        die("Redirecting to index.php"); 
    }

    $rooms = new ViewRoom();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Conference Room Detail</title>
    <meta name="description" content="Conference room management system for Database Systems">
    <meta name="author" content="Team 6">

    <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.indigo-pink.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="../main.css" rel="stylesheet" type="text/css">
	 <link href="assets/css/typehead.css" rel="stylesheet" type="text/css">
	 <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
	 <script src="https://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>
</head>


<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <header class="mdl-layout__header mdl-layout__header--waterfall">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout-title">View Room</span>
            </div>
        </header>
        <div class="mdl-layout__drawer">
            <span class="mdl-layout-title">Scheduler</span>
            <nav class="mdl-navigation">
                <?php //AccountDropdownBuilder::buildDropdown($db, $_SESSION) ?>
            </nav>
        </div>
        <main class="mdl-layout__content">
            <br/>
            <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
              <div class="mdl-card mdl-cell mdl-cell--12-col">
                <div class="mdl-card__supporting-text">
				
				<div id="content">
                    <?php 					
					//$rooms->buildRoom($db, $_GET, $_SESSION['user']['_id']) ?>
					
					<div id="room_search" style="margin-bottom:30px">
				<input class="typeahead" id="seach_val" type="text" placeholder="search conference room" >
				     </div>
					
					<table id="display_table" align="center" class="mdl-data-table mdl-js-data-table mdl-data-table mdl-shadow--2dp" style="height:400px;width:600px">
					<thead ><tr >
					<th class="mdl-data-table__cell--non-numeric">Building &nbsp;</th>
					<th>Room</th>
					<th>Capacity</th>
					<th>Geometry</th>
					<th>Equipments</th>
					<th>Resource Types</th>
					</tr>
					</thead style="display:block;">
					<tbody id="room_detail_table" style="position:absolute; overflow-y: auto; overflow-x: hidden; height:85%; width:100%;">
					 </tbody>
					</table>
					</div>
                </div>
              </div>
            </section>
            <br/>
        </main>
    </div>
	
	<script type="text/javascript">
	var room_in_json = <?php $rooms->getRoom($db, $_GET, $_SESSION['user']['_id']) ?>;
	console.log(room_in_json);
	window["autocomplete_list"]=[];
	for(var i=0;i<room_in_json.length;i++){
		!function outer(i){
		room_in_json[i]["combine_room_name"]="";
		var room=room_in_json[i]["name"];
		var room_number=room_in_json[i]["room_number"];
		var capacity=room_in_json[i]["capacity"];
		var geometry=room_in_json[i]["geometry"];
		var quality_description=room_in_json[i]["quality_description"];
		var description=room_in_json[i]["description"];
		room_in_json[i]["combine_room_name"]=room+","+room_number;
		window["autocomplete_list"].push(room_in_json[i]["combine_room_name"]);
		window["autocomplete_list"].push(description);
		
		var tr_text="<tr><td>"+room+"</td><td>"+room_number+"</td><td>"+capacity+"</td><td>"+geometry+"</td><td>"+quality_description+"</td><td>"+description+"</td>";
		$("#room_detail_table").append(tr_text);
		}(i)
	}//end of for loop and table generation
	function autocomplete(input_list,html_id){
			var substringMatcher = function(strs) {
				return function findMatches(q, cb) {
				var matches, substringRegex;
				matches = [];
				substrRegex = new RegExp(q, 'i');
						$.each(strs, function(i, str) {
						  if (substrRegex.test(str)) {
							matches.push(str);
						  }
						});

						cb(matches);
					  };
					};
		$(html_id+' .typeahead').typeahead({
			  hint: true,
			  highlight: true,
			  minLength: 1
			},
			{
			  name: 'room',
			  source: substringMatcher(input_list)
			});
	}
	window["autocomplete_list"].push("Display all room");
	autocomplete(window["autocomplete_list"],"#room_search");
	
		   $(document).on("click",".tt-menu",function() {
			var matching_val=$("#seach_val").val();
		   $("#room_detail_table").remove();
		   var reappend='<tbody id="room_detail_table" style="position:absolute; overflow-y: auto; overflow-x: hidden; height:85%; width:100%;"></tbody>'
	        $("#display_table").append(reappend);
		    search_table(matching_val);
			
			});

	function search_table(match_val){
		for(var i=0;i<room_in_json.length;i++){
		!function outer(i){
		var room=room_in_json[i]["name"];
		var room_number=room_in_json[i]["room_number"];
		var capacity=room_in_json[i]["capacity"];
		var geometry=room_in_json[i]["geometry"];
		var quality_description=room_in_json[i]["quality_description"];
		var description=room_in_json[i]["description"]; 
	
        if(match_val==room_in_json[i]["combine_room_name"]||match_val==description){
		 var tr_text="<tr><td>"+room+"</td><td>"+room_number+"</td><td>"+capacity+"</td><td>"+geometry+"</td><td>&nbsp;&nbsp;"+quality_description+"</td><td>&nbsp;"+description+"</td>";
		$("#room_detail_table").append(tr_text);			
		}
		if(match_val=="Display all room"){
		var tr_text="<tr><td>"+room+"</td><td>"+room_number+"</td><td>"+capacity+"</td><td>"+geometry+"</td><td>&nbsp;&nbsp;"+quality_description+"</td><td>&nbsp;"+description+"</td>";
		$("#room_detail_table").append(tr_text);
			
		}

		}(i)
	   }//end of for loop and table generation
		
	}
	
	</script>
	
    
    <script src="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.min.js"></script>
</body>
</html>