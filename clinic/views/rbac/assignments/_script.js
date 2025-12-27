$('.items').click(function () {
    var item = [$(this).data('value')];
    var value = $(this).children("input").val();

    //console.log(value);

    $.post(value == 0 ? assign : revoke, {items: item}, function (r) {
        // updateItems(r);
    }).always(function () {
        // $this.children('i.glyphicon-refresh-animate').hide();
    });

});
