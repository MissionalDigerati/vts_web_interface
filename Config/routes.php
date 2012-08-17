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
/**
 * TranslationClip Routes
 *
 * @author Johnathan Pulos
 */
Router::connect('/translations/:translation_id/clips', array('controller' => 'translation_clips', 'action' => 'index'), array('pass' => array('translation_id'), 'id' => '[0-9]+'));
Router::connect('/:language/translations/:translation_id/clips', array('controller' => 'translation_clips', 'action' => 'index'), array('pass' => array('language', 'translation_id'), array('language' => '[a-z]{3}', 'id' => '[0-9]+')));

Router::connect('/translations/:translation_id/clip/:clip_number/add', array('controller' => 'translation_clips', 'action' => 'add'),  array('pass' => array('translation_id', 'clip_number'), 'translation_id' => '[0-9]+', 'clip_number' => '[0-9]+'));
Router::connect('/:language/translations/:translation_id/clip/:clip_number/add', array('controller' => 'translation_clips', 'action' => 'add'),  array('pass' => array( 'translation_id', 'clip_number', 'language'), array('language' => '[a-z]{3}', 'translation_id' => '[0-9]+', 'clip_number' => '[0-9]+')));

Router::connect('/translations/:translation_id/clip/:clip_number/edit/:id', array('controller' => 'translation_clips', 'action' => 'edit'),  array('pass' => array('translation_id', 'clip_number', 'id'), 'translation_id' => '[0-9]+', 'clip_number' => '[0-9]+', 'id' => '[0-9]+'));
Router::connect('/:language/translations/:translation_id/clip/:clip_number/edit/:id', array('controller' => 'translation_clips', 'action' => 'edit'),  array('pass' => array('translation_id', 'clip_number', 'id', 'language'), array('language' => '[a-z]{3}', 'translation_id' => '[0-9]+', 'clip_number' => '[0-9]+', 'id' => '[0-9]+')));

/**
 * Translation Routes
 *
 * @author Johnathan Pulos
 */
Router::connect('/translations/download/:id', array('controller' => 'translations', 'action' => 'download'), array('pass' => array('id'), 'id' => '[a-zA-Z0-9]+'));
Router::connect('/:language/translations/download/:id', array('controller' => 'translations', 'action' => 'download'), array('pass' => array('id', 'language'), array('language' => '[a-z]{3}', 'id' => '[a-zA-Z0-9]+')));

Router::mapResources('translations');
/**
 * User Routes
 *
 * @author Johnathan Pulos
 */
Router::connect('/activate/:activation', array('controller' => 'users', 'action' => 'activate'), array('pass' => array('activation'), 'activation' => '[a-zA-Z0-9]+'));
Router::connect('/:language/activate/:activation', array('controller' => 'users', 'action' => 'activate'), array('pass' => array('activation', 'language'), array('language' => '[a-z]{3}', 'activation' => '[a-zA-Z0-9]+')));

Router::connect('/change_password/:activation', array('controller' => 'users', 'action' => 'change_password'), array('pass' => array('activation'), 'activation' => '[a-zA-Z0-9]+'));
Router::connect('/:language/change_password/:activation', array('controller' => 'users', 'action' => 'change_password'), array('pass' => array('activation', 'language'), array('language' => '[a-z]{3}', 'activation' => '[a-zA-Z0-9]+')));

Router::connect('/join', array('controller' => 'users', 'action' => 'join'));
Router::connect('/:language/join', array('controller' => 'users', 'action' => 'join'), array('pass' => array('language'), array('language' => '[a-z]{3}')));

Router::connect('/my-account', array('controller' => 'users', 'action' => 'my_account'));
Router::connect('/:language/my-account', array('controller' => 'users', 'action' => 'my_account'), array('pass' => array('language'), array('language' => '[a-z]{3}')));

Router::connect('/edit-account', array('controller' => 'users', 'action' => 'edit_account'));
Router::connect('/:language/edit-account', array('controller' => 'users', 'action' => 'edit_account'), array('pass' => array('language'), array('language' => '[a-z]{3}')));

Router::connect('/resend-activation', array('controller' => 'users', 'action' => 'resend_activation'));
Router::connect('/:language/resend-activation', array('controller' => 'users', 'action' => 'resend_activation'), array('pass' => array('language'), array('language' => '[a-z]{3}')));

Router::connect('/request-password-change', array('controller' => 'users', 'action' => 'request_password_change'));
Router::connect('/:language/request-password-change', array('controller' => 'users', 'action' => 'request_password_change'), array('pass' => array('language'), array('language' => '[a-z]{3}')));

Router::connect('/users/login', array('controller' => 'users', 'action' => 'login'));
Router::connect('/:language/users/login', array('controller' => 'users', 'action' => 'login'), array('pass' => array('language'), array('language' => '[a-z]{3}')));

Router::connect('/users/logout', array('controller' => 'users', 'action' => 'logout'));
Router::connect('/:language/users/logout', array('controller' => 'users', 'action' => 'logout'), array('pass' => array('language'), array('language' => '[a-z]{3}')));
/**
 * Recorder Routes
 *
 * @author Johnathan Pulos
 */
Router::connect('/recorder/upload', array('controller' => 'recorder', 'action' => 'upload'));
Router::connect('/:language/recorder/upload', array('controller' => 'recorder', 'action' => 'upload'), array('pass' => array('language'), array('language' => '[a-z]{3}')));

Router::connect('/recorder/has_uploaded', array('controller' => 'recorder', 'action' => 'has_uploaded'));
Router::connect('/:language/recorder/has_uploaded', array('controller' => 'recorder', 'action' => 'has_uploaded'), array('pass' => array('language'), array('language' => '[a-z]{3}')));

Router::connect('/', array('controller' => 'pages', 'action' => 'home'));
Router::connect('/:language', array('controller' => 'pages', 'action' => 'home'), array('pass' => array('language'), array('language' => '[a-z]{3}')));

Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
Router::parseExtensions('json');
Router::connect('/:language/:controller/:action/*', array(), array('language' => '[a-z]{3}'));
CakePlugin::routes();

require CAKE . 'Config' . DS . 'routes.php';