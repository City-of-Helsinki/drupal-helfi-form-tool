*** Keywords ***

Select test data and open browser
    Select test data depending on the selected env  ${environment}
    Sleep                                           10
    Select url depending on the selected env        ${environment}
    Sleep                                           ${azure-browser-sleep}
    Browser specific sleep                          ${environment}    
    Log                                             ${environment}
    Capture Page Screenshot
    set window size    1920  1024
    ${width}     ${height}=     get window size
    log     ${width}
    log    ${height}
    Capture Page Screenshot

Browser specific sleep
    [Arguments]    ${selected-env}
    ## firefox azuressa vaatii jostain syystä sleepin ekan kerran selaimen avaamisen jälkeen
    ${TYPE}=    Set Variable    ${selected-env}
    Run Keyword If              '${TYPE}' == 'dev-firefox'              Sleep           30
    ...     ELSE IF             '${TYPE}' == 'test-firefox'             Sleep           30
    ...     ELSE IF             '${TYPE}' == 'stage-firefox'            Sleep           30
    ...     ELSE                                                        Sleep           1

Select url depending on the selected env
    [Arguments]    ${selected-env}
    # timeout browser opening if no browser available from selenium in 1 minute
    [Timeout]      1 minute

    ${TYPE}=    Set Variable    ${selected-env}
    Run Keyword If              '${TYPE}' == 'dev-firefox'              Open Browser    ${lomake_url}   firefox
    ...     ELSE IF             '${TYPE}' == 'dev-chrome'               Open Browser    ${lomake_url}   gc
    ...     ELSE IF             '${TYPE}' == 'dev-edge'                 Open Browser    ${lomake_url}   edge
    ...     ELSE IF             '${TYPE}' == 'test-firefox'             Open Browser    ${lomake_url}   firefox
    ...     ELSE IF             '${TYPE}' == 'test-chrome'              Open Browser    ${lomake_url}   gc
    ...     ELSE IF             '${TYPE}' == 'test-edge'                Open Browser    ${lomake_url}   edge
    ...     ELSE IF             '${TYPE}' == 'stage-firefox'            Open Browser    ${lomake_url}   firefox
    ...     ELSE IF             '${TYPE}' == 'stage-chrome'             Open Browser    ${lomake_url}   gc
    ...     ELSE IF             '${TYPE}' == 'stage-edge'               Open Browser    ${lomake_url}   edge
    ...     ELSE IF             '${TYPE}' == 'local'                    Open Browser    ${lomake_url}   edge
    ...     ELSE                '${TYPE}' == 'null'                     Open Browser    ${lomake_url}   gc      #temp
    #Maximize Browser Window
    #Set Window Size             1920    1024

Select test data depending on the selected env
    [Arguments]                 ${selected-env}

    ${TYPE}=    Set Variable    ${selected-env}
    Run Keyword If              '${TYPE}' == 'dev-firefox'              Select dev test data and urls
    ...     ELSE IF             '${TYPE}' == 'dev-chrome'               Select dev test data and urls
    ...     ELSE IF             '${TYPE}' == 'dev-edge'                 Select dev test data and urls
    ...     ELSE IF             '${TYPE}' == 'test-firefox'             Select test env test data and urls
    ...     ELSE IF             '${TYPE}' == 'test-chrome'              Select test env test data and urls
    ...     ELSE IF             '${TYPE}' == 'test-edge'                Select test env test data and urls
    ...     ELSE IF             '${TYPE}' == 'stage-firefox'            Select stage env test data and urls
    ...     ELSE IF             '${TYPE}' == 'stage-chrome'             Select stage env test data and urls
    ...     ELSE IF             '${TYPE}' == 'stage-edge'               Select stage env test data and urls
    ...     ELSE IF             '${TYPE}' == 'local'                    Select local env test data and urls
    ...     ELSE                '${TYPE}' == '7'                        temp

Select local test data and urls
    # Env
    Set Suite Variable          ${lomake-selected-env}                  local-
    
    # Url
    Set Suite Variable          ${lomake_url}                           ${local_lomake_url}

Select dev test data and urls
    # Env
    Set Suite Variable          ${lomake-selected-env}                  dev-
    
    # Url
    Set Suite Variable          ${lomake_url}                           ${dev_lomake-todistusjaljennospyynto-tilaus-direct_url}
    Set Suite Variable          ${example-app_url}                      ${dev_example-app_url}
    Set Suite Variable          ${tjpt-direct_url}                      ${dev_lomake-todistusjaljennospyynto-tilaus-direct_url}

    # Testdata
    #Set Suite Variable          ${lomake-direct-url}                    ${testdata-dev-lomake-tehty-hetulla-testuser1-direct-url}

    # Users
    Set Suite Variable          ${testuser1-lomake-email}               ${testuser1-lomake-email-DEV}        
    Set Suite Variable          ${testuser1-lomake-email-user}          ${testuser1-lomake-email-user-DEV}   
    Set Suite Variable          ${testuser1-lomake-email-domain}        ${testuser1-lomake-email-domain-DEV} 


