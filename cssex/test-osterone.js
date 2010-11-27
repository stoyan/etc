/*global CSSEX: true*/
load('sex.js');

if (!arguments[0]) {
    print('usage:\n $ jsc test-osterone.js -- "`cat some.css`"');
    quit();
}

var source = arguments[0],
    tokens = CSSEX.lex(source),
    srcout = CSSEX.toSource(tokens);
    
source = source.replace(/\r\n/g, '\n')
               .replace(/\r/g, '\n');
                   
if (srcout !== source) {
    print('*** ZOMG! Massive FALE!' + (arguments[1] ? ' Offender: ' + arguments[1] : ''));
} else {
    print('OK' + (arguments[1] ? ' - ' + arguments[1] : ''));
}
    



