$(document).ready(() => {
    $('#example').on('click','#delete',function(){
        let id = $(this).attr('data-id');
        $('#delete-accept').attr('data-id',id);
    });
});

function loadTable(obj) 
{
    let table = $(obj.selector).DataTable( {
        "buttons": obj.buttons,
        "bPaginate": true,
        "bLengthChange": false,
        "pageLength": 10,
        "dom": 'B<"top"f>rt<"bottom"lp><"clear">',
        "fnDrawCallback":function(){
            $("input[type='search']").attr("id", "searchBox");
            $('#searchBox').css("width", "300px");
        },
        "ajax": obj.url,
        "columnDefs": obj.columnDefs,
        "columns": obj.columns
    } );
    return table;
}