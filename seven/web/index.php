<?php

use Classes\BaseService;
use SevenIceHrm\Extension;
use SevenIceHrm\Util;

define('MODULE_PATH', __DIR__ . '/../');

include_once APP_BASE_PATH . 'header.php';
include_once APP_BASE_PATH . 'modulejslibs.inc.php';

$instance = BaseService::getInstance();

if (Util::isPOST()) $instance->settingsManager->setSetting(
    Extension::SEVEN_SETTING_KEY_API_KEY, $_POST['apiKey']);
?>

<form method='post'>
    <fieldset>
        <legend>General</legend>

        <div class='form-group'>
            <label for='seven_apiKey'>API Key</label>
            <div class='input-group'>
                <input type='password' name='apiKey' class='form-control'
                       id='seven_apiKey'
                       value='<?= Util::getApiKey($instance) ?>'/>

                <span class='add-on input-group-addon'>
                    <i id='seven_apiKey_toggle' class='fa fa-eye btn btn-xs'></i>
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
        const $apiKey = document.getElementById('seven_apiKey');
        const $toggler = document.getElementById('seven_apiKey_toggle');

        $toggler.addEventListener('click', () => {
            const isToggled = 'text' === $apiKey.type;

            $apiKey.type = isToggled ? 'password' : 'text';
            $toggler.classList.toggle('fa-eye');
            $toggler.classList.toggle('fa-eye-slash');
        });
    });
</script>
