<?php

use Ausi\SlugGenerator\SlugGenerator;

/**
 * Class Identity
 *
 * Version: 1.0.1
 * Date:    2023-03-13
 * Author:  Tobias Wilson
 *
 * This class generates the Unique Identifier (UID) for a person for de-identified matching.
 *
 * The primary change is using SlugGenerator (https://github.com/ausi/slug-generator) to normalise characters.
 * SlugGenerator replaces all characters with A-Z equivalents for a consistent result rather than an internal lookup table.
 *
 * @example getUID('Tobias', 'Wilson, '1980-01-26', 1) will return 'ILOOB260119801'.
 *
 */
 
class Identity {

    /**
     * @param string $strFirstName      - The first name of the person
     * @param string $strLastName       - The last name of the person
     * @param string $strDateOfBirth    - The date-of-birth of the person in the ISO 8601 format
     * @param int $intSex               - The sex of the person as an int: 1 - Male, 2 - Female, 3 - Intersex
     * @return string                   - The UID generated for the person
     */
    public static function getUID(string $strFirstName, string $strLastName, string $strDateOfBirth = '9999-99-99', int $intSex = 9): string {
        $objGenerator = new SlugGenerator;
        $strFirstCode = null;
        $strLastCode = null;
        $strFirst1 = $objGenerator->generate($strFirstName, ['delimiter' => '', 'validChars' => 'A-Z']);
        $strFirst2 = strlen($strFirst1);
        $strFirstArray = str_split($strFirst1);
        $strLast1 = $objGenerator->generate($strLastName, ['delimiter' => '', 'validChars' => 'A-Z']);
        $strLast2 = strlen($strLast1);
        $strLastArray = str_split($strLast1);
        if (empty($strFirst1)) {
            throw new InvalidArgumentException('FirstName is not valid');
        }
        if (empty($strLast1)) {
            throw new InvalidArgumentException('LastName is not valid');
        }
        if (!Date::isValid($strDateOfBirth)) {
            throw new InvalidArgumentException('DOB is not valid');
        }
        if (!filter_var($intSex, FILTER_VALIDATE_INT)) {
            throw new InvalidArgumentException('Sex is not valid');
        }
        if ($strFirst2 > 2) {
            $strFirstCode = $strFirstArray[1] . $strFirstArray[2];
        } elseif ($strFirst2 == 2) {
            $strFirstCode = $strFirstArray[1] . '2';
        } elseif ($strFirst2 == 1) {
            $strFirstCode = '22';
        }
        if ($strLast2 > 4) {
            $strLastCode = $strLastArray[1] . $strLastArray[2] . $strLastArray[4];
        } elseif (($strLast2 == 4) || ($strLast2 == 3)) {
            $strLastCode = $strLastArray[1] . $strLastArray[2] . '2';
        } elseif ($strLast2 < 3) {
            @$strLastCode = $strLastArray[1] . '22';
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
        return ($strLastCode . $strFirstCode . $strDateOfBirthCode . $intSexCode);
    }
}
