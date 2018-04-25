$('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
    var target = this.href.split('#');
    $('.nav a').filter('[href="#'+target[1]+'"]').tab('show');
});
$('#project2').change(function() {
    var project = document.getElementById("project2").value;
    if(project == 'Select Project') {
        $("#load-milestones").css('display', 'none');
        $("#load-budget").css('display', 'none');
        $("#load-pstaff").css('display', 'none');
    }
    else {
        $("#load-milestones").css('display', 'block');
        $("#load-milestones").load('parts/milestone.php', {js_submit_value: project});
        $("#load-budget").css('display', 'block');
        $("#load-budget").load('parts/abudget.php', {js_submit_value: project});
        $("#load-pstaff").css('display', 'block');
        $("#load-pstaff").load('parts/pstaff.php', {js_submit_value: project});
    }
});
$('#project').change(function() {
    var project = $('#project').val();
    $("#load-dates").css('display', 'block');
    $("#load-dates").load('parts/datepicker.php', {js_submit_value: project});
});
$(document).on("change", "#milestone", function(){
    var milestone = $('#milestone').val();
    $("#load-dates2").css('display', 'block');
    $("#load-dates2").load('parts/datepicker2.php', {js_submit_value: milestone});
});
function searchUsers() {
    var input, filter, table, tr, td, i;
    input = document.getElementById("searchusers");
    filter = input.value.toUpperCase();
    table = document.getElementById("user-table");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1];
        if (td) {
            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
                if(filter == 'all') {
                    tr[i].style.display = "none";
                }
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
$('#username').keyup(function() {
    var username = $('#username').val();
    $("#load-username").css('display', 'block');
    $("#load-username").load('parts/username.php', {js_submit_value: username});
});
$('#username2').keyup(function() {
    var username = $('#username2').val();
    $("#load-username2").css('display', 'block');
    $("#load-username2").load('parts/username.php', {js_submit_value: username});
});
$('#email').keyup(function() {
    var email = $('#email').val();
    $("#load-email").css('display', 'block');
    $("#load-email").load('parts/email.php', {js_submit_value: email});
});
$('#email2').keyup(function() {
    var email = $('#email2').val();
    $("#load-email2").css('display', 'block');
    $("#load-email2").load('parts/email.php', {js_submit_value: email});
});
function edit_modal() {
    $("#overlay").css("display", "none");
    $('#edit-delete').html('');
}
$(document).on("click", ".new-comment", function(){
    var name = this.name;
    $("#edit-delete").load('parts/newcomment.php', {js_submit_value : name});
    $("#overlay").css("display", "block");
});
$(document).on("click", ".dproject", function(){
    var name = this.name;
    $("#edit-delete").load('parts/dproject.php', {js_submit_value : name});
    $("#overlay").css("display", "block");
});
$(document).on("click", ".dproject2", function(){
    var project = $("#man-proj").val();
    $("#edit-delete").load('parts/dproject.php', {js_submit_value : project});
    $("#overlay").css("display", "block");
});
$(document).on("click", ".duser", function(){
    var user = $("#man-user").val();
    $("#edit-delete").load('parts/duser.php', {js_submit_value : user});
    $("#overlay").css("display", "block");
});
$(document).on("click", ".e-details", function(){
    var name = this.name;
    $("#edit-delete").load('parts/edetails.php', {js_submit_value : name});
    $("#overlay").css("display", "block");
});
$(document).on("click", ".etask", function(){
    var task = $("#task-list").val();
    $("#edit-delete").load('parts/etask.php', {js_submit_value : task});
    $("#overlay").css("display", "block");
});
$(document).on("click", ".emilestone", function(){
    var milestone = $("#milestone-list").val();
    $("#edit-delete").load('parts/emilestone.php', {js_submit_value : milestone});
    $("#overlay").css("display", "block");
});
$(document).on("click", ".dtask", function(){
    var task = $("#task-list").val();
    $("#edit-delete").load('parts/dtask.php', {js_submit_value : task});
    $("#overlay").css("display", "block");
});
$(document).on("click", ".deuser", function(){
    var user = $("#man-user").val();
    $("#edit-delete").load('parts/deuser.php', {js_submit_value : user});
    $("#overlay").css("display", "block");
});
$(document).on("click", ".euser", function(){
    var user = $("#man-user").val();
    $("#edit-delete").load('parts/euser.php', {js_submit_value : user});
    $("#overlay").css("display", "block");
});
$(document).on("click", ".dmilestone", function(){
    var milestone = $("#milestone-list").val();
    $("#edit-delete").load('parts/dmilestone.php', {js_submit_value : milestone});
    $("#overlay").css("display", "block");
});
$(document).on("click", ".dtransaction", function(){
    var name = this.name;
    $("#edit-delete").load('parts/dtransaction.php', {js_submit_value : name});
    $("#overlay").css("display", "block");
});
$(document).on("click", ".drisk", function(){
    var risk = $("#risk-list").val();
    $("#edit-delete").load('parts/drisk.php', {js_submit_value : risk});
    $("#overlay").css("display", "block");
});
$(document).on("click", ".erisk", function(){
    var name = $("#risk-list").val();
    $("#edit-delete").load('parts/erisk.php', {js_submit_value : name});
    $("#overlay").css("display", "block");
});
$(document).on("click", ".dtransaction", function(){
    var transaction = $("#transaction-list").val();
    $("#edit-delete").load('parts/dtransaction.php', {js_submit_value : transaction});
    $("#overlay").css("display", "block");
});
$('#changepassword').click(function(e){
    e.preventDefault();
    $("#edit-delete").load('parts/password.php');
    $("#overlay").css("display", "block");
    //Reinitialise dropdown
    $('.dropdown-toggle').dropdown();
});
$(document).on("click", ".tproject", function(){
    var name = this.name;
    $("#edit-delete").load('parts/transfer.php', {js_submit_value : name});
    $("#overlay").css("display", "block");
});
$(document).on("click", ".dfile", function(){
    var name = this.name;
    $("#edit-delete").load('parts/dfile.php', {js_submit_value : name});
    $("#overlay").css("display", "block");
});
$(document).on("click", ".eissue", function(){
    var issue = $("#issue-list").val();
    $("#edit-delete").load('parts/eissue.php', {js_submit_value : issue});
    $("#overlay").css("display", "block");
});
$(document).on("click", ".dissue", function(){
    var issue = $("#issue-list").val();
    $("#edit-delete").load('parts/dissue.php', {js_submit_value : issue});
    $("#overlay").css("display", "block");
});