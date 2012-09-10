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
															'Cookie',
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
	public $helpers = array('TwitterBootstrap', 'Form', 'Html' => array('className' => 'CustomHtml'), 'Session', 'Date', 'VtsApi');
	
	/**
	 * The sites current locale setting
	 *
	 * @var string
	 */
	public $locale = '';
	
	/**
	 * Define a CakePHP callback beforeFilter
	 *
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function beforeFilter() {
		$this->setLanguage();
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
	
	/**
	 * set the current language
	 *
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 * @link http://colorblindprogramming.com/multiple-languages-in-a-cakephp-2-application-in-5-steps
	 */
	private function setLanguage() {
		$lang = Configure::read('Config.language');
		/**
		 * if the cookie was previously set, and Config.language has not been set write the Config.language with the value from the Cookie
		 *
		 * @author Johnathan Pulos
		 */
		if ($this->Cookie->read('lang') && !$lang) {
			$lang = $this->Cookie->read('lang');
		}else if(isset($this->params['language']) && ($this->params['language'] !=  $lang)) {
			/**
			 * then update the value in Session and the one in Cookie
			 *
			 * @author Johnathan Pulos
			 */
			$lang = $this->params['language']; 
			$this->Cookie->write('lang', $lang, false, '20 days');
		}
		Configure::write('Config.language', $lang);
		$this->locale = $lang;
	}

	/**
	 * override redirect method
	 *
	 * @param mixed $url 
	 * @param string $status 
	 * @param string $exit 
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 * @link http://colorblindprogramming.com/multiple-languages-in-a-cakephp-2-application-in-5-steps
	 */
	public function redirect($url, $status = NULL, $exit = true) {
		$lang = Configure::read('Config.language');
		if(is_array($url)) {
			if (!isset($url['language']) && $lang) {
				$url['language'] = $lang;
			}
		}else{
			if ($lang) {
				$url = '/' . $lang . $url;
			}
		}
		parent::redirect($url,$status,$exit);
	}

	/**
	 * iterates over cakePHP's invalidFields() errors, and returns a string of the errors.
	 *
	 * @param array $errors the errors received from invalidFields()
	 * @return string
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function ppErrors($errors) {
		$errorMsg = '';
		foreach($errors as $key => $value) {
			foreach ($value as $individualError) {
				if(strpos($errorMsg, $individualError) === false) {
					$errorMsg .= $individualError . " ";
				}
			}
		}
		return trim($errorMsg);
	}

}
