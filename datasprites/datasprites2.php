<?php
/**
 * Branches
 * - FF, O, Saf, IE8 get data URIs
 * - IE 6,7 get MHTML
 * - Vista/Win7 and IE6,7 get special treatment - an extra component
 */


class DataSprites {

    var $config = array(
        'classname_prefix' => '.icon-',
        'separator'        => "_MY_BOUNDARY_SEPARATOR",
        'CRLF'             => "\r\n",
        'expires'          => 'next year',
    );
    
    var $files = array();

    function DataSprites() {

        $ua = $_SERVER['HTTP_USER_AGENT'];
        $this->problem_ie = strstr($ua, "MSIE 6") || strstr($ua, "MSIE 7");
        $this->url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

    }

    function getName($f) {
        $f = pathinfo($f);
        $f = $f['filename'] ? $f['filename'] : $f['basename'];
        $f = explode('.', $f);
        $f = $f[0];
        return preg_replace('/\W/i', '', $f);
    }

    function getClassname($f) {
        return $this->config['classname_prefix'] . $this->getName($f);  
    }

    function get64($f) {
        return base64_encode(file_get_contents($f));    
    }

    function getDeclarationUri($f) {
        $res = $this->getClassName($f);
        $res .= '{';
        $res .= 'background-image:url("data:image/png;base64,' . $this->get64($f) . '");';
        $res .= '}';
        return $res;
    }

    function getDeclarationMHTML($f, $url = '') {
        
        $url = $url ? $url : $this->url;
        $name = $this->getName($f);
        $res = $this->getClassName($f);
        $res .= '{';
        $res .= 'background-image:url(mhtml:' . $url . '!' . $name . ');';
        $res .= '}';
        return $res;    
    }

    function getMHTML() {
        
        $CRLF = $this->config['CRLF'];
        $separator = $this->config['separator'];
        
        $mhtml = 'Content-Type: multipart/related; boundary="' . $separator . '"' . $CRLF . $CRLF;

        foreach ($this->files as $f) {
            $mhtml .= '--' . $separator . $CRLF;
            $mhtml .= 'Content-Location:' . $this->getName($f) . $CRLF;
            $mhtml .= 'Content-Transfer-Encoding:base64'. $CRLF;
            $mhtml .= $CRLF;
            $mhtml .= $this->get64($f) . $CRLF;
        }
        
        $mhtml .= $CRLF . '--' . $separator . '--' . $CRLF;

        return $mhtml;
    }

    function sendHeaders() {


        header('Expires: ' . gmdate('D, d M Y H:i:s', strtotime($this->config['expires'])) . ' GMT');
        ob_start("ob_gzhandler");
        
        header('Vary: Accept-Encoding');
        header('Content-Type: message/rfc822');
    }


    function getData() {

        $data = '';
        $CRLF = $this->config['CRLF'];
        
        // normal browsers
        if (!$this->problem_ie) {
            foreach ($this->files as $f) {
                $data .= $this->getDeclarationUri($f) . $CRLF;
            }
            return $data;
        }

        // IE 6,7 use MHTML
        if ($this->problem_ie) {
            $data .= '/*' . $CRLF;
            $data .= $this->getMHTML();
            $data .= '*/' . $CRLF . $CRLF;

            foreach($this->files as $f) {
                $data .= $this->getDeclarationMHTML($f) . $CRLF;
            }
            return $data;
        }
     
    }
}

/*
// test
$myfiles = array('aol.png', 'deal.png', 'games.png', 'gmail.png', 'horoscopes.png', 'shop.png', 'travel.png');
$ds = new DataSprites();
$ds->files = $myfiles;
$ds->sendHeaders();
echo $ds->getData();
*/
?>
