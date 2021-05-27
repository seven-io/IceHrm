<?php
namespace Sms77IceHrm;

use Classes\BaseService;
use Employees\Common\Model\Employee;
use Employees\Common\Model\EmploymentStatus;
use Jobs\Common\Model\JobTitle;
use Model\Setting;

class Util {
    public static function isPOST(): bool {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public static function addSetting(
        string $name, string $description, string $value = ''): bool {
        $setting = new Setting;
        $setting->category = Extension::SMS77_SETTING_CATEGORY;
        $setting->description = $description;
        $setting->name = $name;
        $setting->value = $value;

        return $setting->Save();
    }

    public static function sms(BaseService $instance): string {
        return self::request($instance, 'sms');
    }

    private static function request(BaseService $instance, string $endpoint): string {
        $apiKey = self::getApiKey($instance);

        if ('' === $apiKey) return 'API Key is missing!';

        $to = $_POST['to'] ?? '';
        if ('' === $to) {
            $to = [];
            $bind = [];
            $where = '';

            foreach ([
                         'country' => $_POST['employee_countries'] ?? '',
                         'employment_status' =>
                             $_POST['employee_employment_statuses'] ?? '',
                         'job_title' => $_POST['employee_job_titles'] ?? '',
                         'status' => $_POST['employee_statuses'] ?? '',
                     ] as $field => $param) {
                if ('' !== $param) {
                    $where .= self::prependWhere($where) . "$field in (?)";
                    $bind[] = $param;
                }
            }
            $where .= self::prependWhere($where)
                . 'mobile_phone IS NOT NULL AND mobile_phone<>""';

            foreach ((new Employee)->Find($where, $bind) as $employee)
                $to[] = $employee->mobile_phone;

            $to = implode(',', $to);
        }

        if ('' === $to) return 'No recipient(s) found!';

        $ch = curl_init("https://gateway.sms77.io/api/$endpoint");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'from' => $_POST['from'],
            'json' => 1,
            'text' => $_POST['text'],
            'to' => $to,
        ]);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['SentWith: IceHrm', "X-Api-Key: $apiKey"]);
        $json = curl_exec($ch);
        curl_close($ch);
        return json_encode(json_decode($json, JSON_PRETTY_PRINT));
    }

    public static function getApiKey(BaseService $instance): string {
        return $instance->settingsManager->getSetting(
            Extension::SMS77_SETTING_KEY_API_KEY);
    }

    private static function prependWhere(string $where): string {
        return '' === $where ? '' : ' and ';
    }

    public static function getActiveEmployees(): array {
        return (new Employee)->getActiveEmployees();
    }

    public static function getEmployeeStatuses(): array {
        return self::fetchColumns('SELECT DISTINCT(status) from Employees', 'status');
    }

    private static function fetchColumns(string $sql, string $column): array {
        $arr = [];
        $db = BaseService::getInstance()->getDB();
        $statuses = $db->Execute($sql);

        while ($r = $statuses->fetchRow()) $arr[] = $r[$column];

        return $arr;
    }

    public static function getEmployeeCountries(): array {
        return self::fetchColumns('SELECT DISTINCT(country) from Employees', 'country');
    }

    public static function getEmployeeJobTitles(): array {
        $titles = [];

        foreach (self::fetchColumns('SELECT DISTINCT(job_title) from Employees',
            'job_title') as $i => $id) {
            unset($titles[$i]);

            $titles[$id] = (new JobTitle)->Find('id = ?', [$id])[0]->name;
        }

        return $titles;
    }

    public static function getEmployeeEmploymentStatuses(): array {
        $titles = [];

        foreach (self::fetchColumns('SELECT DISTINCT(employment_status) from Employees',
            'employment_status') as $i => $id) {
            unset($titles[$i]);

            $titles[$id] = (new EmploymentStatus)->Find('id = ?', [$id])[0]->name;
        }

        return $titles;
    }
}
