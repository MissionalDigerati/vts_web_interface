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
 * Import the Spyc library from the Vendor directory
 *
 * @author Johnathan Pulos
 */
App::import('Vendor', 'Spyc/spyc');
/**
 * A component for working with the Spyc library.  The Spyc library gives the ability to read YAML settings.  Make sure the Spyc library is in your vendor directory in a 
 * folder named Spyc.
 * 
 * @link http://code.google.com/p/spyc/
 * @package default
 * @author Johnathan Pulos
 */
class SpycYAMLComponent extends Component {

	/**
	 * The object of the Spyc library
	 *
	 * @var object
	 */
	public $spycYAML;
	/**
	 * Initialize the Component
	 *
	 * @param Controller $controller The Controller Object
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function initialize(Controller $controller) {
		$this->controller = $controller;
		$this->spycYAML = new Spyc();
	}
	
	/**
	 * Converts the YAML into an array
	 *
	 * @param string $yaml the yaml to convert (string or file)
	 * @return array
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function toArray($yaml) {
		return $this->spycYAML->YAMLLoad($yaml);
	}
	
	/**
	 * turn the array data into YAML
	 *
	 * @param array $data the data to convert
	 * @return string YAML format
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function toYAML($data = array()) {
		return $this->spycYAML->YAMLDump($data);
	}
	
}