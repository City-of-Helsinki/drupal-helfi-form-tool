*** Settings ***
Metadata        Lomake reg test set version 0.1
Metadata        Executed At                 ${selected-env}
Library         SeleniumLibrary
Library         OperatingSystem
Library         String
Resource        ../resources/variables/lomake-Variables.robot
Resource        ../resources/variables/common-Variables.robot
Resource        ../resources/keywords/lomake-Keywords.robot
Resource        ../resources/keywords/common-Keywords.robot
Test Timeout    900 seconds
Test Teardown   Run keywords    Capture Page Screenshot     Delete All Cookies  Close All Browsers
Force Tags      smoke

*** Test Cases ***
#########################################################################################
# Mitä testataan?
# 
# 1. Tarkistetaan, että kenttiin saa syöttää sitä mitä pitääkin
#
# Alkuvaatimukset
# - Testikäyttäjä jolla on profiili
#########################################################################################
# robot -d logit --variable environment:dev-chrome --exitonfailure tests/lomake-Check-tjpt-page-functionality.robot

#stjpt = Todistusjäljennöspyyntö tilaus

Login to lomake page using suomi.fi auth
# 
    [Tags]  critical
    Select test data and open browser
    Go To                                                   ${dev_lomake-todistusjaljennospyynto-tilaus-direct_url}
    Log in using suomi.fi authentication - FI               ${testuser1-lomake-hetu}
    Accept all cookies
    Capture Page Screenshot
    [Teardown]    NONE

Verify all buttons, selections and fields
    # Koulun nimi kenttä
    Input Text                                              ${lomake-koulun-nimi-input}                             Koulun nimi tähän
    Capture Page Screenshot
    # Valitse toimitustavaksi postiennakko
    Click Element                                           ${lomake-tjpt-toimitustapa-postiennakko-radiobutton-FI}

    [Teardown]    NONE