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
	<div class="well">
		<?php
			if($translation['Translation']['isPlayable'] === true):
				echo $this->Html->link('<i class="icon-download-alt icon-white"></i> Download', array('controller'	=>	'translations', 'action'	=>	'download', $translation['Translation']['id']), array('target'	=> '_blank', 'class' =>	'pull-right btn btn-large btn-primary', 'escape'=> false)); 
			endif;
		?>
		<p><strong>Title:</strong> <?php echo $translation['Translation']['title']; ?></p>
		<p><strong>Language:</strong> <?php echo $translation['Translation']['language']; ?></p>
		<p><strong>Created On:</strong> <?php echo $this->Time->nice($translation['Translation']['created']); ?></p>
		<p><strong>Translated By:</strong> <?php echo $translation['User']['name']; ?></p>
		<div class="clear"></div>
	</div>
</div>