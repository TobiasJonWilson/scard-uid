# scard-uid
This PHP class is used to generate UIDs for people to allow for de-identified record matching.

The primary change is using **SlugGenerator** (https://github.com/ausi/slug-generator) to normalise characters rather than an internal lookup table that had been used previously. Instead **SlugGenerator** replaces all characters with A-Z equivalents for a consistent result.

**Example:** Identity::getUID('Tobias', 'Wilson, '1980-01-26', 1) will return 'ILOOB260119801'.

For more information about the logic behind the class, please read the https://github.com/TobiasJonWilson/scard-uid/blob/main/Specification_of_the_UID.pdf file.

**Licence:** I wrote this code which means you could have too.
