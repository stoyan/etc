var jstransform = require('jstransform');
var fs = require('fs');

var visitors = [];
[
  require('jstransform/visitors/es6-arrow-function-visitors'),
  require('jstransform/visitors/es6-class-visitors'),
  require('jstransform/visitors/es6-object-short-notation-visitors'),
  require('jstransform/visitors/es6-rest-param-visitors'),
  require('jstransform/visitors/es6-template-visitors')
].forEach(function(visitor) {
  visitors = visitors.concat(visitor.visitorList);
});

var es6maybe = fs.readFileSync(process.argv[2], 'utf8');
var es5 = jstransform.transform(visitors, es6maybe);

console.log(es5.code);

