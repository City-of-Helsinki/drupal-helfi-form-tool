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
Test Teardown   Run keywords   Capture Page Screenshot     Delete All Cookies    Close All Browsers
Force Tags      smoke

*** Test Cases ***
#########################################################################################
# Mitä testataan?
# 
# 1. Koitetaan avata urlin kautta jonkun toisen käytäjän tekemää lomaketta
#
# Alkuvaatimukset
# - Lomake joka tehty testikäyttäjällä 1
# - Testikäyttäjä 2 jolla on profiili
#########################################################################################
# robot -d logit --variable environment:test-chrome --variable azure-browser-sleep:1 --exitonfailure tests/lomake-direct-lomake-url.robot

Correct user - Open direct lomake Url
    [Tags]  critical
# Avataan suora urli
    Select test data and open browser
    Wait Until Page Contains Element                        ${lomake-login-button-FI}                                   20
    Accept all cookies
    Go To                                                   ${lomake-direct-url}    #${testdata-dev-lomake-tehty-hetulla-testuser1-direct-url}
    Capture Page Screenshot
# Kirjaudutaan käyttäjällä joka on lomakkeen täyttänyt ja lähettänyt
    Wait Until Page Contains Element                        ${lomake-login-button-FI}                                   20
    Click Element                                           ${lomake-login-button-FI}
    Log in using suomi.fi authentication - FI               ${testuser1-lomake-hetu}
# Tarkista, että lomake sivu aukeaa ja se sisältää lomakkeelle täytettyä tietoa
    Wait Until Page Contains                                HEL-TODISTUS-0000       20    #Lomakkeen numero                    20
    Page Should Contain                                     Varmistettu etunimi
    Page Should Contain                                     Varmistettu henkilötunnus

Incorrect user - Open direct lomake Url
    [Tags]  critical
    Select test data and open browser
    Wait Until Page Contains Element                        ${lomake-login-button-FI}                                   20
# Avataan suora urli
    Go To                                                   ${lomake-direct-url}        #${testdata-dev-lomake-tehty-hetulla-testuser1-direct-url}
    Accept all cookies
    Capture Page Screenshot
# Kirjaudutaan käyttäjällä joka on eri kuin lomakkeen täyttänyt ja lähettänyt henkilö
    Wait Until Page Contains Element                        ${lomake-login-button-FI}                                   20
    Click Element                                           ${lomake-login-button-FI}
    Log in using suomi.fi authentication - FI               ${nordea-default-hetu}
# Tarkista, että lomake sivu ei aukea ja sivu näyttää virhe ilmoituksen
    Wait Until Page Contains                                ${lomake-direct-url-error-text-FI}                          20