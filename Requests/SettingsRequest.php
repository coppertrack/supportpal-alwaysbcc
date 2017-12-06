<?php
/**
 * File SettingsRequest.php
 *
 * @package    App\Plugins\AlwaysBcc\Requests
 * @copyright  Copyright (c) 2015-2016 SupportPal (http://www.supportpal.com)
 * @license    http://www.supportpal.com/company/eula
 * @since      File available since Release 2.2.0
 */
namespace App\Plugins\AlwaysBcc\Requests;

use App\Http\Requests\Request;
use Lang;

/**
 * Class SettingsRequest
 *
 * @package    App\Plugins\AlwaysBcc\Requests
 * @copyright  Copyright (c) 2015-2016 SupportPal (http://www.supportpal.com)
 * @license    http://www.supportpal.com/company/eula
 * @version    Release: @package_version@
 * @since      Class available since Release 2.2.0
 */
class SettingsRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
        ];
    }
}
