<?php

session_start();
session_destroy();
header("Location: /pandabiker/index.html");
exit;

?>