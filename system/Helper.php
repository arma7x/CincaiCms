<?php

function startTimer() {
	global $starttime;
	$starttime = microtime(true);
}

function endTimer() {
	global $starttime;
	$endtime = microtime(true);
	$totaltime = number_format(($endtime - $starttime),3);
	return $totaltime;
}

function ramUsage() {
	return (memory_get_peak_usage(true)/1024/1024);
}

function humanReadable($delimiter, $word) {
	return implode(' ', explode($delimiter, $word));
}

function pageTreeNavigator($tree, $ordering) {
	$open = '<ul class="navbar-nav mr-auto">';
	$body = '';
	$close = '</ul>';
	if (is_array($ordering) && (count($ordering) === count($tree))) {
		foreach($ordering as $index => $value) {
			if (isset($tree[$value['index']])) {
			} else {
				unset($ordering[$index]);
			}
		}
		foreach($ordering as $index => $value) {
			$temp = $tree[$value['index']];
			if (is_string($temp)) {
				$text = ucwords(humanReadable('-', explode('.', $temp)[0]));
				$body .= '<li class="nav-item"><a class="nav-link" href="/'.explode('.', $temp)[0].'"><i class="fa '.$value['ico'].'"></i> '.$text.'</a></li>';
			} else {
				foreach ($temp as $sub_index => $sub_value) {
					$b_drop = '';
					if (count($sub_value) == count($sub_value, COUNT_RECURSIVE)) {
						$b_drop .= '<li class="nav-item dropdown">';
						$b_drop .= '<a class="nav-link dropdown-toggle" href="#" id="'.$sub_index.'" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa '.$value['ico'].'"></i> '.ucwords(humanReadable('-', $sub_index)).'</a>';
						$b_drop .= '<div class="dropdown-menu" aria-labelledby="'.$sub_index.'">';
						foreach($sub_value as $child_index => $child_value) {
							$child_text = ucwords(humanReadable('-', explode('.', $child_value)[0]));
							$b_drop .= '<a class="dropdown-item" href="/'.$sub_index.'/'.explode('.', $child_value)[0].'">'.$child_text.'</a>';
						}
						$b_drop .= '</div></li>';
					}
					$body .= $b_drop;
				}
			}
		}
	} else {
		foreach($tree as $index => $value) {
			if (is_string($value)) {
				$text = ucwords(humanReadable('-', explode('.', $value)[0]));
				$body .= '<li class="nav-item"><a class="nav-link" href="/'.explode('.', $value)[0].'">'.$text.'</a></li>';
			} else {
				foreach ($value as $sub_index => $sub_value) {
					$b_drop = '';
					if (count($sub_value) == count($sub_value, COUNT_RECURSIVE)) {
						$b_drop .= '<li class="nav-item dropdown">';
						$b_drop .= '<a class="nav-link dropdown-toggle" href="#" id="'.$sub_index.'" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.ucwords(humanReadable('-', $sub_index)).'</a>';
						$b_drop .= '<div class="dropdown-menu" aria-labelledby="'.$sub_index.'">';
						foreach($sub_value as $child_index => $child_value) {
							$child_text = ucwords(humanReadable('-', explode('.', $child_value)[0]));
							$b_drop .= '<a class="dropdown-item" href="/'.$sub_index.'/'.explode('.', $child_value)[0].'">'.$child_text.'</a>';
						}
						$b_drop .= '</div></li>';
					}
					$body .= $b_drop;
				}
			}
		}
	}
	return $open.$body.$close;
}

function blogTreeNavigator($base, $tree, $style, $metadata) {
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
					$rand = microtime(true).md5($sub_index);
					$body .= '<li><a data-toggle="collapse" href="#'.$rand.'" role="button" aria-expanded="true" aria-controls="'.$rand.'">&#x21b3 '.ucwords(humanReadable('-', $sub_index)).'<ul class="collapse" id="'.$rand.'">';
					foreach ($sub_value as $child_index => $child_value) {
						$body .= '<li><a href="'.$base.'/'.$sub_index.'/'.explode('.', $child_value)[0].'">'.ucwords(humanReadable('-', explode('.', $child_value)[0])).'</a></li>';
					}
					$body .= '</ul></a></li>';
				} else {
					$rand = microtime(true).md5($sub_index);
					$body .= '<li><a data-toggle="collapse" href="#'.$rand.'" role="button" aria-expanded="true" aria-controls="'.$rand.'">&#x21b3; '.ucwords(humanReadable('-', $sub_index)).blogTreeNavigator($base.'/'.$sub_index, $sub_value, 'class="collapse" id="'.$rand.'"', $metadata).'</a></li>';
				}
			}
		}
	}
	return $open.$body.$close;
}
