VTS Web Interface
=================

This is a sample web interface created for crowd sourcing the translation of video using the [VTS API](https://github.com/MissionalDigerati/vts_api) and the [VTS CakePHP Plugin](https://github.com/MissionalDigerati/vts_cakephp_plugin).  You will need the VTS API Service running on a separate instance, in order to use this app.  This application submodules the [VTS CakePHP Plugin](https://github.com/MissionalDigerati/vts_cakephp_plugin).

Package
-------

This is part of the Video Translating Service Package (VTS) which consists of the following repositories:

* [VTS API](https://github.com/MissionalDigerati/vts_api)
	* Submodule - [VTS Rendering Engine](https://github.com/MissionalDigerati/vts_rendering_engine)
* [VTS CakePHP Web Interface](https://github.com/MissionalDigerati/vts_web_interface)	
	* Submodule - [VTS CakePHP 2 Plugin](https://github.com/MissionalDigerati/vts_cakephp_plugin)
	
To use this service, you will need one instance of the [VTS API](https://github.com/MissionalDigerati/vts_api) running on a stand alone server, and the [VTS CakePHP Web Interface](https://github.com/MissionalDigerati/vts_web_interface) running on a separate server.

Requirements
------------

* PHP 5.28 >
* [CakePHP Framework 2.x](http://cakephp.org)
* A [VTS API](https://github.com/MissionalDigerati/video_translator_service) Instance running

Installation
------------

* Download the latest version
* Download [CakePHP Framework 2.x](http://cakephp.org)
* Replace the app directory with this code, and rename it app
* Download the [CakePHP DebugKit](https://github.com/cakephp/debug_kit), and place it in your app/Plugin directory

Development
-----------

Questions or problems? Please post them on the [issue tracker](https://github.com/MissionalDigerati/vts_web_interface/issues). You can contribute changes by forking the project and submitting a pull request.

This script is created by Johnathan Pulos and is under the [GNU General Public License v3](http://www.gnu.org/licenses/gpl-3.0-standalone.html).