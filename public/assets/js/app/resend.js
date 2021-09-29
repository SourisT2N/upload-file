$(document).ready(() => {
    $('#resend').click(function() {
        let that = this;
        $(this).parent().append('<img src="http://webfileupload.so/public/assets/img/load.gif" alt="load.gif">');
        $.ajax({
            type: "PUT",
            url: "/api/resendMail",
            data: JSON.stringify({idURL: getIdFile()}),
            dataType: "json",
            xhrFields: {
                withCredentials: true
              },
            contentType: "application/json",
            success: function (data,a,xhr) {
                if(xhr.status == 200 && xhr.readyState == 4)
                {
                    $('.form-notify').removeClass('success error');
                    $('.form-notify').text(data.message);
                    $('.form-notify').addClass('success');
                    if(!data.status)
                        $('.form-notify').addClass('error');
                    $(that).remove();
                }
            },
            complete: () =>{
                $(that).next().remove();
            }
        });
    });
});