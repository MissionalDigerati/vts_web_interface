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
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {
	/**
	 * Define needed CakePHP Helpers
	 *
	 * @author Johnathan Pulos
	 * @access public
	 */
	public $helpers = array('Time');
	
	/**
	 * Define pagination settings
	 *
	 * @var array
	 */
	public $paginate = array('limit' => 25);
	
	/**
	 * Declare CakePHP's callback
	 *
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('join', 'login', 'activate', 'resend_activation', 'request_password_change', 'change_password');
	}
	
	/**
	 * add method Join
	 *
	 * @return void
	 */
	public function join() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data, true, $this->User->attrAccessible)) {
				$this->Session->setFlash(__('Your account has been added.  Please check the email that you provided to verify your account.'), '_flash_msg', array('msgType' => 'info'));
				$this->redirect('/');
			} else {
				$this->Session->setFlash(__('Unable to create your account. Please, try again.'), '_flash_msg', array('msgType' => 'error'));
				$this->request->data['User']['password'] = '';
				$this->request->data['User']['confirm_password'] = '';
			}
		}
	}
	
	/**
	 * Login to the website
	 *
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
			    $this->redirect($this->Auth->redirect());
			} else {
			  $this->Session->setFlash(__('Invalid username or password, or your account has not been activated yet. Please try again.'), '_flash_msg', array('msgType' => 'error'));
				$this->request->data['User']['password'] = "";
			}
    }
	}
	
	/**
	 * Activate a new user
	 *
	 * @param string $activation the activation code
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function activate($activation = null) {
		if(!$activation) {
			$this->Session->setFlash(__('Please provide an activation code.'), '_flash_msg', array('msgType' => 'error'));
			$this->redirect("/");
		}
		$user = $this->User->findByActivationHash($activation);
		$this->User->id = $user['User']['id'];
		$this->User->saveField('active', 1);
		$this->User->saveField('activation_hash', '');
		$this->Auth->login($user['User']);
		$this->Session->setFlash(__('Thank you.  Your account has been activated, and you have been logged in.'), '_flash_msg', array('msgType' => 'info'));
		$this->redirect('/');
	}
	
	/**
	 * Log out of the website
	 *
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function logout() {
		$this->redirect($this->Auth->logout());
	}
	
	/**
	 * view method My Account /my-account
	 *
	 * @return void
	 */
	public function my_account() {
		$id = $this->Auth->user('id');
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

	/**
	 * edit method /edit-account
	 *
	 * @return void
	 */
	public function edit_account() {
		$id = $this->Auth->user('id');
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if(!isset($this->request->data['User']['change_password'])) {
				/**
				 * They do not want to change their password, so unset fields and remove validation
				 *
				 * @author Johnathan Pulos
				 */
				$this->User->unbindValidation('remove', array('password', 'confirm_password'));
				unset($this->request->data['User']['password']);
				unset($this->request->data['User']['confirm_password']);
			}
			$this->request->data['User']['id'] = $id;
			if ($this->User->save($this->request->data, true, $this->User->attrAccessible)) {
				$this->Session->setFlash(__('Your account has been updated.'), '_flash_msg', array('msgType' => 'info'));
				$this->redirect('/my-account');
			} else {
				$this->Session->setFlash(__('Unable to update your account. Please, try again.'), '_flash_msg', array('msgType' => 'error'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}
		$this->request->data['User']['password'] = "";
		$this->request->data['User']['confirm_password'] = "";
	}
	
	/**
	 * Resend the activation code
	 *
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function resend_activation() {
		if ($this->request->is('post')) {
			$user = $this->User->findByEmail($this->request->data['User']['email']);
			if($user) {
				if($user['User']['active'] != 1) {
					$this->User->id = $user['User']['id'];
					$activationHash = $this->User->getActivationHash();
					if($this->User->saveField('activation_hash', $activationHash)) {
						$this->User->sendWelcome($user['User']['name'],  $activationHash, $user['User']['email']);
						$this->Session->setFlash(__('A new activation email has been sent.'), '_flash_msg', array('msgType' => 'info'));
					}else{
						$this->Session->setFlash(__('Unable to send you the activation email.'), '_flash_msg', array('msgType' => 'error'));
					}
				} else{
					$this->Session->setFlash(__('Your account has already been activated.'), '_flash_msg', array('msgType' => 'error'));
				}
			}else {
				$this->Session->setFlash(__('Unable to locate your account.'), '_flash_msg', array('msgType' => 'error'));
			}
			$this->redirect(array('controller'	=>	'users', 'action'		=>	'login'));
		}
	}
	
	/**
	 * Request to change your password
	 *
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function request_password_change() {
		if($this->request->is('post')) {
			$user = $this->User->findByEmail($this->request->data['User']['email']);
			if($user) {
				$this->User->id = $user['User']['id'];
				$activationHash = $this->User->getActivationHash();
				if($this->User->saveField('activation_hash', $activationHash)) {
					$this->User->sendChangePassword($user['User']['name'],  $activationHash, $user['User']['email']);
					$this->Session->setFlash(__('Instructions have been sent to your email.'), '_flash_msg', array('msgType' => 'info'));
				}else{
					$this->Session->setFlash(__('Unable to complete the request.'), '_flash_msg', array('msgType' => 'error'));
				}
			}else {
				$this->Session->setFlash(__('Unable to locate your account.'), '_flash_msg', array('msgType' => 'error'));
			}
			$this->redirect(array('controller'	=>	'users', 'action'		=>	'login'));
		}
	}
	
	/**
	 * Change Password
	 *
	 * @param string $activation the User.activation_hash
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function change_password($activation = null) {
		if(!$activation) {
			$this->Session->setFlash(__('Your access code does not exist.'), '_flash_msg', array('msgType' => 'error'));
			$this->redirect("/");
		}
		$user = $this->User->findByActivationHash($activation);
		if($this->request->is('post')) {
			/**
			 * We are only validating the password
			 *
			 * @author Johnathan Pulos
			 */
			$this->User->unbindValidation('keep', array('password', 'confirm_password'));
			$this->request->data['User']['id'] = $user['User']['id'];
			$this->request->data['User']['activation_hash'] = '';
			if ($this->User->save($this->request->data, true, array('password', 'activation_hash'))) {
				$this->Auth->login($user['User']);
				$this->Session->setFlash(__('Your account has been updated, and you have been logged in.'), '_flash_msg', array('msgType' => 'info'));
				$this->redirect('/');
			} else {
				$this->Session->setFlash(__('Unable to update your account. Please, try again.'), '_flash_msg', array('msgType' => 'error'));
			}
		}
		$this->request->data['User']['password'] = "";
		$this->request->data['User']['confirm_password'] = "";
	}

/**
 * ADMINISTRATION FUNCTIONS
 *
 * @author Johnathan Pulos
 */

	/**
	 * admin_index method
	 *
	 * @return void
	 */
	public function admin_index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

	/**
	 * admin_view method
	 *
	 * @param integer $id User.id
	 * @return void
	 */
	public function admin_view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
		$this->set('translations', $this->User->Translation->find('all', array('conditions'	=>	array('Translation.user_id'	=>	$id), 'order'	=>	array('Translation.modified' => 'desc', 'Translation.created' => 'desc'))));
	}

	/**
	 * admin_add method
	 *
	 * @return void
	 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->User->create();
			/**
			 * Tell the model that an admin is add the user
			 *
			 * @author Johnathan Pulos
			 */
			$this->User->requestedByAdmin = true;
			/**
			 * Only email if the user does not activate the account
			 *
			 * @author Johnathan Pulos
			 */
			$this->User->bypassWelcomeEmail = (isset($this->request->data['User']['active'])) ? true : false;
			/**
			 * Add additional attributes to the accessible list
			 *
			 * @author Johnathan Pulos
			 */
			$attrAccessible = array_merge($this->User->attrAccessible, array('role','active'));
			if ($this->User->save($this->request->data, true, $attrAccessible)) {
				if(!isset($this->request->data['User']['active'])) {
					$this->Session->setFlash(__('The user has been added, and an email was sent to them.'), '_flash_msg', array('msgType' => 'info'));
				}else {
					$this->Session->setFlash(__('The user has been added.'));
				}
				$this->redirect(array('controller'	=>	'users', 'action'	=>	'index', 'admin'	=>	true));
			} else {
				$this->Session->setFlash(__('Unable to create the account. Please, try again.'), '_flash_msg', array('msgType' => 'error'));
				$this->request->data['User']['password'] = '';
				$this->request->data['User']['confirm_password'] = '';
			}
		}
	}

	/**
	 * admin_edit method
	 *
	 * @param string $id
	 * @return void
	 */
	public function admin_edit($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if(!isset($this->request->data['User']['change_password'])) {
				/**
				 * They do not want to change their password, so unset fields and remove validation
				 *
				 * @author Johnathan Pulos
				 */
				$this->User->unbindValidation('remove', array('password', 'confirm_password'));
				unset($this->request->data['User']['password']);
				unset($this->request->data['User']['confirm_password']);
			}
			if(!isset($this->request->data['User']['active'])) {
				/**
				 * Checkbox hides active if set to zero, so let's set it
				 *
				 * @author Johnathan Pulos
				 */
				$this->request->data['User']['active'] = 0;
			}
			$this->request->data['User']['id'] = $id;
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been updated.'), '_flash_msg', array('msgType' => 'info'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be updated. Please, try again.'), '_flash_msg', array('msgType' => 'error'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}
		$this->request->data['User']['password'] = '';
		$this->request->data['User']['confirm_password'] = '';
		$isActive = ((isset($this->request->data['User']['active'])) && ($this->request->data['User']['active'] == 1)) ? true : false;
		$this->set('isActive', $isActive);
	}

	/**
	 * admin_delete method
	 *
	 * @param integer $id User.id
	 * @return void
	 */
	public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('The user account has been deleted.'), '_flash_msg', array('msgType' => 'info'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Unable to delete the user account.'), '_flash_msg', array('msgType' => 'error'));
		$this->redirect(array('action' => 'index'));
	}
}
