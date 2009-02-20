(function() {

    stoyanSiteSearch = function(json) {
        var i = 0, a, cite, li, activated = false,
            ol = document.createElement('ol'),
            results = json.ysearchresponse.resultset_web,
            dom = YAHOO.util.Dom;
        if (typeof results !== 'undefined' &&
                typeof results.length === 'number') {
            for (; i <  results.length; i++) {
                li = document.createElement('li');
                a  = document.createElement('a');
                a.href = results[i].clickurl;
                a.innerHTML = results[i].title;
                cite = document.createElement('cite');
                cite.innerHTML = results[i].abstract;
                li.appendChild(a);
                li.appendChild(cite);
                ol.appendChild(li);

                if (!activated) {
                    dom.addClass(li, 'sss-active');
                    activated = true;
                }

            }
        }
        var resdiv = dom.get('sss-result');
        resdiv.innerHTML = '';
        resdiv.appendChild(ol);
    };

 
    var url = 'http://boss.yahooapis.com/ysearch/web/v1/';
    var params = [];
    params.push('format=json');
    params.push('callback=stoyanSiteSearch');
    params.push('appid=bUUrTUrV34Ft7S4N.8b1C9hoQ7p7XnpGKfGlac4FhDtbNdEDIZzNVp.s64f_A7g-');
    params.push('sites=' + document.domain);
    params = params.join('&');

    var start = function(){
  
        var dom = YAHOO.util.Dom;
        var ev  = YAHOO.util.Event;

        var last_q = '';    
        var d = document.createElement('div');
        d.id = 'sss-container';
        d.innerHTML = '&nbsp;&nbsp; Site Search: ';
        
        var i = document.createElement('input');
        i.id = 'sss-input';
        i.onkeyup = function(e) {
            var ws, q = encodeURI(dom.get('sss-input').value);
            if (q === '') {
                dom.get('sss-result').innerHTML = '';
                return;
            }
            if (q === last_q) {
                return;
            }
            last_q = q;
            ws = url + q + '?' + params;
            YAHOO.util.Get.script(ws);
        };
        d.appendChild(i);
        var r = document.createElement('div');
        r.id = 'sss-result';
        d.appendChild(r);
        document.body.appendChild(d);

        d = dom.get('sss-container');
        dom.setStyle(d, 'left', dom.getDocumentWidth() - 330 + 'px');
    
        i = dom.get('sss-input');
        i.focus();

    
        ev.addListener(d, 'keydown', function(e) {
            var char = ev.getCharCode(e);
            if (char === 13 || char === 40 || char === 38) { // enter, down or up
                var active = dom.getElementsByClassName('sss-active', 'li');
                if (typeof active.length === 'undefined') {
                    return;
                }
                active = active[0];
                if (char === 13) {
                    top.location = active.getElementsByTagName('a')[0].href;
                    return;
                }

                var next = (char === 40) ? dom.getNextSibling(active) : dom.getPreviousSibling(active);
                if (next) {
                    dom.removeClass(active, 'sss-active');
                    dom.addClass(next, 'sss-active');
                }
            }
        });


    };

    var script = document.createElement('script');
    script.src = 'http://yui.yahooapis.com/combo?2.7.0/build/yuiloader-dom-event/yuiloader-dom-event.js';

    if(script.addEventListener){
        script.addEventListener("load", start, false);
    } else{
        script.onreadystatechange = function(){
            if(this.readyState=="complete"){
                start();
                script = null;
            }
        };
    }
    script.type="text/javascript";
    document.getElementsByTagName('head')[0].appendChild(script);

    var def = '.sss-active, #sss-result li:hover {background: blue; }';
    def+= '#sss-container{'+ 
           'top:0; position:absolute;'+
           'width:300px;background:blue;'+ 
           'color: white; padding-top: 5px; z-index:999;'+ 
           'text-align: left; font-size: 12px;' +
           'font-family: Helvetica, Arial, sans-serif;'+ 
        '}';
    def+= '#sss-result {' +
          'background: #eee; color: #222;'+
        '}';
    def+= '#sss-input {'+
          'margin: 5px;'+
        '}';
    def+= '#sss-result ol {margin: 0;padding:5px 0 0 0; list-style: none;}';
    def+= '#sss-result ol li {list-style: none; padding: 5px 10px;}';
    def+= '#sss-result a{color:blue;}';
    def+= '#sss-result a:hover{background:blue;clor: white;}';
    def+= '#sss-result .sss-active, #sss-result li:hover {color:#ddd;}';
    def+= '#sss-result .sss-active a, #sss-result li:hover a {color:white;}';
    def+= '#sss-result cite {text-decoration: none; padding-top: 3px; display: block;font-style: normal;}';

    var ss1 = document.createElement('style');
    ss1.setAttribute("type", "text/css");
    if (ss1.styleSheet) {   // IE
        ss1.styleSheet.cssText = def;
    } else {                // the world
        ss1.appendChild(document.createTextNode(def));
    }
    var hh1 = document.getElementsByTagName('head')[0];
    hh1.appendChild(ss1);

})();
