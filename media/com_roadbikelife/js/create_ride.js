$(document).ready(function () {

    $(".stravaRoutes .list-group-item").on("click", function () {
        //get the file name
        $(".stravaRoutes .custom-control-input").attr("checked", false);
        $(this).find(".custom-control-input").attr("checked", "checked");
    });
    $("#gpxfile").on("change", function () {
//get the file name
        var fullPath = $(this).val();
        var filename = fullPath.replace(/^.*[\\\/]/, "");

//replace the "Choose a file" label
        $(this).next(".custom-file-label").html(filename);
    });

    $("div.datePicker").each(function () {
        var timepicker = $(this);
        var date = new Date();
        date.setDate(date.getDate() + 7);
        timepicker.datetimepicker({
            format: "DD.MM.YYYY HH:m",
            locale: "DE",
// keepOpen: true,
            inline: true,
            sideBySide: true,
            minDate: new Date(),
            maxDate: date,
            // debug:true,
            calendarWeeks: false,
            viewMode: "days",
            focusOnShow: false,
            icons: {
                time: "fa fa-time",
                date: "fa fa-calendar",
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
                previous: "fa fa-chevron-left",
                next: "fa fa-chevron-right",
                today: "fa fa-screenshot",
                clear: "fa fa-trash",
                close: "fa fa-remove"
            }
        });
// timepicker.data("DateTimePicker").show();

        timepicker.on("dp.change", function (event) {
            var formatted_date = event.date.format("DD.MM.YYYY HH:m");
            $(".date_start_input").val(formatted_date);
        });
    })
})