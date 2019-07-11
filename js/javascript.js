var validateTagOnForm = {
        1:'name',
        2:'email',
        3:'textTasks',
        4:'loginAdmin',
        5:'passwordAdmin',
        6:'textTasksAdmin'
    },
    validateRequired = {
        'name':true,
        'email':true,
        'textTasks':true,
        'loginAdmin':true,
        'passwordAdmin':true,
        'textTasksAdmin':true
    },
    validatePattern = {
        "name": "^[A-zА-яЕЁ]*",
        "email": ".+@.+\\..+",
        "textTasks": "^([A-zА-яЕЁ0-9]+)",
        "loginAdmin":"^[A-z0-9_]*",
        "passwordAdmin":"[A-zА-яЕЁ0-9]+",
        "textTasksAdmin":"^([A-zА-яЕЁ0-9]+)"
    },
    validateMessage = {
        "name": "Ошибка!Неверно введен пользователь!",
        "email": "Ошибка!Неверно введен E-mail!",
        "textTasks": "Ошибка!Некорректно введен текст задачи!",
        "loginAdmin":"Ошибка!Неверно введен логин!",
        "passwordAdmin":"Ошибка!Неверно введен пароль!",
        "textTasksAdmin":"Ошибка!Некорректно введен текст задачи!"
    };

function validateTag(idTag,pattern,errorMessage,event){
    if(document.getElementById(idTag)){
        let val = $('#'+idTag).val(),
            reg = new RegExp(pattern,'g');
        $('.'+idTag).children('.form-error').html('');
        if(!reg.test(val)){
            event.preventDefault();
            $('.'+idTag).children('.form-error').html('<span class="mess-err" style="color:red">'+errorMessage+'</span>');
            return false;
        }
    }
    return true;
}

function setPatternAndRequiredForInput(input,pattern,required) {
    $(input).ready(function () {
        $(input).attr('pattern', pattern);
        $(input).attr('required', required);
    });
}

$('.tasks_button__add').click(function(){
    $('#modal-add-task').modal('show');
});
$('.tasks_button__checkingAdmin').click(function(){
    $('#modal-add-admin').modal('show');
});


//on event closed window
$('#modal-add-admin').on('hidden.bs.modal', function () {
    location.reload();
})

 $('#textTasks').keyup(function () {
        let s_name = $('#name').val(),
            s_email = $('#email').val(),
            s_textTasks = this.value,
            idTag = this.id,
            errorMessage = 'Данная задача уже существует у пользователя '+s_name+'!';
        if(s_name && s_email && s_textTasks){
           $.ajax({
                method: 'post',
                dataType: "json",
                data:{
                    "action":"getIdTasksByIdTasksAndIdUsers",
                    "s_name": s_name,
                    "s_email": s_email,
                    "s_textTasks": s_textTasks,
                    "dataType":"json"
                },
                success: (function (result) {
                    if(result === true){
                        $('.'+idTag).children('.form-error').html('<span class="mess-err" style="color:red">'+errorMessage+'</span>');
                        $('.modal-footer').css('display','none');
                    }else{
                        $('.'+idTag).children('.form-error').html('');
                        $('.modal-footer').css('display','revert');
                    }
                }),
            });
        }
        });
/**---------------------------------for add new task---------------------------**/
$('#add-task').submit(function(event) {
    let validateTagsResult = true,
        validateTagOnForm = {
            1:'name',
            2:'email',
            3:'textTasks'
        },
        s_name = $('#name').val(),
        s_email = $('#email').val(),
        s_textTasks = $('#textTasks').val(),
        s_statusTasks = '0';

    for (var idTag in validateTagOnForm) {
        let tag = validateTagOnForm[idTag],
            validate = true;
        validate = validateTag(tag,validatePattern[tag],validateMessage[tag],event);
        if(!validate) {
            validateTagsResult = validate;
        }
    }
    if(validateTagsResult){
        $.ajax({
            method: 'post',
            dataType: "json",
            data: {
                "action":"insertTask",
                "s_name": s_name,
                "s_email": s_email,
                "s_textTasks": s_textTasks,
                "s_statusTasks": s_statusTasks,
                "dataType":"json"
            },
            success: (function (result) {
                if(result === true){
                    $('#name').val('');
                    $('#email').val('');
                    $('#textTasks').val('');
                    $('#modal-add-task').modal('hide');
                    location.reload();
                }
            })
        });
    }
    return false;
});
/**---------------------------------for checking admin---------------------------**/
$('#add-admin').submit(function(event) {
    let validateTagsResult = true,
        validateTagOnForm = {
            1:'loginAdmin',
            2:'passwordAdmin'
        },
        s_loginAdmin = $('#loginAdmin').val(),
        s_passwordAdmin = $('#passwordAdmin').val(),
        errorMessage = 'Ошибка входа!Пожалуйста проверьте логин и пароль!'

    for (var idTag in validateTagOnForm) {
        let tag = validateTagOnForm[idTag],
            validate = true;
        validate = validateTag(tag,validatePattern[tag],validateMessage[tag],event);
        if(!validate) {
            validateTagsResult = validate;
        }
    }
    if(validateTagsResult){
        $.ajax({
            method: 'post',
            dataType: "json",
            data: {
                "action":"checkedOnAccessRights",
                "s_loginAdmin": s_loginAdmin,
                "s_passwordAdmin": s_passwordAdmin,
                "dataType":"json"
            },
            success: (function (result) {
                if(result === true){
                    $('#loginAdmin').val('');
                    $('#passwordAdmin').val('');
                    $('.passwordAdmin').html('');
                    $('#modal-add-task').modal('hide');
                    location.reload();
                }
                if(result === null){
                    $('.passwordAdmin').html('<span class="mess-err" style="color:red">'+errorMessage+'</span>');
                }
            })
        });
    }
    return false;
});

