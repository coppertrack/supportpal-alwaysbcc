<?php
/**
 * Plugin Routes
 *
 * @package    App\Plugin\AlwaysBcc\Routes
 * @copyright  Copyright (c) 2015-2016 SupportPal (http://www.supportpal.com)
 * @license    http://www.supportpal.com/company/eula
 * @since      File available since Release 2.0.0
 */

$router->get('settings', [
    'can'  => 'view.alwaysbcc_settings',
    'as'   => 'plugin.alwaysbcc.settings',
    'uses' => 'App\Plugins\AlwaysBcc\Controllers\AlwaysBcc@getSettingsPage'
]);

$router->post('settings', [
    'can'  => 'update.alwaysbcc_settings',
    'as'   => 'plugin.alwaysbcc.settings.update',
    'uses' => 'App\Plugins\AlwaysBcc\Controllers\AlwaysBcc@updateSettings'
]);
