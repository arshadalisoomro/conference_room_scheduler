
var map = L.map('map').setView([42.000, -93.911], 8);

L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);



var catchment_bound_center;
var catchment = L.geoJson(null, {
  style:	{
				fill:false,
				stroke:"#6600FF",
                weight: 3,
                opacity: 1,
                color: '#8F8F8F',
                fillOpacity: 0.4 							
				
            },onEachFeature: function (feature, layer) {
	

			}//end of oneach
				
			});
$.getJSON("assets/data/iowa_city_4326.geojson", function (data) {
  catchment.addData(data);
  
catchment_bound_center=catchment.getBounds();
map.fitBounds(catchment_bound_center);
  
  
});
catchment.addTo(map);