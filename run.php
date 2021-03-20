<?php

/*
 * ceaser (done)
 * polyalphapet (done)
 * vegerin (done)
 * one time pad (done)
 * transposition (done)
 * play fair (done)
 * affine التلات
 * rsa الاتنين
 * diff helman الاتنين
 * hill cipher الاحد
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

################ try one time pad algorithm ######


//$oneTimePad = new \Elsheref\Security\Algorithms\OneTimePad('0l;@');
//$encryptedMessage = $oneTimePad->encrypt('Hi!');
////echo $encryptedMessage . PHP_EOL;
//
//echo implode('',array_map(function ($item){
//    return chr($item);
//},explode(' ' , $encryptedMessage))) . PHP_EOL;
//
//$decryptedMessage = $oneTimePad->decrypt(implode('',array_map(function ($item){
//        return chr($item);
//},explode(' ' , $encryptedMessage)))) . PHP_EOL;
//
//
//echo implode('',array_map(function ($item){
//        return chr($item);
//},explode(' ' , $decryptedMessage))) . PHP_EOL;

################ try transposition algorithm ######

//$transposition = new \Elsheref\Security\Algorithms\Transposition('securit');
//echo $transposition->encrypt('we need more snow now') . PHP_EOL;
//echo $transposition->decrypt('NEWERODOCENBWONMWDESA') . PHP_EOL;

################ try play fair algorithm ######
//$playFair = new \Elsheref\Security\Algorithms\PlayFair('MONARCHY');
//$text = 'BALLOON';
//echo 'PLAY FAIR '.PHP_EOL;
//echo 'ENCRYPT OF BALLOON = '.$playFair->encrypt($text) . PHP_EOL;
//echo 'DECTYPT OF IBSUPMNA = '.$playFair->decrypt('IBSUPMNA') . PHP_EOL;