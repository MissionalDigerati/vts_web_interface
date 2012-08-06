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
$(document).ready(function() {
	/**
	 * Resize the videos anytime they resize the page 
	 */
	resizeVideos();
	$(window).resize(function() {
			resizeVideos();
	});
	$(window).resize();
	/**
	 * Set on Timer to resize as well 
	 */
	window.setInterval(resizeVideos, 2000);
});
/**
 * Resize video based on the aspect width of the site
 * 0.5625 is 9 divided by 16 – the ratio of height to width in an HD video player.
 *  
 * http://technology.latakoo.com/2012/04/10/bootstrap-for-video/ 
 * 
 * @return void
 */
function resizeVideos() {
	var objectWidth = $('div.view').width();
    $('object').css({'height':(objectWidth * 0.5625)+'px', 'width': '100%'});
    $('video').css({'height':(objectWidth * 0.5625)+'px', 'width': '100%'});
    $('embed').css({'height':(objectWidth * 0.5625)+'px', 'width': '100%'});
};
