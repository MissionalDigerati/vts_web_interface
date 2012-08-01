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
App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
	
	/** 
	 * Unbinds validation rules and optionally sets the remaining rules to required. 
	 * @link http://bakery.cakephp.org/articles/kiger/2008/12/29/simple-way-to-unbind-validation-set-remaining-rules-to-required
	 * 
	 * @param string $type 'Remove' = removes $fields from $this->validate 
	 *                       'Keep' = removes everything EXCEPT $fields from $this->validate 
	 * @param array $fields 
	 * @param bool $require Whether to set 'required'=>true on remaining fields after unbind 
	 * @return null 
	 * @access public 
	 */ 
	function unbindValidation($type, $fields, $require=false) { 
		if ($type === 'remove') { 
			$this->validate = array_diff_key($this->validate, array_flip($fields)); 
		} else if ($type === 'keep') { 
			$this->validate = array_intersect_key($this->validate, array_flip($fields)); 
		} 

		if ($require === true) { 
			foreach ($this->validate as $field=>$rules) { 
				if (is_array($rules)) { 
					$rule = key($rules); 
					$this->validate[$field][$rule]['required'] = true; 
				}else { 
					$ruleName = (ctype_alpha($rules)) ? $rules : 'required';
					$this->validate[$field] = array($ruleName=>array('rule'=>$rules,'required'=>true));
				} 
			} 
		} 
	}
}
