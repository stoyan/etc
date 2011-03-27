function statz(doc, markup) {
    var jsattribs = [
            "onmouseover",
            "onmouseout",
            "onmousedown",
            "onmouseup",
            "onclick",
            "ondblclick",
            "onmousemove",
            "onload",
            "onerror",
            "onunload",
            "onbeforeunload",
            "onsubmit"
    ],
    cssattribs = ["style"];

    function getInlineSize(tag) {

        var s = 0, all = doc.getElementsByTagName(tag);
        for (var i = 0; i < all.length; i++) {
            s += all[i].innerHTML.length;
        }
        return s;
    }

    function getAttribsSize(attribs) {
        var i = 0,
            j = 0,
            cnt = 0,
            value = '',
            attr = '',
            els = doc.getElementsByTagName('*');
        for (i = 0; i < els.length; i++) {
            for (j = 0; j < attribs.length; j++) {
                attr = attribs[j];
                value = els[i][attr];
                if (attr === "style") {
                  value = value.cssText;
                }
                if (value) {
                    cnt += attr.length;
                    cnt += 3; // ="..."
                    cnt += value.toString().length;
                }
            }

        }
        return cnt;
    }

    var jsatt = getAttribsSize(jsattribs);
    var cssatt = getAttribsSize(cssattribs);



    var msg = [];
    msg.push('JS attributes (e.g. onclick): ' + jsatt + ' bytes');
    msg.push('CSS style attributes: ' + cssatt);
    msg.push('Inline JS: ' + getInlineSize('script'));
    msg.push('Inline CSS: ' + getInlineSize('style'));
    msg.push('All innerHTML: ' + doc.documentElement.innerHTML.length);
    msg.push('# DOM elements: ' + doc.getElementsByTagName('*').length);


    function content2markup(markup, msg) {
        // regexps for script and tags taken from http://www.prototypejs.org/api/string/
        var re_script = /<script[^>]*>([\u0001-\uFFFF]*?)<\/script>/gmi,
            re_style =/<style[^>]*>([\u0001-\uFFFF]*?)<\/style>/mgi,
            re_tags =   /<\/?[^>]+>/gi,
            attribs = ['title', 'alt', 'value'], // content attribs
            attrsize = getAttribsSize(attribs),
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
        msg.push('--');
        msg.push('"fair" ratio counts text in these attributes:');
        msg.push(attribs.join(', '));
        msg.push('as content and not as markup.');
        return msg;
    }

    content2markup(markup, msg);

    return msg;

}


