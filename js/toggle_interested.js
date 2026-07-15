$(document).ready(function () {

    $(".interested-button").click(function () {

        var button = $(this);

        var property_id = button.data("property");

        $.ajax({

            url: "api/toggle_interested.php",

            type: "POST",

            data: {
                property_id: property_id
            },

            success: function (response) {

                if (response == "login") {
                    $("#login-modal").modal("show");
                    return;
                }

                var data = response.split("|");

                var status = data[0];
                var count = data[1];

                $("#interested-user-count-" + property_id).text(count);

                if (status == "added") {

                    button.removeClass("far").addClass("fas");

                }

                if (status == "removed") {

                    button.removeClass("fas").addClass("far");

                }

            }

        });

    });

});x``