$(document).ready(function () {
    //sidenav
    $('.mynav .dch').click(function () {
        $('.sidenav').toggleClass('sidshow');
        $('.dachbord, .mynav').toggleClass('navmargin');
    });

    // conferm delet
    $('.conferm').click(function (e) {
        if (!confirm('هل انت متاكد من حذف هذا العضو')) {
            e.preventDefault();
        }
    });


    //upload
    function _g(el) {
        return document.getElementById(el);
    }
    var itFile = _g('itFile'),
            itDef = _g('itDef');
    if (itFile) {
        itFile.addEventListener('change', uploadFile, false);
        itDef.addEventListener('click', function (e) {;
            itFile.click();
        }, false);
        
        
    }


    function uploadFile() {

        var file = itFile.files[0];

        var mtTy = $('.it-def span'),
                progress = $('.it-img .progress');
        progress.children('.progress-bar').css('width', '0%');
        mtTy.css('color', '#000');
        ;

        var mimiType = file.type;

        // check img
        if (mimiType === 'image/png' || mimiType === 'image/jpeg') {
            
            progress.fadeIn();
            var formData = new FormData();

            formData.append('itImage', file);
            var xhttp;
            if (window.XMLHttpRequest) {
                
                xhttp = new XMLHttpRequest();
            } else {
                
                xhttp = new ActiveXObject("Microsoft.XMLHTTP");
                
            }


            xhttp.upload.addEventListener('progress', progressHandler, false);
            xhttp.addEventListener('load', te, false);
            function te(e) {
                var response = e.target.responseText;
                if (response === '2') {
                    mtTy.html('صورة غير صالحة').css('color', '#F00');
                    
                } else if (response === '11' || response === '1' || response === '0') {
                    mtTy.html('اختر صورة لا تتعدى 2 ميقا').css('color', '#F00');

                } else {
                    $('.it-img #itDef').attr('src', '../uploads/tmp/' + response);
                    $('.it-add #itSrc').val(response);
                }

            }
            function progressHandler(e) {
                progress.children('.progress-bar').text('% ' + ((e.loaded / e.total) * 100));
                progress.children('.progress-bar').css('width', ((e.loaded / e.total) * 100) + '%');
            }
            xhttp.open('POST', '../../uploads/upload_ajax.php');
            xhttp.send(formData);

        } else {
            mtTy.html('الرجاء اختر صورة صالحة');
            mtTy.css('color', '#F00');
            progress.fadeOut();
        }

        

    }



});