Select test env test data and urls
    # Env
    Set Suite Variable          ${lomake-selected-env}                  test-

    # Url
    Set Suite Variable          ${lomake_url}                           ${test_lomake-todistusjaljennospyynto-tilaus-direct_url}
    Set Suite Variable          ${example-app_url}                      ${test_example-app_url}
    Set Suite Variable          ${tjpt-direct_url}                      ${test_lomake-todistusjaljennospyynto-tilaus-direct_url}
    Set Suite Variable          ${lomake-admin-login_url}               ${test_lomake-admin-login_url}

    # Testdata
    #Set Suite Variable          ${lomake-direct-url}                    ${testdata-test-lomake-tehty-hetulla-testuser1-direct-url}

    # Users
    Set Suite Variable          ${testuser1-lomake-email}               ${testuser1-lomake-email-TEST}        
    Set Suite Variable          ${testuser1-lomake-email-user}          ${testuser1-lomake-email-user-TEST}   
    Set Suite Variable          ${testuser1-lomake-email-domain}        ${testuser1-lomake-email-domain-TEST} 

Select stage env test data and urls
    # Env
    Set Suite Variable          ${lomake-selected-env}                  stage-
    
    # Url
    Set Suite Variable          ${lomake_url}                           ${stage_lomake-todistusjaljennospyynto-tilaus-direct_url}
    Set Suite Variable          ${example-app_url}                      ${stage_example-app_url}
    Set Suite Variable          ${tjpt-direct_url}                      ${stage_lomake-todistusjaljennospyynto-tilaus-direct_url}
    Set Suite Variable          ${lomake-admin-login_url}               ${stage_lomake-admin-login_url}

    # Testdata

    # Users

Select prod test data and urls
    # Env
    Set Suite Variable          ${lomake-selected-env}                            prod-



Change language to FI
    Wait Until Page Contains Element    ${Your-profile-login-page-language-dropdown-button}                     20
    ${passed} =                         Run Keyword And Return Status           Page Should Contain             ${Your-profile-login-page-text1-EN}
    Run Keyword If                      ${passed}                               Change language to FI part 2

Change language to FI part 2
    Click Element                       ${Your-profile-login-page-language-dropdown-button}
    Click Element                       ${Your-profile-login-page-language-dropdown-FI}
    Page Should Not Contain             ${Your-profile-login-page-text1-EN}

Valitse tilattavaksi todistukseksi
    [Arguments]                         ${todistus}

    ${TYPE}=    Set Variable            ${todistus}
    Run Keyword If              '${TYPE}' == 'Peruskoulun päättötodistus'       Click Element    ${lomake-tjpt-vtt-peruskoulun-päättö-radiobutton-FI}
    ...     ELSE IF             '${TYPE}' == 'Peruskoulun erotodistus'          Click Element    ${lomake-tjpt-vtt-peruskoulun-erotodistus-radiobutton-FI}
    ...     ELSE IF             '${TYPE}' == 'Lisäopetuksen 10.lk todistus'     Click Element    ${lomake-tjpt-vtt-lisaopetuksen-10lk-todistus-radiobutton-FI}
    ...     ELSE IF             '${TYPE}' == 'Lukion päättötodistus'            Click Element    ${lomake-tjpt-vtt-lukion-paattotodistus-radiobutton-FI}
    ...     ELSE IF             '${TYPE}' == 'Lukion erotodistus'               Click Element    ${lomake-tjpt-vtt-lukion-erotodistus-radiobutton-FI}

Genarate test data for postiennakko toimitustapa
# Etunimi
    Set Suite Variable                  $etunimi   Late
# Sukunimi
    Set Suite Variable                  $sukunimi   Lomake
# Katuosoite
    ${random-kotiosoite-temp} =         Generate Random String  8  [LOWER]
    Set Suite Variable                  $random-kotiosoite   Osoite ${random-kotiosoite-temp} 1a
# Postinumero
    ${random-postinumero-temp} =        Generate Random String  5  [NUMBERS]
    Set Suite Variable                  $random-postinumero   ${random-postinumero-temp}
# Kaupunki
    Set Suite Variable                  $kaupunki   Lande
# Puhelinnumero
    ${random-puhnum-temp} =             Generate Random String  5  [NUMBERS]
    Set Suite Variable                  $random-puhnum   0666${random-puhnum-temp}

