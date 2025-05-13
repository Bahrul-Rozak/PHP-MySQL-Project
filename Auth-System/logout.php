<?php
session_start();        // Mulai session
session_unset();        // Hapus semua data session
session_destroy();      // Hancurkan session-nya
header("Location: login.php"); // Redirect ke halaman login
exit();
