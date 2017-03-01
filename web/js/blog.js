$(".reportComment").click(function(event){
    event.preventDefault();
});
function fadeInSuccess(){
    $(function(){
        $("#success").fadeIn(2000);
    })
}

function setReport(id){
    var path = Routing.generate('set_report');
    var reportData = {"Comment_ID": id};
    $.ajax({
        type: "POST",
        data: reportData,
        url: path,
        success: function(){
            console.log(reportData);
            $("#reportSuccess").fadeIn(1000);
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
}
function deleteComment(id){
    var path = Routing.generate('admin_comment_delete');
    var commentData = {"Comment_ID": id};
    $.ajax({
        type: "POST",
        data: commentData,
        url: path,
        success: function(){
            console.log(commentData);
            window.location.reload();
            $("#deleteSuccess").fadeIn(1000);
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
}