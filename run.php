<?php

/*
 * ceaser (done)
 * polyalphapet (done)
 * vegerin (done)
 * one time pad (done)
 * play fair
 * affine
 * rsa
 * diff helman
 * hill cipher
 * transposition
 * compination between transposition and susbstitution
 * */


// require the composer autoloader file to resolve the namespaces of classes
require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

################## try caesar algorithm ########################
//$caesar = new \Elsheref\Security\Algorithms\Caesar(4 , false , true);
//echo $caesar->encrypt('hatem mohamed elsheref').PHP_EOL;
//echo $caesar->decrypt('lexiqdqsleqihdipwlivij').PHP_EOL;

################ try polyalphabetic substitution algorithm ######
//$plain = 'hatem mohamed elsheref';
//$keyword = 'this is my key';
//$polyalphabetic = new PolyalphabeticSubstitution($keyword,true,true);
//echo $polyalphabetic->encrypt($plain).PHP_EOL;
//echo $polyalphabetic->decrypt('aHbWmieoTYmOHyXSaZeZWf');

################ try polyalphabetic substitution algorithm ######
//$fullVigirine = new \Elsheref\Security\Algorithms\FullViginer('df');
//echo $fullVigirine->encrypt('beda');
//echo $fullVigirine->decrypt('EJGF');