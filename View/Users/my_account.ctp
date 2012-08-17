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
<div class="user view account">
	<div class="well">
		<p><strong>Name:</strong> <?php echo $user['User']['name']; ?></p>
		<p><strong>Email:</strong> <?php echo $user['User']['email']; ?></p>
		<p><strong>Joined On:</strong> <?php echo $this->Time->nice($user['User']['created']); ?></p>
		<?php echo $this->Html->link(__('Edit Account'), $this->Html->appendLanguage('/edit-account'), array('class' => 'btn pull-right')); ?>
		<div class="clear"></div>
	</div>
</div>