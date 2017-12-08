<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Where are the Eyes</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="description" content="Visualized data from the Where are the Eyes Project by The Daylighting Society">
    <meta name="keywords" content="CCTV, surveillance, camera, where are the eyes, the daylighting society">
    <link rel="stylesheet" href="assets/ol.css" type="text/css">
    <link rel="stylesheet" href="assets/style.css" type="text/css">
    <!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
    <script src="assets/ol.js"></script>
  </head>
  <body>
    <div id="map" class="map"></div>
    <script>
      var projection = ol.proj.get('EPSG:3857');

      var vector = new ol.layer.Vector({
        source: new ol.source.Vector({
          url: 'data/eyes.kml',
          format: new ol.format.KML()
        })
      });

      var map = new ol.Map({
        layers: [
          new ol.layer.Tile({
            source: new ol.source.OSM()
          }), vector
        ],
        target: 'map',
        view: new ol.View({
          center: [2126970.8463461736, 5959807.853963373],
          projection: projection,
          zoom: 8
        })
      });

      var displayFeatureInfo = function(pixel) {
        var features = [];
        map.forEachFeatureAtPixel(pixel, function(feature) {
          features.push(feature);
        });
        if (features.length > 0) {
          var info = [];
          var i, ii;
          for (i = 0, ii = features.length; i < ii; ++i) {
            info.push(features[i].get('name'));
          }
          document.getElementById('info').innerHTML = info.join(', ') || '(unknown)';
          map.getTarget().style.cursor = 'pointer';
        } else {
          document.getElementById('info').innerHTML = '&nbsp;';
          map.getTarget().style.cursor = '';
        }
      };

      map.on('pointermove', function(evt) {
        if (evt.dragging) {
          return;
        }
        var pixel = map.getEventPixel(evt.originalEvent);
        displayFeatureInfo(pixel);
      });

      map.on('click', function(evt) {
        displayFeatureInfo(evt.pixel);
      });
    </script>
  </body>
</html>