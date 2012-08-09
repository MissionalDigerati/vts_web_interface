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
<div class="translate-controls upload-controls">
	<?php 
	echo $this->Form->create('TranslationClip', array(	'inputDefaults' => $this->TwitterBootstrap->inputDefaults(), 
																											'class' 				=> 'form-horizontal', 
																											'type' 					=> 'file',
																											'id'						=>	'uploadAudioForm'.$clipNumber));
			if($translationClipId):
				echo $this->Form->hidden('id', array('value' => $translationClipId));
				echo $this->Form->hidden('vts_clip_id', array('value' => $vtsClipId));
			endif;
			echo $this->Form->hidden('video_file_location', array('value' => $videoFileUrl));
			echo $this->Form->hidden('translation_request_token', array('value' => $translationToken));
			echo $this->Form->hidden('clip_order', array('value' => $clipNumber));
			echo $this->Form->hidden('submission_type', array('value' => 'upload'));
			echo $this->Form->input('audio_file', array('type' => 'file', 'label' => false));
		?>
		<button type="submit" class="btn btn-primary">
			<?php echo __('Upload'); ?>
		</button>
	<?php echo $this->Form->end(); ?>
</div>