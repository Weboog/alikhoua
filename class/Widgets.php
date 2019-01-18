<?php

class Widgets{
	

	private $_type;
	private $_value;
	private $_size;
	private $_need;
	
	const TYPE_NAMES = 'names';
	const TYPE_USERNAME = 'username';
	const TYPE_PASS = 'password';
	const TYPE_PIN = 'pin';
	const TYPE_EMAIL = 'email';
	const TYPE_NUMBER = 'number';
	const TYPE_SELECT = 'select';
	const TYPE_TITLE = 'title';
	const TYPE_TXT = 'txt';

	public function __construct($type, $value, $size, $need = array()){
        $this->_type = $type;
        $this->_value = $value;
        $this->_size = $size;
        $this->_need = $need;
	}

	public function isValid(){
		$test = false;
		switch ($this->_type) {
		    case self::TYPE_NAMES:
		        if(preg_match('#^[a-zA-Z]{5,'.$this->_value.'}[ ]{1}[a-zA-Z]{5,'.$this->_value.'}$#', $this->_value)){
		            $test = true;
		        }
		        return $test;
		        break;
		    case self::TYPE_USERNAME:
		        if(preg_match('#^[a-zA-Z0-9]{5,'.$this->_size.'}$#', $this->_value)){
		            $test = true;
		        }
		        return $test;
		        break;
			case self::TYPE_PASS:
				if(preg_match('#^[a-zA-Z\-._0-9]{6,'.$this->_size.'}$#', $this->_value)){
					$test = true;
				}
				return $test;
				break;
			case self::TYPE_PIN:
			    if(preg_match('#^[0-9]{'.$this->_size.'}$#', $this->_value)){
			        $test = true;
			    }
			    return $test;
			    break;
			case self::TYPE_EMAIL:
				if(preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#', $this->_value)){
					$test = true;
				}
				return $test;
				break;
            case self::TYPE_NUMBER:
                if(preg_match('#^[0-9.]{1,}$#', $this->_value)){
                    $test = true;
                }
                return $test;
                break;
            case self::TYPE_SELECT:
                if(in_array($this->_value, $this->_need)){
                    $test = true;
                }
                return $test;
                break;
            case self::TYPE_TITLE:
                if(preg_match('#^[a-zA-Z\' àâçèéêîôùû]{5,'.$this->_size.'}$#', $this->_value)){
                    $test = true;
                }
                return $test;
                break;
            case self::TYPE_TXT:
                if(trim(strlen($this->_value)) >= 25 && trim(strlen($this->_value)) <= $this->_size){
                    $test = true;
                }
                return $test;
                break;
		}
		
	}

}