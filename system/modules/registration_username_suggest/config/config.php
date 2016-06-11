<?php
/**
 * Created by PhpStorm.
 * User: Marko
 * Date: 11.06.2016
 * Time: 11:58
 */

if (TL_MODE == 'FE')
{

    $GLOBALS['TL_HOOKS']['initializeSystem'][] = array('\MCupic\RegistrationUsernameSuggest', 'initializeSystem');

    //$GLOBALS['TL_CSS'][] = 'system/modules/downloads_multifile/assets/ce_downloads_multifile.css';
    $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/registration_username_suggest/assets/js/registration_username_suggest.js';

/**
    $first = "fritz";
    $last = "meier";

    for ($i = 2; $i < 10; $i++)
    {
        $objMember = new \MemberModel();
        $objMember->username = $first . $last . $i;
        $objMember->firstname = $first;
        $objMember->lastname = $last;
        $objMember->email = $first . $last . $i . "@gmx.ch";
        $objMember->save();
    }
    $first = "meier";
    $last = "fritz";

    for ($i = 2; $i < 10; $i++)
    {
        $objMember = new \MemberModel();
        $objMember->username = $first . $last . $i;
        $objMember->firstname = $first;
        $objMember->lastname = $last;
        $objMember->email = $first . $last . $i . "@gmx.ch";
        $objMember->save();
    }
    $first = "f";
    $last = "meier";

    for ($i = 2; $i < 10; $i++)
    {
        $objMember = new \MemberModel();
        $objMember->username = $first . $last . $i;
        $objMember->firstname = $first;
        $objMember->lastname = $last;
        $objMember->email = $first . $last . $i . "@gmx.ch";
        $objMember->save();
    }
**/
}
