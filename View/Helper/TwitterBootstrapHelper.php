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
App::uses('AppHelper', 'View/Helper');

class TwitterBootstrapHelper extends AppHelper {
  /**
   * Setup which helpers you need for this helper
   *
   * @var string
   */  
	public $helpers = array();

	/**
	 * Sends back an array of input defaults to match Twitter Bootstrap styling
	 * @link http://stackoverflow.com/questions/9129214/cakephp-2-0-with-twitter-bootstrap
	 *
	 * @return array
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function inputDefaults() {
		return array(
							    'div' => 'control-group',
							    'label' => array('class' => 'control-label'),
							    'between' => '<div class="controls">',
							    'after' => '</div>',
							    'class' => 'span3',
							    'error' => array('attributes' => array('wrap' => 'div', 'class' => 'alert alert-error'))
								);
	}

}