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
 * Setup the email class
 *
 * @author Johnathan Pulos
 */
App::uses('CakeEmail', 'Network/Email');
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
																														'email'	=>	array(
																																								'rule'	=>	'email', 
																																								'message'	=> 'Must be a valid email address.'
																																							),
																														'mustBeUniqueEmail'	=>	array(
																																												'rule'	=>	'mustBeUniqueEmail', 
																																												'message'	=> 'Sorry,  that email address already has an account.'
																																											)
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
	 * Email must be unique, since it is the username
	 *
	 * @param array $field the field to check
	 * @return boolean
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function mustBeUniqueEmail($field = array()) {
		foreach($field as $key => $value) {
			$user = $this->find('first', array('conditions'	=>	array('email'	=>	$value)));
			if(!empty($user)) {
				if($user['User']['email'] == $value) {
					if(isset($this->data[$this->name]['id'])) {
						if ($this->data[$this->name]['id'] != $user['User']['id']) {
							return FALSE;
						}
					} else{
						return FALSE;
					}
				}
			}
		} 
		return TRUE;
	}
	
	/**
	 * Creates an activation hash for the current user.
	 *
	 * @param date $created_on User.created_on 
	 * @return string
	 * @author Technoguru Aka. Johnathan Pulos
	 */
	function getActivationHash() {  
		return substr(Security::hash(uniqid(date('YmdGis'))), 0, 25);
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


	public function afterSave($created = FALSE) {
		if($created === TRUE) {
			$email = $this->data[$this->name];
			exit;
			/**
			 * Send the user an email for validation
			 *
			 * @author Johnathan Pulos
			 */
		}
		return true;
	}
	
}
