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
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	/**
	 * Define components you will be using
	 *
	 * @var array
	 */
	public $components = array(	'DebugKit.Toolbar', 
															'Session',
															'Auth' => array(
																				        'authenticate' => array(
																															            'Form' => array('fields' => array('username' => 'email'),
																																														'scope'	=>	array('User.active'	=>	1)
																																													)
																				        )
															    )
														);
	/**
	 * Define helpers you will be using
	 *
	 * @var array
	 */
	public $helpers = array('TwitterBootstrap', 'Form', 'Html', 'Session');
	
	/**
	 * Define a CakePHP callback beforeFilter
	 *
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function beforeFilter() {
		/**
		 * Capture and redirect non-admins
		 *
		 * @author Johnathan Pulos
		 */
		if($this->request->prefix == 'admin') {
			if(!$this->Auth->user('id')) {
				$this->Session->setFlash(__('You do not have access to this area.'));
				$this->redirect('/');
			}else if ($this->Auth->user('role') != 'ADMIN') {
				$this->Session->setFlash(__('You do not have access to this area.'));
				$this->redirect('/');
			}
		}
	}
}
