<?php
/**
 * Created by PhpStorm.
 * User: Marko
 * Date: 05.06.2016
 * Time: 16:31
 */

namespace MCupic;


class RegistrationUsernameSuggest
{

    protected $strTable = 'tl_member';

    /**
     * file ids
     * @var array
     */
    protected $arrIDS = array();

    /**
     * controiller
     */
    public function initializeSystem()
    {
        if (\Input::get('isAjax') == 'true' && \Input::get('registration_username_suggest') == 'true')
        {
            if (trim(\Input::get('username')) != '')
            {
                $this->checkUsername(\Input::get('username'), \Input::get('firstname'), \Input::get('lastname'));
            }
        }
    }

    /**
     * @param $username
     * @param $firstname
     * @param $lastname
     */
    protected function checkUsername($username, $firstname = '', $lastname = '')
    {
        \Controller::loadLanguageFile('default');

        $json = array();
        $arrValidUsernames = array();
        $firstname = strtolower($firstname);
        $lastname = strtolower($lastname);

        $db = \Database::getInstance();
        $objUser = $db->prepare("SELECT * FROM " . $this->strTable . " WHERE username=?")->execute($username);
        if (!$objUser->numRows)
        {
            // All ok, username is valid and unused
            $json['status'] = 'valid';
            $json['usernames'] = array($username);
            $json['messageLine1'] = $GLOBALS['TL_LANG']['FMD']['registrationUsernameSuggest']['msg0'];
            $json['messageLine2'] = '';
        }
        else
        {
            if (trim($firstname) == '' && trim($lastname) == '')
            {
                $json['status'] = 'invalid';
                $json['usernames'] = array();
                $json['messageLine1'] = $GLOBALS['TL_LANG']['FMD']['registrationUsernameSuggest']['msg1'];
                $json['messageLine2'] = '';

            }
            else
            {
                // Generate username
                $arrTest = array();

                // Try elvispresley*
                $test1 = $firstname . $lastname;
                $objUser = $db->prepare("SELECT * FROM " . $this->strTable . " WHERE username LIKE ?")->execute($test1);
                $arr1 = $objUser->fetchEach('username');
                $arrTest = array_merge($arrTest, $arr1);


                // Try presleyelvis*
                $test2 = $lastname . $firstname;
                $objUser = $db->prepare("SELECT * FROM " . $this->strTable . " WHERE username LIKE ?")->execute($test2);
                $arr2 = $objUser->fetchEach('username');
                $arrTest = array_merge($arrTest, $arr2);


                // Try epresley*
                if ($firstname != '')
                {
                    $test3 = substr($firstname, 0, 1) . $lastname;
                    $objUser = $db->prepare("SELECT * FROM " . $this->strTable . " WHERE username LIKE ?")->execute($test3);
                    $arr3 = $objUser->fetchEach('username');
                    $arrTest = array_merge($arrTest, $arr3);
                }


                if (!in_array($test1, $arrTest))
                {
                    $arrValidUsernames[] = $test1;
                }
                else
                {
                    $arrValidUsernames[] = $this->getValidUsername($test1, $arrTest);
                }


                if (!in_array($test2, $arrTest))
                {
                    $arrValidUsernames[] = $test2;
                }
                else
                {
                    $arrValidUsernames[] = $this->getValidUsername($test2, $arrTest);
                }


                if ($firstname != '')
                {
                    if (!in_array($test3, $arrTest))
                    {
                        $arrValidUsernames[] = $test3;
                    }
                    else
                    {
                        $arrValidUsernames[] = $this->getValidUsername($test3, $arrTest);
                    }
                }

                $arrValidUsernames = array_values(array_unique($arrValidUsernames));

                // Send at least 3 usernames
                while (count($arrValidUsernames) < 4)
                {
                    $arrValidUsernames[] = $this->getValidUsername($username, $arrValidUsernames);
                }
                $arrValidUsernames = array_values(array_unique($arrValidUsernames));

                $json['status'] = 'invalid';
                $json['usernames'] = $arrValidUsernames;
                $json['messageLine1'] = $GLOBALS['TL_LANG']['FMD']['registrationUsernameSuggest']['msg1'];
                $json['messageLine2'] = $GLOBALS['TL_LANG']['FMD']['registrationUsernameSuggest']['msg2'];
            }
        }

        echo json_encode($json);
        exit();
    }

    /**
     * @param $strTest
     * @param $arrUsernames
     * @return string
     */
    protected function getValidUsername($strTest, $arrUsernames)
    {
        for ($i = 1; $i < 100000000; $i++)
        {
            $test = $strTest . $i;
            if (!in_array($test, $arrUsernames))
            {
                return $test;
            }
        }
        return '';
    }

}