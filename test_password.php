<?php

$password = "admin123";

$hash = "$2y$10$/X4GAiQXFNHM5C3fL5W2k.4oA0SyOfveZs7v2QF7xRhF0ew6Wewx6";

if (password_verify($password, $hash)) {

    echo "PASSWORD BENAR";

} else {

    echo "PASSWORD SALAH";

}