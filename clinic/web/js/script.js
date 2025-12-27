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

function setUpdateServiceForm(id, title, title_alt, duration, price, max_app) {
    $('#update-clinic-service-id').val(id);
    $('#update-clinic-service-title').val(title);
    $('#update-clinic-service-title_alt').val(title_alt);
    $('#update-clinic-service-duration').val(duration).trigger('change');
    $('#update-clinic-service-price').val(price);
    $('#update-clinic-service-max-app').val(max_app);
}

function clearUpdateServiceForm() {
    $('#update-clinic-service-id').val('');
    $('#update-clinic-service-title').val('');
    $('#update-clinic-service-title_alt').val('');
    $('#update-clinic-service-duration').val('');
    $('#update-clinic-service-price').val('');
    $('#update-clinic-service-max-app').val('');
}

function setAddDoctorServiceID(id) {
    $('#doctorservice-branch_id').val(id);
}

function setUpdateMedicineForm(id, name, formats) {
    $('#update-medicine-formats input').prop('checked', false);

    $('#update-medicine-id').val(id);
    $('#update-medicine-name').val(name);
    formats.forEach(function (form) {
        $('#update-medicine-formats input[value="' + form + '"]').prop('checked', true);
    });
}

function add_invoice_item() {
    var index = $('#invoice-items > .item-row').last().attr('data-index');
    var labels = {
        item: $('#invoice-items .field-invoiceitem-' + index + '-item label').text(),
        quantity: $('#invoice-items .field-invoiceitem-' + index + '-qty label').text(),
        amount: $('#invoice-items .field-invoiceitem-' + index + '-amount label').text(),
        vat: $('#invoice-items .field-invoiceitem-' + index + '-vat label').text(),
        discount_value: $('#invoice-items .field-invoiceitem-' + index + '-discount_value label').text(),
        open_services: $('#invoice-items .dropup.row-' + index + ' .open-services-list .text').text(),
        apply_discount: $('#invoice-items .dropup.row-' + index + ' .apply-discount-to-all .text').text(),
        delete_item: $('#invoice-items .dropup.row-' + index).attr('data-delete')
    };
    index++;

    var row = '<div class="item-row" data-index="' + index + '">';

    row += '<div class="row m-0">';

    row += '<div class="col">';
    row += '<div class="form-label-group form-group field-invoiceitem-' + index + '-item">';
    row += '<input type="text" id="invoiceitem-' + index + '-item" class="form-control" name="InvoiceItem[' + index + '][item]" value="" autocomplete="off" placeholder="' + labels.item + '">';
    row += '<label for="invoiceitem-' + index + '-item">' + labels.item + '</label>';
    row += '</div>';
    row += '</div>';
    row += '<div class="col-lg-3 col-md-3">';
    row += '<div class="form-label-group form-group field-invoiceitem-' + index + '-qty">';
    row += '<input type="text" id="invoiceitem-' + index + '-qty" class="form-control" name="InvoiceItem[' + index + '][qty]" value="" autocomplete="off" placeholder="' + labels.quantity + '">';
    row += '<label for="invoiceitem-' + index + '-qty">' + labels.quantity + '</label>';
    row += '</div>';
    row += '</div>';
    row += '<div class="col-lg-3 col-md-3">';
    row += '<div class="form-label-group form-group input-append field-invoiceitem-' + index + '-amount">';
    row += '<input type="text" id="invoiceitem-' + index + '-amount" class="form-control" name="InvoiceItem[' + index + '][amount]" value="" autocomplete="off" placeholder="' + labels.amount + '">';
    row += '<label for="invoiceitem-' + index + '-amount">' + labels.amount + '</label>';
    row += '</div>';
    row += '</div>';
    row += '<div class="col-sm-auto py-3">';
    row += '<div class="form-label-group form-group field-invoiceitem-' + index + '-vat">';
    row += '<div class="custom-control custom-switch">';
    row += '<input type="hidden" name="InvoiceItem[' + index + '][vat]" value="0">';
    row += '<input type="checkbox" id="invoiceitem-' + index + '-vat" class="custom-control-input" name="InvoiceItem[' + index + '][vat]" value="1">';
    row += '<label class="custom-control-label" for="invoiceitem-' + index + '-vat">' + labels.vat + '</label>';
    row += '</div>';
    row += '</div>';
    row += '</div>';

    row += '</div>';

    row += '<div class="row m-0">';

    row += '<div class="col-lg-3 col-md-3">';
    row += '<div class="form-label-group form-group field-invoiceitem-' + index + '-discount_value">';
    row += '<input type="text" id="invoiceitem-' + index + '-discount_value" class="form-control" name="InvoiceItem[' + index + '][discount_value]" autocomplete="off" placeholder="' + labels.amount + '">';
    row += '<label for="invoiceitem-' + index + '-discount_value">' + labels.discount_value + '</label>';
    row += '</div>';
    row += '</div>';
    row += '<div class="col pt-1">';
    row += '<div class="form-group field-invoiceitem-' + index + '-discount_unit">';
    row += '<input type="hidden" name="InvoiceItem[' + index + '][discount_unit]" value="">';
    row += '<div id="invoiceitem-' + index + '-discount_unit" role="radiogroup">';
    row += '<div class="custom-control custom-radio">';
    row += '<input type="radio" id="i0_' + index + '" class="custom-control-input" name="InvoiceItem[' + index + '][discount_unit]" value="percent" checked="">';
    row += '<label class="custom-control-label" for="i0_' + index + '">%</label>';
    row += '</div>';
    row += '<div class="custom-control custom-radio">';
    row += '<input type="radio" id="i1_' + index + '" class="custom-control-input" name="InvoiceItem[' + index + '][discount_unit]" value="fixed">';
    row += '<label class="custom-control-label" for="i1_' + index + '">BHD</label>';
    row += '</div>';
    row += '</div>';
    row += '</div>';
    row += '</div>';

    row += '<div class="col-auto align-self-center">';
    row += '<div class="dropup row-' + index + '" data-delete="' + labels.delete_item + '">';
    row += '<button class="material-icon" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">more_vert</button>';
    row += '<div class="dropdown-menu dropdown-menu-top dropdown-menu-right">';
    row += '<div class="mdc-list-group">';
    row += '<button type="button" class="mdc-list-item delete-item-row">';
    row += '<div class="icon material-icon">close</div>';
    row += '<div class="text">' + labels.delete_item + '</div>';
    row += '</button>';
    row += '<div class="mdc-divider my-2"></div>';
    row += '<button type="button" class="mdc-list-item apply-discount-to-all" data-name="InvoiceItem[' + index + ']">';
    row += '<div class="icon material-icon">content_copy</div>';
    row += '<div class="text">' + labels.apply_discount + '</div>';
    row += '</button>';
    row += '<button type="button" class="mdc-list-item open-services-list">';
    row += '<div class="icon material-icon">medical_services</div>';
    row += '<div class="text">' + labels.open_services + '</div>';
    row += '</button>';
    row += '</div>';
    row += '</div>';
    row += '</div>';
    row += '</div>';

    row += '</div>';

    row += '</div>';

    $('#invoice-items').append(row);
}

