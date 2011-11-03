<?php

/**
 * database wrapper for storing users
 * @author Mike Sherov @mikesherov
 * @name Database_Commit
 */

class Database_User extends Database {

	/**
	 * location of the file storing the data
	 * @var string
	 */
	protected $file_location = 'data/users.json';

	/**
	 * name of the column in an array that contains the primary key for this "table"
	 * @var string
	 */
	protected $primary_key = 'email';
}