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
Test Teardown   Run keywords  Capture Page Screenshot     Delete All Cookies    Close All Browsers
Force Tags      smoke

*** Variables ***
${koulun-nimi}                                              Mauri Makkaran ala-aste
${lisätiedot}                                               Ei mulla mitään lisätietoja ole
${nouto-viesti}                                             Noudetaan kasvatuksen ja koulutuksen toimialan arkistolta
${random-text-lomakkeen-tiedot-sivulla}                     HEL-TODISTUS-0000

*** Test Cases ***
#########################################################################################
# Mitä testataan?
# 
# 1. Kirjaudutaan suomi.fi kautta sisään lomake todistusjäljennöspyyntö tilaus sivulle
# 2. Hyväksytään cookiet
# 3. Täytetään lomake ja lähetetään se
# 4. Sähköpostista käydään hakemassa linkki
# 5. Avataan sähköpostissa ollut linkki ja tarkistetaan, että oikea lomake näkyy sivulla
#
# Alkuvaatimukset
# - Testikäyttäjä jolla on profiili
#########################################################################################
# robot -d logit --variable environment:test-chrome --variable azure-browser-sleep:1 --exitonfailure tests/lomake-direct-url-and-content-in-mail.robot

#tjpt = Todistusjäljennöspyyntö tilaus

Open mailbox and delete old messages
    Select test data and open browser       # Browser 1
    Kirjaudu guerrillamail.com                              ${testuser1-lomake-email-user}
    Sleep                                                   3
    Poista kaikki viestit avoinna olevasta guerrillamail mailiboxista
    [Teardown]    NONE

Login to lomake page using suomi.fi auth
# 
    [Tags]  critical
    Select test data and open browser       # Browser 2
    Accept all cookies
    Wait Until Page Contains Element                        ${lomake-login-button-FI}                                   20
    Click Element                                           ${lomake-login-button-FI}
    Log in using suomi.fi authentication - FI               ${testuser1-lomake-hetu}
    Wait Until Page Contains Element                        ${lomake-front-page-random-element}                         20
    Go To                                                   ${tjpt-direct_url}
    Capture Page Screenshot
    [Teardown]    NONE

Fill form and send
    # Tarkistetaan, että kaikki todistus valinnat ovat valittavissa
    Valitse tilattavaksi todistukseksi                      Peruskoulun päättötodistus
    Capture Page Screenshot
    # Lisää koulun nimi
    Input Text                                              ${lomake-koulun-nimi-input}                                 ${koulun-nimi}
    Capture Page Screenshot
    # Valitse toimitustavaksi nouto
    Click Element                                           ${lomake-tjpt-toimitustapa-nouto-radiobutton-FI}
    Wait Until Page Contains                                ${nouto-viesti}                                             20
    Capture Page Screenshot
    # Lisätiedot
    Input Text                                              ${lomake-tjpt-lisätiedot-field}                             ${lisätiedot}
    # Rekisteriseloste
    Click Element                                           ${lomake-tjpt-rekisteriseloste-checkbox}
    Capture Page Screenshot
    Click Element                                           ${lomake-tjpt-laheta-lomake-button}
    Wait Until Page Contains                                ${lomake-tjpt-todistus-pyynto-lahetetty-text-FI}            20
    Capture Page Screenshot
    Sleep       5
    #Kirjaudu ulos
    ${urli} =                                               Get Location
    Log                                                     ${urli}
    #Click Element                                           ${lomake-tjpt-sulje-ja-kirjaudu-ulos-button}
    #Wait Until Page Contains                                You have been logged out of City of Helsinki services       20
    [Teardown]    NONE

Open mailbox, check content and get direct lomake url
    Switch Browser                                          1   #Browser 1
    Kirjaudu guerrillamail.com                              ${testuser1-lomake-email-user}
    #Sleep                                                   3
    #Poista kaikki viestit avoinna olevasta guerrillamail mailiboxista
    Odota emailin form submission viestiä
    Click Element                                           ${guerrillamail-ekan-viestin-otsikko}
    Wait Until Page Contains Element                        ${guerrillamail-ekan-viestin-sisalto}
    Wait Until Page Contains Element                        ${guerrillamail-lomake-link}                                20
    ${url}=  Get Element Attribute                          ${guerrillamail-lomake-link}                                href
    Log                                                     ${url}
    Switch Browser                                          2   #Browser 2
    Go To                                                   ${url}
    ## Tarkista, että lomake sivu aukeaa ja se sisältää lomakkeelle täytettyä tietoa
    Wait Until Page Contains                                ${random-text-lomakkeen-tiedot-sivulla}                     20
    Page Should Contain                                     ${koulun-nimi}
    Page Should Contain                                     ${lisätiedot}