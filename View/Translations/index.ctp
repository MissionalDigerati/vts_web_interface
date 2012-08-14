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
			</tr>
		</thead>
	  <tbody>
	    <?php foreach($translations as $translation): ?>
				<tr>
			    <td class="title">
						<?php echo $translation['Translation']['title']; ?> (<?php echo $translation['Translation']['language']; ?>)
						<?php $labelType = (strtolower($translation['Translation']['status']) == 'published') ? 'label-success' : 'label-info'; ?>
						<span class="label <?php echo $labelType; ?> pull-right">
							<Info><?php echo $this->VtsApi->translateStatus($translation['Translation']['status']); ?></Info>
						</span>
					</td>
			    <td class="actions">
						<?php
						 	if(strtolower($translation['Translation']['status']) == 'published'):
								echo $this->Html->link('<i class="icon-zoom-in"></i> ' . __('View'), array('controller'	=>	'translations',	'action'	=>	'view', 'admin'	=>	false, $translation['Translation']['id']), array('escape'	=>	false, 'class'	=>	'btn'));
							else:
								echo $this->element('../Translations/_manage_button', array('translation'	=>	$translation)); 
							endif;
						?>
					</td>
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