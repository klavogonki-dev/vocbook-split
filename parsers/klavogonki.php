<?php
/**
 * @file Actual function for parse books on klavogonki.ru
 */
$parse = function ($sr, callable $add, $flags = BookFlag::NONE) {
	$text = stream_get_contents($sr);

	if (substr($text, 0, 3) == chr(239).chr(187).chr(191))
		$text = substr($text, 3);

	$text = preg_replace('#[ \t]+#', ' ', $text);
	$text = preg_replace('#[ \r\n]*\n[ \r\n]*#', "\n", $text);
	$text = str_replace(array(' ','…'), array(' ','...'), $text);
	if (!($flags & BookFlag::VOC_PRIVATE)) {
		$text = str_replace(
			array('’','`','´','“','”','„','‟'),
			array("'","'","'",'"','"','"','"'), $text);
	}

	$text = iconv('utf-8','ucs-2be', $text);

	$len = mb_strlen($text, 'ucs-2be') * 2;

	$end = array('.', '?', '!');
	foreach ($end as &$i)
		$i = iconv('utf-8', 'ucs-2be', $i);
	$quote = iconv('utf-8', 'ucs-2be', '"');
	$semaphores = array(
		'quotes' => [0, iconv('utf-8','ucs-2be','«'), iconv('utf-8','ucs-2be','»')],
		'pars' => [0, iconv('utf-8','ucs-2be','('), iconv('utf-8','ucs-2be',')')],
		'qpars' => [0, iconv('utf-8','ucs-2be','['), iconv('utf-8','ucs-2be',']')],
		'fpars' => [0, iconv('utf-8','ucs-2be','{'), iconv('utf-8','ucs-2be','}')] );

	$nl = iconv('utf-8', 'ucs-2be', "\n");

	$space = iconv('utf-8', 'ucs-2be', ' ');

	$segment = '';
	$segment_len = 0;
	$quote_sem = 0;
	$c = 1;
	$symbols = 0;

	mb_regex_encoding('utf-8');

	for ($i = 0; $i < $len; $i += 2)
	{
		$chr = $text[$i].$text[$i+1];

		if (!($flags & BookFlag::VOC_PRIVATE)
		 && !mb_ereg_match("^[Ёёa-zА-Яа-я0-9A-Z\!\?\,\.\;\:\'\-\–\—\+\_\"«»\s\@\#\$\%\^\&\*\(\)\~\<\>\№\{\}=\\\]+$", iconv('ucs-2be','utf-8', $chr))
		) { continue; }

		$symbols++;

		if ($i < $len - 200*2 && $segment_len >= 400
		 && ($chr == $space || $chr == $nl)
		 && in_array($text[$i-2].$text[$i-1], $end)
		 && ($semaphores['quotes'][0] == 0
			 && $semaphores['pars'][0] == 0
			 && $semaphores['qpars'][0] == 0
			 && $semaphores['fpars'][0] == 0
			 || $segment_len >= 600
		 ) ) {
			$segment = iconv('ucs-2be', 'utf-8', $segment);
			$segment = trim($segment);
			$segment = str_replace("\n", '\n', $segment);

			$add($segment, $c++);

			$segment = '';
			$segment_len = 0;

			foreach ($semaphores as &$s)
				$s[0] = 0;

			continue;
		}

		if ($chr == $quote)
			$quote_sem = $quote_sem ? 0 : 1;
		foreach ($semaphores as &$s) {
			if ($chr == $s[1])
				$s[0]++;
			if ($chr == $s[2] && $s[0] > 0)
				$s[0]--;
		}

		$segment .= $chr;
		$segment_len++;
	}

	$segment = iconv('ucs-2be', 'utf-8', $segment);
	$segment = trim($segment);
	$segment = str_replace("\n", '\n', $segment);
	if (mb_substr($segment, mb_strlen($segment,'utf-8')-1, 1, 'utf-8') != '.')
		$segment .= '.';

	$add($segment, $c);
};
