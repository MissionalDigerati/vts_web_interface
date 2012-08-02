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
<h1>My Translations</h1>
<?php echo $this->Html->link(__('Add a Translation'), array('controller'	=>	'translations',	'action'	=>	'add',	'admin'	=>	false), array('class' => 'btn pull-right')); ?>
<div id="my-translations list-translations">
	<div class="clear"></div><br>
	<table class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th>Title (Language)</th>
				<th></th>
			</tr>
		</thead>
	  <tbody>
	    <?php foreach($translations as $translation): ?>
				<tr>
			    <td class="title"><?php echo $translation['Translation']['title']; ?> (<?php echo $translation['Translation']['language']; ?>)</td>
			    <td class="actions"><?php echo $this->Html->link(__('View'), "/translations/" . $translation['Translation']['id'] . "/clips", array('class' => 'btn'));?></td>
			  </tr>
			<?php endforeach; ?>
			<?php if(empty($translations)): ?>
				<tr>
					<td colspan="2">No Translations</td>
				</tr>
			<?php endif; ?>
	  </tbody>
	</table>
</div>