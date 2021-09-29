import * as func from './validation.js';
$(document).ready(() => {
    $('.form-group input').blur(function() {
        func.checkInput($(this));
    })

    $('.login-form').submit(function(e)
    {
        e.preventDefault();
        let count = 0;
        $('.form-group input').each(function()
        {
            count += func.checkInput($(this)); 
        });

        if(count == $('.form-group input').length)
        {
            let arr = Array.from($(this).serializeArray()).reduce((acc,val)=>{
                return {...acc,[val.name] : val.value};
            },{});

            $.post("/api/login", JSON.stringify(arr),
                function (data, textStatus, jqXHR) {
                    if(jqXHR.readyState == 4 && jqXHR.status == 200)
                    {
                        $('.form-notify').removeClass('success error');
                        $('.form-notify').text(data.message);
                        grecaptcha.reset();
                        if(data.status)
                        {
                            $('.form-notify').addClass('success');
                            setTimeout(() => {
                                location.href = '/';
                            },1000);
                        }
                        else
                            $('.form-notify').addClass('error');
                    }
                }
            );
        }
    });
});