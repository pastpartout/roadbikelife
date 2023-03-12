
    $(function() {


    function renderJson(elem) {
        try {
            var input = eval('(' + elem.text() + ')');
        }
        catch (error) {
            // return alert("Cannot eval JSON: " + error);
        }
        var options = {
            collapsed: true,
            rootCollapsable: true,
            withQuotes: true,
            withLinks: true
        };
        console.log(elem)
        console.log(input)
        elem.jsonViewer(input, options);
    }


    $('.json-viewer').each(function() {
        renderJson($(this));
    })

    // Display JSON sample on page load
});