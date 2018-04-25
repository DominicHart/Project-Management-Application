$('#phase1').change(function() {
    var phase = document.getElementById("phase1").value;
    //If the select value is other
    if (phase == 'Other') {
        //Show the text input the define a phase and focus it
        $('.phase').removeClass('col-sm-12');
        $('.phase').addClass('col-sm-6');
        $('.phase2').css('display', 'block');
        $('#phase2').focus();
    }
    else {
        //If isn't other or user changes mind, hide text input and unfocus it
        $('.phase').removeClass('col-sm-6');
        $('.phase').addClass('col-sm-12');
        $('.phase2').css('display', 'none');
        $('#phase').focus();
    }
});
$('#customer').focusin(function() {
    var customer = document.getElementById("customer").value;
    //If select value is other
    if (customer == 'Other') {
        //Provide link to add a new customer
        $('.customer').removeClass('col-sm-12');
        $('.customer').addClass('col-sm-6');
        $('.newcustomer').css('display', 'block');
        $('#newcustomer').focus();
    }
    else {
        //If it isnt or user changes mind, hide link
        $('.customer').removeClass('col-sm-6');
        $('.customer').addClass('col-sm-12');
        $('.newcustomer').css('display', 'none');
        $('#customer').focus();
    }
});
$('#customer').change(function() {
    var customer = document.getElementById("customer").value;
    //If select value is other
    if (customer == 'Other') {
        //Provide link to add a new customer
        $('.customer').removeClass('col-sm-12');
        $('.customer').addClass('col-sm-6');
        $('.newcustomer').css('display', 'block');
        $('#newcustomer').focus();
    }
    else {
        //If it isnt or user changes mind, hide link
        $('.customer').removeClass('col-sm-6');
        $('.customer').addClass('col-sm-12');
        $('.newcustomer').css('display', 'none');
        $('#customer').focus();
    }
});
$('#password2').blur(function() {
    var password1 = document.getElementById("password1").value;
    var password2 = document.getElementById("password2").value;
    //Compare passwords
        if(password1 != password2) {
            //If the error does not already exist
            if(!$('#show-error').length) {
                //Append the error
                $('.password1').before("<div class='col-sm-12' id='show-error'><p class='error' style='color:red;font-weight:bold;text-align:left;margin:0 0 10px 0;'>The passwords do not match</p></div>");
            }
            //Disable the form button
            $('#scustomer').attr("disabled", "disabled");
            $('#scustomer').addClass("disabled");
        }
        else {
            //Remove the error if it exists
            if($('#show-error').length) {
                $('.password1').prev().remove("#show-error");
            }
            $('#scustomer').removeAttr("disabled");
            $('#scustomer').removeClass("disabled");
        }
});
//Check for capslock when logging in
$('.passwordlogin').keypress(function(e) {
    var kc = e.which; //get keycode
    var isUp = (kc >= 65 && kc <= 90) ? true : false; // uppercase
    var isLow = (kc >= 97 && kc <= 122) ? true : false; // lowercase
    // event.shiftKey does not seem to be normalized by jQuery(?) for IE8-
    var isShift = ( e.shiftKey ) ? e.shiftKey : ( (kc == 16) ? true : false ); // shift is pressed

    // uppercase w/out shift or lowercase with shift == caps lock
    if ( (isUp && !isShift) || (isLow && isShift) ) {
        //If the error does not exist, append it
        if(!$('#show-capslock').length) {
            $('.passwordlogin').before("<div id='show-capslock'><p class='error2'>Capslock is on</p></div>");
        }
    }
    else {
        //If the error exists, remove it
        if($('#show-capslock').length) {
            $('.passwordlogin').prev().remove("#show-capslock");
        }
    }
});
//Check for capslock when changing password
$('#password1').keypress(function(e) {
    var kc = e.which; //get keycode
    var isUp = (kc >= 65 && kc <= 90) ? true : false; // uppercase
    var isLow = (kc >= 97 && kc <= 122) ? true : false; // lowercase
    // event.shiftKey does not seem to be normalized by jQuery(?) for IE8-
    var isShift = ( e.shiftKey ) ? e.shiftKey : ( (kc == 16) ? true : false ); // shift is pressed

    // uppercase w/out shift or lowercase with shift == caps lock
    if ( (isUp && !isShift) || (isLow && isShift) ) {
        //If the error does not exist, append it
        if(!$('#show-capslock').length) {
            $('.password1').before("<div class='col-sm-12' id='show-capslock'><p class='error2'>CAPSLOCK is on</p></div>");
        }
    }
    else {
        //If the error exists, remove it
        if($('#show-capslock').length) {
            $('.password1').prev().remove("#show-capslock");
        }
    }
});
$('#password1').keypress(function() {
    //Password requirements - active validation
    var password = document.getElementById("password1").value;
    var upperCase= new RegExp('[A-Z]');
    var lowerCase= new RegExp('[a-z]');
    var numbers = new RegExp('[0-9]');
    //Check for uppercase letters, if none, display error
    if(!password.match(upperCase)) {
        //Check if error already exists
        if(!$('#prequirement').length) {
            //If not, append error
            $('.password1').before("<div class='col-sm-12' id='prequirement'><p class='error3'>Password must contain: uppercase</p></div>");
        }
        else {
            //If yes, simply change error text instead of appending another
            $('p.error3').text("Password must contain: uppercase");
        }
    }
    //Check for lowercase letters
    if(!password.match(lowerCase)) {
        //Check if error already exists
        if(!$('#prequirement').length) {
            //If not, append error
            $('.password1').before("<div class='col-sm-12' id='prequirement'><p class='error3'>Password must contain: lowercase</p></div>");
        }
        else {
            //If yes, simply change error text instead of appending another
            $('p.error3').text("Password must contain: lowercase");
        }
    }
    //Check for numeric values
    if(!password.match(numbers)) {
        //Check if error already exists
        if(!$('#prequirement').length) {
            //If not, append error
            $('.password1').before("<div class='col-sm-12' id='prequirement'><p class='error3'>Password must contain: number</p></div>");
        }
        else {
            //If yes, simply change error text instead of appending another
            $('p.error3').text("Password must contain: number");
        }
    }
    //Check password length
    if(password.length < 9) {
        //Check if error already exists
        if(!$('#prequirement').length) {
            //If not, append error
            $('.password1').before("<div class='col-sm-12' id='prequirement'><p class='error3'>Password must be 9 characters or longer</p></div>");
        }
        else {
            //If yes, simply change error text instead of appending another
            $('p.error3').text("Password must be 9 characters or longer");
        }
    }
    //If meets all requirements
    else if(password.length > 8 && password.match(upperCase) && password.match(lowerCase) && password.match(numbers)) {
        if($('#prequirement').length) {
            $('.password1').prev().remove("#prequirement");
        }
    }
});
$(document).ready(function() {
    $('.datepicker').datepicker({
        //beforeShowDay: $.datepicker.noWeekends
    });
});
$(document).on("focusin", ".datepicker", function(){
    $('.datepicker').datepicker({
        //beforeShowDay: $.datepicker.noWeekends
    });
});