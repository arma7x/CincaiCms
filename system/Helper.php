<?php

function startTimer() {
	global $starttime;
	$starttime = microtime(TRUE);
}

function endTimer() {
	global $starttime;
	$endtime = microtime(TRUE);
	$totaltime = number_format(($endtime - $starttime),3);
	return $totaltime;
}

function ramUsage() {
	return (memory_get_peak_usage(true)/1024/1024);
}

function humanReadable($delimiter, $word) {
	return implode(' ', explode($delimiter, $word));
}

function navigator($base, $tree, $style, $metadata) {
	$open = '<ul '.$style.'>';
	$close = '</ul>';
	$body = '';
	foreach ($tree as $index => $value) {
		if (is_string($value)) {
			$text = 'foo';
			if (isset($metadata[explode('.', $value)[0]]))
				$text = $metadata[explode('.', $value)[0]]['title'];
			else
				$text = ucwords(humanReadable('-', explode('.', $value)[0]));
			$body .= '<li><a href="'.$base.'/'.explode('.', $value)[0].'">'.$text.'</a></li>';
		} else {
			foreach ($value as $sub_index => $sub_value) {
				if (count($sub_value) == count($sub_value, COUNT_RECURSIVE)) {
					$rand = explode('.', (microtime(true)))[1];
					$body .= '<li>&#x21b3; <a data-toggle="collapse" href="#'.$rand.'" role="button" aria-expanded="false" aria-controls="'.$rand.'">'.ucwords(humanReadable('-', $sub_index)).'<ul class="collapse" id="'.$rand.'">';
					foreach ($sub_value as $child_index => $child_value) {
						$body .= '<li><a href="'.$base.'/'.$sub_index.'/'.explode('.', $child_value)[0].'">'.ucwords(humanReadable('-', explode('.', $child_value)[0])).'</a></li>';
					}
					$body .= '</ul></a></li>';
				} else {
					$rand = explode('.', (microtime(true)))[1];
					$body .= '<li>&#x21b3; <a data-toggle="collapse" href="#'.$rand.'" role="button" aria-expanded="false" aria-controls="'.$rand.'">'.ucwords(humanReadable('-', $sub_index)).navigator($base.'/'.$sub_index, $sub_value, 'class="collapse" id="'.$rand.'"', $metadata).'</a></li>';
				}
			}
		}
	}
	return $open.$body.$close;
}
