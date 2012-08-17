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
 * Handle internationalization
 *
 * @link http://colorblindprogramming.com/multiple-languages-in-a-cakephp-2-application-in-5-steps
 * @author Johnathan Pulos
 */
App::uses('HtmlHelper', 'View/Helper');
class CustomHtmlHelper extends HtmlHelper {
	
	/**
	 * Overwrite cakePHP's url method
	 *
	 * @param string $url 
	 * @param boolean $full 
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function url($url = null, $full = false) {
		if(!isset($url['language']) && isset($this->params['language'])) {
			$url['language'] = $this->params['language'];
		}
		return parent::url($url, $full);
	}
	
	/**
	 * Takes a static url, and appends the language code if it is defined
	 *
	 * @param string $staticUrl the static url with a leading slash
	 * @return string
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function appendLanguage($staticUrl = '') {
		if(!isset($url['language']) && isset($this->params['language'])) {
			return '/' . $this->params['language'] . $staticUrl;
		}else {
			return $staticUrl;
		}
	}

}