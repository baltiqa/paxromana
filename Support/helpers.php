<?php


function url_shorten( $url, $length = 35 ) {
    $stripped = str_replace( array( 'https://', 'http://', 'www.' ), '', $url );
    $short_url = rtrim( $stripped, '/\\' );

    if ( strlen( $short_url ) > $length ) {
        $short_url = substr( $short_url, 0, $length - 3 ) . '&hellip;';
    }
    return $short_url;
}

function flatten($elements, $depth) {
    $result = array();
    foreach ($elements as $element) {
        $element = (array) $element;
        $element['depth'] = $depth;
        if (isset($element['child'])) {
            $children = $element['child'];
            unset($element['child']);
        } else {
            $children = null;
        }
        $result[] = $element;
        if (isset($children)) {
            $result = array_merge($result, flatten($children, $depth + 1));
        }
    }
    return $result;
}

function substr_startswith($haystack, $needle) {
    return substr($haystack, 0, strlen($needle)) === $needle;
}

function highlight($text, $terms) {
    if (!is_array($terms)) $terms = [ $terms, ];
    $highlight = array();
    foreach($terms as $term) {
       $highlight[]= '<span class="highlight">'.$term.'</span>';
    }

    return str_ireplace($terms, $highlight, $text);
}


function file_upload_max_size() {
  static $max_size = -1;

  if ($max_size < 0) {
    // Start with post_max_size.
    $post_max_size = parse_size(ini_get('post_max_size'));
    if ($post_max_size > 0) {
      $max_size = $post_max_size;
    }

    // If upload_max_size is less, then reduce. Except if upload_max_size is
    // zero, which indicates no limit.
    $upload_max = parse_size(ini_get('upload_max_filesize'));
    if ($upload_max > 0 && $upload_max < $max_size) {
      $max_size = $upload_max;
    }
  }
  return $max_size;
}



function current_website($url) {
    return env('APP_URL') == $url;
}

function load_urls() {
    $content = fopen(storage_path('hoi.txt'),"r");
    $urls = array();
    while(!feof($content))  {
        $urls[]= str_replace("\n","",fgets($content));
    }
    
    fclose($content);

    return $urls;
}

function random_website_letter($url_string) {
    
    $pos = rand(0,(strlen($url_string)-1));

    $endstring= str_split($url_string);
    $url = "";

    $count = 0;
    foreach($endstring as $letter ) {
        if($count == $pos && $letter == $url_string[$pos]) {
            $letter = "<span>".$letter."</span>";
        }
        $url.=$letter;
        $count++;
    }
    return $url;
}

function filter_message($message) {
        $message = preg_replace('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/i', '(phone hidden)', $message); // extract email
        $message = preg_replace('/(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?/', '(email hidden)', $message);
    return $message;
}

function bbc2html($content) {
    $search = array (
      '/(\[b\])(.*?)(\[\/b\])/',
      '/(\[i\])(.*?)(\[\/i\])/',
      '/(\[u\])(.*?)(\[\/u\])/',
      '/(\[li\])(.*?)(\[\/li\])/',
      '/(\[color=red\])(.*?)(\[\/color\])/',
      '/(\[color=blue\])(.*?)(\[\/color\])/',
      '/(\[color=white\])(.*?)(\[\/color\])/',
      '/(\[h1\])(.*?)(\[\/h1\])/',
      '/(\[h2\])(.*?)(\[\/h2\])/',
      '/(\[h3\])(.*?)(\[\/h3\])/',
    );
  
    $replace = array (
      '<b>$2</b>',
      '<em>$2</em>',
      '<u>$2</u>',
      '<li>$2</li>',
      '<span style="color:red;">$2</span>',
      '<span style="color:blue;">$2</span>',
      '<span style="color:white;">$2</span>',
      '<h1>$2</h1>',
      '<h2>$2</h2>',
      '<h3>$2</h3>',
    );
  
    return preg_replace($search, $replace, $content);
 }

 function xss_clean($data)
{
// Fix &entity\n;
$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

// Remove any attribute starting with "on" or xmlns
$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

// Remove javascript: and vbscript: protocols
$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

// Remove namespaced elements (we do not need them)
$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

do
{
    // Remove really unwanted tags
    $old_data = $data;
    $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
}
while ($old_data !== $data);

// we are done...
return $data;
}

function filter_username($str) {
    $len = strlen($str);
    return substr($str, 0, 1).str_repeat('*', $len - 2).substr($str, $len - 1, 1);
}

function getDir($id, $levels_deep = 32) {
    $file_hash   = md5($id);
    $dirname     = implode("/", str_split(
        substr($file_hash, 0, $levels_deep)
    ));
    return $dirname;
}
function store($dirname, $filename) {
    return $dirname . "/" . $filename;
}


function array_to_string($input, $level = 0) {
    $array = json_decode(json_encode($input), true);
    $text = "";
    foreach($array as $k => $v) {
        if(is_array($v)) {
            $level = $level+1;
            $text .= str_repeat("&#8212;", $level) . " " . $k."\n";
            $text .= array_to_string($v, $level);
        } else{
            if($v)
                $text .= str_repeat("&#8212;", $level+1) . " $k: $v\n";
        }
    }
    return $text;
}


function get_query_string() {
    return app('request')->getQueryString();
}


function unslug($text, $delimiter = '-')
{
    return ucfirst(str_replace($delimiter, ' ', $text));
}


