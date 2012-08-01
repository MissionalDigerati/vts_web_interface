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
	 * Declare CakePHP's callback
	 *
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('add', 'login', 'activate');
	}
/**
 * view method My Account
 *
 * @return void
 */
	public function view() {
		$id = $this->Auth->user('id');
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
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
			    $this->Session->setFlash(__('Invalid username or password, or your account has not been activated yet. Please try again.'));
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
			$this->Session->setFlash(__('Please provide an activation code.'));
			$this->redirect("/");
		}
		$user = $this->User->findByActivationHash($activation);
		$this->User->id = $user['User']['id'];
		$this->User->saveField('active', 1);
		$this->User->saveField('activation_hash', '');
		$this->Auth->login($user['User']);
		$this->Session->setFlash(__('Thank you.  Your account has been activated, and you have been logged in.'));
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
 * add method Join
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data, true, $this->User->attrAccessible)) {
				$this->Session->setFlash(__('Your account has been added.  Please check the email that you provided to verify your account.'));
				$this->redirect('/');
			} else {
				$this->Session->setFlash(__('Unable to create your account. Please, try again.'));
				$this->request->data['User']['password'] = '';
				$this->request->data['User']['confirm_password'] = '';
			}
		}
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}
	}


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
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
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
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}
	}

/**
 * admin_delete method
 *
 * @param string $id
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
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
