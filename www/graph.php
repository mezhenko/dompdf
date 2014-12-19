<?php

use Personado\Graph_Render;

  require_once "../dompdf_config.inc.php";

  imagepng(Graph_Render::drawBar(new \Personado\Graph_Options(
    json_decode('
      [{"label":"36-45 Years","value":0.1795},{"label":"26-36 Years","value":0.1474},{"label":"46-55 Years","value":0.1474},{"label":"13-18 Years (Teenagesadsss ssr)","value":0.1218},{"label":"19-25 Years","value":0.4038}]
    '),"Age Group",1800,1400)
  ));