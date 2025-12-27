function initDropdownMenu(input) {
    $(input).selectpicker({
        noneSelectedText: '',
        noneResultsText: 'Can\'t find any item!'
    });
}

function initTimepicker(input) {
    $(input).timepicker({
        minuteStep: 5,
    });
}

function initDatepicker(input) {
    $(input).datepicker({
        orientation: 'bottom left',
        format: 'yyyy-mm-dd',
    });
}

$(document).ready(function () {
    initDropdownMenu('select.bootstrap-select');
    initTimepicker('.form-control.bootstrap-timepicker');
    initDatepicker('.form-control.bootstrap-datepicker');
});