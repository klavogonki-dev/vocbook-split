<?php
/**
 * @file Application entry point.
 * Execute the script from shell: `php index.php`.
 * 
 * @author Vitaliy Busko <vitaliy.opensource@gmail.com>
 */

require_once "vendor/vocbook-fetch/autoloader.php";
require_once "books.php";
require_once "parser.php";

use vocbook\Book;
use vocbook\Config;

abstract class BookFlag
{
	const NONE = 0x00;
	const VOC_PRIVATE = 0x01;
}

$cfg_file = realpath(__DIR__ . "/conf.php");
if ($cfg_file) {
	include $cfg_file;
	if (isset($cfg_local) && is_array($cfg_local))
		Config::set($cfg_local);
}

/** Feel free to change code below **/

$book = new Book($books[0]);
if ($book->load()) {
	$book->split($parse);
	print "Parts: " . count($book->parts) . "\r\n";
	// $book->view_parts();
	$book->save_parts();
}
