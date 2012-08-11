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
<h1>Welcome to Open Bible Stories Video Translator Service</h1>
<div class="pages home video_view">
	<table class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th class="center">Available Translations</th>
			</tr>
		</thead>
	  <tbody>
	    <?php foreach($translations as $translation): ?>
				<tr>
			    <td class="title">
						<?php 
							echo $this->Html->link('<i class="icon-zoom-in"></i> ' . __('View'), array('controller'	=>	'translations', 'action'	=>	'view', $translation['Translation']['id']), array('class'=>	'btn pull-right view-home', 'escape'	=>	false)); 
						?>
						<?php echo $translation['Translation']['title']; ?> (<?php echo $translation['Translation']['language']; ?>)<br>
						<em>Translated By:<?php echo $translation['User']['name']; ?></em>
					</td>
			  </tr>
			<?php endforeach; ?>
			<?php if(empty($translations)): ?>
				<tr>
					<td>No Translations</td>
				</tr>
			<?php endif; ?>
	  </tbody>
	</table>
	<?php echo $this->element('_pagination_links'); ?>
</div>