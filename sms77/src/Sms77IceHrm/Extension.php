<?php
namespace Sms77IceHrm;

use Classes\IceExtension;

class Extension extends IceExtension {
    const SMS77_SETTING_CATEGORY = 'Sms77';
    const SMS77_SETTING_KEY_API_KEY = 'Sms77: API Key';

    public function install() {
        // TODO: init extension settings - they are in web/index.php as of now
    }

    public function uninstall() {
        // TODO: delete extension settings
    }

    public function setupModuleClassDefinitions() {
    }
}
