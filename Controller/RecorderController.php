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
/**
 * Import the AppController
 *
 * @author Johnathan Pulos
 */
App::uses('AppController', 'Controller');
/**
 * Import the Uploader class in the Vendor Directory
 *
 * @author Johnathan Pulos
 */
App::import('Vendor', 'Uploader.Uploader');
/**
 * A sample controller that interacts with the plugin
 *
 * @package default
 * @author Johnathan Pulos
 */
class RecorderController extends AppController {
	/**
	 * Controller name
	 *
	 * @var string
	 * @access public
	 */
		public $name = 'Recorder';

	/**
	 * This controller uses the plugins models
	 *
	 * @var array
	 */
		public $uses = array('VideoTranslatorService.Clip', 'VideoTranslatorService.MasterRecording');
		
		/**
		 * Call any necessary Components
		 *
		 * @var array
		 */
		public $components = array('SpycYAML');

		/**
		 * Declare a cakePHP callback to set the video clips needed
		 *
		 * @return void
		 * @access public
		 * @author Johnathan Pulos
		 */
		public function beforeFilter() {	
			parent::beforeFilter();
		}
		
		/**
		 * Display the recorder for this clip
		 *
		 * @param integer $number the clip number to work with
		 * @return void
		 * @access public
		 * @author Johnathan Pulos
		 */
		public function clip($number = null) {
			if (!$number) {
				throw new NotFoundException(__('Invalid Clip Number'));
			}
			$videoClips = $this->SpycYAML->toArray(ROOT . DS . APP_DIR . DS . 'Config' . DS . 'clip_settings.yml');
			$this->set('currentClip', $videoClips['clip_' . $number]);
			$this->set('clipCount', array('current'	=>	$number, 'total'	=>	count($videoClips)));
		}

}