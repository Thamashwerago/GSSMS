<?php

session_start();

if (isset($_SESSION["userid"])){
    echo true;
} else {
    echo false;
}