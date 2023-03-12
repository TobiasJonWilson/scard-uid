# scard-uid
This PHP class is used to generate UIDs for people to allow for de-identified record matching.

The primary change is using SlugGenerator (https://github.com/ausi/slug-generator) to normalise characters.

SlugGenerator replaces all characters with A-Z equivalents for a consistent result rather than an internal lookup table.

Example: getUID('Tobias', 'Wilson, '1980-01-26', 1) will return 'ILOOB260119801'.

For more information about the logic behind the class, please read the https://github.com/TobiasJonWilson/scard-uid/blob/main/Specification_of_the_UID.pdf file.
