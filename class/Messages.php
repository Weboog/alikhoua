<?php

class Messages{
	
	const TYPE_SUCCESS = 'success';
	const TYPE_ERROR = 'error';
	private static $TYPE;

	private static $arr_success = array();
	private static $arr_error = array();

	public function __construct($type, $message){
		switch ($type) {
			case self::TYPE_SUCCESS:
				self::$arr_success[] = $message;
				break;
			case self::TYPE_ERROR:
				self::$arr_error[] = $message;
				break;
		}
	}	
	/**
	/Retrun Array
	*/
	public static function showMessages(){
		if(count(self::$arr_success) > 0){
		    self::setType(Messages::TYPE_SUCCESS);
			return self::$arr_success;
		}
		if(count(self::$arr_error) > 0){
		    self::setType(Messages::TYPE_ERROR);
			return self::$arr_error;
		}
	}
	
	public static function setType($type){
	    self::$TYPE = $type;
	}
	
	public static function getType(){
	    return self::$TYPE;
	}

}