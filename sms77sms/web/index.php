<?php

use Classes\BaseService;
use Sms77IceHrm\Util;

define('MODULE_PATH', __DIR__ . '/../');

include APP_BASE_PATH . 'header.php';
include APP_BASE_PATH . 'modulejslibs.inc.php';

$instance = BaseService::getInstance();
$response = Util::isPOST() ? Util::sms($instance) : null;
?>
<h1>Send SMS | Sms77</h1>
<p>Send bulk SMS to employees.</p>

<form id='sms77_submit' method='post'>
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

    <div class='form-group'>
        <label>Debug<input name='debug' type='checkbox' value='1'/></label>

        <label>Flash<input name='flash' type='checkbox' value='1'/></label>

        <label>No Reload<input name='no_reload' type='checkbox' value='1'/></label>

        <label>Performance Tracking<input name='performance_tracking' type='checkbox'
                                          value='1'/></label>
    </div>

    <div class='row form-group'>
        <div class='col-md-6 no-padding'>
            <label for='sms77_delay'>Delay</label>
            <input class='form-control' id='sms77_delay' name='delay'/>
        </div>

        <div class='col-md-6 no-padding'>
            <label for='sms77_foreign_id'>Foreign ID</label>
            <input class='form-control' id='sms77_foreign_id' maxlength='64'
                   name='foreign_id'/>
        </div>
    </div>

    <div class='row form-group'>
        <div class='col-md-6 no-padding'>
            <label for='sms77_from'>From</label>
            <input class='form-control' id='sms77_from' maxlength='16' name='from'
                   value='<?= $instance->settingsManager->getSetting('Company: Name') ?>'
            />
        </div>

        <div class='col-md-6 no-padding'>
            <label for='sms77_label'>Label</label>
            <input class='form-control' id='sms77_label' maxlength='100'
                   name='label'/>
        </div>
    </div>

    <div class='form-group'>
        <label for='sms77_to'>To</label>
        <input class='form-control' id='sms77_to' name='to'/>
    </div>

    <div class='form-group'>
        <label class='control-label' for='sms77_text'>Text</label>
        <textarea class='form-control' id='sms77_text' maxlength='1520'
                  name='text' required rows='5'></textarea>
    </div>

    <div class='form-group'>
        <button class='btn btn-info' type='submit'>Submit</button>
    </div>
</form>
<?php if (isset($response)): ?>
    <div class='alert alert-warning alert-dismissible' role='alert'>
        <button aria-label='Close' class='close' data-dismiss='alert' type='button'>
            <span aria-hidden='true'>&times;</span>
        </button>

        <pre><?= $response ?></pre>
    </div>
<?php endif; ?>
