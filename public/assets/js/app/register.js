import * as func from './validation.js';

$(document).ready(() => {
    let flag = true;
    $('.form-group input').blur(function(){
        func.checkInput($(this));
    });
    $('.register-form').on('submit',function(e) {
        e.preventDefault();
        let count = 0;
        $('.form-group input').each(function()
        {
            count += func.checkInput($(this)); 
        });
        if(count === $('.form-group input').length && flag)
        {
            $('#register').attr('disabled','true');
            $('.btn-submit').append('<img src="http://webfileupload.so/public/assets/img/load.gif" alt="load.gif">');
            flag = false;
            let arr = Array.from($(this).serializeArray()).reduce((acc,val)=>{
                return {...acc,[val.name] : val.value};
            },{});
            $.ajax({
                type: "post",
                url: "/api/register",
                data: JSON.stringify(arr),
                contentType: 'application/json',
                success: function (res,a,xhr) {
                    if(xhr.readyState == 4 && xhr.status == 200)
                    {
                        $('.form-notify').removeClass('success error');
                        $('.form-notify').text(res.message);
                        $('.form-notify').addClass('success');
                        if(!res.status)
                            $('.form-notify').addClass('error');
                    }
                },
                complete: () => 
                {
                    $('#register').removeAttr('disabled');
                    grecaptcha.reset();
                    $('.btn-submit img').remove();
                    flag = true;
                }
            });
        }
    });
});

/*
class error 'is-invalid'

class success 'is-valid'
*/

