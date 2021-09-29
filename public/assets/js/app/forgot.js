import * as func from './validation.js';
$(document).ready(() => {
    $('.login-body').on('blur','.form-group input',function() {
        func.checkInput($(this));
    });
    $('.login-form').submit(function(e)
    {
        e.preventDefault();
        let count = 0;
        $('.form-notify').removeClass('text-danger text-success');
        $('.form-group input').each(function()
        {
            count += func.checkInput($(this)); 
        });
        if(count == $('.form-group input').length)
        {
            $('.btn-submit').append('<img src="http://webfileupload.so/public/assets/img/load.gif" alt="load.gif">');
            $.post("/api/getCode", $(this).serialize(),
                function (data) {
                    $('.form-notify').text(data.message);
                    grecaptcha.reset();
                    $('.form-notify').addClass('text-danger');
                    $('.btn-submit img').remove();
                    if(data.status)
                    {
                        $('.form-notify').removeClass('text-danger');
                        $('.form-notify').addClass('text-success');
                        $('.login-form .login-body').html(show());
                        $('#login').text('Change password');
                        $('#login').attr('id','accept');
                    }
                },
                "json"
            );
        }
    });
    $('.login-form').on('click','#accept',(e) => {
        e.preventDefault();
        let count = 0;
        $('.form-notify').removeClass('text-danger text-success');
        $('.form-group input').each(function()
        {
            count += func.checkInput($(this)); 
        });
        if(count == $('.form-group input').length)
        {
            $.post("/api/newPass", $('.login-form').serialize(),
                function (data) {
                    grecaptcha.reset();
                    $('.form-notify').text(data.message);
                    $('.form-notify').addClass('text-danger');
                    if(data.status)
                    {
                        $('.form-notify').removeClass('text-danger');
                        $('.form-notify').addClass('text-success');
                        $('.login-form').html('');
                    }
                },
                "json"
            );
        }
    });
});

function show()
{
    return `<div class="form-group">
            <label>Code</label>
            <input type="text" class="form-control" name="code" placeholder="Code">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="text" class="form-control" name="password" placeholder="New Password">
        </div>
        <div class="form-group">
            <label>Re Password</label>
            <input type="text" class="form-control" name="re-password" placeholder="Re Password">
        </div>  `;
}