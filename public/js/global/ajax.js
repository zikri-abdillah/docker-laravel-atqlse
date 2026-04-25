function postdata(postdata) {
    var jqxhr = $.ajax({
        url: url,
        method: "POST",
        data: { id: "menuId", csrf_test_name: csrfName },
    })
        .done(function () {
            alert("success");
        })
        .fail(function () {
            alert("error");
        })
        .always(function () {
            alert("complete");
        });

    // Perform other work here ...

    // Set another completion function for the request above
    // jqxhr.always(function () {
    //     alert("second complete");
    // });

}
