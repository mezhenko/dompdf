<?php

// this is the simplest demo render script

require_once("../dompdf_config.inc.php");

// We check wether the user is accessing the demo locally
$local = array("::1", "127.0.0.1");
$is_local = in_array($_SERVER['REMOTE_ADDR'], $local);


if ( isset( $_POST["html"] ) && $is_local ) {

    $html = get_magic_quotes_gpc()?
        stripslashes($_POST["html"])
        :$_POST["html"];

    $paper = isset($_POST["paper"])?
        $_POST["paper"]
        :'a4';

    $orientation = isset($_POST["orientation"])?
        $_POST["orientation"]
        :'portrait';

    $dompdf = new DOMPDF();
    $dompdf->load_html($html);
    $dompdf->set_paper($paper, $orientation);
    $dompdf->render();

    $dompdf->stream("dompdf_out.pdf", array("Attachment" => !isset( $_POST["preview"])));

    exit(0);
}