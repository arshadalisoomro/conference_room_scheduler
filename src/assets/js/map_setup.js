
var map = L.map('map').setView([41.660, -91.541], 14);

L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
})

var qst = new L.TileLayer('http://otile1.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png', {attribution:'Tiles Courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a> <img src="http://developer.mapquest.com/content/osm/mq_logo.png">'});


/*USGS national map service*/
var nationalMapUrl = 'http://basemap.nationalmap.gov/ArcGIS/rest/services/USGSTopo/MapServer/tile/{z}/{y}/{x}';
var nationalMapAttribution = "<a href='http:usgs.gov'>USGS</a> National Map Data";
var nationalMap = new L.TileLayer(nationalMapUrl, {maxZoom: 15, attribution: nationalMapAttribution},{noWrap: true});
/*end of national map service*/

qst.addTo(map);

var catchment_bound_center;
var catchment = L.geoJson(null, {
  style:	{
				fill:true,
				stroke:"#6600FF",
                weight: 1,
                opacity: 1,
                color: '#9966FF',
                fillOpacity: 0.6 							
				
            },onEachFeature: function (feature, layer) {
				if( feature.properties.name=== null){
					layer.setStyle({
						fill:false,
						stroke:false,
						weight: 0.5,
						fillOpacity: 0.1 
					})
				}
				
			$(document).on("click","tr",function() {
				console.log($(this).attr('class'));
				if(feature.properties.name==$(this).attr('class') && feature.properties.name!= null){
					console.log(feature);
					window['tf']=feature;
				}

			
			});
				
				
				
				
				
				layer.on('mouseover', function(e){
					layer.setStyle({
						stroke:"#FFCC00",
						weight: 2,
						color: '#FFCC00',
						fillOpacity: 0.9
					});
					$("#seach_val").val(feature.properties.name);
					
				})
				layer.on('mouseout', function(e){
					layer.setStyle({
						stroke:"#6600FF",
						weight: 1,
						opacity: 1,
						color: '#9966FF',
						fillOpacity: 0.6 
					});
					$("#seach_val").val("");
				})
				layer.on('click', function(e){
					layer.setStyle({
						stroke:"#FF0066",
						weight: 3,
					})
					$("#room_detail_table").empty();
					search_table(feature.properties.name,"name");
				})	
	


			}//end of oneach
				
			});
$.getJSON("assets/data/iowa_city_4326.geojson", function (data) {
  catchment.addData(data);
  
//catchment_bound_center=catchment.getBounds();
//map.fitBounds(catchment_bound_center); 
  
  
});
catchment.addTo(map);


function search_table(match_val,table_field_name){
		for(var i=0;i<room_in_json.length;i++){
		!function outer(i){
		var room=room_in_json[i]["name"];
		var room_number=room_in_json[i]["room_number"];
		var capacity=room_in_json[i]["capacity"];
		var geometry=room_in_json[i]["geometry"];
		var quality_description=room_in_json[i]["quality_description"];
		var description=room_in_json[i]["description"]; 
	
        if(match_val==room_in_json[i][table_field_name]||match_val==description){
		
		var tr_text="<tr class='"+room+"'><td>"+description+"</td><td>"+quality_description+"</td><td>"+room+"</td><td>"+room_number+"</td><td>"+capacity+"</td><td>"+geometry+"</td>";
		$("#room_detail_table").append(tr_text);			
		}
		if(match_val=="Display all room"){		
		var tr_text="<tr class='"+room+"'><td class='"+room+"'>"+description+"</td><td class='"+room+"'>"+quality_description+"</td><td class='"+room+"'>"+room+"</td><td class='"+room+"'>"+room_number+"</td><td class='"+room+"'>"+capacity+"</td><td class='"+room+"'>"+geometry+"</td>";$("#room_detail_table").append(tr_text);
			
		}

		}(i)
	   }//end of for loop and table generation
		
	}