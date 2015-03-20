$('#search').keyup(function() {
    var that = this;
    $.each($('tr'),function(i, val) {
        if ($(val).find('.name').text().indexOf($(that).val()) == -1) {
            if ($(val).attr('id') != "tableHeader") {
                $('tr').eq(i).hide();
            }
        } else {
            $('tr').eq(i).show();
        }
    });
});
