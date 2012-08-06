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
	<?php echo $this->element('../Users/_manage_button', array('user'	=>	$user, 'pull_right'	=>	true)); ?>
	<div class="clear"></div><br>
	<h2>User Information</h2>
	<div class="well">
		<p><strong>Name:</strong> <?php echo $user['User']['name']; ?></p>
		<p><strong>Email:</strong> <?php echo $user['User']['email']; ?></p>
		<p><strong>Role:</strong> <?php echo ucwords(strtolower($user['User']['role'])); ?></p>
		<p><strong>Status:</strong> <?php 
			if($user['User']['active'] == 1):
				echo 'Active';
			else:
				echo 'Pending';
			endif;
		?></p>
		<p><strong>Joined On:</strong> <?php echo $this->Time->nice($user['User']['created']); ?></p>
		<div class="clear"></div>
	</div>
	
	<h2>User Translations</h2>
	<table class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th>Title</th>
				<th>Language</th>
				<th></th>
			</tr>
		</thead>
	  <tbody>
	    <?php foreach($translations as $translation): ?>
				<tr>
			    <td class="title"><?php echo $translation['Translation']['title']; ?></td>
					<td><?php echo $translation['Translation']['language']; ?></td>
			    <td class="actions"><?php echo $this->element('../Translations/_manage_button', array('translation'	=>	$translation, 'isAdmin'	=>	true)); ?></td>
			  </tr>
			<?php endforeach; ?>
			<?php if(empty($translations)): ?>
				<tr>
					<td colspan="3">No Translations</td>
				</tr>
			<?php endif; ?>
	  </tbody>
	</table>
</div>