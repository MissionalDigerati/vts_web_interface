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
<div class="recorder center">
	<h1>Clip <?php echo $clipCount['current']; ?> of <?php echo $clipCount['total']; ?></h1>
	<?php echo $this->Html->image($currentClip['local_image_file']); ?>
	<div class="well text-to-translate">
		<?php echo $currentClip['text']; ?>
	</div>
	<div class="translate-controls">
		<span id="record-button-wrapper">
			<a href="" class="record-button btn btn-primary"><i class="icon-bullhorn icon-white"></i> <?php echo __('Record'); ?></a> 
		</span>
		<span id="stop-recording-button-wrapper" class="hidden">
			<a href="" class="stop-recording-button btn btn-primary"><i class="icon-stop icon-white"></i> <?php echo __('Stop'); ?></a> 
		</span>
		<a href="" class="next-clip-button btn"><?php echo __('Next'); ?> <i class="icon-chevron-right"></i></a>
	</div>
	<div id="recorder-wrapper"></div>
</div>