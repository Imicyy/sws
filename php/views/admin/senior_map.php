<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="icon" type="image/png" href="<?= htmlspecialchars(asset_url('images/SilayLogo.png'), ENT_QUOTES) ?>">
  <title>Senior Map</title>
  
  <!-- ArcGIS CSS -->
  <link rel="stylesheet" href="https://js.arcgis.com/4.29/esri/themes/light/main.css"/>
  <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('css/light-theme.css?v=20260424'), ENT_QUOTES) ?>">
  
  <style>
    html, body, #viewDiv {
      height: 100%;
      margin: 0;
      padding: 0;
    }
  </style>
</head>
<body>
  <div id="viewDiv"></div>

  <!-- ArcGIS JS API -->
  <script src="https://js.arcgis.com/4.29/"></script>

  <!-- Load your ArcGIS.js file through ArcGIS AMD loader -->
  <script>
    require(["/assets/js/mapsenior.js"]);
  </script>
</body>
</html>