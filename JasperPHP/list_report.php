<?php
require_once "jasper2php.php";

$jasperphp = new jasper2php();
$jasperphp->jasperfile='/reports/societe/list_report.jasper'; //Path where the .jasper file stay
$jasperphp->outputpath='/reports/societe/'; //path to output the pdf file. The "web user" need to have write access
$jasperphp->libpath='./'; //path where the lib resides
$jasperphp->user="jesse.rovira"; //user whom is creating the report, can be the logged user in the application
$jasperphp->filename="auto"; //you can name the file specifically, or let the lib do it
$jasperphp->addParameter("respath", './', $jasperphp::TYPE_STRING); //The path to read subreports, images and other assets inside report. You can change, but remember to use the same parameter inside report
$jasperphp->addParameter("teste", "344455", $jasperphp::TYPE_NUMBER); //any other parameter you need!
$output = trim($jasperphp->runReport()); //get the bytes of PDF
pdf_printOnBrowser($output); //some library to open the PDF in Browser
?>