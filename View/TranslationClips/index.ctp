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
$heading = __("Clips for %s");
$totalClips = count($videoClipData);
?>
<div class="translation-clips index">
	<?php 
		if(strtolower($translation['Translation']['status']) == 'rendered'):
			echo $this->Html->link(__('Publish'), array('controller'	=>	'translations', 'action'	=>	'publish_video', $translation['Translation']['id']), array('class' => 'btn pull-right btn-large btn-primary'), __('Are you sure you want to publish this video? You will no longer be able to edit the video clips.'));
			echo $this->Html->link(__('Modify'), array('controller'	=>	'translations', 'action'	=>	'remove_video', $translation['Translation']['id']), array('class' => 'btn pull-right btn-small btn-inverse modify-btn'));
		elseif(strtolower($translation['Translation']['status']) == 'rendering'):
				echo $this->Html->link(__('Rendering'), '', array('class' => 'btn pull-right btn-large btn-inverse disabled'));
		elseif(strtolower($translation['Translation']['status']) == 'pending'):
			if((!empty($vtsClipData)) && (strtoupper($vtsClipData['Translation']['ready_for_processing']) == 'YES') && ($totalClips == count($vtsClipData['Clips']))):
				echo $this->Html->link(__('Render'), array('controller'	=>	'translations', 'action'	=>	'render_video', $translation['Translation']['id']), array('class' => 'btn pull-right btn-large btn-inverse')); 
			else:
				echo $this->Html->link(__('Render'), '', array('class' => 'btn pull-right btn-large btn-inverse disabled')); 
			endif;
		endif;
	?>
	<h1><?php echo sprintf($heading, $translation['Translation']['title']); ?></h1><br>
	<div class="clear"></div>
	<table class="table table-striped table-bordered table-condensed">
	  <tbody>
	<?php 
		for($i = 1; $i <= $totalClips; $i++):
			if(array_key_exists($i, $clipOrderNumberAndIdArray)):
				$translationClip = $clipOrderNumberAndIdArray[$i];
			else:
				/**
				 * Not added yet
				 *
				 * @author Johnathan Pulos
				 */
				$translationClip = array();
			endif;
			echo $this->element('../TranslationClips/_clip', array('clip'	=>	$translationClip, 'videoClipData'	=>	$videoClipData, 'totalClips' => $totalClips, 'clipNumber'	=>	$i));
		endfor; 
	?>
	  </tbody>
	</table>
</div>