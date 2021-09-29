$(document).ready(function(){
    $('#file').change(function(){
        let file = this.files[0];
        let fdt = new FormData();
        fdt.append(this.name,file);
        $.ajax({
            type: "post",
            url: "/api/uploadFile",
            data: fdt,
            dataType: "json",
            enctype: 'multipart/form-data',
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
            success: function (res,x,a) {
                if(res.status && a.readyState === 4 && a.status === 200)
                    $('.title').after(addNameUrl(res.name,res.url));
            }
        })
        .always(function(res){
            $('.dropzone #file').val(null);
            $('.dropzone').remove();
            $('.title h1').text(res.text??'An Unknown Error');
            $('.btn-s.btn-group').html('<button class="btn-outline-success btn btn-block done">Done</button>');
            $('.btn-s.btn-group').addClass('success');
        });
    });

    $('.btn-s').on('click','.btn.done',function () 
    {
        location.href = '/';
    });

    $('.center').on('click','i.icon-copy',function() {
        let copyText = $(this).prev().attr('href');
        document.addEventListener('copy', function(e) {
            e.clipboardData.setData('text/plain', copyText);
            e.preventDefault();
         }, true);
         document.execCommand('copy'); 
    });
});

function uploadProgress(e)
{
    let percent = 0;
    let total = e.total;
    let position = e.loaded || e.position;
    if(e.lengthComputable)
        percent = Math.round(position / total * 100);
    $('.btn-content').text(percent + '%');
    $('.btn-progress').css('width',+ percent + '%');
}

function addNameUrl(name,url)
{
    return `<div class="row link-content justify-content-center">
            <a href="https://webfileupload.so/download/${url}" class="col-9">${name}</a>
            <i class="far fa-copy icon-copy col-1"></i>
          </div>`;
}