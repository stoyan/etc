(function(){

    // regexps for script and tags taken from http://www.prototypejs.org/api/string/
    var re_script = /<script[^>]*>([\u0001-\uFFFF]*?)<\/script>/gmi,
        re_style =/<style[^>]*>([\u0001-\uFFFF]*?)<\/style>/mgi,
        re_tags =   /<\/?[^>]+>/gi,
        markup = '',
        attribs = ['title', 'alt', 'value'], // content attribs 
        x = new XMLHttpRequest();

    // get a fresh copy of the markup from the server
    // because it might have been tampered with by js
    x.open('GET', location.href, true);
    x.onreadystatechange = function() {
        if (x.readyState === 4) {
            markup = x.responseText;
            calc(markup);
        }
    };
    x.send(null);

    function calc(markup) {
        var attrsize = getAttribsSize(),
            msg = [],
            content = '';

        content = markup.replace(re_script, '').replace(re_style, '');
        content = content.replace(re_tags, '');

        var ml = markup.length,
            cl = content.length,
            ratio = (cl / ml).toFixed(2),
            fair = ((cl + attrsize) / (ml - attrsize)).toFixed(2);      

        msg.push('Total size: ' + ml + ' bytes');
        msg.push('Content size: ' + cl + ' bytes');
        msg.push('Content-to-markup ratio: ' + ratio);
        msg.push('Fair ratio * : ' + fair);
        msg.push('');
        msg.push('"fair" ratio counts these attributes:');
        msg.push(attribs.join(', '));
        msg.push('as content and not markup.');
        alert(msg.join('\n'));
    }
    
    // the bytes used by "content" attributes
    // it's nice to promote, not penalize for title and alt tags
    function getAttribsSize() {
        var i = 0,
            j = 0,        
            cnt = 0,
            value = '',
            attr = '',
            els = document.getElementsByTagName('*');
        for (i = 0; i < els.length; i++) {
            for (j = 0; j < attribs.length; j++) {
                attr = attribs[j];
                value = els[i][attr];
                if (value && typeof value === 'string') {
                    cnt += attr.length;
                    cnt += 3; // ="..."
                    cnt += value.length;
                } 
            }

        }
        return cnt; 
    }

})();