function add_prescription_item(removeText) {
    var index = $('#prescription-items > .item-row').last().attr('data-index');
    index++;

    var labels = {
        medicine: $('#prescription-items .field-prescriptionitem-0-medicine label').text(),
        form: $('#prescription-items .field-prescriptionitem-0-form label').text(),
        strength: $('#prescription-items .field-prescriptionitem-0-strength label').text(),
        frequency: $('#prescription-items .field-prescriptionitem-0-frequency label').text(),
        duration: $('#prescription-items .field-prescriptionitem-0-duration label').text(),
        comment: $('#prescription-items .field-prescriptionitem-0-comment label').text()
    };

    var row = '<div class="item-row card-body" data-index="' + index + '">';

    row += '<div class="row">';
    row += '<div class="col-lg-6">';
    row += '<div class="form-label-group form-group field-prescriptionitem-' + index + '-medicine">';
    row += '<select id="prescriptionitem-' + index + '-medicine" class="form-control bootstrap-select medicines-list" name="PrescriptionItem[' + index + '][medicine]" data-live-search="true">';
    row += '</select>';
    row += '<label for="prescriptionitem-' + index + '-medicine">' + labels.medicine + '</label>';
    row += '</div>';
    row += '</div>';
    row += '<div class="col-lg-4">';
    row += '<div class="form-label-group form-group field-prescriptionitem-' + index + '-form">';
    row += '<select id="prescriptionitem-' + index + '-form" class="form-control bootstrap-select format-list" name="PrescriptionItem[' + index + '][form]">';
    row += '</select>';
    row += '<label for="prescriptionitem-' + index + '-form">' + labels.form + '</label>';
    row += '</div>';
    row += '</div>';
    row += '</div>';

    row += '<div class="row">';
    row += '<div class="col-lg-3 col-md-4">';
    row += '<div class="form-label-group form-group field-prescriptionitem-' + index + '-strength">';
    row += '<input type="text" id="prescriptionitem-' + index + '-strength" class="form-control" name="PrescriptionItem[' + index + '][strength]" value="" autocomplete="off" placeholder="' + labels.strength + '">';
    row += '<label for="prescriptionitem-' + index + '-strength">' + labels.strength + '</label>';
    row += '<small class="form-text text-muted">E.g. 250 mg/5 mL</small>';
    row += '</div>';
    row += '</div>';
    row += '<div class="col-lg-3 col-md-4">';
    row += '<div class="form-label-group form-group field-prescriptionitem-' + index + '-frequency">';
    row += '<input type="text" id="prescriptionitem-' + index + '-frequency" class="form-control" name="PrescriptionItem[' + index + '][frequency]" value="" autocomplete="off" placeholder="' + labels.frequency + '">';
    row += '<label for="prescriptionitem-' + index + '-frequency">' + labels.frequency + '</label>';
    row += '<small class="form-text text-muted">E.g. 10 mL/8 hours</small>';
    row += '</div>';
    row += '</div>';
    row += '<div class="col-lg-3 col-md-4">';
    row += '<div class="form-label-group form-group field-prescriptionitem-' + index + '-duration">';
    row += '<input type="text" id="prescriptionitem-' + index + '-duration" class="form-control" name="PrescriptionItem[' + index + '][duration]" value="" autocomplete="off" placeholder="' + labels.duration + '">';
    row += '<label for="prescriptionitem-' + index + '-duration">' + labels.duration + '</label>';
    row += '<small class="form-text text-muted">E.g. 5 days</small>';
    row += '</div>';
    row += '</div>';
    row += '</div>';

    row += '<div class="row">';
    row += '<div class="col-md-9">';
    row += '<div class="form-label-group form-group field-prescriptionitem-' + index + '-comment">';
    row += '<input type="text" id="prescriptionitem-' + index + '-comment" class="form-control" name="PrescriptionItem[' + index + '][comment]" value="" autocomplete="off" placeholder="' + labels.comment + '">';
    row += '<label for="prescriptionitem-' + index + '-comment">' + labels.comment + '</label>';
    row += '</div>';
    row += '</div>';
    row += '<div class="col align-self-end">';
    row += '<div class="mdc-button-group direction-reverse pt-0 pb-3">';
    row += '<button type="button" class="mdc-button salamat-color delete-item-row">';
    row += '<div class="icon material-icon">close</div>' + removeText;
    row += '</button>';
    row += '</div>';
    row += '</div>';
    row += '</div>';

    row += '</div>';

    $('#prescription-items').append(row);

    $('#prescriptionitem-0-medicine option').clone().appendTo('#prescriptionitem-' + index + '-medicine');
    initDropdownMenu('#prescriptionitem-' + index + '-medicine');
    $('#prescriptionitem-0-form option').clone().appendTo('#prescriptionitem-' + index + '-form');
    initDropdownMenu('#prescriptionitem-' + index + '-form');
    $('#prescriptionitem-' + index + '-form').val('').trigger('change');
}

