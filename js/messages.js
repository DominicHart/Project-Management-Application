$('.conversation h4').click(function() {
    if(!$(this).parent().hasClass('active')) {
        $('.conversation').removeClass('active');
        $(this).parent().addClass('active');
    }
    else {
        $('.conversation').removeClass('active');
    }
});
//Messages
$('#selectall').click(function() {
    //If select all checkbox is checked
    if($(this).prop('checked')) {
        //Check all select message checkboxes
        $('.selectmessage').prop('checked', true);
        //Get total of checked select message checkboxes
        var total = $('.selectmessage:checked').length;
        //If the delete button doesn't exist
        if(!$('#deletemessages').length && (!$('#archivemessages').length)) {
            //Add thr buttons and message
            $(this).parent().after('<button id="deletemessages">Delete</button>');
            $(this).parent().after('<p class="choose">'+ total + ' conversations selected, choose an action:</p><button id="archivemessages">Archive</button>');
        }
        else {
            //Otherwise just update the count
            $('.choose').text(+ total + ' conversations selected, choose an action:');
        }
    }
    else {
        //If you are unchecking the select all checkbox
        $('.selectmessage').prop('checked', false);
        //If the buttons exist
        if($('#deletemessages').length && $('#archivemessages').length) {
            //Remove buttons and message
            $('.choose').remove();
            $('#deletemessages').remove();
            $('#archivemessages').remove();
        }
    }
});
$('.selectmessage').click(function() {
    //Get number of checked select message checkboxes
    var total = $('.selectmessage:checked').length;
    //If the checkbox is being checked
    if($(this).prop('checked')) {
        //If the message and buttons do not exist add them
        if(!$('#deletemessages').length && (!$('#archivemessages').length)) {
            $('#selectall').parent().after('<button id="deletemessages">Delete</button>');
            $('#selectall').parent().after('<p class="choose">'+ total + ' conversations selected, choose an action:</p><button id="archivemessages">Archive</button>');
        }
        else {
            //If they do exist, update count
            $('.choose').text(+ total + ' conversations selected, choose an action:');
        }
    }
    //If a select message checkbox is unchecked
    else {
        //If the select all checkbox is also unchecked
        if($('#selectall').is(':not(:checked)')) {
            //If there aren't any other select message checkboxes checked
            if(total < 1) {
                //If the message and buttons exist remove them
                if ($('#deletemessages').length && $('#archivemessages').length) {
                    $('.choose').remove();
                    $('#deletemessages').remove();
                    $('#archivemessages').remove();
                }
            }
            //If there are other select message checkboxes checked, update count
            else {
                $('.choose').text(+ total + ' conversations selected, choose an action:');
            }
        }
        //If select all checkbox is checked
        else {
            //Uncheck select all checkbox and update total
            $('#selectall').prop('checked', false);
            $('.choose').text(+total + ' conversations selected, choose an action:');
        }
    }
});
$(document).on('click', '#archivemessages', function() {
    var conversations = [];
    $('.selectmessage:checked').map(function(){
        conversations.push($(this).val());
    });
    var IDs = conversations.toString();
    $("#edit-delete").load('parts/amessage.php', {js_submit_value : IDs});
    $("#overlay").css("display", "block");
});
$(document).on('click', '#deletemessages', function() {
    var conversations = [];
    $('.selectmessage:checked').map(function(){
       conversations.push($(this).val());
    });
    var IDs = conversations.toString();
    $("#edit-delete").load('parts/dmessage.php', {js_submit_value : IDs});
    $("#overlay").css("display", "block");
});
//Archive
$('#selectarchive').click(function() {
    //If select all checkbox is checked
    if($(this).prop('checked')) {
        //Check all select message checkboxes
        $('.selectitem').prop('checked', true);
        //Get total of checked select message checkboxes
        var total = $('.selectitem:checked').length;
        //If the delete button doesn't exist
        if(!$('#deletemessages2').length && (!$('#activatemessages').length)) {
            //Add thr buttons and message
            $(this).parent().after('<button id="deletemessages2">Delete</button>');
            $(this).parent().after('<p class="choose">'+ total + ' conversations selected, choose an action:</p><button id="activatemessages">Move to inbox</button>');
        }
        else {
            //Otherwise just update the count
            $('.choose').text(+ total + ' conversations selected, choose an action:');
        }
    }
    else {
        //If you are unchecking the select all checkbox
        $('.selectitem').prop('checked', false);
        //If the buttons exist
        if($('#deletemessages2').length && $('#activatemessages').length) {
            //Remove buttons and message
            $('.choose').remove();
            $('#deletemessages2').remove();
            $('#activatemessages').remove();
        }
    }
});
$('.selectitem').click(function() {
    //Get number of checked select message checkboxes
    var total = $('.selectitem:checked').length;
    //If the checkbox is being checked
    if($(this).prop('checked')) {
        //If the message and buttons do not exist add them
        if(!$('#deletemessages2').length && (!$('#activatemessages').length)) {
            $('#selectarchive').parent().after('<button id="deletemessages2">Delete</button>');
            $('#selectarchive').parent().after('<p class="choose">'+ total + ' conversations selected, choose an action:</p><button id="activatemessages">Move to inbox</button>');
        }
        else {
            //If they do exist, update count
            $('.choose').text(+ total + ' conversations selected, choose an action:');
        }
    }
    //If a select message checkbox is unchecked
    else {
        //If the select all checkbox is also unchecked
        if($('#selectarchive').is(':not(:checked)')) {
            //If there aren't any other select message checkboxes checked
            if(total < 1) {
                //If the message and buttons exist remove them
                if ($('#deletemessages2').length && $('#activatemessages').length) {
                    $('.choose').remove();
                    $('#deletemessages2').remove();
                    $('#activatemessages').remove();
                }
            }
            //If there are other select message checkboxes checked, update count
            else {
                $('.choose').text(+ total + ' conversations selected, choose an action:');
            }
        }
        //If select all checkbox is checked
        else {
            //Uncheck select all checkbox and update total
            $('#selectarchive').prop('checked', false);
            $('.choose').text(+total + ' conversations selected, choose an action:');
        }
    }
});
$(document).on('click', '#activatemessages', function() {
    var conversations = [];
    $('.selectitem:checked').map(function(){
        conversations.push($(this).val());
    });
    var IDs = conversations.toString();
    $("#edit-delete").load('parts/a2message.php', {js_submit_value : IDs});
    $("#overlay").css("display", "block");
});
$(document).on('click', '#deletemessages2', function() {
    var conversations = [];
    $('.selectitem:checked').map(function(){
        conversations.push($(this).val());
    });
    var IDs = conversations.toString();
    $("#edit-delete").load('parts/dmessage.php', {js_submit_value : IDs});
    $("#overlay").css("display", "block");
});