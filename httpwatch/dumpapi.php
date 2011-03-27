<?php
/**
 * This script dumps HTTPWatch's API config from some pre-configs
 *
 * The pre-configs are actually just example objects, which we
 * cn reflect on and extract property names.
 * Whatever this script prints, write it to a file, then make sure
 * HTTPWatch::api static property points to this file.
 * The default filename is HTTPWatchAPI.php and placed
 * in the HTTPWatch's directory.
 *
 * So use like:
 * $ php dumpapi.php > HTTPWatchAPI.php
 */

include "HTTPWatch.php";

// output - JS or PHP
$output = 'php';
if (!empty($argv[1])) {
  $jsmaybe = trim($argv[1], '-');
  if ($jsmaybe === 'js') {
    $output = 'js';
  }
}

function dumpAPI($classes, $ovals, $cvals) {
  ob_start();
  foreach($classes as $classname => $obj) {
    // dumps a whole class declaration
    com_print_typeinfo($obj);
  }
  $typeinfo = ob_get_contents();
  ob_end_clean();
  // evaluate all dumped classes
  eval($typeinfo);

  $properties = array();
  foreach($classes as $classname => $obj) {

    $value = 1;
    if (!empty($cvals[$classname])) {
      // class overwites - when all properties of an object
      // are of the same class, e.g. Timings
      $value = $cvals[$classname];
    }
    $props = array_fill_keys(
      array_keys(get_class_vars($classname)),
      $value
    );

    // object overwrites, e.g. CacheAfter is of class CacheInfo
    foreach ($ovals as $key => $overwrite) {
      if (isset($props[$key])) {
        $props[$key] = $overwrite;
      }
    }

    // property is another object of known class
    // e.g. Request => Request
    foreach ($classes as $clname => $oo) {
      if (isset($props[$clname])) {
        $props[$clname] = $clname;
      }
    }

    $properties[$classname] = $props;

  }

  return $properties;

}

HTTPWatch::$apipath = 'whatevers';
$http = new HTTPWatch();
$http->go('http://google.com/search?q=stoyan');

// some vars for short
$plug =& $http->watch;
$item = $plug->Log->Entries->Item(0);
$summary = $plug->Log->Entries->Summary;

// class names and example objects for each
// so we can introspect the objects and derive
// properties from them
$classes = array(
  'Entry'    => $item,
  'Content'  => $item->Content,
  'Summary'  => $summary,
  'CacheInfo'=> $item->CacheBefore,
  'Request'  => $item->Request,
  //'POSTParameter'  => $item->Request->POSTParameters(0),
  'Response' => $item->Response,
  'Timings'  => $item->Timings,
  'Timing'   => $item->Timings->Blocked,
  'Cookie'   => $item->Request->Cookies->Item(0),
  'Warning'  => $plug->Log->Entries->Item(3)->Warnings(0),
  'WarningSummary'   => $summary->WarningSummaries(0),
  'ResultSummary'    => $summary->StatusCodes(0),
  'TimingSummaries'  => $summary->TimingSummaries,
  'TimingSummary'    => $summary->TimingSummaries->Blocked,
  'Header'           => $item->Request->Headers->Item(0),
  'QueryStringValue' => $item->Request->QueryStringValues->Item(0),
);

// exceptions:
// + members that are lists
// + members that are objects of a class not matching their name
// + unsupported members
$object_values = array(
  // lists
  'Cookies' => array('Cookie'),
  'Entries' => array('Entry'),
  'Headers' => array('Header'),
  'Pages'   => array('Page'),
  'PageEvents'       => array('PageEvent'),
  'POSTParameters'   => array('POSTParameter'),
  'QueryStringValues'=> array('QueryStringValue'),
  'ResultSummaries'  => array('ResultSummary'),
  'Warnings'         => array('Warning'),
  'WarningSummaries' => array('WarningSummary'),
  // strangely named
  'CacheBefore' => 'CacheInfo',
  'CacheAfter'  => 'CacheInfo',
  'Errors'      => array('ResultSummary'),
  'StatusCodes' => array('ResultSummary'),
  'Events'      => 'PageEvents',
  // unsupported
  'Page'        => false,
);

// list-like members that are actually not lists
$class_values = array(
  'Timings' => 'Timing', // Timings is not a list, but hashy
  'TimingSummaries' => 'TimingSummary'
);

$api = dumpAPI($classes, $object_values, $class_values);
$http->done();

sleep(2);

// Test for properties that are only in the paid version
// except for some top sites and their URLs and CDNs
$paidproperties = array();
$http = new HTTPWatch();
$http->go('http://www.phpied.com/images/underline.gif');

// some vars for short
$plug =& $http->watch;
$entry = $http->watch->Log->Entries->Item(0);
$summary = $http->watch->Log->Entries->Summary;
$these = array(
  'Entry' => $entry,
  'Summary' => $summary
);

foreach ($these as $class => $obj) {
  foreach ($api[$class] as $prop => $ignore) {
    try {
      $foo = $obj->$prop;
    } catch (Exception $e) {
      $paidproperties[$prop] = 1;
    }
  }
}


$http->done();

if ($output === 'js') {
  echo "var httpwatchapi = {};";
  echo "\nhttpwatchapi.api = " . json_encode($api);
  echo "\nhttpwatchapi.paidproperties = " . json_encode($paidproperties);
  die();
}
echo '<?php $api = ';
var_export($api);
echo ';';

echo "\n", '$paidproperties = ';
var_export($paidproperties);
echo ';?>';

?>