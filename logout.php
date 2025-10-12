<?php

//aktifkan session
session_start();

//hapus session
session_destroy();

//alihkan halaman ke halaman login
header('Location: index.php?msg=logout');