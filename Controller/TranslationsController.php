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
 * A sample controller that interacts with the plugin
 *
 * @package default
 * @author Johnathan Pulos
 */
class TranslationsController extends AppController {
	
	/**
	 * Controller name
	 *
	 * @var string
	 * @access public
	 */
		public $name = 'Translations';

	/**
	 * This controller uses the plugins models
	 *
	 * @var array
	 */
		public $uses = array('Translation', 'VideoTranslatorService.TranslationRequest', 'VideoTranslatorService.MasterRecording');
		
	/**
	 * index method
	 *
	 * @return void
	 */
		public function index() {
			$this->set('translations', $this->Translation->find('all'));
		}
		
		/**
		 * Create a new translation
		 *
		 * @return void
		 * @access public
		 * @author Johnathan Pulos
		 */
		public function add() {
			$this->TranslationRequest->create();
			if(!empty($this->request->data)) {
				if ($this->TranslationRequest->save(array(), false)) {
					$translationRequest = $this->TranslationRequest->read(null, $this->TranslationRequest->id);
					$this->request->data['Translation']['token'] = $translationRequest['TranslationRequest']['token'];
					$this->request->data['Translation']['expires_at'] = $translationRequest['TranslationRequest']['expires_at'];
					if ($this->Translation->save($this->request->data, true)) {
						$this->Session->setFlash(__('The translation has been created.  Now upload your audio files for each clip.'));
						$this->redirect("/translations/" . $this->Translation->id . "/clips");
					} else{
						$this->Session->setFlash(__('Unable to save the translation.'));
					}
				}else {
					throw new CakeException(__('Unable to create the translation request.'));
				}
			}
		}
		
		/**
		 * Begin the video rendering process
		 *
		 * @param integer $id Translation.id
		 * @return void
		 * @access public
		 * @author Johnathan Pulos
		 */
		public function render_video($id = null) {
			$this->Translation->id = $id;
			if (!$this->Translation->exists()) {
				throw new NotFoundException(__('Invalid Translation'));
			}
			$translation = $this->Translation->read(null, $this->Translation->id);
			$finalFilename = $this->Translation->id . "_translated_video";
			$data = array(	'translation_request_token' => $translation['Translation']['token'],
											'title' 										=> $translation['Translation']['title'],
											'language' 									=> $translation['Translation']['language'],
											'final_filename'						=> $finalFilename
									);
			if($this->MasterRecording->save($data, false)) {
				$this->Translation->set('vts_master_recording_id', $this->MasterRecording->id);
				$this->Translation->set('master_recording_file', VTS_URL . 'files/master_recordings/' . $finalFilename . "/" . $finalFilename . ".mp4");
				$this->Translation->save();
				$this->Session->setFlash(__('The translation is being rendered.  This may take a few minutes.'));
				$this->redirect("/translations/" . $this->Translation->id . "/clips");
			}else {
				$this->Session->setFlash(__('Unable to render the translation.'));
				$this->redirect("/translations/" . $this->Translation->id . "/clips");
			}
		}
		
		public function remove_video($id = null) {
			$this->Translation->id = $id;
			if (!$this->Translation->exists()) {
				throw new NotFoundException(__('Invalid Translation'));
			}
			$translation = $this->Translation->read(null, $this->Translation->id);
			$this->MasterRecording->id = $translation['Translation']['vts_master_recording_id'];
		 	$this->MasterRecording->translation_request_token = $translation['Translation']['token'];
		 	if($this->MasterRecording->delete()) {
				$this->Translation->set('vts_master_recording_id', '');
				$this->Translation->set('master_recording_file', '');
				$this->Translation->save();
				$this->Session->setFlash(__('The translation video has been removed.  You may now edit your clips.'));
				$this->redirect("/translations/" . $this->Translation->id . "/clips");
			}else {
				$this->Session->setFlash(__('Unable to remove the translation video. Please try again later.'));
				$this->redirect("/translations/" . $this->Translation->id . "/clips");
			}
		}

}