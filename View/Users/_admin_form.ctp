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
	echo $this->Form->input('name');
	echo $this->Form->input('role', array('type'	=>	'select', 'options'	=>	array('USER'	=>	'User', 'ADMIN'	=>	'Administrator')));
	echo $this->Form->input('email');
	echo $this->Form->input('password');
	echo $this->Form->input('confirm_password', array('type'=>	'password'));
	if($method == 'ADD'):
		?>
		<div class="control-group">
			<div class="controls">
				<label class="checkbox">
				<input type="checkbox" name="data[User][active]" value="1">
					Activate User
				</label>
				<p class="help-block">If you do not check "Activate User",  the user will be emailed to activate their account.</p>
			</div>
		</div>
	</fieldset>
		<?php
	endif;
?>