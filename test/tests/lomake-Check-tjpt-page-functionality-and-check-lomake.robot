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

*** Variables ***

${lomake-testdata-koulunnimi}                               Mauri Makkaran ala-aste
${lomake-testdata-lisatiedot}                               Ei mulla mitään lisätietoja ole


*** Test Cases ***
#########################################################################################
# Mitä testataan?
# 
# 1. Kirjaudutaan suomi.fi kautta sisään
# 2. Hyväksytään cookiet
# 3. Tarkistetaan, että kaikki todistus valinnat ovat valittavissa
# 4. Lisätään koulun nimi
# 5. Valitse toimitustavaksi nouto ja tarkistetaan, että oikea teksi tulee näkyviin
# 6. Valitaan toimitustavaksi postiennakko
# 7. Täytetään postiennakko yhteystiedot
# 8. Hyväksytään rekisteriseloste
# 9. Avataan tarkista lähetetty lomake sivu ja tarkistetaan, että sivulta löytyy oikea lisätty tieto
# 10. Kirjaudutaan ulos ylävalikon kautta
#
# Alkuvaatimukset
# - Testikäyttäjä jolla on profiili
#########################################################################################
# robot -d logit --variable environment:test-chrome --variable azure-browser-sleep:1 --exitonfailure tests/lomake-Check-tjpt-page-functionality-and-check-lomake.robot

#stjpt = Todistusjäljennöspyyntö tilaus

Login to lomake page using suomi.fi auth
    [Tags]  critical
    Select test data and open browser
    Wait Until Page Contains Element                        ${lomake-login-button-FI}                                   20
    Accept all cookies
    Click Element                                           ${lomake-login-button-FI}
    Log in using suomi.fi authentication - FI               ${testuser1-lomake-hetu}
    Wait Until Page Contains Element                        ${lomake-front-page-random-element}                         20
    #Go To                                                   ${dev_lomake-todistusjaljennospyynto-tilaus-direct_url}
    Capture Page Screenshot
    [Teardown]    NONE

Verify all buttons, selections and fields
    # Tarkistetaan, että kaikki todistus valinnat ovat valittavissa
    Valitse tilattavaksi todistukseksi                      Peruskoulun päättötodistus
    Capture Page Screenshot
    Valitse tilattavaksi todistukseksi                      Peruskoulun erotodistus
    Capture Page Screenshot
    Valitse tilattavaksi todistukseksi                      Lisäopetuksen 10.lk todistus
    Capture Page Screenshot
    Valitse tilattavaksi todistukseksi                      Lukion päättötodistus
    Capture Page Screenshot
    Valitse tilattavaksi todistukseksi                      Lukion erotodistus
    Capture Page Screenshot
    # Lisää koulun nimi
    Input Text                                              ${lomake-koulun-nimi-input}                                 ${lomake-testdata-koulunnimi}
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
    Input Text                                              ${lomake-tjpt-lisätiedot-field}                             ${lomake-testdata-lisatiedot}
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
    #Page Should Contain                                     ${etunimi}
    #Page Should Contain                                     ${sukunimi}
    Page Should Contain                                     ${random-kotiosoite}
    Page Should Contain                                     ${random-postinumero}
    Page Should Contain                                     ${kaupunki}
    #Page Should Contain                                     ${random-puhnum}
    Page Should Contain                                     ${lomake-testdata-koulunnimi}
    Page Should Contain                                     ${lomake-testdata-lisatiedot}
    [Teardown]    NONE

Logout using upper right menu
    Wait Until Page Contains Element                        ${lomake-tjpt-upper-right-menu-button}       
    Click Element                                           ${lomake-tjpt-upper-right-menu-button}
    Wait Until Element Is Visible                           ${lomake-tjpt-upper-right-menu-logout-button}               20
    #Wait Until Page Contains Element                        ${lomake-tjpt-upper-right-menu-logout-button}
    Click Element                                           ${lomake-tjpt-upper-right-menu-logout-button}       
    Wait Until Page Contains                                You have been logged out of City of Helsinki services       20