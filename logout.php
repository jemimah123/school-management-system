<?php

session_start();
unset($_SESSION['admin_id']);
unset($_SESSION['admin_email']);
session_destroy();
header('Location: index');
exit;
