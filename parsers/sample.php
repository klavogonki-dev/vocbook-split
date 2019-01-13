<?php

use vocbook\BookPart;
/**
 * @file Simple example of a split-book function.
 * Copy the file into project root directory as "parser.php"
 * 
 * @author Vitaliy Busko <vitaliy.opensource@gmail.com>
 */

/**
 * The function will be called from `vocbook\Book` class
 * and must correct parse input text data from opened
 * stream handle (first argument `$sr` - *s*tream *r*eader).
 * You don't need to close the `$sr` stream by call `fclose`.
 * 
 * `$add` is just a callable variable. Also it is array
 * that includes two elements: instance of `Book` class
 * and pointer to `add_part` method of `Book` class.
 * However, you can use the argument as array for debug code
 * rather than production.
 * 
 * You should call a callback function (second argument `$add`)
 * for every parsed book fragment.
 * 
 * Signature of a callback function (second argument `$add`):
 * - $add(BookPart $bp)
 * - $add(string $segment, integer $segment_number)
 * 
 * @param resource $sr - handle of an opened stream reader (mode 'r').
 * Use standard functions such as `fread`, `fgets` or other
 * for reading from the stream.
 * @param callable $add - callback function to save segment
 * @param BookFlag $flags - bitmask, checks for private\public
 * voc properties and other
 * @return void
 */
$parse = function ($sr, callable $add, $flags = BookFlag::NONE) {
	$segment_length = 200; // minimum length of a segment (book part)
	$count = 0; // count of current segment (book part) symbols
	$segment_number = 0; // counter: current part
	$segment = ''; // text of the current segment

	while (false !== ($c = fgetc($sr))) {
		$segment .= $c;
		// Adds the segment if we found a dot symbol and
		// the segment include great then `$segment_length` symbols.
		if ($count++ > $segment_length && $c === '.') {
			$count = 0;
			$add($segment, ++$segment_number); // call with two arguments
		}
	}

	// call with single argument via creating instance of `BookPart` class
	$add(new BookPart($segment, ++$segment_number));
};
