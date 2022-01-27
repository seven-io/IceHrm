<?php

use Classes\BaseService;
use Sms77IceHrm\Extension;
use Sms77IceHrm\Util;

define('MODULE_PATH', __DIR__ . '/../');

include_once APP_BASE_PATH . 'header.php';
include_once APP_BASE_PATH . 'modulejslibs.inc.php';

$instance = BaseService::getInstance();

if (Util::isPOST()) $instance->settingsManager->setSetting(
    Extension::SMS77_SETTING_KEY_API_KEY, $_POST['apiKey']);
?>

<form method='post'>
    <fieldset>
        <legend>General</legend>

        <div class='form-group'>
            <label for='sms77_apiKey'>API Key</label>
            <div class='input-group'>
                <input type='password' name='apiKey' class='form-control'
                       id='sms77_apiKey'
                       value='<?= Util::getApiKey($instance) ?>'/>

                <span class='add-on input-group-addon'>
                    <i id='sms77_apiKey_toggle' class='fa fa-eye btn btn-xs'></i>
		        </span>
            </div>
        </div>
    </fieldset>

    <div class='form-group'>
        <button class='btn btn-info' type='submit'>Submit
        </button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const $apiKey = document.getElementById('sms77_apiKey');
        const $toggler = document.getElementById('sms77_apiKey_toggle');

        $toggler.addEventListener('click', e => {
            const isToggled = 'text' === $apiKey.type;

            $apiKey.type = isToggled ? 'password' : 'text';
            $toggler.classList.toggle('fa-eye');
            $toggler.classList.toggle('fa-eye-slash');
        });
    });
</script>
