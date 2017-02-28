$(".reportComment").click(function(event){
    event.preventDefault();
});
function fadeInSuccess(){
    $(function(){
        $("#success").fadeIn(2000);
    })
}

function setReport(id){
    var bloghost = "http://localhost/blogSymfony/web/app_dev.php";
    var reportData = {"Comment_ID": id};
    $.ajax({
        type: "POST",
        data: reportData,
        url: bloghost + '/setreport',
        success: function(){
            console.log(reportData);
            $("#reportSuccess").fadeIn(1000);
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
}