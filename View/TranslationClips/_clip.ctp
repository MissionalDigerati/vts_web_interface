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
$heading = __("Clip %u of %u");
$heading = sprintf($heading, $clipNumber, $totalClips);
$labelType = ((!empty($clip)) && (strtolower($clip['TranslationClip']['vts_status']) == 'complete')) ? 'label-success' : 'label-info';
?>
<tr>
	<td colspan="2" class="center"><h2><?php echo $heading; ?></h2></td>
</tr>
<tr>
	<td class="video_view width_50">
		<?php
		 	if(empty($clip)):
				echo $this->Html->image($videoClipSettings['clip_'.$clipNumber]['local_image_file']);
			elseif(($clip['TranslationClip']['vts_file_path'] != '') && ($clip['TranslationClip']['vts_file_path'] != null)):
				echo $this->element('_quicktime_embed', array('videoLocation' => VTS_URL.$clip['TranslationClip']['vts_file_path']));
			else:
				echo $this->Html->image($videoClipSettings['clip_'.$clipNumber]['local_image_file']);
			endif;
		?>
	</td>
	<td class="actions width_50">
		<div class="well">
			<?php echo $videoClipSettings['clip_'.$clipNumber]['text']; ?>
		</div>
		<span class="label <?php echo $labelType; ?>">
			<Info><?php 
							if(!empty($clip)):
								echo $this->VtsApi->translateStatus($clip['TranslationClip']['vts_status']);
							else:
								echo __('Pending');
							endif;
						?></Info>
		</span>
		<?php 
			if($translation['Translation']['isEditable'] === true):
			
				if(empty($clip)):
					echo $this->Html->link(__('Add Audio'), '/translations/'.$translation['Translation']['id'].'/clip/'.$clipNumber.'/add', array('class' => 'btn pull-right'));
				elseif((strtolower($clip['TranslationClip']['vts_status']) == 'complete')):
					echo $this->Html->link(__('Edit Audio'), '/translations/'.$translation['Translation']['id'].'/clip/'.$clipNumber.'/edit/'.$clip['TranslationClip']['id'], array('class' => 'btn pull-right'));
				endif;
			
			endif;
	 	?>
	</td>
</tr>