/**---------------------------------for get rights user---------------------------**/
$('.tasks_button__checkingUser').click(function () {
    $.ajax({
        method: 'post',
        dataType: "json",
        data: {
            "action":"checkedOnAccessRights",
            "s_loginAdmin": null,
            "s_passwordAdmin": null,
            "dataType":"json"
        },
        success: (function (result) {
            location.reload();
        })
    });
})
/**---------------------------------modal editing task admin---------------------------**/
if(document.getElementById('modal-add-admin')) {
    var idTask = null,
        s_name = null,
        s_email = null,
        s_statusTask = 0,
        s_textTasks = null,
        defaulTagName = 'Admin';

    $('.statusTask').click(function(){
        idTask = this.id;
        idTask = idTask.replace(/statusTask_/g,'');
        //get value for input from table
        s_statusTask = $('#'+this.id).data('statustask');
        s_name = $('#nameUser_' + idTask).text().trim();
        s_email = $('#emailUser_' + idTask).text().trim();
        s_textTasks = $('#taskUser_' + idTask).text().trim();
        //set value in input modal window
        $('#name'+defaulTagName).val(s_name);
        $('#email'+defaulTagName).val(s_email);
        $('#textTasks'+defaulTagName).val(s_textTasks);
        $('#customCheck1').prop('checked',s_statusTask);

        $('#modal-editing-task-admin').modal('show');
        $('.modal-footer').css('display','revert');
    });

    $('#editing-task-admin').submit(function (event) {
        let validateTagsResult = true,
            validateTagOnForm = {
                1: 'textTasksAdmin'
            };
        //get value form
        s_textTasks = $('#textTasks'+defaulTagName).val();
        s_statusTask = $('#customCheck1').is(':checked');
        if(s_statusTask){s_statusTask = 1};
        if(!s_statusTask){s_statusTask = 0};
        console.log(s_statusTask);

        for (let idTag in validateTagOnForm) {
            let tag = validateTagOnForm[idTag],
                validate = true;
            validate = validateTag(tag, validatePattern[tag], validateMessage[tag], event);
            if (!validate) {
                validateTagsResult = validate;
            }
        }
        if (validateTagsResult) {
            $.ajax({
                method: 'post',
                dataType: "json",
                data: {
                    "action": "editingTask",
                    "s_idTask":idTask,
                    "s_textTasks": s_textTasks,
                    "s_statusTasks": ''+s_statusTask+'',
                    "dataType": "json"
                },
                success: (function (result) {
                    if (result === true) {
                       $('#modal-editing-task-admin').modal('hide');
                        location.reload();
                    }
                })
            });
        }
        return false;
    });
}

$(".tasks_sorting").on('change',function () {
 let idTypeSorting = $('option:selected', this).val(),
     url = window.location.href,
     newUrl = url,
     getParam = '&';
    if(!/[\\?]/.test(newUrl)){
        getParam = "?";
    }
    if(!/sortingTask=\d/.test(newUrl)){
        newUrl = url+getParam+"sortingTask=0";
    }
    newUrl = newUrl.replace(/(sortingTask=\d)/g,'sortingTask='+idTypeSorting);
    window.location.href = newUrl;
})

$(window).on('load', function() {
    //set patters and required for tags
    for (var idTag in validateTagOnForm) {
        let tag = validateTagOnForm[idTag];
        setPatternAndRequiredForInput('#'+tag,validatePattern[tag],validateRequired[tag]);
    }


});