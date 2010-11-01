<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<title>chunkview</title>
    <style type="text/css">
        dd {display: none;}
        dt {cursor: pointer; text-decoration: underline; display: inline; margin-right: 5px; padding: 5px; border: #ddd 1px solid;}
        dt:hover {background: #ccc;}
        .active {background: #eee;}
        li {padding: 15px 0 0 0}
        iframe {width: 100%; height: 400px; position: relative; left: -50px;}
        #q {width: 100%}
    </style>
</head>
<body>

    <form action="chunkview.php" methpd="get">
    URL:
    <input id="q" name="q" value="<?php echo @htmlentities($_GET['q'])?>">
    <br />
    <input type="checkbox" name="gzip" value="yep" <?php if ($_GET['gzip']) echo "checked"; ?>>Send header <code>Accept-Encoding: gzip,deflate</code>
    <input type="submit" name="go" />
    </form>

<?php

error_reporting(E_NONE);
if (empty($_GET['q'])) die('need a URL');
$scheme = parse_url($_GET['q'], PHP_URL_SCHEME);
if ($scheme !== "http" && $scheme !== "https"){
    die('I need a URL'); // thanks Billy Hoffman
}

$header = array(
    "User-Agent: Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9.1) Gecko/20090624 Firefox/3.5",
    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
    "Accept-Language: en-us,en;q=0.5",
    "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7",
    "Keep-Alive: 300",
    "Connection: keep-alive",

);
if ($_GET['gzip']) {
    $header[] = "Accept-Encoding: gzip,deflate";
}
$opts = array( 'http' => array (
        'method'=>'GET',
        'protocol_version' => '1.1',
        'header'=> implode("\r\n", $header)
        )
    );

// get the stream
$ctx = stream_context_create($opts);
$fp = fopen($_GET['q'],'rb',false,$ctx);
$r = stream_get_contents($fp);
fclose($fp);

$chunk_info = array();
$r = explode("\r\n", $r);
$len = 0; $i = 0; $cat = 'cat '; $sofarlen = 0;
@mkdir('/tmp/chunkr');
chdir('/tmp/chunkr');

foreach($r as $key => $chunk) {

    if (trim($chunk) == '') continue;
    
    if ($key % 2 === 0) {

        $chunk_info[$i] = array(
            'raw'      => '',
            'plain'    => '',
            'rawsize'  => '',
            'bytesize' => ''
        );

        $chunk = trim($chunk);
        $len = hexdec($chunk);
            
        $chunk_info[$i]['rawsize'] = $chunk;
        $chunk_info[$i]['bytesize'] = $len;
    
        

    } else {
                
        $cat .= $i . ' ';
        
        if ($_GET['gzip']) {

            $chunk = trim($chunk, "\r\n");
            file_put_contents('/tmp/chunkr/' . $i, $chunk);


            $cmd = "$cat | gzip -d -c -q";
            $out = array();
            exec($cmd, $out);
            
            $out = implode('', $out);
            $this_chunk = substr($out, $sofarlen);
            $sofarlen += strlen($this_chunk);

            $chunk_info[$i]['raw'] = $chunk;
            $chunk_info[$i]['plain'] = $this_chunk;
            
        } else {
            $chunk_info[$i]['plain'] = $chunk;
        }
        $i++;

    }

}


// presentation
echo '<ol id="results">';
foreach($chunk_info as $k => $v) {
    echo "<li>chunk size: " . $v['rawsize'] . " (" . $v['bytesize'] . " bytes)";
    
    if ($v['bytesize'] > 0) {
    
        echo "<div>";
        echo  "<dl>";
        if ($v['raw']) {
            echo   '<dt>raw</dt><dd id="raw-' . $k . '">' . htmlentities($v['raw']) . '</dd>';
        }
        echo   '<dt>plain</dt><dd id="plain-' . $k . '">' . htmlentities($v['plain']) . '</dd>';
        echo   '<dt>html</dt><dd id="html-' . $k . '"><iframe src="about:blank" name="frame-'. $k .'"></iframe></dd>';
        echo   '<dt id="new-'. $k .'">in a new window</dt></dt>';
        echo  "</dl>";
        echo "</div>";
    
    }
    
    echo "</li>";
}
echo '</ol>';

?>

<script>
var data = <?php echo json_encode($chunk_info); ?>;

document.getElementById('results').onclick = function(e) {
    e = e || window.event;
    var target = e.target || e.srcElement;
    
    if (target.nodeName.toLowerCase() !== 'dt') {
        return;
    }
    
    target.className = target.className === 'active' ? '' : 'active';
    
    var dd = target.nextSibling;
    if (dd) {
        dd.style.display = (dd.style.display === 'block') ? 'none' : 'block';
    }
    
    if (target.innerHTML === 'html' || target.id.indexOf("new-") !== -1) {
        var id = dd ? dd.id.replace('html-', '') : target.id.replace('new-', ''),
            fr = window.frames['frame-' + id];

        var html = '';
        for (var i = 0; i <= id; i++) {
            html += data[i]['plain'];
        }
            
        var w = null;
        if (target.id === ('new-' + id)) {
            w = window.open();
            target.className = '';
        } else {
            w = fr;
        }
        w.document.open();
        w.document.writeln(html);
        w.document.close();            
    }
    
};
</script>
</body>
</html>