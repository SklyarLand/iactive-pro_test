$('#test').click(function(){
    let message = "{ 'messages':[ { 'message':'Как ваши дела?', 'phone':'7999999999' }, {'message':'Привет!','phone':'7999999998'}]}";
    let json = JSON.stringify(message);

    $.ajax({
        type: "POST",
        url: "api/webhook",
        port: 9000,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        data: json,
        success: function(result){
            console.log(result);
        }
    })
})