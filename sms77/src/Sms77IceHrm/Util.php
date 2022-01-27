<?php
namespace Sms77IceHrm;

use Classes\BaseService;
use Employees\Common\Model\Employee;
use Employees\Common\Model\EmploymentStatus;
use Jobs\Common\Model\JobTitle;
use Model\Setting;
use ReflectionClass;

abstract class Filters {
    const EMPLOYEE_EMPLOYMENT_STATUSES = 'employee_employment_statuses';
    const EMPLOYEE_COUNTRIES = 'employee_countries';
    const EMPLOYEE_JOB_TITLES = 'employee_job_titles';
    const EMPLOYEE_STATUSES = 'employee_statuses';

    public static function values() {
        return (new ReflectionClass(self::class))->getConstants();
    }
}

abstract class Util {
    public static function addSetting(
        string $name, string $description, string $value = '') {
        $setting = new Setting;
        $setting->category = Extension::SMS77_SETTING_CATEGORY;
        $setting->description = $description;
        $setting->name = $name;
        $setting->value = $value;

        return $setting->Save();
    }

    public static function sms() {
        self::request('sms', true);
    }

    private static function request(string $endpoint, bool $multipleRecipients) {
        if (!self::isPOST()) return;

        $instance = BaseService::getInstance();
        if (!$instance) {
            self::renderAlert('Unknown error. Please try again!');
            return;
        }

        $apiKey = self::getApiKey($instance);
        if (empty($apiKey)) {
            self::renderAlert('API Key is missing!');
            return;
        }

        $recipients = self::buildRecipients();
        if (empty($recipients)) {
            self::renderAlert('No recipient(s) found!');
            return;
        }
        $recipients = $multipleRecipients ? [implode(',', $recipients)] : $recipients;

        foreach (Filters::values() as $constant) unset($_POST[$constant]);

        $json = [];
        $ch = curl_init('https://gateway.sms77.io/api/' . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, ['SentWith: IceHrm', 'X-Api-Key: ' . $apiKey]);
        foreach ($recipients as $to) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, array_merge($_POST, [
                'json' => 1,
                'to' => $to,
            ]));
            $json = curl_exec($ch);
        }
        curl_close($ch);

        self::renderAlert(json_encode(json_decode($json, JSON_PRETTY_PRINT)));
    }

    private static function buildRecipients() {
        $to = isset($_POST['to']) ? $_POST['to'] : '';

        if ('' !== $to) return explode(',', $to);

        $to = [];
        $bind = [];
        $where = '';

        foreach ([
                     'country' => isset($_POST[Filters::EMPLOYEE_COUNTRIES]) 
                         ? $_POST[Filters::EMPLOYEE_COUNTRIES] : '',
                     'employment_status' =>
                         isset($_POST[Filters::EMPLOYEE_EMPLOYMENT_STATUSES]) 
                             ? $_POST[Filters::EMPLOYEE_EMPLOYMENT_STATUSES] : '',
                     'job_title' => isset($_POST[Filters::EMPLOYEE_JOB_TITLES])
                         ? $_POST[Filters::EMPLOYEE_JOB_TITLES] : '',
                     'status' => isset($_POST[Filters::EMPLOYEE_STATUSES]) 
                         ? $_POST[Filters::EMPLOYEE_STATUSES] : '',
                 ] as $field => $param) {
            if ('' !== $param) {
                $where .= self::prependWhere($where) . $field . ' in (?)';
                $bind[] = $param;
            }
        }
        $where .= self::prependWhere($where)
            . 'mobile_phone IS NOT NULL AND mobile_phone<>\'\'';

        foreach ((new Employee)->Find($where, $bind) as $employee)
            $to[] = $employee->mobile_phone;

        return $to;
    }

    public static function isPOST() {
        return 'POST' === $_SERVER['REQUEST_METHOD'];
    }

    private static function renderAlert(string $message) {
        ?>
        <div class='alert alert-warning alert-dismissible' role='alert'>
            <button aria-label='Close' class='close' data-dismiss='alert' type='button'>
                <span aria-hidden='true'>&times;</span>
            </button>

            <pre><?= $message ?></pre>
        </div>
        <?php
    }

    public static function getApiKey(BaseService $instance) {
        return $instance->settingsManager->getSetting(
            Extension::SMS77_SETTING_KEY_API_KEY);
    }

    private static function prependWhere(string $where) {
        return '' === $where ? '' : ' and ';
    }

    public static function voice() {
        self::request('voice', false);
    }

    public static function renderFilters() {
        ?>
        <fieldset>
            <legend>Filters</legend>

            <div class='row form-group'>
                <label class='col-md-3 no-padding'>
                    Status
                    <select class='form-control' name='employee_statuses'>
                        <option></option>
                        <?php foreach (Util::getEmployeeStatuses() as $status): ?>
                            <option value='<?= $status ?>'><?= $status ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <label class='col-md-3 no-padding'>
                    Country
                    <select class='form-control' name='employee_countries'>
                        <option></option>
                        <?php foreach (Util::getEmployeeCountries() as $country): ?>
                            <option value='<?= $country ?>'><?= $country ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <label class='col-md-3 no-padding'>
                    Job Title
                    <select class='form-control' name='employee_job_titles'>
                        <option></option>
                        <?php foreach (Util::getEmployeeJobTitles() as $id => $title): ?>
                            <option value='<?= $id ?>'><?= $title ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <label class='col-md-3 no-padding'>
                    Employment Status
                    <select class='form-control' name='employee_employment_statuses'>
                        <option></option>
                        <?php foreach (Util::getEmployeeEmploymentStatuses() as $id => $status): ?>
                            <option value='<?= $id ?>'><?= $status ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>
        </fieldset>
        <hr>
        <?php
    }

    private static function getEmployeeStatuses() {
        return self::fetchColumns('SELECT DISTINCT(status) from Employees', 'status');
    }

    private static function fetchColumns(string $sql, string $column) {
        $arr = [];
        $db = BaseService::getInstance()->getDB();
        $statuses = $db->Execute($sql);

        while ($r = $statuses->fetchRow()) $arr[] = $r[$column];

        return $arr;
    }

    private static function getEmployeeCountries() {
        return self::fetchColumns('SELECT DISTINCT(country) from Employees', 'country');
    }

    private static function getEmployeeJobTitles() {
        $titles = [];

        foreach (self::fetchColumns('SELECT DISTINCT(job_title) from Employees',
            'job_title') as $i => $id) {
            unset($titles[$i]);

            $titles[$id] = (new JobTitle)->Find('id = ?', [$id])[0]->name;
        }

        return $titles;
    }

    private static function getEmployeeEmploymentStatuses() {
        $titles = [];

        foreach (self::fetchColumns('SELECT DISTINCT(employment_status) from Employees',
            'employment_status') as $i => $id) {
            unset($titles[$i]);

            $titles[$id] = (new EmploymentStatus)->Find('id = ?', [$id])[0]->name;
        }

        return $titles;
    }

    public static function renderSubmit() {
        ?>
        <div class='form-group'>
            <button class='btn btn-info' type='submit'>Submit</button>
        </div>
        <?php
    }

    public static function renderTo() {
        ?>
        <div class='form-group'>
            <label for='sms77_to'>To</label>
            <input class='form-control' id='sms77_to' name='to'/>
        </div>
        <?php
    }

    public static function renderTextarea(int $maxLength) {
        ?>
        <div class='form-group'>
            <label class='control-label' for='sms77_text'>Text</label>
            <textarea class='form-control' id='sms77_text' maxlength='<?= $maxLength ?>'
                      name='text' required rows='5'></textarea>
        </div>
        <?php
    }
}
