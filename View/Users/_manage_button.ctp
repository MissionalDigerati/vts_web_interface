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
?>
<div class="btn-group<?php if((isset($pull_right)) && ($pull_right === true)) { echo ' pull-right';} ?>">
  <a class="btn" href="#"><i class="icon-user"></i> Manage</a>
  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
  <ul class="dropdown-menu">
    <li>
				<?php 
					echo $this->Html->link('<i class="icon-zoom-in"></i> ' . __('View'), array('controller'	=>	'users', 'action'	=>	'view',	'admin'	=>	true, $user['User']['id']), array('escape'	=>	false)); 
				?>
		</li>
		<li>
				<?php 
					echo $this->Html->link('<i class="icon-pencil"></i> ' . __('Edit'), array('controller'	=>	'users', 'action'	=>	'edit',	'admin'	=>	true, $user['User']['id']), array('escape'	=>	false)); 
				?>
		</li>
		<li>
				<?php 
					echo $this->Form->postLink('<i class="icon-trash"></i> ' . __('Delete'), array('controller'	=>	'users', 'action'	=>	'delete',	'admin'	=>	true, $user['User']['id']), array('escape'	=>	false), sprintf(__('Are you sure you want to delete the account for %s?'), $user['User']['name'])); 
				?>
		</li>
		
  </ul>
</div>