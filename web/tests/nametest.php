<?php 

//namespace fub;
ini_set('display_errors', 'On');
ini_set ( 'error_reporting', E_ALL );
error_reporting(E_ALL);


echo "started...";


include 'file1.php';
use foo as feline;


echo \feline\Cat::says();//, "<br />\n";



include 'file2.php';
include 'file3.php';

use bar as canine;
use animate;

echo Cat::says(), "<br />\n";
echo \canine\Dog::says(), "<br />\n";
echo \animate\Animal::breathes(), "<br />\n";

?>