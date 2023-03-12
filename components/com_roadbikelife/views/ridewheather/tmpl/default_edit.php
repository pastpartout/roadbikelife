<?php

defined('_JEXEC') or die;

$headerTitle = 'Route hinzufügen';

$document = JFactory::getDocument();
$document->addScript('/media/com_roadbikelife/js/moment-with-locales.min.js');
$document->addScript('/media/com_roadbikelife/js/bootstrap-datetimepicker.min.js');
$document->addScriptDeclaration('
$(document).ready(function () {



    $(\'div.datePicker\').each(function() {
        var timepicker = $(this);
        var date = new Date();
        date.setDate(date.getDate() + 7);
        timepicker.datetimepicker({
            format: "DD.MM.YYYY HH:m",
            locale: \'DE\',
            // keepOpen: true,
            inline: true,
            sideBySide: true,
            minDate: new Date(),
            maxDate: date,
            // debug:true,
            calendarWeeks: false,
            viewMode: \'days\',
            focusOnShow: false,
            icons: {
                time: \'fa fa-time\',
                date: \'fa fa-calendar\',
                up: \'fa fa-chevron-up\',
                down: \'fa fa-chevron-down\',
                previous: \'fa fa-chevron-left\',
                next: \'fa fa-chevron-right\',
                today: \'fa fa-screenshot\',
                clear: \'fa fa-trash\',
                close: \'fa fa-remove\'
            }
        });

        timepicker.on(\'dp.change\', function (event) {
            var formatted_date = event.date.format(\'DD.MM.YYYY HH:m\');
            $(\'.date_start_input\').val(formatted_date);
        });
    })
})

');

?>
<!-- Modal -->
<div class="modal fade" id="changeDateModal" tabindex="-1" role="dialog"
     aria-labelledby="changeDateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeDateModalLabel">Zeit/Tempo ändern</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <? $field = $this->form->getField('date_start'); ?>
                    <?= $field->label; ?>
                    <div class="calendarWrapper">
                        <div id="date_start" class="datePicker"></div>
                        <input type="hidden" name="date_start" class="date_start_input">
                    </div>
                    <?= $this->form->renderField('speed'); ?>
                    <input type="hidden" name="task" value="ridewheather.edit">
                    <input type="hidden" name="token" value="<?= $item->token; ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
                    <button type="submit" class="btn btn-primary">Speichern</button>
                </div>
            </div>
        </form>
    </div>
</div>
