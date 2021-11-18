App = {
    init: function (){

        $('#table_list').DataTable({
            ajax: 'api/api.php?cmd=list',
            pageLength: 25,
            columns:[
                { data: 'id' },
                { data: 'title' },
                { data: 'intro' },
                { data: 'url',
                    "mRender": function ( data, type, row ) {
                        return "<a target='_blank' href='"+row.url+"'>Aplikuj</a>";
                    }},
                { data: 'option',
                    "mRender": function ( data, type, row ) {
                        return "<button class='details btn btn-primary' data-id='"+row.id+"'>Więcej</button>";
                    },
                }
            ],
            fnDrawCallback: function() {
                $(".details").off().on("click",function(){
                    App.getDetails($(this).data("id"));
                });
            }
        });

    },

    getDetails: function(id){
        $.ajax({
            type: 'GET',
            dataType: 'json',
            context: this,
            url: 'api/api.php?cmd=item&id='+id,
            success: function (data) {
                $('.modal-body').empty().append($.templates("#detailsTmpl").render(data));
                $("#details").modal("show");
            }});
    }
}
App.init();