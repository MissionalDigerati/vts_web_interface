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
<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" height="360" width="640" scale="tofit"> 
	<param name="src" value="<?php echo $videoLocation; ?>">
	<param name="autoplay" value="false">
	<param name="wmode" value="transparent">
	<param name="type" value="video/quicktime" height="360" width="640">
	<embed src="<?php echo $videoLocation; ?>"quality="high" height="360" width="640" autoplay="false" scale="tofit" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/"></embed>
</object>