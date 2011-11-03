<?php

/**
 * Super simple file based storage useful for a demo
 * @author Mike Sherov @mikesherov
 * @name Database
 */

abstract class Database {

	/**
	 * location of the file storing the data
	 * @var string
	 */
	protected $file_location = '';

	/**
	 * name of the column in an array that contains the primary key for this "table"
	 * @var string
	 */
	protected $primary_key = '';

	/**
	 * internal representation of the entire "table", containing all keys and values
	 * stupid, I know, but useful for a quick demo
	 * @var array
	 */
	protected $data = array();

	/**
	 * makes sure our database file exists, and populates the entire dataset into memory
	 */
	public function __construct(){
		if(!file_exists($this->file_location)){
			 $handle = fopen($this->file_location, 'w');
			 fclose($handle);
		}
		$this->data = json_decode(file_get_contents($this->file_location), true);
	}

	/**
	 * get a row from the "table"
	 * @param string $key
	 * @return array
	 */
	public function get($key){
		return isset($this->data[$key])? $this->data[$key] : array();
	}

	/**
	 * set a row into memory, but don't write to disk yet
	 * chainable
	 *
	 * @param array $row
	 * @return Database
	 */
	public function set(array $row){
		$this->data[$row[$this->primary_key]] = $row;
		return $this;
	}

	/**
	 * write the contents of the database from memory to disk
	 * chainable
	 * @return Database
	 */
	public function write(){
		file_put_contents($this->file_location, json_encode($this->data));
		return $this;
	}

	/**
	 * utility function to return the entire contents of the "table" from memory
	 * @return array
	 */
	public function dump(){
		return $this->data;
	}
}