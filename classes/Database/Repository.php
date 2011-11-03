<?php

/**
 * database wrapper for storing repos
 * @author Mike Sherov @mikesherov
 * @name Database_Commit
 */

class Database_Repository extends Database {

	/**
	 * location of the file storing the data
	 * @var string
	 */
	protected $file_location = 'data/repositories.json';

	/**
	 * name of the column in an array that contains the primary key for this "table"
	 * @var string
	 */
	protected $primary_key = 'url';
}