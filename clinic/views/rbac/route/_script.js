$('.routes').click(function () {
    var route = [$(this).data('value')];
    var value = $(this).children("input").val();

    //console.log(value);

    $.post(value == 0 ? assign : remove, {routes: route}, function (r) {
        // updateItems(r);
    }).always(function () {
        // $this.children('i.glyphicon-refresh-animate').hide();
    });

});

$('[name=search]').keyup(function () {
    var q = $(this).val();

    $.each($(".routes"), function (key, elm) {

        var $elm = $(elm);
        var value = $elm.data('value');

        $elm.parent().parent().parent().css('display', 'block');

        if(q.length <= 0)
        {

            return;

        }

        if(value.indexOf(q) <= 0)
        {
            $elm.parent().parent().parent().css('display', 'none');
        }
        else {
            $elm.parent().parent().parent().css('display', 'block');
        }

        //console.log();
    })

});