<?php
$content = file_get_contents('c:/xampp/htdocs/webtiengtrung/resources/views/admin/nguoidung/index.blade.php');
$fixed = mb_convert_encoding($content, 'Windows-1252', 'UTF-8');
file_put_contents('c:/xampp/htdocs/webtiengtrung/resources/views/admin/nguoidung/index.blade.php', $fixed);
echo "Fixed encoding.";
?>
