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
Force Tags      regression

*** Variables ***

${koulun-nimi}                                              Direct url testi koulu

*** Test Cases ***
#########################################################################################
# Mitä testataan?
# 
# 1. Täytetään käyttäjällä A lomake ja lähetetään se
# 2. Avataan lähetetyn lomakkeen tiedot ja otetaan suora urli talteen
# 3. Koitetaan avata urlin kautta käyttäjän A tekemä lomake käyttäjällä B jonka pitää feilata
#
# Alkuvaatimukset
# - Testikäyttäjällä A ja B on profiili
#########################################################################################
# robot -d logit --variable environment:test-chrome --variable azure-browser-sleep:1 --exitonfailure tests/lomake-direct-lomake-url.robot

Login to lomake page using suomi.fi auth
    [Tags]  critical
    Select test data and open browser
    Wait Until Page Contains Element                        ${lomake-login-button-FI}                                   20
    Accept all cookies
    Click Element                                           ${lomake-login-button-FI}
    Log in using suomi.fi authentication - FI               ${testuser1-lomake-hetu}
    Wait Until Page Contains Element                        ${lomake-front-page-random-element}                         20
    Capture Page Screenshot
    [Teardown]    NONE

Fill lomake and send
    # 
    Valitse tilattavaksi todistukseksi                      Peruskoulun päättötodistus
    Capture Page Screenshot
    # Lisää koulun nimi
    Input Text                                              ${lomake-koulun-nimi-input}                                 ${koulun-nimi}
    Capture Page Screenshot
    # Valitse toimitustavaksi nouto
    Click Element                                           ${lomake-tjpt-toimitustapa-nouto-radiobutton-FI}
    Wait Until Page Contains                                Noudetaan kasvatuksen ja koulutuksen toimialan arkistolta   20
    Capture Page Screenshot
    # Rekisteriseloste
    Click Element                                           ${lomake-tjpt-rekisteriseloste-checkbox}
    Capture Page Screenshot
    Click Element                                           ${lomake-tjpt-laheta-lomake-button}
    Wait Until Page Contains                                ${lomake-tjpt-todistus-pyynto-lahetetty-text-FI}            20
    [Teardown]    NONE

Open and check lomakkeen tiedot page content 
    Click Element                                           ${lomake-tjpt-nayta-lomakkeen-tiedot}
    # Tarkista, että sivu aukeaa ja se sisältää lomakkeelle täytettyä tietoa
    Wait Until Page Contains                                Lomakkeen numero                                            20
    #Page Should Contain                                     ${random-kotiosoite}
    #Page Should Contain                                     ${random-postinumero}
    #Page Should Contain                                     ${kaupunki}
    Page Should Contain                                     ${koulun-nimi}
    [Teardown]    NONE

Urli talteen
    ${suora-urli-temp} =                                    Get Location
    Log                                                     ${suora-urli-temp}
    Set Suite Variable                                      ${suora-urli}           ${suora-urli-temp}

Correct user - Open direct lomake Url
    [Tags]  critical
# Avataan suora urli
    Select test data and open browser
    Wait Until Page Contains Element                        ${lomake-login-button-FI}                                   20
    Accept all cookies
    Go To                                                   ${suora-urli}       #${lomake-direct-url}    #${testdata-dev-lomake-tehty-hetulla-testuser1-direct-url}
    Capture Page Screenshot
# Kirjaudutaan käyttäjällä joka on lomakkeen täyttänyt ja lähettänyt
    Wait Until Page Contains Element                        ${lomake-login-button-FI}                                   20
    Click Element                                           ${lomake-login-button-FI}
    Log in using suomi.fi authentication - FI               ${testuser1-lomake-hetu}
# Tarkista, että lomake sivu aukeaa ja se sisältää lomakkeelle täytettyä tietoa
    #Sleep                                                   5
    Wait Until Page Contains                                Lomakkeen numero                                            20
    Page Should Contain                                     ${koulun-nimi}

Incorrect user - Open direct lomake Url
    [Tags]  critical
    Select test data and open browser
    Wait Until Page Contains Element                        ${lomake-login-button-FI}                                   20
# Avataan suora urli
    Go To                                                   ${suora-urli}       #${lomake-direct-url}        #${testdata-dev-lomake-tehty-hetulla-testuser1-direct-url}
    Accept all cookies
    Capture Page Screenshot
# Kirjaudutaan käyttäjällä joka on eri kuin lomakkeen täyttänyt ja lähettänyt henkilö
    Wait Until Page Contains Element                        ${lomake-login-button-FI}                                   20
    Click Element                                           ${lomake-login-button-FI}
    Log in using suomi.fi authentication - FI               ${nordea-default-hetu}
# Tarkista, että lomake sivu ei aukea ja sivu näyttää virhe ilmoituksen
    Wait Until Page Contains                                ${lomake-direct-url-error-text-FI}                          20