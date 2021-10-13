<?php

use \Smalot\PdfParser;

/**
 * Exemple extract text from PDF document
 */
//get root path
$rootDir = realpath(dirname( __FILE__)."/../../../");

// Include Composer autoloader if not already done.
//require $rootDir . "/vendor/autoload.php";

// Parse pdf file and build necessary objects.
$parser = new Parser();
$pdf    = $parser->parseFile($rootDir."/res/pdf/document.pdf");

$text = $pdf->getText();

echo $text;
echo PHP_EOL;
echo PHP_EOL;
