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
$method = (array_key_exists($count, $currentClipOrderNumberAndIds)) ? 'edit' : 'add';
?>
<?php if(($method != 'edit') || (($method == 'edit') && (in_array($count, $show)))): ?>
	<div class="upload-translation">
		<?php 
		echo $this->Form->create('TranslationClip', array(	'inputDefaults' => $this->TwitterBootstrap->inputDefaults(), 
																												'class' 				=> 'form-horizontal', 
																												'type' 					=> 'file',
																												'id'						=>	'uploadAudioForm'.$count,
																												'url'						=>	'/translations/'.$translationId.'/clips/'.$method));
		?>
			<fieldset>
				<legend>Translation for Clip #<?php echo $count; ?></legend>
				<?php
					if($method == 'edit'):
						echo $this->Form->hidden('id', array('value' => $currentClipOrderNumberAndIds[$count]));
					endif;
				?>
				<?php echo $this->Form->hidden('video_file_location', array('value' => $videoFileUrl)); ?>
				<?php echo $this->Form->hidden('translation_request_token', array('value' => $translationToken)); ?>
				<?php echo $this->Form->hidden('clip_order', array('value' => $count)); ?>
				<?php echo $this->Form->input('audio_file', array('type' => 'file')); ?>
			</fieldset>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary">
					<?php 
						if($method == 'edit'):
							echo __('Update Clip #') . $count; 
						else:
							echo __('Add Clip #') . $count; 
						endif;
					?>
				</button>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
<?php else: ?>
	<fieldset>
		<legend>
			Translation for Clip #<?php echo $count; ?> Uploaded
			<?php echo $this->Html->link(__('Edit'), '/translations/'.$translationId.'/clips?show='.$count, array('class' => 'btn pull-right edit-clip')); ?>
		</legend>
	</fieldset>
<?php endif; ?>