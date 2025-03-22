<?php
session_start();
session_destroy();
header("Location: moonlight.html");
exit();
?>