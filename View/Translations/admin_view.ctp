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
<div class="translation video_view">
	<?php 
		if($translation['Translation']['isPlayable'] === true):
			echo $this->element('_quicktime_embed', array('videoLocation' => $translation['Translation']['master_recording_file']));
		endif; 
	?>
	<?php echo $this->element('../Translations/_manage_button', array('translation'	=>	$translation, 'pull_right'	=>	true, 'isAdmin'	=> true)); ?>
	<div class="clear"></div><br>
	<div class="well">
		<?php 
			if($translation['Translation']['isPlayable'] === true):
				echo $this->Html->link('<i class="icon-download-alt icon-white"></i> Download', array('controller'	=>	'translations', 'action'	=>	'download', 'admin'	=>	false, $translation['Translation']['id']), array('target'	=> '_blank', 'class' =>	'pull-right btn btn-large btn-primary', 'escape'=> false)); 
			endif;
		?>
		<p><strong>Title:</strong> <?php echo $translation['Translation']['title']; ?></p>
		<p><strong>Language:</strong> <?php echo $translation['Translation']['language']; ?></p>
		<p><strong>Status:</strong> <?php echo $this->VtsApi->translateStatus($translation['Translation']['status']); ?></p>
		<p><strong>Created On:</strong> <?php echo $this->Time->nice($translation['Translation']['created']); ?></p>
		<p><strong>Expires On:</strong> <?php echo $this->Time->nice($translation['Translation']['expires_at']); ?></p>
		<p><strong>Uploaded By:</strong> <?php echo $this->Html->link($translation['User']['name'], array('controller'	=>	'users', 'action'	=>	'view', 'admin'	=>	true, $translation['User']['id'])); ?></p>
		<div class="clear"></div>
	</div>
</div>