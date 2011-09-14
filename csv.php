<?php

require_once('util.php');

/**
 * Read a CSV file and return contents as array
 * @param string $file
 * @param bool $ignore_header Whether to ignore the first row
 * @return array|bool Returns FALSE on error, else array
 */
function csv_read($file, $ignore_header = FALSE) {
	if (!is_string($file) || empty($file)) {
		return FALSE;
	}
	$rows = array();
	if (($handle = @fopen($file, 'r')) !== FALSE) {
		// Loop over rows
		$row_num = -1;
	    while (($rowdata = @fgetcsv($handle)) !== FALSE) {
	    	$row = array();
	    	$row_num++;
		    // Ignore header?
        	if ($row_num == 0 && $ignore_header) {
        		continue;
        	}
	        // Loop over columns
	        for ($c = 0; $c < count($rowdata); $c++) {
	            $row[] = $rowdata[$c];
	        }
	        $rows[] = $row;
	    }
	    @fclose($handle);
	} else {
		return FALSE;
	}
	return $rows;
}


/**
 * Write to a CSV file from contents of array
 * 
 * You can provide a table array of row arrays like this:
 * csv_write( array( array('name', 'email') , array('aram', 'akarmenia@gmail.com') ) , 'test.csv' );
 * Or just a single array as a row.
 *
 * @param array $array Array of rows each with an array of columns
 * @param string $file The csv file to write output
 * @param bool $append Whether to append or replace the file contents, FALSE by default
 * @param bool $clean Whether to clean the strings of HTML encoding, TRUE by default
 * @return bool TRUE if successful, else FALSE
 */
function csv_write($array, $file, $append = FALSE, $clean = TRUE) {
	if (!is_string($file) || !is_array($array) || empty($file) || empty($array)) {
		return FALSE;
	}
	$mode = ($append) ? 'a' : 'w';
	$fp = @fopen($file, $mode);
	if (!$fp) {
		return FALSE;
	}
	
	/* If the first cell of the array isn't an array itself, then we must be given a single row. */
	if (!is_array($array[0])) {
		$array = array($array);
	}

	// Write only given line
	foreach ($array as $row) {
		if ($clean) {
			$row = clean_array($row);
		}
		if (is_array($row) && !empty($row)) {
			@fputcsv($fp, $row);
		}
	}

	@fclose($fp);
	return TRUE;
}

?>