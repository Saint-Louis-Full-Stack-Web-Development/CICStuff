<?php

function calculateSign($month, $day)
{

switch ($month)
{
    case 1:
        if ($day <= 20)
        {
            $signid = 1;
        }
        else
        {
            $signid = 2;
        }
        break;

    case 2:
        if ($day <= 19)
        {
            $signid = 2;
        }
        else
        {
            $signid = 3;
        }
        break;

    case 3:
        $signid = ($day <= 20 ? 3 : 4);
        break;

    case 4:
        $signid = ($day <= 20 ? 4 : 5);
        break;

    case 5:
        $signid = ($day <= 21 ? 5 : 6);
        break;

    case 6:
        $signid = ($day <= 21 ? 6 : 7);
        break;

    case 7:
        $signid = ($day <= 22 ? 7 : 8);
        break;

    case 8:
        $signid = ($day <= 21 ? 8 : 9);
        break;

    case 9:
        $signid = ($day <= 23 ? 9 : 10);
        break;

    case 10:
        $signid = ($day <= 23 ? 10 : 11);
        break;

    case 11:
        $signid = ($day <= 22 ? 11 : 12);
        break;

    case 12:
        $signid = ($day <= 22 ? 12 : 1);
        break;

} // END: switch($month) 

echo $signid ;

} // END: function calculateSunSign()

?>