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
<?php echo $this->Form->create('Translation', array('inputDefaults' => $this->TwitterBootstrap->inputDefaults(), 'class' => 'form-horizontal'));?>
	<fieldset>
		<legend><?php echo ($method == 'EDIT') ? __('Edit Translation') : __('Add Translation'); ?></legend><br>
		<?php if($method == 'CREATE'): ?>
			<label for="TranslationVideoPrefix" class="control-label"><?php echo __('Video Title'); ?></label>
			<?php
					echo $this->Form->input('video_prefix', array('label'	=>	false, 'type'	=>	'select', 'options'	=>	$videoOptions));
				else: 
					echo $this->Form->input('video_prefix', array('type'	=>	'hidden'));
				endif;
		?>
	<label for="TranslationTitle" class="control-label"><?php echo __('Translated Title'); ?></label>
	<?php
		echo $this->Form->input('title', array('label'	=>	false));
		echo $this->Form->input('language', array('type' => 'string'));
	?>
	</fieldset>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary disable-after-click" rel="<?php echo ($method == 'EDIT') ? __('Updating Translation') : __('Creating Translation'); ?>">
			<?php echo ($method == 'EDIT') ? __('Update Translation') : __('Create Translation'); ?>
		</button>
	</div>
<?php echo $this->Form->end(); ?>