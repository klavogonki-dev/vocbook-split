<?php
/**
 * @file Here you can easy add your own books.
 * 
 * Book's description is associative array:
 *  - `driver [REQUIRED]`: what driver has been used. List of all available drivers
 *    you can see at `vendor/vocbook-fetch/vocbook/drivers` directory.
 *  - `id` [REQUIRED]: unique id of a resource from where will be loaded the book
 *     by the driver
 *  - `author` [REQUIRED]: author of the book
 *  - `title` [REQUIRED]: title of the book,
 *  - `type` [OPTIONAL]: typeof of the vocabulary: `public` or `private`.
 *     Default value is `public`.
 * 
 * IMPORTANT! Don't forget comment line with throwing exception below!
 * 
 * @author Vitaliy Busko <vitaliy.opensource@gmail.com>
 */

/** COMMENT LINE BELOW: **/
throw new Exception("You must edit file `" . __FILE__ . "` to start work");

/** ADDS YOUR BOOKS BELOW: **/
$books = [
	// [
	// 	"driver" => "local",
	// 	"id" => realpath("/home/Fenex/Downloads/starkov_1-utf-8.txt"),
	// 	"author" => "Сергей Абрамов",
	// 	"title" => "В лесу прифронтовом"
	// ],
	// Example for Windows, PHP <= 7.0 and cyrillic in path:
	// [
	// 	'driver' => 'local',
	// 	'id' => realpath(mb_convert_encoding('C:\Книги\Название по-русски.txt', 'cp1251', 'utf-8')),
	// 	'author' => 'Автор',
	// 	'title' => 'Название книги'
	// ]
];
