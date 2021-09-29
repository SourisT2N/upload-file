$(document).ready(() => {
    let table = loadTable({
        selector: "#example",
        url: '/ajaxAdmin/files',
        buttons: [
            { text: "Thêm File",attr: {id: 'add','data-toggle': 'modal','data-target': '#modal-upload'},className: "btn btn-outline-danger btn-icon-text",init: function(api, node, config) {
              $(node).removeClass('dt-button');
              $(node).prepend(`<i class="ti-upload btn-icon-prepend mr-1"></i>`);
           }},
           { text: "Xoá",attr: {id: 'del'},className: "btn btn-outline-warning btn-icon-text",init: function(api, node, config) {
              $(node).removeClass('dt-button ')
           }}
        ],
        columnDefs: [
            { "title": "Check", "targets": 0,"width": "10%" },
            { "title": "Name File", "targets": 1 },
            { "title": "Extension", "targets": 2 },
            { "title": "Storages", "targets": 3 },
            { "title": "Date Upload", "targets": 4 },
            { "title": "Date Expires", "targets": 5 },
            { "title": "Options", "targets": 6 }
        ],
        columns: [
            {
                "render": function(e,x,rows){
                    return `<input type="checkbox" value="${rows['_idFile']}">`;
                }
            },
            {
                "render": function(e,x,rows){
                    return `<a href="/download/${rows['_name_md5']}">${rows['_nameFile']}</a>`;
                }
            },
            {"data": "_extension"},
            {"data": "storages"},
            {"data": "date_upload"},
            {"data": "date_expires"},
            {
                "render": function(e,x,rows){
                  return `
                  <a id="delete" data-id='${rows['_idFile']}' data-toggle="modal" data-target="#modal-default">
                    <svg style="width:24px;height:24px;" viewBox="0 0 24 24">
                        <path fill="#fc0000" ng-attr-d="{{icon.data}}" d="M9,3V4H4V6H5V19A2,2 0 0,0 7,21H17A2,2 0 0,0 19,19V6H20V4H15V3H9M7,6H17V19H7V6M9,8V17H11V8H9M13,8V17H15V8H13Z"></path>
                    </svg>
                  </a>`;
                }
            }
        ]
    });

    $('#add').on('click',function()
    {
        $('.image-upload-wrap').css('display','block');
        $('.image-upload-wrap').html(`
                    <input class="file-upload-input" type='file'/>
                    <div class="drag-text">
                    <h3>Drag and drop a file</h3>
                    </div>`);
    });
    $('.image-upload-wrap').on('change','input[type="file"]',function(){
        if(this.files.length == 0)
            return;
        let file = this.files[0];
        let fdt = new FormData();
        fdt.append('file[]',file);
        $('.image-upload-wrap').html('');
        $('.image-upload-wrap').css('display','none');
        $.ajax({
            type: "post",
            url: "/api/uploadFile",
            data: fdt,
            contentType: false,
            cache: false,
            processData: false,
            timeout: 5000,
            async: true,
            xhr: function(){
                let myXhr = new $.ajaxSettings.xhr();
                if(myXhr.upload)
                {
                    myXhr.upload.addEventListener('progress',uploadProgress,false);
                }
                return myXhr;
            },
            enctype: "multipart/form-data",
            dataType: "json",
            success: function (res) {
                $('#modal-upload').modal('hide');
                if(res.status)
                {
                    table.clear().draw();
                    table.ajax.reload();
                    toastr.success(res.text);
                }
                else
                    toastr.error(res.text);
            }
        });
    });
    $('#del').on('click',() =>{
        let arrId = $.map($('#example tbody input[type="checkbox"]:checked'), function (elementOrValue, indexOrKey) {
            return elementOrValue.value ;
        });
        if(arrId.length == 0)
            return;
        $.ajax({
            type: "delete",
            url: "/ajaxAdmin/delete/files",
            data: JSON.stringify({'idFiles': arrId}),
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
    $('#delete-accept').click(function(){
        let id = $(this).attr('data-id');
        $.ajax({
            type: "delete",
            url: "/ajaxAdmin/delete/files",
            data: JSON.stringify({'idFiles': [id]}),
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
});

function uploadProgress(e)
{
    let percent = 0;
    let total = e.total;
    let position = e.loaded || e.position;
    if(e.lengthComputable)
        percent = Math.round(position / total * 100);
    $('.file-upload-content').addClass('active');
    $('#progress').css('width', + percent + '%');
}