Fill postiennakko information
    Input Text                          ${lomake-tjpt-toimitustapa-etunimi-field-FI}            ${etunimi}
    Input Text                          ${lomake-tjpt-toimitustapa-sukunimi-field-FI}           ${sukunimi}
    Input Text                          ${lomake-tjpt-toimitustapa-osoite-field-FI}             ${random-kotiosoite}
    Input Text                          ${lomake-tjpt-toimitustapa-postinumero-field-FI}        ${random-postinumero}
    Input Text                          ${lomake-tjpt-toimitustapa-kaupunki-field-FI}           ${kaupunki}
    Input Text                          ${lomake-tjpt-toimitustapa-puhelinnumero-field-FI}      ${random-puhnum}

#######################################################
# Sähköposti
Kirjaudu guerrillamail.com
    [Arguments]                                         ${mail-user}     #Anna tähän pelkkä user eli gjroiejgre osoitteesta gjroiejgre@guerrillamail.com
    Go To                                               ${guerrillamail-url}
    Tarkista onko sertti taas kerran vanhentunut
    Wait Until Page Contains Element                    ${guerrillamail-user-field-button}
    Click Element                                       ${guerrillamail-user-field-button}
    Input Text                                          ${guerrillamail-user-field}             ${mail-user}
    Click Element                                       ${guerrillamail-domain-dropdown}
    Click Element                                       ${guerrillamail-user-set-button}

Tarkista onko sertti taas kerran vanhentunut
    Sleep                                               5
    ${passed} =                         Run Keyword And Return Status           Page Should Contain Element            ${guerrillamail-sert-fail-advanced-button}
    Run Keyword If                      ${passed}                               Tarkista onko sertti taas kerran vanhentunut - proceed

Tarkista onko sertti taas kerran vanhentunut - proceed
    Wait Until Page Contains Element                    ${guerrillamail-sert-fail-advanced-button}      20
    Click Element                                       ${guerrillamail-sert-fail-advanced-button}
    Wait Until Page Contains Element                    ${guerrillamail-sert-fail-proceed-link}         20
    Click Element                                       ${guerrillamail-sert-fail-proceed-link}

Poista kaikki viestit avoinna olevasta guerrillamail mailiboxista
    ${message-count-temp} =      Get Element Count      //tbody[@id='email_list']/tr//input[@name='mid[]']
    Set Suite Variable      ${message-count}            ${message-count-temp}
    Run Keyword If          ${message-count} == 0       Set Suite Variable     ${empty-list}   true
    ...     ELSE IF         ${message-count} != 0       Delete message loop
    ...     ELSE            ${message-count} != 0       Delete message loop

Delete message loop
    FOR    ${thearvo}      IN RANGE                     ${message-count}
        Click Element                                   //tbody[@id='email_list']/tr//input[@name='mid[]']
        Click Element                                   //input[@id='del_button']
        Sleep                                           3
    END

Odota emailin form submission viestiä
# Kun luodaan profiilia
    Wait Until Keyword Succeeds                         2 min   5 sec     Lataa sivu uudestaan ja tarkista onko vahvistus viesti tullut

Lataa sivu uudestaan ja tarkista onko vahvistus viesti tullut
    Reload Page
    Wait Until Page Contains                            Lomakkeesi on katseltavissa    #New submission    #Vahvista sähköposti

Odota emailin päivitä tietosi viestiä
# Kun sähköpostiosoite vaihdetaan profiilista
    Wait Until Keyword Succeeds                         2 min   5 sec     Lataa sivu uudestaan ja tarkista onko päivitä tietosi viesti tullut

Lataa sivu uudestaan ja tarkista onko päivitä tietosi viesti tullut
    Reload Page
    Wait Until Page Contains                            ${Profiili-UI-omat-tiedot-sahkoposti-paivita-tietosi-text1-FI}

#######################################################
# Admin

Login as VerkkolomakeAdmin
    Wait Until Page Contains Element                    ${lomake-admin-login-page-username-field}       20
    Input Text                                          ${lomake-admin-login-page-username-field}       ${lomake-admin-VerkkolomakeAdmin-username}
    Input Text                                          ${lomake-admin-login-page-password-field}       ${lomake-admin-VerkkolomakeAdmin-password}
    Click Element                                       ${lomake-admin-login-page-kirjaudu-button}
    Wait Until Page Contains Element                    ${lomake-admin-front-page-asetukset-menu}

Login as VerkkolomakeHallinnoija
    Wait Until Page Contains Element                    ${lomake-admin-login-page-username-field}       20
    Input Text                                          ${lomake-admin-login-page-username-field}       ${lomake-admin-VerkkolomakeHallinnoija-username}
    Input Text                                          ${lomake-admin-login-page-password-field}       ${lomake-admin-VerkkolomakeHallinnoija-password}
    Click Element                                       ${lomake-admin-login-page-kirjaudu-button}
    Wait Until Page Contains Element                    ${lomake-admin-front-page-rakenne-menu}