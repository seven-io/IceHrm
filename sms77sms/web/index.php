<?php

use Classes\BaseService;
use Sms77IceHrm\Util;

define('MODULE_PATH', __DIR__ . '/../');

include APP_BASE_PATH . 'header.php';
include APP_BASE_PATH . 'modulejslibs.inc.php';
?>
<h1>Send SMS | Sms77</h1>
<p>Send bulk SMS to employees.</p>

<form method='post'>
    <?php Util::renderFilters(); ?>

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
                   value='<?= BaseService::getInstance()
                       ->settingsManager->getSetting('Company: Name') ?>'
            />
        </div>

        <div class='col-md-6 no-padding'>
            <label for='sms77_label'>Label</label>
            <input class='form-control' id='sms77_label' maxlength='100'
                   name='label'/>
        </div>
    </div>

    <?php Util::renderTo(); ?>

    <?php Util::renderTextarea(1520) ?>

    <?php Util::renderSubmit(); ?>
</form>
<?php Util::sms() ?>
