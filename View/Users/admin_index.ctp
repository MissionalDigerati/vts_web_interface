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
<h1>Manage Users</h1>
<?php echo $this->Html->link(__('Add User'), array('controller'	=>	'users',	'action'	=>	'add',	'admin'	=>	true), array('class' => 'btn pull-right')); ?>
<div id="list-users manage">
	<div class="clear"></div><br>
	<table class="table table-striped table-bordered table-condensed">
	  <tbody>
			<tr>
				<th><?php echo $this->Paginator->sort('name', 'Name'); ?></th>
				<th><?php echo $this->Paginator->sort('email', 'Email'); ?></th>
				<th><?php echo $this->Paginator->sort('role', 'Role'); ?></th>
				<th><?php echo $this->Paginator->sort('active', 'Status'); ?></th>
				<th><?php echo $this->Paginator->sort('created', 'Joined On'); ?></th>
				<th></th>
			</tr>
	    <?php foreach($users as $user): ?>
				<tr>
			    <td><?php echo $user['User']['name']; ?></td>
					<td><?php echo $user['User']['email']; ?></td>
					<td><?php echo ucwords(strtolower($user['User']['role'])); ?></td>
					<td><?php 
						if($user['User']['active'] == 1):
							echo 'Active';
						else:
							echo 'Pending';
						endif;
					?></td>
					<td><?php echo $this->Time->nice($user['User']['created']); ?></td>
			    <td class="actions">
			    	<?php
			 			if($this->Session->read('Auth.User.id') == $user['User']['id']):
							echo $this->Html->link(__('My Account'), '/my-account', array('class' => 'btn'));
						else:
							echo $this->element('../Users/_manage_button', array('user'	=>	$user));
						endif; 
					?>
			    </td>
			  </tr>
			<?php endforeach; ?>
	  </tbody>
	</table>
	<?php echo $this->element('_pagination_links'); ?>
</div>