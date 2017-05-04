<?php

function lang($langtext) {
    if (!isset($_SESSION['lang'])) {
        $lang = 'en';
    } else {
        $lang = 'ar';
    }
    $arr = array(
        'Login' => 'تسجيل الدخول',
        'Username or email address' => 'البريد الالكتروني او اسم المستخدم',
        'Your Password' => 'كلمة السر'
    );
    if ($lang == 'en') {
        return array_keys($arr, $langtext)[0];
    } else {
        return $langtext;
    }
}

echo lang('تسجيل الدخول');
