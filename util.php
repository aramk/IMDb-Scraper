<?php

function curl_get_html($url) {
	$ch = curl_init($url);
	
	$options = array(CURLOPT_RETURNTRANSFER => TRUE,
					 CURLOPT_FOLLOWLOCATION => TRUE,
					 CURLOPT_MAXREDIRS => 5,
					 CURLOPT_CONNECTTIMEOUT => 20,
					 CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT']);
	curl_setopt_array($ch, $options);
	
	$html = curl_exec($ch);
	
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if ($http_code < 200 || $http_code >= 400) {
		return FALSE;
	}
	
	curl_close($ch);
	
	return $html;
}

// Count how many times the $needles occur in the $haystack
function substr_count_array( $haystack, $needles, $insensitive = TRUE ) {
	$count = 0;
	foreach ($needles as $substring) {
		if ($insensitive) {
			$count += substr_count_i( $haystack, $substring);
		} else {
			$count += substr_count( $haystack, $substring);
		}
	}
	return $count;
}

// Case insensitive substr_count
function substr_count_i( $haystack, $substring ) {
	$haystack = strtolower($haystack);
	$substring = strtolower($substring);
	return substr_count( $haystack, $substring );
}

// Returns an array of the occurences found inside the $haystacks array using the $needles array.
function substr_count_arrays( $haystacks, $needles ) {
	$counts = array();	
	for ($i = 0; $i < count($haystacks); $i++) {
		$counts[$i] = substr_count_array($haystacks[$i], $needles);
	}
	return $counts;
}

//$needles = array('fuck', 'cunt', 'bitch');
//$haystacks = array('fuck bitch', 'fuck fuck fuck', 'fuck cunt');
//var_dump(substr_count_arrays($haystacks, $needles));

// Trim a string or an array of strings recursively
function trim_r($array) {
    if (is_string($array)) {
        return trim($array);
    } else if (!is_array($array)) {
        return '';
    }
    $keys = array_keys($array);
    for ($i=0; $i<count($keys); $i++) {
        $key = $keys[$i];
        if ( is_array($array[$key]) ) {
            $array[$key] = trim_r($array[$key]);
        } else if ( is_string($array[$key]) ) {
            $array[$key] = trim($array[$key]);
        }
    }
    return $array;
}

// Match regex and return given index
function regex_get($regex, $str, $index = 1, $clean = TRUE) {
    preg_match($regex, $str, $matches);
    if (count($matches) > 0) {
        $index = intval($index);
        if ($index >= count($matches)) {
            return '';
        }
        $match = $matches[$index];
        if ($clean === 'num') {
        	$match = clean_num($match);
        } else if ($clean == TRUE) {
        	$match = clean_str($match);
        }
        return $match;
    } else {
        return '';
    }
}

// Extract the title id out of a URL
function imdb_url_id($url) {
    if (!is_string($url)) {
        return '';
    }
    $id = regex_get('#title\\/(.*)\\/#', $url, 1);
    return empty($id) ? FALSE : $id;
}

function url_add_slash($url) {
	return preg_replace('#([^\\/])$#', '\1/', $url);
}

// Removes HTML encoding
function clean_str($str, $quotes = FALSE, $only_chars = FALSE) {
	if (is_string($str)) {
		$str = trim( html_entity_decode( strip_tags($str) , ENT_NOQUOTES, 'UTF-8') );
		if ($quotes) {
			$str = preg_replace('#"|\'#', '', $str);
		}
		if ($only_chars) {
			$str = preg_replace('#[^\\w\\s]#', '', $str);
		}
	}
	return $str;
}

function clean_num($str) {
	return preg_replace('#[^\d\.,]#', '', clean_str($str));
}

// Converts a string to a number. Decides whether to use int or float.
function numval($str) {
	$int = intval($str);
	$float = floatval($str);
	if ($int == $float) {
		return $int;
	} else {
		return $float;
	}
}

// Recursively cleans an array of strings
function clean_array($array) {
	if (is_array($array)) {
		for ($i = 0; $i < count($array); $i++) {
			if ( is_array($array[$i]) ) {
				$array[$i] = clean_array($array[$i]);
			} else if ( is_string($array[$i]) ) {
				$array[$i] = clean_str($array[$i]);
			}
		}
	}
	return $array;
}

// Removes all non-alphanumerics, makes lowercase and cleans
function normalise($str) {
	if (is_string($str)) {
		return strtolower(preg_replace('#[^\\w]#', '', clean_str($str)));
	}
	return '';
}

// Returns TRUE if a $needle found in $haystack, normalises both first
function normpos($haystack, $needle) {
	if (is_string($haystack) && is_string($needle)) {
		return stripos(normalise($haystack), normalise($needle)) !== FALSE;
	} else {
		return FALSE;
	}
}

// Tests if two normalised strings are equal
function normeq($str1, $str2) {
	if (is_string($str1) && is_string($str2)) {
		return normalise($str1) === normalise($str2);
	} else {
		return FALSE;
	}
}

?>