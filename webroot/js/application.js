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
/**
 * The recording form to be submitted after processing the clip
 * @param string 
 */
var recordingForm = '';
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
	if($('div.recorder').length > 0) {
		setupRecorder();
	}
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
    $('object').not('#audiorecorder').css({'height':(objectWidth * 0.5625)+'px', 'width': '100%'});
    $('video').not('#audiorecorder').css({'height':(objectWidth * 0.5625)+'px', 'width': '100%'});
    $('embed').not('#audiorecorder').css({'height':(objectWidth * 0.5625)+'px', 'width': '100%'});
};
/**
 * Javascript for the Recorder clip page 
 */
/**
 * Setup the recorder
 * 
 * @param void
 * @return void 
 */
function setupRecorder() {
	var recorderWidth = $('div.recorder').width();
	var flashLeftPosition = (recorderWidth-300)/2;
	var fileName = $('#TranslationClipAudioFilePath').val();
	$('.save-button').addClass('disabled');
	var settings = {
		 'rec_width': '300',
		 'rec_height': '200',
			'rec_top': '50%',
			'rec_left': flashLeftPosition+'px',
	   'swf_path': '/swf/jRecorder.swf',
	   'host': '/recorder/upload?file_name='+fileName,
	   'callback_started_recording' : function(){},
	   'callback_finished_recording' : function(){},
	   'callback_stopped_recording': function(){},
	   'callback_error_recording' : function(code){callbackJRecorderError(code);},
	   'callback_finished_sending' : function(){callbackJRecorderFinishedSending();},
	   'callback_activityTime': function(time){},
	   'callback_activityLevel' : function(level){}
	};
	$.jRecorder(settings, $('div#recorder-wrapper'));
	$('.record-button').click(function(){
	    $.jRecorder.record(300);
			$('span#record-button-wrapper').addClass('hidden');
			$('span#stop-recording-button-wrapper').removeClass('hidden');
			$('.save-button').addClass('disabled');
			return false;
	 });
	$('.stop-recording-button').click(function(){
	    $.jRecorder.stop();
			$('span#record-button-wrapper').removeClass('hidden');
			$('span#stop-recording-button-wrapper').addClass('hidden');
			$('.save-button').removeClass('disabled');
			return false;
	 });
	$('.save-button').click(function(){
			$('.record-button').addClass('disabled');
			var rel = $(this).attr('rel');
			recordingForm = '#'+rel;
	    $.jRecorder.sendData();
			pollServer('/recorder/has_uploaded?file_name='+fileName, {}, 'GET', 10, function() { $(recordingForm).submit(); });
			return false;
	 });
};
/**
 * polls the server and triggers callback when the resulting data is {status: 'success'} in json
 * @param String url the url to poll
 * @param Object params the params to pass
 * @param String method the method to use
 * @param Integer interval the number of seconds to poll the server
 * @param Function params a callback function to call
 * @return void
 */
function pollServer(url, params, method, inteval, callBack) {
	$.ajax({
	  url: url,
	  type: method,
	  dataType: 'json',
	  data: params,
	  success: function(data) {
			console.log(data);
			if(data['status'] == 'success') {
				callBack();
			}else{
				setTimeout(function(){pollServer(url, params, method, inteval, callBack);},inteval);
			}
	 },
	  error: function(jqXHR, textStatus, errorThrown) {
		    console.log("ERROR:"+textStatus+" "+errorThrown);
	  }
	});
};
/**
 * JRecorder is done sending the file to the server.  Callback for JRecorder
 * @return void
 *  
 */
function callbackJRecorderFinishedSending() {
};
/**
 * There was an error with the recording. Callback for JRecorder
 * @param string $code the error code
 * @return void 
 */
function callbackJRecorderError(code) {
    console.log('Error, code:' + code);
};