<doctype html!>
<html>
<head>
    <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>PHP/MySQL &amp; Google Maps Example</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
    <script type="text/javascript">
    //<![CDATA[


    //Define custom icons by type
    var customIcons = {
      collection: {
        icon: 'shapes/green_MarkerC.png'
      },
      delivery: {
        icon: 'shapes/orange_MarkerD.png'
      }
    };

    //load, center, set zoom and map type for default map.
    function load() {
      var map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(51.601810, -2.980374),
        zoom: 11,
        mapTypeId: 'roadmap'
      });
      var infoWindow = new google.maps.InfoWindow;

      // Define XML file location to be used.
      downloadUrl("xml.php", function(data) {
        var xml = data.responseXML;
        //set markers as elements of database table
        var markers = xml.documentElement.getElementsByTagName("data");
        for (var i = 0; i < markers.length; i++) {
          var name = markers[i].getAttribute("name");
          var address = markers[i].getAttribute("address");
          var type = markers[i].getAttribute("type");
          var date = markers[i].getAttribute("date");
          var items = markers[i].getAttribute("items");
          var point = new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng")));
          //change betwenn "<b>" for info window heading based on variable
          //change after <br/> for info window contents
          var html = "<b>" + date + "</b> <br/>" + items;
          var icon = customIcons[type] || {};
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            icon: icon.icon
          });
          bindInfoWindow(marker, map, infoWindow, html);
        }
      });
    }

    function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }

    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function doNothing() {}

    //]]>

  </script>

  </head>


<header>
<img src="">
<h2>Collections &amp; Deliveries</h2>
</header>
<br>
  <body onload="load()">
    <div id="map" style="width: 90%; height: 60%"></div>
<br>
<div id="forms">
<form action="xml.php" method="post" >
<label for="from"><p>From</p></label>
<input type="text" id="from" name="from" placeholder="2015-07-01">
<label for="to"><p>to</p></label>
<input type="text" id="to" name="to" placeholder="2015-07-01">

  <input type="submit" value="Submit" name="Submit">
</form>
</div>
<br>

<br>
<div id="forms">
	<h2> Upload CSV to Database</h2>
	<form action="csv.php" method="post" enctype="multipart/form-data">
	<p>Select file to upload: </p>
	<input type="file" name="fileToUpload" id="fileToUpload">
  <br>
  <div id="submit">
	<input type="submit" value="Upload File" name="Submit"> 
</div>
	</form>
</div>
<br>
</body>
<footer>
  <a href="https://uk.linkedin.com/in/garethclabon">
    <h3> Gareth Clabon 2015 </h3>
</a> 
</footer>
</html>