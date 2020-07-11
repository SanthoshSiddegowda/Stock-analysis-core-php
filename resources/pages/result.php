<?php

include 'controllers/ResultController.php';


$request = (object)$_POST;

$result = new ResultController();

#setting Inputs
$result->setInputs($request);


#storing csv and getting file path
$tmpName = $_FILES['file']['tmp_name'];
$csvAsArray = array_map('str_getcsv', file($tmpName));
$result->storeCSV($csvAsArray);

#filtering values
$res = $result->filterByStockName();

$calc = $result->calcValues();

echo "<pre>";
print_r($calc);
print_r($res);
echo "</pre>";
