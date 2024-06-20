$(function () {
    $('#contact-form').validator();
    console.log("Working")
    // when the form is submitted
    $('#contact-form').on('submit', function (e) {

        // if the validator does not prevent form submit
        if (!e.isDefaultPrevented()) {
            var url = "./assets/forms/contact.php";

            // POST values in the background the the script URL
            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize(),
                success: function (data)
                {
                    // data = JSON object that contact.php returns
                    // we recieve the type of the message: success x danger and apply it to the
                    var messageAlert = 'alert-' + data.type;
                    var messageText = data.message;
                    console.log(messageText);

                    // let's compose Bootstrap alert box HTML

                    var alertBox = '<div class="alert alert-primary ' + messageAlert + ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + messageText + '</div>';
                    // var alertBox = `<div class="alert alert-success" role="alert">
                    //                    A simple success alert—check it out!
                    //             </div>`

                    // If we have messageAlert and messageText
                    if (messageAlert && messageText) {
                        // inject the alert to .messages div in our form
                        $('#contact-form').find('.messages').html(alertBox);
                        // empty the form
                        $('#contact-form')[0].reset();
                    }
                }
            });
            return false;
        }
    })
});