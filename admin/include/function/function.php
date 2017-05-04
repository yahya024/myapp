<?php
/*
 * yahya bensedira
 * Copyright © 2017
 */


// fucntion count

function count_db($count, $from) {
    global $con;
    $stmt = $con->prepare("SELECT count(:count) as count FROM $from");
    $stmt->bindParam(':count', $count);
    $stmt->execute();
    return $stmt->fetch();
}

// users no activ
function user_no_activ($orderBy, $limit = '') {
    global $con;
    $sql = empty($limit) ? "SELECT * FROM users WHERE status = 0" : "SELECT * FROM users WHERE status = 0" . " ORDER by id " . $orderBy . " LIMIT " . $limit;
    $stmt = $con->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

// get user wher id
function getuser($id) {
    global $con;
    $stmit = $con->prepare('SELECT * FROM users WHERE id= :id');
    $stmit->execute(array('id' => $id));
    return $stmit->fetch();
}

// get item wher id
function getitem($id) {
    global $con;
    $stmit = $con->prepare('SELECT * FROM items WHERE id= :id');

    $stmit->execute(array('id' => $id));

    return $stmit->fetch();
}

// get all users
function get_all_users($noactive = true) {
    $noactive = $noactive ? null : 'WHERE status = 1';
    global $con;
    $stmit = $con->prepare("SELECT * FROM users $noactive");
    $stmit->execute();
    return $stmit->fetchAll();
}

// user exists
function user_exists($id) {
    global $con;
    $stmit = $con->prepare('SELECT id FROM users WHERE id = ?');
    $stmit->execute(array($id));
//    if($stmit->rowCount() == 1){
//        return true;
//    } else {
//        return false;
//    }
    return $stmit->rowCount() ? true : false;
}

// categories exists
function cat_exists($id) {
    global $con;
    $stmit = $con->prepare('SELECT id FROM categories WHERE id = ?');
    $stmit->execute(array($id));
    return $stmit->rowCount() ? true : false;
}

// item exists
function item_exists($id) {
    global $con;
    $stmit = $con->prepare('SELECT id FROM items WHERE id = ?');
    $stmit->execute(array($id));
    return $stmit->rowCount() ? true : false;
}

// delet user
function delet_user($id) {
    global $con;
    $stmit = $con->prepare('DELETE FROM users WHERE id = ?');
    $stmit->execute(array($id));

    return $stmit->rowCount() ? true : false;
}

// remove_dir_user
function remove_dir_user($dir) {
    if (is_dir($dir)) {
        $scan = scandir($dir);
        foreach ($scan as $sc) {
            if ($sc == '.' || $sc == '..') {
                continue;
            }

            $dir_ar = array();
            if (is_dir($dir . '/' . $sc)) {
                $dir_ar[] = $sc;
            }

            if (!empty($dir_ar)) {
                for ($d = 0, $dd = count($dir_ar); $d < $dd; $d++) {
                    $scfile = scandir($dir . '/' . $dir_ar[$d]);
                    $cont_file = array();
                    foreach ($scfile as $file) {
                        if ($file == '.' || $file == '..') {
                            continue;
                        }
                        $cont_file[] = $file;
                    }
                    for ($i = 0, $ii = count($cont_file); $i < $ii; $i++) {
                        unlink($dir . '/' . $dir_ar[$d] . '/' . $cont_file[$i]);
                    }

                    rmdir($dir . '/' . $dir_ar[$d]);
                }
            }
        }

        rmdir($dir);
    }
}

// filter string
function filterstring($st) {
    $string = filter_var($st, FILTER_SANITIZE_STRING);
    return stripslashes($string);
}

// filter int
function filterint($int) {
    $int = filter_var($int, FILTER_SANITIZE_NUMBER_INT);
    return abs($int);
}

// get all categories
function get_cat($noactive = true) {
    $noactive = $noactive ? null : 'WHERE active = 1';
    global $con;
    $stmit = $con->prepare("SELECT * FROM categories $noactive");
    $stmit->execute();
    return $stmit->fetchAll();
}

// test

function pr($array) {
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}
