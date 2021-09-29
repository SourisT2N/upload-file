$(document).ready(function(){
    let table = $('#example').DataTable( {
        "scrollY":        "200px",
        "scrollCollapse": true,
        "paging":         false,
        "ajax": "/api/getFileUser",
        "columns": [
            {
             "render": function(data,type,row){
                return `<a href="/download/${row['_name_md5']}">${row['_nameFile']}</a>`;
            }},
            { "data": "_extension" },
            { "data": "date_upload" },
            { "data": "date_expires" },
            { "data": "storages" },
            {
                "render": function(data,type,row)
                {
                    return `<i class="far fa-trash-alt" id="delete" data-id='${row['_idFile']}' data-toggle="modal" data-target="#dialog1"></i>`;
                }
            }
        ]
    } );
    $('.icon-copy').click(() => {
        copyInnerText("#api_key");
    });

    $('span[data-target="#exampleModalCenter"]').click(function() {
        let name = this.id.split('-')['1'];
        $('.modal-body#text-modal').html(createButton(name));
        $('.modal-footer button[type="submit"]').attr('data-id',name);
        $("#exampleModalLongTitle").text('');
    });

    $('#example').on('click','tbody tr td i#delete',function() {
        let idFile = this.getAttribute('data-id');
        $('#modal-delete').text('Are you sure to delete this file?');
        $('#dialog1 .modal-footer #deleteFile').attr('data-id',idFile);
    });
    let deleteMessage;
    $('#deleteFile').click(function() {
        let idFile = $(this).attr('data-id');
        if(deleteMessage)
            clearTimeout(deleteMessage);
        $.ajax({
            type: "DELETE",
            url: "https://webfileupload.so/api/delete/file/" + idFile,
            dataType: "json",
            success: function (data) {
                $('#dialog1').modal('hide');
                table.clear().draw();
                table.ajax.reload();
                $('#message-box').html(createMessage(data.message,data.status));
                deleteMessage = setTimeout(() => $('#message-box').html(''),2000);
            }
        });
    });

    $('button[type="submit"]').click(function(e){
        e.preventDefault();
        $("#exampleModalLongTitle").removeClass('text-danger text-success');
        let id = $(this).attr('data-id');
        let values = [];
        $.each($('#text-modal input'),(i,v) => values.push($(v).val()));
        let regex = /^\w{6,20}$/i;
        let check = values.map((val) => regex.test(val));
        if(check.includes(false))
        {
            grecaptcha.reset();
            $("#exampleModalLongTitle").text('Check input again');
            $("#exampleModalLongTitle").addClass('text-danger');
            return;
        }
        $.ajax({
            type: "PUT",
            url: "/api/change/"+id,
            data: $('#form-change').serialize(),
            dataType: "json",
            success: function (data) {
                grecaptcha.reset();
                $("#exampleModalLongTitle").text(data.message);
                if(data.status)
                {
                    $("#exampleModalLongTitle").addClass('text-success');
                    setTimeout(() => {
                        $('#exampleModalCenter').modal('hide');
                    }, 2000);
                }
                else
                    $("#exampleModalLongTitle").addClass('text-danger');
            }
        });
    });
    $('.card .donate').click(function(e){
        e.preventDefault();
            let id = $(this).attr('id');
            $.post("/api/payment/"+id,
                function (data) {
                    if(data.status)
                        location.href = data.url;
                    else
                    {
                        $('#message-box').html(createMessage(data.message,data.status));
                        setTimeout(() => {
                            $('#message-box').html('');
                        }, 2000);
                    }
                },
                "json"
            );
    });
});
function copyInnerText(id)
{
    let value = document.querySelector(id).innerText;
    let input = document.createElement('input');
    document.body.appendChild(input);
    input.type = 'text';
    input.value = value;
    input.select();
    document.execCommand('copy');
    input.parentNode.removeChild(input);
}