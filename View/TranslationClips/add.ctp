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
$recorderSettings = array(	'clipNumber'	=>	$clipCount['current'], 
														'translationId'	=>	$translation['Translation']['id'], 
														'videoFileUrl'	=>	$currentClip['vts_video_file'], 
														'translationToken'	=>	$translation['Translation']['token']
													);
?>
<div class="recorder center">
	<?php echo $this->element('../TranslationClips/_details'); ?>
	<div class="tabbable"> <!-- Only required for left/right tabs -->
	  <ul class="nav nav-tabs">
	    <li class="active"><a href="#tab1" data-toggle="tab">Upload Audio</a></li>
	    <li><a href="#tab2" data-toggle="tab">Record in Browser</a></li>
	  </ul>
	  <div class="tab-content">
	    <div class="tab-pane active" id="tab1">
	      <p>I'm in Section 1.</p>
	    </div>
	    <div class="tab-pane" id="tab2">
	      <?php echo $this->element('../TranslationClips/_record', $recorderSettings); ?>
	    </div>
	  </div>
	</div>
</div>