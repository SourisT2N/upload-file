$(document).ready(function() {
    $('#datepicker').datetimepicker({
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-chevron-up",
            down: "fa fa-chevron-down",
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-screenshot',
            clear: 'fa fa-trash',
            close: 'fa fa-remove'
        }
     });
    let table = loadTable({
        selector: "#example",
        url: '/ajaxAdmin/donates',
        buttons: [
            { text: "Thêm Donate",attr: {id: 'add','data-toggle': 'modal','data-target': '#form-modal'},className: "btn btn-outline-danger btn-icon-text",init: function(api, node, config) {
                $(node).removeClass('dt-button');
                $(node).prepend(`<i class="ti-upload btn-icon-prepend mr-1"></i>`);
            }},
            { text: "Xoá",attr: {id: 'del'},className: "btn btn-outline-warning btn-icon-text",init: function(api, node, config) {
                $(node).removeClass('dt-button ')
            }}
        ],
        columnDefs: [
            { "title": "Check", "targets": 0,"width": "10%" },
            { "title": "ID", "targets": 1 },
            { "title": "ID Transaction", "targets": 2 },
            { "title": "Type", "targets": 3 },
            { "title": "Amount", "targets": 4 },
            { "title": "Status", "targets": 5 },
            { "title": "Date Donate", "targets": 6 },
            { "title": "Users", "targets": 7 },
            { "title": "Options", "targets": 8 },
        ],
        columns: [
            {
                "render": function(e,x,rows){
                    return `<input type="checkbox" value="${rows['id']}">`;
                }
            },
            {"data": "id"},
            {"data": "transaction_id"},
            {"data": "type"},
            {"data": "amount"},
            {
                "render": function(e,x,rows){
                    let className = rows['status'] == "1"?'success':'danger';
                    let message = rows['status'] == "1"?'Success':'Cancel';
                    return `<div class="badge badge-${className}">${message}</div>`
                }
            },
            {"data": "date_donate"},
            {"data": "fullname"},
            {
                "render": function(e,x,rows){
                  return `
                  <a id="edit" data-id='${rows['id']}' data-toggle="modal" data-target="#form-modal">
                    <svg style="width:24px;height:24px;" viewBox="0 0 24 24">
                        <path fill="green" ng-attr-d="{{icon.data}}" d="M4 6H2V20C2 21.11 2.9 22 4 22H18V20H4V6M18.7 7.35L17.7 8.35L15.65 6.3L16.65 5.3C16.86 5.08 17.21 5.08 17.42 5.3L18.7 6.58C18.92 6.79 18.92 7.14 18.7 7.35M9 12.94L15.06 6.88L17.12 8.94L11.06 15H9V12.94M20 4L20 4L20 16L8 16L8 4H20M20 2H8C6.9 2 6 2.9 6 4V16C6 17.1 6.9 18 8 18H20C21.1 18 22 17.1 22 16V4C22 2.9 21.1 2 20 2Z"></path>
                    </svg>
                  </a>
                  <a id="delete" data-id='${rows['id']}' data-toggle="modal" data-target="#modal-default">
                    <svg style="width:24px;height:24px;" viewBox="0 0 24 24">
                        <path fill="#fc0000" ng-attr-d="{{icon.data}}" d="M9,3V4H4V6H5V19A2,2 0 0,0 7,21H17A2,2 0 0,0 19,19V6H20V4H15V3H9M7,6H17V19H7V6M9,8V17H11V8H9M13,8V17H15V8H13Z"></path>
                    </svg>
                  </a>`;
                }
            }
        ]
    });
    $('#delete-accept').click(function() {
        let id = $(this).attr('data-id');
        $.ajax({
            type: "DELETE",
            url: "/ajaxAdmin/delete/donate",
            data: JSON.stringify({'arrId': [id]}),
            dataType: "json",
            success: function (res) {
                $('#modal-default').modal('hide');
                if(res.status == 200)
                {
                    table.clear().draw();
                    table.ajax.reload();
                    toastr.success(res.message);
                }
                else
                    toastr.error(res.message);
            }
        });
    });
    $('#add').click(() => {
        $('.btn.btn-success').text('Add');
        $('.btn.btn-success').attr('id','add-btn');
        $('#form').trigger("reset");
        $('#amount').text('0 VND');
    });
    $('#example').on('click','#edit', function() {
        $('.btn.btn-success').text('Edit');
        $('.btn.btn-success').attr('id','edit-btn');
        $('.btn.btn-success').attr('data-id',$(this).attr('data-id'));
        let id = $(this).attr('data-id');
        $.get("/ajaxAdmin/donate/"+id,
            function (res) {
                if(res.status != 200)
                {
                    console.error(res.message);
                    return;
                }
                $('input[name="id"]').val(res['data'].id);
                $('input[name="transaction_id"]').val(res['data']['transaction_id']);
                $('input[name="amount"]').val(res['data'].amount);
                $('input[name="type"]').val(res['data'].type);
                $('input[name="email"]').val(res['data'].email);
                $(`select[name="status"] option[value="${res['data']['status']}"]`).prop('selected',true);
                let date = new Date(res['data']['date_donate']);
                $('input[name="date"]').val(new Intl.DateTimeFormat('vi-VN').format(date));
            },
            "json"
        );
    });
    $('#del').click(() => {
        let arrId = $.map($('#example tbody input[type="checkbox"]:checked'), function (elementOrValue, indexOrKey) {
            return elementOrValue.value ;
        });
        if(arrId.length == 0)
            return;
        $.ajax({
            type: "delete",
            url: "/ajaxAdmin/delete/donate",
            data: JSON.stringify({'arrId': arrId}),
            dataType: "json",
            success: function (res) {
                if(res.status == 200)
                {
                    table.clear().draw();
                    table.ajax.reload();
                    toastr.success(res.message);
                }
                else
                    toastr.error(res.message);
            }
        });
    });
    $('input[name="amount"]').on('input',function() {
        let regex = /^\d*$/;
        if(!regex.test(this.value))
            return;
        let value = new Intl.NumberFormat('ja-JP').format(this.value == ''?0:this.value) + ' VND';
        $(this).next('span').text(value);
    });
    $('#form-modal').on('click','#add-btn',() => {
        if(!checkForm())
            return;
        $.post("/ajaxAdmin/add/donate", $('#form').serialize(),
            function (res) {
                if(res.status == 200) 
                {
                    table.clear().draw();
                    table.ajax.reload();
                    toastr.success(res.message);
                    $('#form-modal').modal('hide');
                }
                else
                    toastr.error(res.message);
            },
            "json"
        );
    });
    $('#form-modal').on('click','#edit-btn',function() {
        if(!checkForm())
            return;
        let id = $(this).attr('data-id');
        let data = $('#form').serializeArray().reduce((v,el) => {
            v[el.name] = el.value;
            return v;
        },{});
        $.ajax({
            type: "put",
            url: "/ajaxAdmin/update/donate/"+id,
            data: JSON.stringify(data),
            dataType: "json",
            success: function (res) {
                if(res.status == 200) 
                {
                    table.clear().draw();
                    table.ajax.reload();
                    toastr.success(res.message);
                    $('#form-modal').modal('hide');
                }
                else
                    toastr.error(res.message);
            }
        });
    });
});

function checkForm()
{
    let el = $('#form input');
    let message = Array.from(el.map((e,v) => checkInput(v)));
    let select = $('.form-control[name="status"]').val();
    if(select != 0 && select != 1)
        message.push(false);
    if(message.includes(false) || el.length != message.length || el.length == 0)
    {
        toastr.error('Check input again');
        return false;
    }
    return true;
}

function checkInput(el)
{
    let name = $(el).attr('name');
    let val = el.value;
    switch(name)
    {
        case 'id':
            return (/^\w+$/i).test(val);
        case 'type':
            return (/^\w+$/i).test(val);
        case 'transaction_id':
            return (/^\d+$/).test(val);
        case  'amount':
            return (/^\d+$/).test(val);
        case 'email':
            return (/^[a-z0-9_\.]+@[a-z]{3,5}(\.[a-z]{2,3}){1,2}$/i).test(val);
        case 'date':
            return new Date(val) != 'Invalid Date';
        default:
            return false;
    }
}