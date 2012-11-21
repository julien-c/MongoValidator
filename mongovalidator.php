<?php

class MongoValidator extends Laravel\Validator {
	
	/**
	 * Validate the uniqueness of an attribute value on a given database table.
	 *
	 * If a database column is not specified, the attribute will be used.
	 *
	 * @param  string  $attribute
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validate_unique($attribute, $value, $parameters)
	{
		// We allow the table column to be specified just in case the column does
		// not have the same name as the attribute. It must be within the second
		// parameter position, right after the database table name.
		if (isset($parameters[1]))
		{
			$attribute = $parameters[1];
		}
		
		$query = array($attribute => $value);
		
		// We also allow an ID to be specified that will not be included in the
		// uniqueness check. This makes updating columns easier since it is
		// fine for the given ID to exist in the table.
		if (isset($parameters[2]))
		{
			$query['_id'] = array('$ne' => new MongoId($parameters[2]));
		}
		
		$count = $this->mongo()->$parameters[0]->count($query);
		
		return $count == 0;
	}
	
	
	/**
	 * Validate the existence of an attribute value in a database table.
	 *
	 * @param  string  $attribute
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validate_exists($attribute, $value, $parameters)
	{
		if (isset($parameters[1])) $attribute = $parameters[1];
		
		// Grab the number of elements we are looking for. If the given value is
		// in array, we'll count all of the values in the array, otherwise we
		// can just make sure the count is greater or equal to one.
		$count = (is_array($value)) ? count($value) : 1;
		
		// If the given value is an array, we will check for the existence of
		// all the values in the database, otherwise we'll check for the
		// presence of the single given value in the database.
		if (is_array($value))
		{
			$query = array($attribute => array('$in' => $value));
		}
		else
		{
			$query = array($attribute => $value);
		}
		
		$queryCount = $this->mongo()->$parameters[0]->count($query);
		
		return $queryCount >= $count;
	}
	
	
	/**
	 * Get the database connection for the Validator.
	 *
	 * @return Database\Connection
	 */
	protected function mongo()
	{
		$m = new Mongo();
		$db = Config::get('database.connections.mongo.db');
		
		return $m->$db;
	}
	
}