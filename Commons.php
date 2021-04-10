<?php

class Commons {
    //put your code here
    function redirect($path)
    {
        header("Location:".$path."");
    }
}
