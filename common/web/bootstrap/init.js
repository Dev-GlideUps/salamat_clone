function readImageFile(input) {
    if (input.files && input.files[0] && input.files[0].type.match('image.*')) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            $(input).siblings('.custom-file-label').attr('style', "background-image: url('"+e.target.result+"');");
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        if ($(input).siblings('.custom-file-label').hasClass('has-photo')) {
            var photo = $(input).siblings('.custom-file-label').attr('data-photo');
            $(input).siblings('.custom-file-label').attr('style', "background-image: url('"+photo+"');");
        } else {
            $(input).siblings('.custom-file-label').removeAttr('style');
        }
    }
}

function setGridDataRowDelete(id) {
    $('#grid-data-row-delete .record-id').val(id);
}

function nano_scoller_init(selector) {
    $(selector).nanoScroller({
        iOSNativeScrolling: true,
        preventPageScrolling: true
    });
}

function overlay_scoller_init(selector) {
    $(selector).overlayScrollbars({
        // options
        className : "os-theme-main",
    });
}

$(document).ready(function() {
    nano_scoller_init('.nano');
    overlay_scoller_init('.overlay-scroller');

    $('body').on('click', '#mdc-nav-drawer ~ .mdc-drawer-scrim', function(event) {
        $('#mdc-nav-drawer').removeClass('active');
    });

    $('body').on('click', '#mdc-top-app-bar > .nav-icon', function(event) {
        if ($('#mdc-nav-drawer').hasClass('active')) {
            $('#mdc-nav-drawer').removeClass('active');
        } else {
            $('#mdc-nav-drawer').addClass('active');
        }
    });

    $('body').on('change', '.personal-photo-input .custom-file .form-control-file', function(event) {
        readImageFile(this);
    });

    $('body').append($('.modal[role="dialog"]'));
});