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
?>
<tr>
	<td colspan="2" class="center"><h2><?php echo $heading; ?></h2></td>
</tr>
<tr>
	<td class="video_view width_50">
		<?php
		 	if(empty($clip)):
				echo $this->Html->image($videoClipData['clip_'.$clipNumber]['local_image_file']);
			elseif(($clip['TranslationClip']['vts_file_path'] != '') && ($clip['TranslationClip']['vts_file_path'] != null)):
				echo $this->element('_quicktime_embed', array('videoLocation' => VTS_URL.$clip['TranslationClip']['vts_file_path']));
			else:
				echo $this->Html->image($videoClipData['clip_'.$clipNumber]['local_image_file']);
			endif;
		?>
	</td>
	<td class="actions width_50"></td>
</tr>