<?php

namespace Elgg\Http;

/**
 * An immutable, absolute, HTTP(S) url.
 * 
 * @package    Elgg.Core
 * @subpackage Http
 * @since      1.10.0
 * 
 * @access private
 */
final class Url {

    /** @var array */
    private $parts;
    
    /**
     * Use Url::parse to create instances from strings.
     * 
     * @param array $parts The url in parsed form (e.g., after call to parse_url).
     */
    private function __construct(array $parts) {
        $this->parts = $parts;
    }
    
    /**
     * Resolve the given path as a relative to this Url.
     * 
     * @param string $url The relative url.
     * 
     * @return string
     */
    public function normalize($url) {
        $validated = Url::validate($url);
        
        // work around for handling absoluate IRIs (RFC 3987) - see #4190
        if (!$validated && (strpos($url, 'http:') === 0) || (strpos($url, 'https:') === 0)) {
            $validated = true;
        }
        
        if ($validated) {
            // all normal URLs including mailto:
            return $url;
        
        } elseif (preg_match("#^(\#|\?|//)#i", $url)) {
            // '//example.com' (Shortcut for protocol.)
            // '?query=test', #target
            return $url;
        
        } elseif (stripos($url, 'javascript:') === 0 || stripos($url, 'mailto:') === 0) {
            // 'javascript:' and 'mailto:'
            // Not covered in FILTER_VALIDATE_URL
            return $url;
        
        } elseif (preg_match("#^[^/]*\.php(\?.*)?$#i", $url)) {
            // 'install.php', 'install.php?step=step'
        
        } elseif (preg_match("#^[^/]*\.#i", $url)) {
            // 'example.com', 'example.com/subpage'
            return "http://$url";
        } 
        
        // 'page/handler', 'mod/plugin/file.php'
        
        $origin = $this->getOrigin();
        $path = rtrim($this->parts['path'], '/');
        $url = ltrim($url, '/');
        
        return $this->getOrigin() . "$path/$url";
    }
    
    /**
     * Modify the query parameters of this url.
     * 
     * @param array $elements Associative array of new query params.
     * 
     * @return Url
     */
    public function setQueryParams(array $elements) {
        $url_array = $this->parts;
        
        if (isset($url_array['query'])) {
            $query = Url::parseStr($url_array['query']);
        } else {
            $query = array();
        }
        
        foreach ($elements as $k => $v) {
            if ($v === null) {
                unset($query[$k]);
            } else {
                $query[$k] = $v;
            }
        }
        
        // why check path? A: if no path, this may be a relative URL like "?foo=1". In this case,
        // the output "" would be interpreted the current URL, so in this case we *must* set
        // a query to make sure elements are removed.
        if ($query || empty($url_array['path'])) {
            $url_array['query'] = \http_build_query($query);
        } else {
            unset($url_array['query']);
        }
        
        return new Url($url_array);
    }
    
    /**
     * Whether this url is functionally equivalent to the given url.
     * 
     * @param Url $other The Url to compare to.
     * 
     * @return bool
     */
    public function equals(Url $other) {
        $url1_info = $this->parts;
        $url2_info = $other->parts;
        
        if (isset($url1_info['path'])) {
            $url1_info['path'] = trim($url1_info['path'], '/');
        }
        
        if (isset($url2_info['path'])) {
            $url2_info['path'] = trim($url2_info['path'], '/');
        }
        
        // compare basic bits
        $parts = array('scheme', 'host', 'path');
        
        foreach ($parts as $part) {
            if ((isset($url1_info[$part]) && isset($url2_info[$part]))
                && $url1_info[$part] != $url2_info[$part]) {
                return false;
            } elseif (isset($url1_info[$part]) && !isset($url2_info[$part])) {
                return false;
            } elseif (!isset($url1_info[$part]) && isset($url2_info[$part])) {
                return false;
            }
        }
        
        // quick compare of get params
        if (isset($url1_info['query']) && isset($url2_info['query'])
            && $url1_info['query'] == $url2_info['query']) {
            return true;
        }
        
        // compare get params that might be out of order
        $url1_params = array();
        $url2_params = array();
        
        if (isset($url1_info['query'])) {
            if ($url1_info['query'] = \html_entity_decode($url1_info['query'])) {
                $url1_params = Url::parseStr($url1_info['query']);
            }
        }
        
        if (isset($url2_info['query'])) {
            if ($url2_info['query'] = \html_entity_decode($url2_info['query'])) {
                $url2_params = Url::parseStr($url2_info['query']);
            }
        }
        
        // drop ignored params
        foreach ($ignore_params as $param) {
            if (isset($url1_params[$param])) {
                unset($url1_params[$param]);
            }
            if (isset($url2_params[$param])) {
                unset($url2_params[$param]);
            }
        }
        
        // array_diff_assoc only returns the items in arr1 that aren't in arrN
        // but not the items that ARE in arrN but NOT in arr1
        // if arr1 is an empty array, this function will return 0 no matter what.
        // since we only care if they're different and not how different,
        // add the results together to get a non-zero (ie, different) result
        $diff_count = count(\array_diff_assoc($url1_params, $url2_params));
        $diff_count += count(\array_diff_assoc($url2_params, $url1_params));
        if ($diff_count > 0) {
            return false;
        }
        
        return true;
    
    }
    
    /**
     * Get a url representing only the scheme/host/port of the current Url.
     * 
     * @return Url
     */
    public function getOrigin() {
        return Url::createFromParts(array(
            'scheme' => $this->parts['scheme'],
            'host' => $this->parts['host'],
            'port' => elgg_extract('port', $this->parts),
        ));
    }
    
    /** @inheritDoc */
    public function __toString() {
        $parts = $this->parts;
        
        // build only what's given to us.
        $scheme = isset($parts['scheme']) ? "{$parts['scheme']}://" : '';
        $host = isset($parts['host']) ? "{$parts['host']}" : '';
        $port = isset($parts['port']) ? ":{$parts['port']}" : '';
        $path = isset($parts['path']) ? "{$parts['path']}" : '';
        $query = isset($parts['query']) ? "?{$parts['query']}" : '';
        $fragment = isset($parts['fragment']) ? "#{$parts['fragment']}" : '';
        
        return $scheme . $host . $port . $path . $query . $fragment;
    }
    
    /**
     * Whether the given string is a valid Url.
     * 
     * @param string $url The string url.
     * 
     * @return bool
     */
    private static function validate($url) {
        return (bool)filter_var($url, FILTER_VALIDATE_URL);
    }
    
    /**
     * Create a Url instance from a string.
     * 
     * @param string $string The url in string format.
     * 
     * @return Url
     */
    public static function parse($string) {
        return new Url(parse_url($string));
    }
    
    /**
     * Create a Url instance from pre-parsed url parts.
     * 
     * @param array $parts The associative array of url components.
     * 
     * @return Url
     */
    public static function createFromParts(array $parts) {
        return new Url($parts);
    }
    
    /**
     * Parses a query string using mb_parse_str() if available.
     * 
     * NOTE: This differs from parse_str() by returning the results
     * instead of placing them in the local scope!
     *
     * @param string $str The string
     * 
     * @return array
     * @since 1.10.0
     */
    public static function parseStr($str) {
        $results = array();
        
        if (is_callable('mb_parse_str')) {
            mb_parse_str($str, $results);
        } else {
            parse_str($str, $results);
        }
        
        return $results;
    }
}