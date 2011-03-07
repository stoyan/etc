<?php $api = array (
  'Entry' => 
  array (
    'URL' => 1,
    'Method' => 1,
    'Started' => 1,
    'StartedSecs' => 1,
    'StartedDateTime' => 1,
    'Time' => 1,
    'Result' => 1,
    'CacheBefore' => 'CacheInfo',
    'CacheAfter' => 'CacheInfo',
    'ServerIP' => 1,
    'ServerPort' => 1,
    'ClientIP' => 1,
    'ClientPort' => 1,
    'Request' => 'Request',
    'Response' => 'Response',
    'Content' => 'Content',
    'IsRestrictedURL' => 1,
    'BytesSent' => 1,
    'BytesReceived' => 1,
    'IsComplete' => 1,
    'StatusCode' => 1,
    'Error' => 1,
    'IsRedirect' => 1,
    'RedirectURL' => 1,
    'Page' => false,
    'Timings' => 'Timings',
    'Warnings' => 
    array (
      0 => 'Warning',
    ),
  ),
  'Content' => 
  array (
    'MimeType' => 1,
    'Size' => 1,
    'IsFromCache' => 1,
    'IsCompressed' => 1,
    'CompressedSize' => 1,
    'CompressionType' => 1,
    'Data' => 1,
    'IsImage' => 1,
    'ImageWidth' => 1,
    'ImageHeight' => 1,
  ),
  'Summary' => 
  array (
    'Time' => 1,
    'RoundTrips' => 1,
    'BytesSent' => 1,
    'BytesReceived' => 1,
    'CompressionSavedBytes' => 1,
    'DNSLookUps' => 1,
    'TCPConnects' => 1,
    'TotalHTTPSOverhead' => 1,
    'AverageHTTPSOverhead' => 1,
    'StatusCodes' => 
    array (
      0 => 'ResultSummary',
    ),
    'Errors' => 
    array (
      0 => 'ResultSummary',
    ),
    'TimingSummaries' => 'TimingSummaries',
    'WarningSummaries' => 
    array (
      0 => 'WarningSummary',
    ),
  ),
  'CacheInfo' => 
  array (
    'URLInCache' => 1,
    'Expires' => 1,
    'IsExpiresSet' => 1,
    'LastUpdate' => 1,
    'LastAccess' => 1,
    'LastModified' => 1,
    'IsLastModifiedSet' => 1,
    'ETag' => 1,
    'HitCount' => 1,
  ),
  'Request' => 
  array (
    'RequestLine' => 1,
    'Cookies' => 
    array (
      0 => 'Cookie',
    ),
    'Headers' => 
    array (
      0 => 'Header',
    ),
    'POSTParameters' => 
    array (
      0 => 'POSTParameter',
    ),
    'POSTMimeType' => 1,
    'QueryStringValues' => 
    array (
      0 => 'QueryStringValue',
    ),
    'Stream' => 1,
  ),
  'Response' => 
  array (
    'StatusLine' => 1,
    'Cookies' => 
    array (
      0 => 'Cookie',
    ),
    'Headers' => 
    array (
      0 => 'Header',
    ),
    'Stream' => 1,
    'Chunks' => 1,
  ),
  'Timings' => 
  array (
    'Blocked' => 'Timing',
    'DNSLookup' => 'Timing',
    'Connect' => 'Timing',
    'Send' => 'Timing',
    'Wait' => 'Timing',
    'Receive' => 'Timing',
    'TTFB' => 'Timing',
    'Network' => 'Timing',
    'CacheRead' => 'Timing',
  ),
  'Timing' => 
  array (
    'Valid' => 1,
    'Started' => 1,
    'Duration' => 1,
  ),
  'Cookie' => 
  array (
    'Name' => 1,
    'Value' => 1,
    'Domain' => 1,
    'Path' => 1,
    'Expires' => 1,
    'IsSessionCookie' => 1,
    'Source' => 1,
    'IsHttpOnly' => 1,
    'IsHttpOnlyKnown' => 1,
    'IsSecure' => 1,
    'IsSecureKnown' => 1,
  ),
  'Warning' => 
  array (
    'Code' => 1,
    'ID' => 1,
    'Type' => 1,
    'Description' => 1,
  ),
  'WarningSummary' => 
  array (
    'Code' => 1,
    'ID' => 1,
    'Type' => 1,
    'Description' => 1,
    'Occurrences' => 1,
  ),
  'ResultSummary' => 
  array (
    'Result' => 1,
    'Description' => 1,
    'Occurrences' => 1,
  ),
  'TimingSummaries' => 
  array (
    'Blocked' => 'TimingSummary',
    'DNSLookup' => 'TimingSummary',
    'Connect' => 'TimingSummary',
    'Send' => 'TimingSummary',
    'Wait' => 'TimingSummary',
    'Receive' => 'TimingSummary',
    'TTFB' => 'TimingSummary',
    'Network' => 'TimingSummary',
    'CacheRead' => 'TimingSummary',
  ),
  'TimingSummary' => 
  array (
    'Minimum' => 1,
    'Maximum' => 1,
    'Total' => 1,
    'Average' => 1,
    'Occurrences' => 1,
  ),
  'Header' => 
  array (
    'Name' => 1,
    'Value' => 1,
  ),
  'QueryStringValue' => 
  array (
    'Name' => 1,
    'Value' => 1,
  ),
);
$paidproperties = array (
  'CacheBefore' => 1,
  'CacheAfter' => 1,
  'ServerIP' => 1,
  'ServerPort' => 1,
  'ClientIP' => 1,
  'ClientPort' => 1,
  'Request' => 1,
  'Response' => 1,
  'Content' => 1,
  'BytesSent' => 1,
  'Timings' => 1,
  'Warnings' => 1,
  'TimingSummaries' => 1,
  'WarningSummaries' => 1,
);?>