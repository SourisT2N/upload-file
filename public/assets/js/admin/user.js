$(document).ready(() => {
    $('.col-sm-9.text-secondary input:not([type="submit"])').on('input',function() {
        let name = $(this).attr('name');
        let val = $(this).val();
        if(!checkInput(name,val))
            return;
        if(name == 'fullname')
            $('#profile h4').text(val);
        else if(name == 'email')
            $('#profile p').text(val);
    });
    $('#create').click(function (e) {
        e.preventDefault();
        let el = Array.from($('.col-sm-9.text-secondary input:not([type="submit"])'));
        let array = el.map((v) => checkInput(v.name,v.value));
        if(array.includes(false) || el.length != array.length || el.length == 0)
        {
            toastr.error('Check input again');
            return;
        }
        $.post("/ajaxAdmin/add/user", $('#form').serialize(),
            function (res) {
                if(res.status == 200)
                {
                    toastr.success(res.message);
                    $('#form').trigger('reset');
                }
                else
                    toastr.error(res.message);
            },
            "json"
        );
    });
    $('#edit').click(function(e){
        e.preventDefault();
        let id = $(this).attr('data-id');
        let el = Array.from($('.col-sm-9.text-secondary input:not([type="submit"])'));
        let array = el.map((v) => checkInput(v.name,v.value));
        if(array.includes(false) || el.length != array.length || el.length == 0)
        {
            toastr.error('Check input again');
            return;
        }
        let data = Array.from($('#form').serializeArray()).reduce((cur,val) => {
            cur[val.name] = val.value;
            return cur;
        },{})
        data.id = id;
        $.ajax({
            type: "PUT",
            url: "/ajaxAdmin/update/user",
            data: JSON.stringify(data),
            dataType: "json",
            success: function (res) {
                if(res.status == 200)
                    toastr.success(res.message);
                else
                    toastr.error(res.message);
            }
        });
    });
});

function checkInput(name,val)
{
    switch (name) {
        case 'fullname': 
            return (/^[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂẾưăạảấầẩẫậắằẳẵặẹẻẽềềểếỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵýỷỹ\s|]{3,50}$/).test(val);
        case 'email' :
            return (/^[a-z0-9_\.]+@[a-z]{3,5}(\.[a-z]{2,3}){1,2}$/i).test(val);
        case 'password':
            return (/^\w+$/).test(val);
        default: 
            return false;
    }
}