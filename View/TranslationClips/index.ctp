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
$count = 1;
?>
<div class="page-header">
  <h1>
		<?php echo $translation['Translation']['title']; ?> (<?php echo $translation['Translation']['language']; ?>)
		<?php
			if($renderState == 'READY'):
				echo $this->Html->link(__('Render'), '/translations/render_video/'.$translation['Translation']['id'], array('class' => 'btn pull-right btn-large btn-inverse'));
			elseif($renderState == 'PROCESSING'):
				echo $this->Html->link(__('Processing'), '', array('class' => 'btn pull-right btn-large btn-inverse disabled', 'title' => 'Please wait.  It is rendering.'));
			elseif($renderState == 'COMPLETE'):
				echo $this->Html->link(__('View Video'), array('controller'=>	'translations', 'action'	=>	'view', $translation['Translation']['id']), array('class' => 'btn pull-right btn-large btn-inverse')); 
			else:
				echo $this->Html->link(__('Render'), '', array('class' => 'btn pull-right btn-large btn-inverse disabled', 'title' => 'Please upload all clips before rendering.')); 
			endif;
		?>
	</h1>
</div>
<p><?php echo __('In order to render this translation,  please upload all required audio clips.  The "Render" button will remain disabled until the server has completed processing the files'); ?>
<div class="page-header">
  <h2>Audio Clips</h2>
</div>
<?php if(($renderState == 'READY')  || ($renderState == 'PENDING')): ?>
	<div class="form">
		<?php foreach($videoClipsNeeded['compassionateFather'] as $videoClip): ?>
			<?php 
				echo $this->element('../TranslationClips/_form', array(	'translationId' => $translation['Translation']['id'], 
																																'videoFileUrl' => $videoClip, 
																																'translationToken' => $translation['Translation']['token'], 
																																'count' => $count));
			 $count = $count+1;
			?>
		<?php endforeach; ?>
	</div>
<?php else: ?>
	<p>If you made a mistake, and need to edit your audio clips,  please <?php echo $this->Html->link(__('click here'), '/translations/remove_video/'.$translation['Translation']['id'], array());?>.  Please Note,  you will need to re-render the video when your done.</p>
<?php endif; ?>