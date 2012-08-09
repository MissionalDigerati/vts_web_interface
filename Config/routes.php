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
Router::connect('/translations/:translation_id/clip/:number/add', array('controller' => 'translation_clips', 'action' => 'add'),  array('pass' => array('translation_id', 'number'), 'translation_id' => '[0-9]+', 'number' => '[0-9]+'));
Router::connect('/translations/:translation_id/clip/:number/edit', array('controller' => 'translation_clips', 'action' => 'edit'),  array('pass' => array('translation_id', 'number'), 'translation_id' => '[0-9]+', 'number' => '[0-9]+'));
/**
 * Translation Routes
 *
 * @author Johnathan Pulos
 */
Router::connect('/translations/download/:id', array('controller' => 'translations', 'action' => 'download'), array('pass' => array('id'), 'id' => '[a-zA-Z0-9]+'));
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
 * Recorder Routes
 *
 * @author Johnathan Pulos
 */
Router::connect('/recorder/upload', array('controller' => 'recorder', 'action' => 'upload'), array());
Router::connect('/recorder/has_uploaded', array('controller' => 'recorder', 'action' => 'has_uploaded'), array());

Router::connect('/', array('controller' => 'pages', 'action' => 'home'));
Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
Router::parseExtensions('json');

CakePlugin::routes();

require CAKE . 'Config' . DS . 'routes.php';