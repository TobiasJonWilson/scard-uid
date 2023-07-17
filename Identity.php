<?php

use InvalidArgumentException;
use Ausi\SlugGenerator\SlugGenerator;

/**
 * Class Identity
 *
 * Version: 1.0.2
 * Date:    2023-07-17
 * Author:  Tobias Wilson
 * Repo:    https://github.com/TobiasJonWilson/scard-uid/
 */
 
class Identity {

    /**
     * @param string $strFirstName      - The first name of the person
     * @param string $strLastName       - The last name of the person
     * @param string $strDateOfBirth    - The date of birth of the person in the ISO 8601 format
     * @param int $intSex               - The sex of the person as an int: 1 - Male, 2 - Female, 3 - Undisclosed
     * @return string                   - The UID generated for the person
     */
    public static function getUID(string $strFirstName, string $strLastName, string $strDateOfBirth = '9999-99-99', int $intSex = 9): string {
        $objGenerator = new SlugGenerator;
        $strFirstCode = null;
        $strLastCode = null;
        $strFirst = $objGenerator->generate($strFirstName, ['delimiter' => '', 'validChars' => 'A-Z']);
        $intFirst = strlen($strFirst);
        $arrFirst = str_split($strFirst);
        $strLast = $objGenerator->generate($strLastName, ['delimiter' => '', 'validChars' => 'A-Z']);
        $intLast = strlen($strLast);
        $arrLast = str_split($strLast);
        if (empty($strFirst)) {
            throw new InvalidArgumentException('FirstName is not valid');
        }
        if (empty($strLast)) {
            throw new InvalidArgumentException('LastName is not valid');
        }
        if (!Date::isValid($strDateOfBirth)) {
            throw new InvalidArgumentException('DOB is not valid');
        }
        if (!filter_var($intSex, FILTER_VALIDATE_INT)) {
            throw new InvalidArgumentException('Sex is not valid');
        }
        if ($intFirst > 2) {
            $strFirstCode = $arrFirst[1] . $arrFirst[2];
        } elseif ($intFirst == 2) {
            $strFirstCode = $arrFirst[1] . '2';
        } elseif ($intFirst == 1) {
            $strFirstCode = '22';
        }
        if ($intLast > 4) {
            $strLastCode = $arrLast[1] . $arrLast[2] . $arrLast[4];
        } elseif (($intLast == 4) || ($intLast == 3)) {
            $strLastCode = $arrLast[1] . $arrLast[2] . '2';
        } elseif ($intLast < 3) {
            @$strLastCode = $arrLast[1] . '22';
        } else {
            $strLastCode = '999';
        }
        [$Day, $Month, $Year] = explode('-', $strDateOfBirth);
        $change = [$Year, $Month, $Day];
        $strDateOfBirthCode = implode('', $change);
        if ($intSex == '1') {
            $intSexCode = '1';
        } elseif ($intSex == '2') {
            $intSexCode = '2';
        } else {
            $intSexCode = '9';
        }
        return strtoupper($strLastCode . $strFirstCode . $strDateOfBirthCode . $intSexCode);
    }
}