function setBlockDialogAction(dialog, url) {
    $(dialog + ' a.action-button').attr('href', url);
}

function resetBlockDialogAction() {
    $('#user-account-block a.action-button, #user-account-unblock a.action-button').attr('href', 'javascript: ;');
}

$(document).ready(function () {
    initDropdownMenu('select.bootstrap-select');
    initTimepicker('.form-control.bootstrap-timepicker');
    initDatepicker('.form-control.bootstrap-datepicker');

    $('form.auth-form').on('beforeSubmit', function (event) {
        $(this).children('.loading-block').addClass('active');
    });

    $('body').on('click', '.modal button[type="submit"]', function (event) {
        if (!$(this).hasClass('no-dismiss')) {
            $(this).closest('.modal').modal('hide');
        }
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

    $('#invoice-items, #prescription-items').on('click', 'button.delete-item-row', function (event) {
        $(this).closest('.item-row').remove();
    });

    $('#invoice-items').on('click', 'button.open-services-list', function (event) {
        $('#invoice-items .item-row.active').removeClass('active');
        var index = $(this).closest('.item-row').addClass('active').attr('data-index');
        $('#branch-services.mdc-sheets-side').attr('data-item', index);
        mdc_sheets_side_open('#branch-services.mdc-sheets-side');
    });

    $('#branch-services.mdc-sheets-side').on('click', 'button.invoice-item-service', function (event) {
        var index = $('#branch-services.mdc-sheets-side').attr('data-item');
        $('#invoiceitem-' + index + '-item').val($(this).attr('data-title')).trigger('change');
        $('#invoiceitem-' + index + '-amount').val($(this).attr('data-price')).trigger('change');
        $('#invoice-items .item-row.active').removeClass('active');
        $('#branch-services.mdc-sheets-side').attr('data-item', '');
        mdc_sheets_side_close('#branch-services.mdc-sheets-side');
    });

    $('#invoice-items').on('click', 'button.apply-discount-to-all', function (event) {
        var name = $(this).data('name');
        var discount_value = $('input[name="' + name + '[discount_value]"]').val();
        var discount_unit = $('input[name="' + name + '[discount_unit]"]:checked').val();
        $('input[name$="[discount_value]"]').val(discount_value);
        $('input[name$="[discount_unit]"][value=' + discount_unit + ']').prop('checked', true);
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