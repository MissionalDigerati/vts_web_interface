Open Bible Stories Video Translator Service
===========================================

This is a sample application created for [Open Bible Stories](http://www.openbiblestories.com) using the [Video Translator API](https://github.com/MissionalDigerati/video_translator_service) and the [Video Translator Service CakePHP Plugin](https://github.com/MissionalDigerati/video_translator_service_cakephp_plugin).  You will need the Video Translator Service running on a separate instance, in order to use this app.  This application submodules the Video Translator Service CakePHP Plugin.

Requirements
------------

* PHP 5.28 >
* [CakePHP Framework 2.x](http://cakephp.org)
* A [Video Translation API](https://github.com/MissionalDigerati/video_translator_service) Instance running

Installation
------------

## Download the code

Download the latest version, and rename the downloaded folder to _VideoTranslatorService_. Then place the folder into your app/Plugin directory.

## Install plugin into App
*Add the following to your app/Config/core.php file and set to your API url*

```php
define("VTS_URL", "http://api.obs.local/");
```

*Add the following to your app/Config/database.php file*

```php
public $vtsTranslationRequest = array(
    'datasource' => 'VideoTranslatorService.TranslationRequestSource',
    'vtsUrl' => VTS_URL
);

public $vtsClip = array(
    'datasource' => 'VideoTranslatorService.ClipSource',
    'vtsUrl' => VTS_URL
);

public $vtsMasterRecording = array(
    'datasource' => 'VideoTranslatorService.MasterRecordingSource',
    'vtsUrl' => VTS_URL
);
```

*Add the following to your app/Config/bootstrap.php file*

```php
CakePlugin::load('VideoTranslatorService');
```

Development
-----------

Questions or problems? Please post them on the [issue tracker](https://github.com/MissionalDigerati/video_translator_service_cakephp_plugin/issues). You can contribute changes by forking the project and submitting a pull request.

This script is created by Johnathan Pulos and is under the [GNU General Public License v3](http://www.gnu.org/licenses/gpl-3.0-standalone.html).