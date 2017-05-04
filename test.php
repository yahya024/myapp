<?php 

echo '<pre>';
//print_r($_FILES);
$ptrn = '/(.+)(?=\.\w+\b)/';
//$mat = preg_match_all($ptrn, 'yahy.jpg', $ar);
print_r(preg_replace($ptrn, 'sssssss', 'yahy.jpg'));
//print_r($ar);
//echo $ar[0][0];
//print_r(exif_imagetype($_FILES['img']['tmp_name']));

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
    <form method="POST" method="POST" enctype="multipart/form-data" action="test.php">
        <input type="file" name="img">
        <input type="submit">
    </form>
</body>
</html>
