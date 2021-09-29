$(document).ready(() => {
    let table = loadTable({
        selector: "#example",
        url: '/ajaxAdmin/users',
        buttons: [
            { text: "Thêm User",attr: {id: 'add','data-toggle': 'modal','data-target': '#modal-upload'},className: "btn btn-outline-danger btn-icon-text",init: function(api, node, config) {
              $(node).removeClass('dt-button');
              $(node).prepend(`<i class="ti-upload btn-icon-prepend mr-1"></i>`);
           }},
           { text: "Xoá",attr: {id: 'del'},className: "btn btn-outline-warning btn-icon-text",init: function(api, node, config) {
              $(node).removeClass('dt-button ')
           }}
        ],
        columnDefs: [
            { "title": "Check", "targets": 0,"width": "10%" },
            { "title": "Fullname", "targets": 1 },
            { "title": "Email", "targets": 2 },
            { "title": "Permission", "targets": 3 },
            { "title": "Date Created", "targets": 4 },
            { "title": "Date Updated", "targets": 5 },
            { "title": "Status", "targets": 6 },
            { "title": "Blocked", "targets": 7 }
        ],
        columns: [
            {
                "render": function(e,x,rows){
                    return `<input type="checkbox" value="${rows['id']}">`;
                }
            },
            {
                "render": function(e,x,rows){
                    return `<a href="/@Administrator/users/${rows['id']}">${rows['fullname']}</a>`;
                }
            },
            {"data": "email"},
            {
                "render": function(e,x,rows){
                    let permission = '';
                    if(rows['permission'] == 0)
                        permission = 'Member';
                    else if(rows['permission'] == 1)
                        permission = 'Manager';
                    else
                        permission = 'Admin';
                    return permission;
                }
            },
            {"data": "date_created"},
            {"data": "date_update"},
            {
                "render": function(e,x,rows){
                    let className = rows['status'] == "1" ? 'success' : 'danger';
                    let message = rows['status']  == "1" ? 'active' : 'no active';
                    return `<div class="pointer badge badge-${className}" data-id="${rows['id']}" id="status">${message}</div>`
                }
            },
            {
                "render": function(e,x,rows){
                    let className = rows['blocked']  == "1" ? 'success' : 'danger';
                    let message = rows['blocked']  == "1" ? 'active' : 'blocked';
                    return `<div class="pointer badge badge-${className}" data-id="${rows['id']}" id="blocked">${message}</div>`
                }
            }
        ]
    });
    $('#del').click(() => {
        let arrId = $.map($('#example tbody input[type="checkbox"]:checked'), function (elementOrValue, indexOrKey) {
            return elementOrValue.value ;
        });
        if(arrId.length == 0)
            return;
        $.ajax({
            type: "DELETE",
            url: "/ajaxAdmin/delete/users",
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
    $('#example').on('click','#status',function() {
        let id = $(this).attr('data-id');
        let that = this;
        $.ajax({
            type: "PUT",
            url: "/ajaxAdmin/changes/users",
            data: JSON.stringify({'id' : id,'status': Date.now()}),
            dataType: "json",
            success: function (res) {
                if(res.status != 200)
                {
                    toastr.error(res.message);
                    return;
                }
                toastr.success(res.message);
                if($(that).hasClass('badge-success'))
                {
                    $(that).removeClass('badge-success');
                    $(that).addClass('badge-danger');
                    $(that).text('no active')
                }
                else
                {
                    $(that).removeClass('badge-danger');
                    $(that).addClass('badge-success');
                    $(that).text('active')
                }
                getAuth();
            }
        });
    });
    $('#example').on('click','#blocked',function() {
        let id = $(this).attr('data-id');
        let that = this;
        $.ajax({
            type: "PUT",
            url: "/ajaxAdmin/changes/users",
            data: JSON.stringify({'id' : id,'blocked': Date.now()}),
            dataType: "json",
            success: function (res) {
                if(res.status != 200)
                {
                    toastr.error(res.message);
                    return;
                }
                if($(that).hasClass('badge-success'))
                {
                    $(that).removeClass('badge-success');
                    $(that).addClass('badge-danger');
                    $(that).text('blocked')
                }
                else
                {
                    $(that).removeClass('badge-danger');
                    $(that).addClass('badge-success');
                    $(that).text('active')
                }
            }
        });
    });
    $('#add').click(() => location.href = '/@Administrator/add/users');
});