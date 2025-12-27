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
        orientation: 'bottom',
        format: 'yyyy-mm-dd',
    });
}

function initMonthpicker(input) {
    $(input).datepicker({

        orientation: 'bottom',
        ormat: "mm-yyyy",
        startView: "months",
        minViewMode: "months"

    });
}

function setPasswordForm(id) {
    $('#changepasswordform-userid').val(id);
}

function applyWorkingHoursToAllDays() {
    var mainSelector = 'form.has-workinghours .working-hours-form-container';
    var hasHours = $(mainSelector + ' .working-hours-form[data-weekday="7"] .working-hours-container .working-hours-row').length !== 0;
    for (var i = 1; i < 7; i++) {
        $(mainSelector + ' .week-day-' + i).val(1);
        $(mainSelector + ' .mdc-list-group.collapsed').removeClass('collapsed').addClass('expanded');
        $(mainSelector + ' .working-hours-form[data-weekday="' + i + '"] .working-hours-container .working-hours-row button.delete-row').trigger('click');
        if (hasHours) {
            $(mainSelector + ' .working-hours-form[data-weekday="' + i + '"] .working-24-hours').addClass('d-none');
            $(mainSelector + ' .working-hours-form[data-weekday="7"] .working-hours-container .working-hours-row').each(function (j) {
                var rowIndex = $(this).attr('data-index');
                var from = $('#workinghoursform-7-' + rowIndex + '-from').val();
                var to = $('#workinghoursform-7-' + rowIndex + '-to').val();
                console.log(rowIndex);
                console.log(from);
                console.log(to);
                addSetOfWorkingHours($(mainSelector + ' .working-hours-form[data-weekday="' + i + '"]'), from, to);
            });
        }
    }
}

function addSetOfWorkingHours(object, from, to) {
    if ($(object).find('.working-hours-container > .working-hours-row').length == 4) {
        return false;
    }

    var weekDay = $(object).attr('data-weekday');
    var index = $(object).find('.working-hours-container > .working-hours-row').last().attr('data-index');
    if (isNaN(index)) {
        index = -1;
    }
    index++;

    var input = '<div class="mdc-list-item working-hours-row" data-index="' + index + '">\n';
    input += '<div class="text">\n';
    input += '<div class="form-label-group form-group field-workinghoursform-' + weekDay + '-' + index + '-from required">\n';
    input += '<input type="text" id="workinghoursform-' + weekDay + '-' + index + '-from" class="form-control" name="WorkingHoursForm[' + weekDay + '][' + index + '][from]" value="' + from + '">\n';
    input += '</div>\n';
    input += '<div class="material-icon text-hint">minimize</div>\n';
    input += '<div class="form-label-group form-group field-workinghoursform-' + weekDay + '-' + index + '-to required">\n';
    input += '<input type="text" id="workinghoursform-' + weekDay + '-' + index + '-to" class="form-control" name="WorkingHoursForm[' + weekDay + '][' + index + '][to]" value="' + to + '">\n';
    input += '</div>\n';
    input += '</div>\n';
    input += '<div class="meta icon">\n';
    input += '<button type="button" class="material-icon delete-row">close</button>\n';
    input += '</div>\n';
    input += '</div>\n';

    $(object).find('.working-hours-container').append(input);
    $(object).children('.working-24-hours').addClass('d-none');

    initTimepicker('#workinghoursform-' + weekDay + '-' + index + '-from, #workinghoursform-' + weekDay + '-' + index + '-to');
}

function setDeleteServiceID(id) {
    $('#delete-service-form input.service-id').val(id);
}

function clearDeleteServiceID() {
    $('#delete-service-form input.service-id').val('');
}

function setUpdateServiceForm(id, title, title_alt, duration, price) {
    $('#update-clinic-service-id').val(id);
    $('#update-clinic-service-title').val(title);
    $('#update-clinic-service-title_alt').val(title_alt);
    $('#update-clinic-service-duration').val(duration).trigger('change');
    $('#update-clinic-service-price').val(price);
}

function clearUpdateServiceForm() {
    $('#update-clinic-service-id').val('');
    $('#update-clinic-service-title').val('');
    $('#update-clinic-service-title_alt').val('');
    $('#update-clinic-service-duration').val('');
    $('#update-clinic-service-price').val('');
}

function setAddDoctorServiceID(id) {
    $('#doctorservice-branch_id').val(id);
}

$(document).ready(function () {
    initDropdownMenu('select.bootstrap-select');
    initTimepicker('.form-control.bootstrap-timepicker');
    initDatepicker('.form-control.bootstrap-datepicker');
    initMonthpicker('.form-control.bootstrap-monthpicker');

    $('form.auth-form').on('beforeSubmit', function (event) {
        $(this).children('.loading-block').addClass('active');
    });

    // Javascript to enable link to tab
    var url = document.location.toString();
    if (url.match('#')) {
        $('.nav-tabs a.nav-link[href="#' + url.split('#')[1] + '"]').tab('show');
        window.location.hash = '';
    }

    $('body').on('click', '.working-hours-form-container > .mdc-list-group > button.mdc-list-item', function (event) {
        if ($(this).parent('.mdc-list-group').hasClass('expanded')) {
            $(this).siblings('.form-group').children('input.form-control').val(1);
        } else {
            $(this).siblings('.form-group').children('input.form-control').val(0);
        }
    });

    $('body').on('click', '.input-group > .input-group-append > .password-visibility-button', function (event) {
        var icon = $(this).text();
        var type = 'password';
        if (icon == 'visibility') {
            icon = 'visibility_off';
            type = 'password';
        } else {
            icon = 'visibility';
            type = 'text';
        }
        $(this).text(icon).parent('.input-group-append').siblings('input.form-control').attr('type', type);
    });

    $('body').on('click', '.working-hours-form button.add-set-of-hours', function (event) {
        addSetOfWorkingHours($(this).closest('.working-hours-form'), '8:00 AM', '4:00 PM');
    });

    $('body').on('click', '.working-hours-container > .working-hours-row button.delete-row', function (event) {
        var weekDay = $(this).closest('.working-hours-form').attr('data-weekday');
        var count = $(this).closest('.working-hours-container').children('.working-hours-row').length;
        if (count == 1) {
            $(this).closest('.working-hours-form').children('.working-24-hours').removeClass('d-none');
        } else {
            $(this).closest('.working-hours-form').children('.working-24-hours').addClass('d-none');
        }
        var index = $(this).closest('.working-hours-row').attr('data-index');
        $(this).closest('form.has-workinghours').yiiActiveForm('remove', 'workinghoursform' + weekDay + '-' + index + '-from');
        $(this).closest('form.has-workinghours').yiiActiveForm('remove', 'workinghoursform' + weekDay + '-' + index + '-to');
        $(this).closest('.working-hours-row').remove();
    });
});