$(document).ready(() => {
    $('#login-form').submit(function(e)
    {
        e.preventDefault();
        let arr = $(this).serializeArray();
        let message = [];
        let data = {};
        if(arr.length === 0)
            message.push('Not Empty');
        arr.forEach(val => {
            if(val.name == 'email')
                ((/^[a-z0-9_\.]+@[a-z]{3,5}(\.[a-z]{2,3}){1,2}$/i).test(val.value)?message:message.push('Email contains only (a-z), (0-9), (._)'));
            if(val.name == 'password')
                ((/^\w{6,20}$/i).test(val.value)?message:message.push('Password contains only (a-z), (0-9), (_), from 6-20 characters'));
            data[val.name] = val.value;
        });
        if(message.length === 0)
            $.post("/api/login", JSON.stringify(data),
                function (res) {
                    if(res.status)
                    {
                        toastr.success(res.message);
                        setTimeout(()=>{
                            location.href = '/@Administrator';
                        },2000);
                    }
                    else
                        toastr.error(res.message);
                },
                "json"
            );
        else
        {
            let str = message.join('\n');
            toastr.error(str);
        }
        grecaptcha.reset();
    });
});