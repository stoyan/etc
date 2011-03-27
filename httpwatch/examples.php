<?php
require 'HTTPWatch.php';



//$http = new HTTPWatch();
//$http->go('http://phpied.com/sdf');

/*
// 1.
$sum = $http->watch->Log->Entries->Summary;
echo "in: {$sum->BytesReceived}, out: {$sum->BytesSent}\n";

// 1.a.
$http->watch->Clear();
$http->go('http://google.com');
$sum = $http->watch->Log->Entries->Summary;
echo "in: {$sum->BytesReceived}, out: {$sum->BytesSent}";
*/


// 2.
//$http->skipStreams = false;
//$entries = $http->getEntries();
//print_r($entries);

// 3.
// echo print_r(json_decode($http->toHAR()));


// 4.

$ie = new HTTPWatch();
$ie->go('http://google.com/');
$sum = $ie->getSummary();
$ff = new HTTPWatch('ff');
$ff->go('http://google.com/');
$sumff = $ff->getSummary();

echo "\nRun 1 ";
echo $ie->watch->Log->BrowserName, ' ';
echo $ie->watch->Log->BrowserVersion;
echo "\nSent: ", $sum['BytesSent'], "; Received: ", $sum['BytesReceived'];

echo "\nRun 2 ";
echo $ff->watch->Log->BrowserName, ' ';
echo $ff->watch->Log->BrowserVersion;
echo "\nSent: ", $sumff['BytesSent'], "; Received: ", $sumff['BytesReceived'];

$ie->done();
$ff->done();


/*
$http = new HTTPWatch();
$http->go("http://givepngachance.com");
print_r($http->getEntries());
$http->done();
*/
