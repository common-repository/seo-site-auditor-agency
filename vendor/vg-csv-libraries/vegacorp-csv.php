<?php

function vg_array_to_csv($data, $filepath, $csv_headers = null, $delimiter = ',' ) {
    
$fp = fopen($filepath, 'w');

$first = true;

if( empty( $csv_headers )){
	$first_row = current( $data );
	$csv_headers = implode(',', array_keys( $first_row ) );
}

$csv_headers = explode(',', $csv_headers );
foreach ($data as $row) {
	
	if( $first ){
fputcsv($fp, $csv_headers, $delimiter);		
$first = false;
	}
    fputcsv($fp, $row, $delimiter);
}

fclose($fp);
}

		function vg_get_csv( $filename, $delimiter = ',', $fix_encoding = true ) {
			// Loop through the file lines
			$file_handle = @fopen($filename, 'r');
			if ($file_handle) {
				$csv_reader = new ReadCSV($file_handle, $delimiter, "\xEF\xBB\xBF"); // Skip any UTF-8 byte order mark.
				$first = true;
				$rkey = 0;
				$file_line_index = 0;

				$csv = array();
				while (( $line = $csv_reader->get_row() ) !== NULL) {

					$file_line_index++;

					// If the first line is empty, abort
					// If another line is empty, just skip it
					if (empty($line)) {
						if ($first) {
							break;
						} else {
							continue;
						}
					}
					// If we are on the first line, the columns are the headers
					if ($first) {
						$headers = $line;
						$first = false;
						continue;
					}

					foreach ($line as $ckey => $column) {
						$column = trim($column);
						$column_name = $headers[$ckey];
						
						if( $fix_encoding ){
						$column = iconv(mb_detect_encoding($column, mb_detect_order(), true), "UTF-8", $column);
						$column_name = iconv(mb_detect_encoding($column_name, mb_detect_order(), true), "UTF-8", $column_name);
						}


						$csv[$file_line_index - 1][$column_name] = $column;
					}

					$rkey++;
				}
				fclose($file_handle);
			}



			return $csv;
		}
