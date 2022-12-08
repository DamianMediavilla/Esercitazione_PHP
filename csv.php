<?php

$file = "datosdescargables.csv";
$name = "dati_scaricati.csv";

header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename='. $name);
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file));
header("Content-Type: text/csv");
readfile($file);

?>