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
<div class="translation view">
	<?php echo $this->element('../Translations/_manage_button', array('translation'	=>	$translation, 'pull_right'	=>	true, 'isAdmin'	=> true)); ?>
	<div class="clear"></div><br>
	<div class="well">
		<p><strong>Title:</strong> <?php echo $translation['Translation']['title']; ?></p>
		<p><strong>Language:</strong> <?php echo $translation['Translation']['language']; ?></p>
		<p><strong>Status:</strong> <?php 
				if(!empty($translation['Translation']['master_recording_file'])) {
					echo 'Complete';
				}else {
					if($this->Date->isExpired($translation['Translation']['expires_at'])) {
						echo 'Expired';
					}else {
						echo 'Pending';
					}
				}
		?></p>
		<p><strong>Created On:</strong> <?php echo $this->Time->nice($translation['Translation']['created']); ?></p>
		<p><strong>Expires On:</strong> <?php echo $this->Time->nice($translation['Translation']['expires_at']); ?></p>
		<p><strong>Uploaded Audio For Clips:</strong> 
			<?php
				if(count($uploadedClips) == $maxClips):
					echo __("All");
				else:
					$clips = '';
					foreach($uploadedClips as $clip):
						$clips .= "#".$clip.", ";
					endforeach;
					echo substr_replace($clips ,"",-2);
				endif;
			?>
		</p>
		<div class="clear"></div>
	</div>
</div>