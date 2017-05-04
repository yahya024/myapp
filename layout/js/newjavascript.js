/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function () {
    /* nav add class active */
    $("#myNavbar ul:first-of-type li").not("#myNavbar ul:first-of-type li:last-of-type").on("click", function () {
        $(this).siblings().removeClass("active");
        $(this).addClass("active");
    });

    /* start modal */
//    $("#singIn").click(function () {
//        $("#myModal").modal({
//            backdrop: true,
//            keyboard: true
//        });
//    });
//
//    $('#myModal').on('shown.bs.modal', function (e) {
//        $("#email").delay(2222).focus();
//    });
    /* start modal */
});


