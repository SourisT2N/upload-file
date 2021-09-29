$(document).ready(function (){
    fetch('/api/getFile/' + getIdFile(),
    {
        method: 'GET',
        mode: 'cors', 
        cache: 'no-cache', 
        credentials: 'same-origin',
        headers: {
        'Content-Type': 'application/json'
        },
    })
    .then(res => res.json())
    .then(data => {
        let file = data.file;
        $('.center').html(getButtonDownload(data.text,data.status));
        let path = 'upload/' + file['_urlFile'] + `.${file['_extension']}`
        $('.center #link').attr('href',path);
        $('.center #link').attr('download', `${file['_nameFile']}.${file['_extension']}`);

    })
    .catch(e => {
        console.error(e);
    });
    $('.center').on('click','.btn-group .btn.btn-block',() => location.href = '/');
});

function getButtonDownload(text,status)
{
    let download = status ? '<a href="" id="link" class="btn-outline-primary btn btn-lg">Download</a>' : '';
    return `<div class="title">
             <h1>${text}</h1>
          </div>
          <div class="btn-group success">
          ${download}
          <button class="btn-outline-primary btn btn-block">Done</button>
        </div>`;
}