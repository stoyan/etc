// Before we begin...

// stdout helper
if (typeof print !== "function") {
  var print = function(what) {
    WScript.Echo(what);
  };
}
// include other scripts
function require(filename) {
  var fs = new ActiveXObject("Scripting.FileSystemObject"),
      txt = fs.OpenTextFile(filename, 1),
      code = txt.ReadAll();
  txt.Close();
  return code;
}

eval(require('httpwatch.js'));


var args = WScript.Arguments,
    example = 1;

if (args.length > 0) {
  example = parseInt(args.Item(0), 10);
}

switch (example) {
case 1:

  var http = new HTTPWatch();
  http.go('http://search.yahoo.com');
  var har = eval('(' + http.toHAR() + ')');
  print(har.log.browser.name + ' ' + har.log.browser.version);
  print('# requests: ' + har.log.entries.length);
  http.done();

  var http = new HTTPWatch('ff');
  http.go('http://search.yahoo.com');
  var har = eval('(' + http.toHAR() + ')');
  print(har.log.browser.name + ' ' + har.log.browser.version);
  print('# requests: ' + har.log.entries.length);
  http.done();

break;
case 2:

  var http = new HTTPWatch();
  http.go('http://google.com');
  http.toHAR();
  var comps = http.getComponentsByType();
  for (var i in comps) {
    print(i + ': ' + comps[i].length);
  }
  http.done();

break;
case 3:
  var http = new HTTPWatch();
  http.go('search.yahoo.com');
  var document = http.watch.container.document;
  print(document.getElementsByTagName('*').length);
  print(document.documentElement.innerHTML);
  http.done();

break;
case 4:

  eval(require('statz.js'));

  var http = new HTTPWatch();
  http.go('www.google.com');
  http.toHAR();

  var document = http.watch.container.document;
  var html = http.har.log.entries[0].response.content.text;
  var out = statz(document, html);
  print(out.join("\n"));

  http.done();



break;
default:

  print("Gimme an example number 1-4, e.g. $cscript example.js 2");

} // switch ends




/*
Example run:

C:\jigits\etc\HTTPWA~1>cscript examples.js 1
Microsoft (R) Windows Script Host Version 5.7
Copyright (C) Microsoft Corporation. All rights reserved.

Internet Explorer 6.0.2900.5512
# requests: 10
Firefox 3.5.6
# requests: 15

C:\jigits\etc\HTTPWA~1>cscript //nologo examples.js 2
redirect: 1
text/html: 3
image/gif: 4
image/png: 3
text/javascript: 1

C:\jigits\etc\HTTPWA~1>cscript //nologo examples.js 4
JS attributes (e.g. onclick): 1207 bytes
CSS style attributes: 883
Inline JS: 5243
Inline CSS: 5015
All innerHTML: 17283
# DOM elements: 134
Total size: 14124 bytes
Content size: 401 bytes
Content-to-markup ratio: 0.03
Fair ratio * : 0.04
--
"fair" ratio counts text in these attributes:
title, alt, value
as content and not as markup.
*/