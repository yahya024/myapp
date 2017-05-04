<?php

ini_set("display_errors", "of");
session_start();
if (isset($_SESSION['admin_id'])) {
    if ($_SERVER['CONTENT_LENGTH'] < 868192) {
        if (!empty($_FILES)) {
            $tmp_name = $_FILES['itImage']['tmp_name'];
            if ($_FILES['itImage']['error'] === 0) {
                if (exif_imagetype($tmp_name) === IMAGETYPE_JPEG || exif_imagetype($tmp_name) === IMAGETYPE_PNG) {
                    $ptrn = '/(.+)(?=\.\w+\b)/';
                    $name = preg_replace($ptrn, $_SESSION['admin_id'] . '_' . time(), $_FILES['itImage']['name']);
                    $up_name = 'tmp/' . $name;
                    if (isset($_SESSION['img_tmp'])) {
                        if (file_exists('tmp/' . $_SESSION['img_tmp'])) {
                            unlink('tmp/' . $_SESSION['img_tmp']);
                        }
                    }
                    move_uploaded_file($tmp_name, $up_name);
                    $_SESSION['img_tmp'] = $name;
                    echo $name;
                } else {
                    echo '2';
                }
            } else {
                echo '11';
            }
        } else {
            echo '0';
        }
    } else {
        echo '1';
    }
}



