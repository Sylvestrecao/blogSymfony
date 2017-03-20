$(".reportComment").click(function(event){
    event.preventDefault();
});

function closeDiv(){
    $(function(){
        $("#reportSuccess").css("display", "none");
    })
}
function fadeInSuccess(){
    $(function(){
        $("#success").fadeIn(2000);
    })
}

function inputTitleValidation(){
    var input =  $("#blogbundle_post_name");
    var formGroup = $("#titleGroup");
    input.on('keyup keypress blur change', function() {
        var inputLength = input.val().length;
        if(inputLength < 5){
            formGroup.addClass("has-error")
            formGroup.removeClass("has-success")
        }
        else{
            formGroup.addClass("has-success")
            formGroup.removeClass("has-error")
        }
    });
}

function setReport(id){
    var path = Routing.generate('set_report');
    var reportData = {"Comment_ID": id};
    $.ajax({
        type: "POST",
        data: reportData,
        url: path,
        success: function(data){
            if(data){
                $("#reportSuccess").fadeIn(1000);
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
}
function deleteComment(id, postId){
    var path = Routing.generate('admin_comment_delete');
    var commentData = {"Comment_ID": id};
    
    $.ajax({
        type: "POST",
        data: commentData,
        url: path,
        success: function(data){
            if(data){
                $("#deleteSuccess").fadeIn(1000);
                $("." + id).remove();
                getCommentNumber(postId);
                getReportCommentNumber(postId);
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
}

function getCommentNumber(postId){
    var path = Routing.generate('admin_get_comments_post');
    var commentData = {"Post_ID": postId};

    $.ajax({
        type: "GET",
        data: commentData,
        url: path,
        success: function(data){
            $(".commentNumber").text(data);
            if(data > 1){
                $(".pluralCommentNumber").text('s');
            }
            else{
                $(".pluralCommentNumber").remove();
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
}
function getReportCommentNumber(postId){
    var path = Routing.generate('admin_get_report_comments_post');
    var commentData = {"Post_ID": postId};

    $.ajax({
        type: "GET",
        data: commentData,
        url: path,
        success: function(data){
            $(".reportCommentNumber").text(data);
            if(data > 1){
                $(".pluralReportCommentNumber").text('s');
            }
            else{
                $(".pluralReportCommentNumber").remove();
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
}
