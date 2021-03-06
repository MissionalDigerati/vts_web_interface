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
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Pages';
	
	/**
	 * Define pagination settings
	 *
	 * @var array
	 */
	public $paginate = array('limit' => 25, 'order' => array('Translation.modified' => 'desc', 'Translation.created' => 'desc'), 'conditions' => array('Translation.status' => 'PUBLISHED'));

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Translation');
	
	/* Declare CakePHP's callback
	 *
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('*');
	}
	
	/**
	 * The home page for the site
	 *
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function home() {
		$this->Translation->recursive = 0;
		$this->set('translations', $this->paginate());
	}

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 */
	public function display() {
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		$this->set(compact('page', 'subpage', 'title_for_layout'));
		$this->render(implode('/', $path));
	}
}
