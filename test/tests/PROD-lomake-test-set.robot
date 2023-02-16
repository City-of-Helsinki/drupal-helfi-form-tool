*** Settings ***
Metadata        Lomake PROD test set version 1.0
Metadata        Executed At                 ${selected-env}
Library         SeleniumLibrary
Library         OperatingSystem
Library         String
Resource        ../resources/variables/lomake-Variables.robot
Resource        ../resources/variables/common-Variables.robot
Resource        ../resources/keywords/lomake-Keywords.robot
Resource        ../resources/keywords/common-Keywords.robot
Test Timeout    900 seconds
Test Teardown   Run keywords   Capture Page Screenshot     Delete All Cookies  Close All Browsers
Force Tags      prod

*** Test Cases ***
#########################################################################################
# Mitä testataan?
# 
# 1. Tarkistetaan, että linkki hel.fi sivulta lomakkeen sisäänkirjautumis sivulle toimii
# 2. Sisäänkirjautumis nappi vie suomi.fi kirjautumiseen
#
# Alkuvaatimukset
# - Testikäyttäjä jolla on profiili
#########################################################################################
# robot -d logit --variable environment:prod-chrome --exitonfailure tests/PROD-lomake-test-set.robot

In hel.fi page open lomake page
    [Tags]  critical
    Select test data and open browser
    Go To                                                   hel.fi sivun urli tähän  #${dev_lomake-todistusjaljennospyynto-tilaus-direct_url}
    Click Element                                           Lomake linkki tähän
    Wait Until Page Contains Element                        ${lomake-login-button-FI}                                   20
    Click Element                                           ${lomake-login-button-FI}
    Accept all cookies
    Capture Page Screenshot
    [Teardown]    NONE

Click login link
    Wait Until Page Contains Element                        ${Helsinki-Tunnistus-Testi-page-testitunnistaja-button-FI}              20 