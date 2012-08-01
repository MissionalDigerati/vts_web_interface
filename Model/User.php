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
																																								'message'	=> 'Must be a valid email address.',
																																								'required'	=>	true
																																							),
																														'mustBeUniqueEmail'	=>	array(
																																												'rule'	=>	'mustBeUniqueEmail', 
																																												'message'	=> 'Sorry,  that email address already has an account.'
																																											)
																													),
														'name'	=>							array(
																														'rule'	=>	'notEmpty',	
																														'message'	=>	'This field cannot be left blank.',
																														'required'	=>	true
																													),
														'password'	=>					array(
																														'notEmpty'	=>	array(
																																										'rule'	=>	'notEmpty',	
																																										'message'	=>	'This field cannot be left blank.',
																																										'required'	=>	true
																																									), 
																														'minLength'	=>	array(	
																																										'rule'	=>	array('minLength', '8'),	
																																										'message' => 'Minimum 8 characters long.'
																																									), 
																														'mustMatchConfirmPassword'	=>	array(	
																																										'rule'	=>	'mustMatchConfirmPassword',	
																																										'message' => 'Your password confirmation must match.'
																																									)
																												),
														'confirm_password'	=>	array(	'notEmpty'	=>	array(
																																										'rule'	=>	'notEmpty',	
																																										'message'	=>	'This field cannot be left blank.',
																																										'required'	=>	true
																																									), 
																														'minLength'	=>	array(	
																																										'rule'	=>	array('minLength', '8'),	
																																										'message' => 'Minimum 8 characters long.'
																																									)
																													)
													);

	
	/**
	 * Compares a field with its confirm_field, and returns true if they match
	 *
	 * @param string $field array of the field and its value
	 * @return boolean
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function mustMatchConfirmPassword($field = array()) {
		foreach($field as $key => $value) {
			if($value != $this->data[$this->name]["confirm_password"]) { 
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

	/**
	 * Call CakePHP's callback method afterSave
	 *
	 * @param boolean $created wheater they are creating or updating
	 * @return boolean
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function afterSave($created = FALSE) {
		if($created === TRUE) {
			$activationHash = $this->getActivationHash();
			$user = $this->read(null, $this->id);
			/**
			 * Setup the new user by setting their role and activation_hash
			 *
			 * @author Johnathan Pulos
			 */
			$this->saveField('role', 'USER');
			$this->saveField('activation_hash', $activationHash);
			$this->sendWelcome($user['User']['name'],  $activationHash, $user['User']['email']);
		}
		return true;
	}
	
	/**
	 * Send a welcome message
	 *
	 * @param string $name The User.name
	 * @param string $activationHash The User.activation_hash
	 * @param string $email The User.email
	 * @return boolean
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function sendWelcome($name,  $activationHash, $email) {
		$cakeEmail = new CakeEmail();
		$viewVars = array('name'	=>	$name, 'activationHash'	=>	$activationHash);
		try {
		    $cakeEmail->template('welcome', 'default')->
								emailFormat('both')->
								to($email)->
								from('info@openbiblestories.com')->
								subject('Welcome to Open Bible Stories')->
								viewVars($viewVars)->
								send();
				return true;
		} catch (Exception $e) {
			/**
			 * Email failed log it, and move on
			 *
			 * @author Johnathan Pulos
			 */
		  $this->log("[UNABLE TO EMAIL NEW USER] -> " . $email . " (" . $name . ")");
			return false;
		}
	}
	
}
