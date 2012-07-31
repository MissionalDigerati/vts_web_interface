<?php
/**
 * This file is part of Video Translator Service Website Example.
 * 
 * Video Translator Service Website Example is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Video Translator Service Website Example is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see 
 * <http://www.gnu.org/licenses/>.
 *
 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
 * @copyright Copyright 2012 Missional Digerati
 * 
 */
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 */
class User extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
	
	/**
	 * An array of fields that can be modified by a form
	 *
	 * @var array
	 */
	public $attrAccessible = array('name', 'email', 'password');
	
	/**
	 * Define validations for model
	 *
	 * @var array
	 */
	public $validate = array(	'email'	=>							array(
																														'rule'	=>	'email', 
																														'message'	=> 'Must be a valid email address.'
																													),
														'name'	=>							array(
																														'rule'	=>	'notEmpty',	
																														'message'	=>	'This field cannot be left blank.'
																													),
														'password'	=>					array(
																														'notEmpty'	=>	array(
																																										'rule'	=>	'notEmpty',	
																																										'message'	=>	'This field cannot be left blank.'
																																									), 
																														'minLength'	=>	array(	
																																										'rule'	=>	array('minLength', '8'),	
																																										'message' => 'Minimum 8 characters long.'
																																									), 
																														'mustMatchConfirm'	=>	array(	
																																										'rule'	=>	'mustMatchConfirm',	
																																										'message' => 'Your password confirmation must match.'
																																									)
																												),
														'confirm_password'	=>	array(	'notEmpty'	=>	array(
																																										'rule'	=>	'notEmpty',	
																																										'message'	=>	'This field cannot be left blank.'
																																									), 
																														'minLength'	=>	array(	
																																										'rule'	=>	array('minLength', '8'),	
																																										'message' => 'Minimum 8 characters long.'
																																									)
																													)
													);

	
	/**
	 * Compares a field with its confirm_field, and returns tru if they match
	 *
	 * @param string $field array of the field and its value
	 * @return boolean
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function mustMatchConfirm($field = array()) {
		foreach($field as $key => $value) {
			if($value != $this->data[$this->name]["confirm_" . $key]) { 
				return FALSE; 
			}
		} 
		return TRUE;
	}
	
	/**
	 * Call CakePHP's callback beforeSave
	 *
	 * @param array $options array of options
	 * @return boolean
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function beforeSave($options = array()) {
		if((isset($this->data[$this->name]['password'])) && (!empty($this->data[$this->name]['password']))) {
			/**
			 * Hash the password
			 *
			 * @author Johnathan Pulos
			 */
			$this->data[$this->name]['password'] = AuthComponent::password($this->data[$this->name]['password']);
		}
    return true;
  }
	
}
