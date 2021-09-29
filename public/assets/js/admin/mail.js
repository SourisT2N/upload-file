$(document).ready(() => {
    CKEDITOR.replace( 'inputMessage');
    $('form').submit(async function(e) {
        try
        {
            e.preventDefault();
            $('button[type="submit"]').attr('disabled',true);
            $('button[type="submit"]').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Sending...`);
            let data = Array.from($(this).serializeArray()).reduce((cur,val) => {
                if(val.value !== '')
                {
                    if(val.name != 'email' || (val.name == 'email' && (/^[a-z0-9_\.]+@[a-z]{3,5}(\.[a-z]{2,3}){1,2}$/i).test(val.value)))
                        cur[val.name] = val.value;
                }
                return cur;
            },{});
            let body = CKEDITOR.instances['inputMessage'].getData();
            if(Object.keys(data).length !== 3 || body == '')
                throw('Check input again');
            data.body = body;
            await $.post("/ajaxAdmin/send-mail", data,
                function (res) {
                    if(res.status == 200)
                        toastr.success(res.message);
                    else
                        toastr.error(res.message);
                },
                "json"
            );
            throw('');
        }
        catch(e)
        {
            if(e.length != 0)
                toastr.error(e);
            $('button[type="submit"]').attr('disabled',false);
            $('button[type="submit"]').html(`Send Message`);
        }
    });
});

