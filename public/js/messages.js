$(document).ready(function() {
    $(function() { $('[data-toggle="tooltip"]').tooltip() });
    $(".elevatezoom").elevateZoom({ scrollZoom: !0 })
});
$(function() {
    $.get('/unread/notifications', function(data) {
        $('#notification_count_message').text('You have ' + data.data.length + (data.data.length > 1 ? ' new notifications' : ' new notification'));
        if (data.data.length > 0) { $('#notification_count i').addClass('animated infinite tada'); } else { $('#notification_count i').removeClass('animated infinite tada') }
        $('#notification_count span').text(data.data.length);
        $('.menu').trigger('change');
    })
});
$(function() {
    menuItem = $('#bookings-mi small.label span');
    if (menuItem.text() > 0) {
        menuItem.parent().addClass('animated pulse infinite');
    }
});
$(function() { $("#dialog-make-booking").dialog({ autoOpen: !1, resizable: !1, height: "auto", show: { effect: "blind", duration: 500 }, hide: { effect: "explode", duration: 500 }, width: "auto", modal: !0, buttons: { "Delete all items": function() { $(this).dialog("close") }, Cancel: function() { $(this).dialog("close") } } }) });
$(".view-event").on("click", function() { $("#dialog-make-booking").dialog("open") });