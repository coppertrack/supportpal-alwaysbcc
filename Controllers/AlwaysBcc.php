<?php
/**
 * Always BCC Plugin
 *
 * @package    App\Plugins\AlwaysBcc\Controllers
 * @copyright  Copyright (c) 2015-2017 SupportPal (http://www.supportpal.com)
 * @license    http://www.supportpal.com/company/eula
 * @since      File available since Release 2.0.0
 */
namespace App\Plugins\AlwaysBcc\Controllers;

use App\Modules\Core\Controllers\Plugins\Plugin;
use App\Modules\Core\Models\EmailQueue;
use App\Plugins\AlwaysBcc\Requests\SettingsRequest;
use Exception;
use Input;
use JsValidator;
use Lang;
use Redirect;
use Session;
use TemplateView;

/**
 * Class AlwaysBcc
 *
 * @package    App\Plugins\AlwaysBcc\Controllers
 * @copyright  Copyright (c) 2015-2017 SupportPal (http://www.supportpal.com)
 * @license    http://www.supportpal.com/company/eula
 * @version    Release: @package_version@
 * @since      Class available since Release 2.0.0
 */
class AlwaysBcc extends Plugin
{
    /**
     * Plugin identifier.
     */
    const IDENTIFIER = 'AlwaysBcc';

    /**
     * Plugin settings.
     *
     * @var array
     */
    private $settings;

    /**
     * Initialise the plugin
     */
    public function __construct()
    {
        $this->setIdentifier(self::IDENTIFIER);

        // Register the settings page
        $this->registerSetting('plugin.alwaysbcc.settings');

        // Get the plugin settings
        $this->settings = $this->getSettings();
        
        // Register events.
        $this->registerEvents();
    }

    /**
     * Get the settings page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function getSettingsPage()
    {
        return TemplateView::other('AlwaysBcc::settings')
            ->with('jsValidator', JsValidator::formRequest(SettingsRequest::class))
            ->with('fields', $this->settings);
    }

    /**
     * Update the settings
     *
     * @param  SettingsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSettings(SettingsRequest $request)
    {
        // Get module id
        $data = Input::only([ 'email' ]);

        try {
            // Work through each row of data
            foreach ($data as $key => $value) {
                if (! empty($value) || $value == 0) {
                    $this->addSetting($key, $value);
                }
            }

            // All done, return with a success message
            Session::flash('success', Lang::get('messages.success_settings'));
        } catch (Exception $e) {
            // Return with a success message
            Session::flash('error', Lang::get('messages.error_settings'));
        }

        return Redirect::back();
    }

    /**
     * Plugins can run an installation routine when they are activated. This
     * will typically include adding default values, initialising database tables
     * and so on.
     *
     * @return boolean
     */
    public function activate()
    {
        try {
            $attributes = [ 'view' => true, 'create' => true, 'update' => true, 'delete' => true ];
            $this->addPermission('settings', $attributes, 'AlwaysBcc::lang.permission');

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Deactivating serves as temporarily disabling the plugin, but the files still
     * remain. This function should typically clear any caches and temporary directories.
     *
     * @return boolean
     */
    public function deactivate()
    {
        // Do nothing
        return true;
    }

    /**
     * When a plugin is uninstalled, it should be completely removed as if it never
     * was there. This function should delete any created database tables, and any files
     * created outside of the plugin directory.
     *
     * @return boolean
     */
    public function uninstall()
    {
        try {
            // Remove settings
            $this->removeSettings();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Register model events.
     *
     * @return void
     */
    private function registerEvents()
    {
        EmailQueue::saving(function (EmailQueue $queue) {
            $queue->bcc = array_merge($queue->bcc, [ $this->settings['email'] ]);
        });
    }
}
