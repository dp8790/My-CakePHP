<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
$cakeDescription = 'CakePHP 3.0 Demo with Full Calendar Functionality';
?>
<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>
            <?= $cakeDescription ?>:
            <?= $this->fetch('title') ?>
        </title>
        <?= $this->Html->meta('icon') ?>

        <?= $this->Html->css(array('base.css', 'cake.css', 'fullcalendar.css', 'datepicker.css', 'select2.min.css', 'custom.css')) ?>
        <?php echo $this->Html->script(array('jquery.min.js', 'jquery-cloneya.js', 'moment.min.js', 'jquery-ui.custom.min.js', 'fullcalendar.min.js', 'bootstrap-datepicker.js', 'select2.full.js')); ?>
        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>
        <?= $this->fetch('script') ?>
        <style>
            body {
                margin: 40px 10px;
                padding: 0;
                font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
                font-size: 14px;
            }
            #calendar {
                max-width: 900px;
                margin: 0 auto;
            }
        </style>
    </head>
    <body>
        <header>
            <div class="header-title">
                <span><?= $this->fetch('title') ?></span>
            </div>
            <?php if (!empty(currentUserId)) { ?>
                <div class="header-help">
                    <span>
                        <?php echo $this->Html->link('Logout', ['controller' => 'Users', 'action' => 'logout']); ?>
                    </span>
                </div>
            <?php } ?>
        </header>
        <div id="container">
            <div id="content">
                <?= $this->Flash->render() ?>
                <div class="row">
                    <?= $this->fetch('content') ?>
                </div>
            </div>
            <footer>
            </footer>
        </div>
    </body>
</html>
