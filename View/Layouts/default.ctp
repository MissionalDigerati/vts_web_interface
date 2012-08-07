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
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo __('Open Bible Stories:: Translator'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <?php echo $this->Html->css(array('bootstrap.min', 'bootstrap-responsive.min', 'application')); ?>
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="/"><?php echo __('Open Bible Stories'); ?></a>
          <div class="nav-collapse">
            <ul class="nav pull-right">
              <li><a href="/"><?php echo __('Home'); ?></a></li>
							<?php 
								if($this->Session->read('Auth.User.id')):
									echo '<li>' . $this->Html->link(__('Welcome ') . $this->Session->read('Auth.User.name'), '/my-account', array('title'	=>	'View My Account')) . '</li>';
									echo '<li>' . $this->Html->link(__('My Translations'), array('controller'	=>	'translations', 'action'	=>	'index',	'admin'	=>	false)) . '</li>';
									if($this->Session->read('Auth.User.role') == 'ADMIN'):
										?>
										<li class="dropdown" id="#manage" class="active">
											<a href="#manage" class="dropdown-toggle" data-toggle="dropdown">Manage<b class="caret"></b></a>
											<ul class="dropdown-menu">
												<li><?php echo $this->Html->link('Users', array('controller' => 'users', 'action' => 'index', 'admin'=> true)); ?></li>
												<li><?php echo $this->Html->link('Translations', array('controller' => 'translations', 'action' => 'index', 'admin'=> true)); ?></li>
											</ul>
										</li>
										<?php
									endif;
									echo '<li>' . $this->Html->link(__('Logout'), array('controller' => 'users', 'action' => 'logout', 'admin'	=>	false)) . '</li>';
								else:
									echo '<li>' . $this->Html->link(__('Login'), array('controller' => 'users', 'action' => 'login', 'admin'	=>	false)) . '</li>';
								endif;
							?>
							<li>
								<div>
									<?php 
										if($this->Session->read('Auth.User.id')):
											echo $this->Html->link(__('Add a Translation'), array('controller' => 'translations', 'action' => 'add', 'admin'	=>	false), array('class' => 'btn btn-primary'));
										else:
											echo $this->Html->link(__('Join'), '/join', array('class' => 'btn btn-primary'));
										endif;
									?>
								</div>
							</li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
    <div class="container">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
    </div> <!-- /container -->
		<script type="text/javascript" charset="utf-8" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<?php
			echo $this->Html->script(array('bootstrap.min', 'application'));
			if($this->name == 'Recorder') {
				echo $this->Html->script('jRecorder');
			}
		?>
  </body>
</html>