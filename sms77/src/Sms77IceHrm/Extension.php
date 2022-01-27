<?php
namespace Sms77IceHrm;

use Classes\IceExtension;
use Model\Setting;

class Extension extends IceExtension {
    const SMS77_SETTING_CATEGORY = 'Sms77';
    const SMS77_SETTING_KEY_API_KEY = 'Sms77: API Key';
    const SETTINGS = [
        self::SMS77_SETTING_KEY_API_KEY => 'Sms77 API key required for sending',
    ];

    public function install() {
        foreach (self::SETTINGS as $name => $desc) self::addSetting($name, $desc);
    }

    public function uninstall() {
        foreach (array_keys(self::SETTINGS) as $name) self::deleteSetting($name);
    }

    public function setupModuleClassDefinitions() {
    }

    protected static function addSetting(
        string $name, string $description, string $value = '') {
        $setting = new Setting;
        $setting->category = self::SMS77_SETTING_CATEGORY;
        $setting->description = $description;
        $setting->name = $name;
        $setting->value = $value;

        return $setting->Save();
    }

    protected static function deleteSetting(string $name) {
        $setting = new Setting;
        $setting->Load("name = ? AND category = ?", [$name, self::SMS77_SETTING_CATEGORY]);

        return $setting->Delete();
    }
}
