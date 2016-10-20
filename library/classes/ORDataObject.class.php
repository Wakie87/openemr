<?php
	require_once (dirname(__FILE__) ."/../sql.inc");
    require_once (dirname(__FILE__) ."/../formdata.inc.php");
	require_once("Patient.class.php");
	require_once("Person.class.php");
	require_once("Provider.class.php");
	require_once("Pharmacy.class.php");

/**
 * class ORDataObject
 *
 */

class ORDataObject {
	var $_prefix;
	var $_table;
	var $_db;

	function __construct() {
	  $this->_db = DB::instance();
	}

	function persist() {
		$sql = "REPLACE INTO " . $_prefix . $this->_table . " SET ";
		//echo "<br><br>";
		$fields = sqlListFields($this->_table);
        $res =  "SHOW KEYS FROM ". $this->_table . " WHERE Key_name = 'PRIMARY'";
        $res = sqlQuery($res);
        $pkeys = array($res['Column_name']);

		foreach ($fields as $field) {
			$func = "get_" . $field;
			//echo "f: $field m: $func status: " .  (is_callable(array($this,$func))? "yes" : "no") . "<br>";
			if (is_callable(array($this,$func))) {
				$val = call_user_func(array($this,$func));

                 //modified 01-2010 by BGM to centralize to formdata.inc.php
			     // have place several debug statements to allow standardized testing over next several months
				if (!is_array($val)) {
				        //DEBUG LINE -
                        //error_log("ORDataObject persist before strip: ".$field. " " .$val, 0);
					$val = strip_escape_custom($val);
				        //DEBUG LINE -
                        //error_log("ORDataObject persist after strip: ".$field. " " .$val, 0);
				}

				if (array($field)) {
					call_user_func(array(&$this,"set_".$field), $val);
                    //echo "s: $field to: $val <br>";
				}

				if (!empty($val)) {
					//echo "s: $field to: $val <br>";
                    //modified 01-2010 by BGM to centralize to formdata.inc.php
			        // have place several debug statements to allow standardized testing over next several months
					$sql .= " `" . $field . "` = '" . add_escape_custom(strval($val)) ."',";
				        //DEBUG LINE - error_log("ORDataObject persist after escape: ".add_escape_custom(strval($val)), 0);
				        //DEBUG LINE - error_log("ORDataObject persist after escape and then stripslashes test: ".stripslashes(add_escape_custom(strval($val))), 0);
				        //DEBUG LINE - error_log("ORDataObject original before the escape and then stripslashes test: ".strval($val), 0);
				}
			}
		}

		if (strrpos($sql,",") == (strlen($sql) -1)) {
				$sql = substr($sql,0,(strlen($sql) -1));
		}

		//echo "<br>sql is: " . $sql . "<br /><br>";
		sqlQuery($sql);
		return true;
	}

	function populate() {
		$sql = "SELECT * from " . $this->_prefix  . $this->_table . " WHERE id = '" . add_escape_custom(strval($this->id))  . "'";
		$results = sqlQuery($sql);
		  if (is_array($results)) {
			foreach ($results as $field_name => $field) {
				$func = "set_" . $field_name;
				//echo "f: $field m: $func status: " .  (is_callable(array($this,$func))? "yes" : "no") . "<br>";
				if (is_callable(array($this,$func))) {

					if (!empty($field)) {
						//echo "s: $field_name to: $field <br>";
						call_user_func(array(&$this,$func),$field);

					}
				}
			}
		}
	}

	function populate_array($results) {
		  if (is_array($results)) {
			foreach ($results as $field_name => $field) {
				$func = "set_" . $field_name;
				//echo "f: $field m: $func status: " .  (is_callable(array($this,$func))? "yes" : "no") . "<br>";
				if (is_callable(array($this,$func))) {

					if (!empty($field)) {
						//echo "s: $field_name to: $field <br>";
						call_user_func(array(&$this,$func),$field);

					}
				}
			}
		}
	}

	/**
	 * Helper function that loads enumerations from the data as an array, this is also efficient
	 * because it uses psuedo-class variables so that it doesnt have to do database work for each instance
	 *
	 * @param string $field_name name of the enumeration in this objects table
	 * @param boolean $blank optional value to include a empty element at position 0, default is true
	 * @return array array of values as name to index pairs found in the db enumeration of this field
	 */
	function _load_enum($field_name,$blank = true) {
		if (!empty($GLOBALS['static']['enums'][$this->_table][$field_name])
			&& is_array($GLOBALS['static']['enums'][$this->_table][$field_name])
			&& !empty($this->_table)) 												{

			return $GLOBALS['static']['enums'][$this->_table][$field_name];
		}
		else {
            $sql = "SHOW COLUMNS FROM ".$this->_table." LIKE '".$field_name."'";
            $cols = sqlQuery($sql);
            if (!empty($cols)) {
                $type_dec = $cols['Type'];
                $regex = "/'(.*?)'/";
                preg_match_all($regex , $type_dec, $enum_array);
                $enum = $enum_array[1];


                array_unshift($enum," ");

               if (!$blank) {
                 unset($enum[0]);
               }
                $enum = array_flip($enum);
                $GLOBALS['static']['enums'][$this->_table][$field_name] = $enum;

             }
			return $enum;
		}
	}

	function _utility_array($obj_ar,$reverse=false,$blank=true, $name_func="get_name", $value_func="get_id") {
		$ar = array();
		if ($blank) {
			$ar[0] = " ";
		}
		if (!is_array($obj_ar)) return $ar;
		foreach($obj_ar as $obj) {
			$ar[$obj->$value_func()] = $obj->$name_func();
		}
		if ($reverse) {
			$ar = array_flip($ar);
		}
		return $ar;
	}

} // end of ORDataObject
?>
