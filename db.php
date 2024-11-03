<?php

function get_connect()
{
    return new PDO("sqlite:database.sqlite");
}
