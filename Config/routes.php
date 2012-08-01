<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
Router::connect('/translations/:translation_id/clips', array('controller' => 'translation_clips', 'action' => 'index'), array('pass' => array('translation_id'), 'id' => '[0-9]+'));
Router::connect('/translations/:translation_id/clips/add', array('controller' => 'translation_clips', 'action' => 'add'), array('pass' => array('translation_id'), 'id' => '[0-9]+'));
Router::connect('/translations/:translation_id/clips/edit', array('controller' => 'translation_clips', 'action' => 'edit'), array('pass' => array('translation_id'), 'id' => '[0-9]+'));
Router::mapResources('translations');
/**
 * User Routes
 *
 * @author Johnathan Pulos
 */
Router::connect('/activate/:activation', array('controller' => 'users', 'action' => 'activate'), array('pass' => array('activation'), 'activation' => '[a-zA-Z0-9]+'));
Router::connect('/change_password/:activation', array('controller' => 'users', 'action' => 'change_password'), array('pass' => array('activation'), 'activation' => '[a-zA-Z0-9]+'));
Router::connect('/join', array('controller' => 'users', 'action' => 'join'));
Router::connect('/my-account', array('controller' => 'users', 'action' => 'my_account'));
Router::connect('/edit-account', array('controller' => 'users', 'action' => 'edit_account'));
Router::connect('/resend-activation', array('controller' => 'users', 'action' => 'resend_activation'));
Router::connect('/request-password-change', array('controller' => 'users', 'action' => 'request_password_change'));
Router::connect('/users/login', array('controller' => 'users', 'action' => 'login'));
Router::connect('/users/logout', array('controller' => 'users', 'action' => 'logout'));
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'translations', 'action' => 'index'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * Load all plugin routes.  See the CakePlugin documentation on 
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
