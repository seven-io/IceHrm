<?php

use SevenIceHrm\Util;

define('MODULE_PATH', __DIR__ . '/../');

include_once APP_BASE_PATH . 'header.php';
include_once APP_BASE_PATH . 'modulejslibs.inc.php';
?>
    <h1>Send Voice | seven</h1>
    <p>Issue bulk text-to-speech calls to employees.</p>

    <form method='post'>
        <?php Util::renderFilters(); ?>

        <div class='row form-group'>
            <div class='col-md-6 no-padding'>
                <label for='seven_from'>From</label>
                <input class='form-control' id='seven_from' maxlength='16' name='from'/>
            </div>

            <div class='col-md-6 no-padding'>
                <label>
                    XML<br>
                    <input name='xml' type='checkbox' value='1'/>
                </label>
            </div>
        </div>

        <?php Util::renderTo(); ?>

        <?php Util::renderTextarea(10000) ?>

        <?php Util::renderSubmit(); ?>
    </form>
<?php Util::voice() ?>
