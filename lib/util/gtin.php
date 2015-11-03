<?php


function  gtin2upc ($gtin) {

  if (!$gtin)
    return  null;

  $upc = ltrim ($gtin, '0');

  if (strlen ($upc > 12))
    return  null;
    
  return  substr ($gtin, 2);
}


function  gtin2ean ($gtin) {

  $ean = ltrim ($gtin, '0');
  
  if (strlen ($ean) != 13)
    return  null;
    
  return  $ean;
}


function  gtin2gtin ($gtin) {

  $gtin = ltrim ($gtin, '0');
  
  if (strlen ($gtin) != 14)
    return  null;
    
  return  $gtin;
}


?>