*** Settings ***
Metadata        Lomake smoke test set version 1.0
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
Force Tags      smoke

*** Test Cases ***
#########################################################################################
# Mitä testataan?
# 
# 1. Kirjaudutaan suomi.fi kautta sisään
# 2. Hyväksytään cookiet
# 3. Valitaan todistus
# 4. Lisätään koulun nimi
# 5. Valitse toimitustavaksi nouto ja tarkistetaan, että oikea teksi tulee näkyviin
# 6. Valitaan toimitustavaksi postiennakko
# 7. Täytetään postiennakko yhteystiedot
# 8. Hyväksytään rekisteriseloste
# 9. Lähetetään lomake
# 10. Tarkistetaan, että lomake lähetetty sivu aukeaa
# 11. Tarkistetaan, että uloskirjautuminen toimii
#
# Alkuvaatimukset
# - Testikäyttäjä jolla on profiili
#########################################################################################
# robot -d logit --variable environment:stage-chrome --exitonfailure tests/SMOKE-lomake-test-set.robot

Login to lomake page using suomi.fi auth
# 
    [Tags]  critical
    Select test data and open browser
    Wait Until Page Contains Element                        ${lomake-login-button-FI}                                   20
    Click Element                                           ${lomake-login-button-FI}
    Log in using suomi.fi authentication - FI               ${testuser1-lomake-hetu}
    Wait Until Page Contains Element                        ${lomake-front-page-random-element}                         20
    Go To                                                   ${dev_lomake-todistusjaljennospyynto-tilaus-direct_url}
    Accept all cookies
    Capture Page Screenshot
    [Teardown]    NONE

Fill and send form
    # Tarkistetaan, että kaikki todistus valinnat ovat valittavissa
    Valitse tilattavaksi todistukseksi                      Peruskoulun päättötodistus
    Capture Page Screenshot
    # Lisää koulun nimi
    Input Text                                              ${lomake-koulun-nimi-input}                                 SMOKE testi koulun nimi kenttä
    Capture Page Screenshot
    # Valitse toimitustavaksi nouto
    Click Element                                           ${lomake-tjpt-toimitustapa-nouto-radiobutton-FI}
    Wait Until Page Contains                                Noudetaan kasvatuksen ja koulutuksen toimialan arkistolta   20
    Capture Page Screenshot
    # Valitse toimitustavaksi postiennakko
    Click Element                                           ${lomake-tjpt-toimitustapa-postiennakko-radiobutton-FI}
    Genarate test data for postiennakko toimitustapa
    Fill postiennakko information
    # Lisätiedot
    Input Text                                              ${lomake-tjpt-lisätiedot-field}                             SMOKE testi lisätietoja kenttä
    # Rekisteriseloste
    Click Element                                           ${lomake-tjpt-rekisteriseloste-checkbox}
    Capture Page Screenshot
    Click Element                                           ${lomake-tjpt-laheta-lomake-button}
    Wait Until Page Contains                                ${lomake-tjpt-todistus-pyynto-lahetetty-text-FI}            20
    #Kirjaudu ulos
    Get Location
    ${urli} =                                               Get Location
    Log                                                     ${urli}
    Click Element                                           ${lomake-tjpt-sulje-ja-kirjaudu-ulos-button}
    Wait Until Page Contains                                You have been logged out of City of Helsinki services       20
    #[Teardown]    NONE