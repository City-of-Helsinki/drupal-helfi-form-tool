*** Variables ***

${environment}                                                  test-firefox    # Tähän tieto missä testit halutaan ajaa 
                                                                                # (esim. local tai test-firefox) jostain syytä
                                                                                # chrome ei toimi kontissa.
                                                                                # Tämän muuttaminen ei vaikuta azuressa ajettaviin
                                                                                # testeihin

${azure-browser-sleep}                                          20

${nordea-default-hetu}                                          210281-9988
${testuser1-lomake-hetu}                                        150552-9979     # Taavetti Järvites

                                                                                # Tarvitsee profiilin
${testuser1-lomake-email-DEV}                                   452444354343545@guerrillamail.com    # Taavetti Järvites
${testuser1-lomake-email-user-DEV}                              452444354343545
${testuser1-lomake-email-domain-DEV}                            @guerrillamail.com

${testuser1-lomake-email-TEST}                                  taavettijarvitesTEST@guerrillamail.com    # Taavetti Järvites
${testuser1-lomake-email-user-TEST}                             taavettijarvitesTEST
${testuser1-lomake-email-domain-TEST}                           @guerrillamail.com

${testuser1-lomake-email-STAGE}                                  taavettijarvitesSTAGE@guerrillamail.com    # Taavetti Järvites
${testuser1-lomake-email-user-STAGE}                             taavettijarvitesSTAGE
${testuser1-lomake-email-domain-STAGE}                           @guerrillamail.com

${testuser2-lomake-hetu}                                        230253-998A     # Pirkkomaija Linderstes

${lomake-admin-VerkkolomakeAdmin-username}                      VerkkolomakeAdmin
${lomake-admin-VerkkolomakeAdmin-password}                      w$g2+zU&KIJwScJU

${lomake-admin-VerkkolomakeHallinnoija-username}                VerkkolomakeHallinnoija
${lomake-admin-VerkkolomakeHallinnoija-password}                w0giZIQ3jujBeIrZ

# Testdata
# Local
#${testdata-local-lomake-tehty-hetulla-testuser1-direct-url}     ?????????????/lomake/HEL-TODISTUS-0000??????

# DEV
#${testdata-dev-lomake-tehty-hetulla-testuser1-direct-url}       https://www.hel.fi/fi/dev-lomakkeet/lomake/HEL-TODISTUS-00000085-DEV
                                                                # https://www.hel.fi/fi/dev-lomakkeet/lomake/HEL-TODISTUS-00000085-DEV?check_logged_in=1
# TEST
#${testdata-test-lomake-tehty-hetulla-testuser1-direct-url}      https://www.hel.fi/fi/test-lomakkeet/lomake/HEL-TODISTUS-00000198-TEST
# STAGE


# Urlit
# Local - Omalla koneella jos haluaa testailla
${local_lomake-todistusjaljennospyynto-tilaus-direct_url}       hel-fi-form-tool.docker.so  # ????

# DEV
${dev_lomake-login_url}                                         https://www.hel.fi/fi/dev-lomakkeet/   #https://lomaketyokalu.dev.hel.ninja/fi
${dev_lomake-direct-logout_url}                                 https://www.hel.fi/fi/dev-lomakkeet/user/logout    #https://lomaketyokalu.dev.hel.ninja/user/logout
${dev_lomake-todistusjaljennospyynto-tilaus-direct_url}         https://www.hel.fi/fi/dev-lomakkeet/todistusjaljennospyynto-tilaus  #https://lomaketyokalu.dev.hel.ninja/fi/form/todistusjaljennospyynto-tilaus
${dev_example-app_url}                                          https://example-ui.dev.hel.ninja/
# TEST
${test_lomake-login_url}                                        https://www.hel.fi/fi/test-lomakkeet
${test_lomake-direct-logout_url}                                https://www.hel.fi/fi/test-lomakkeet/user/logout
${test_lomake-todistusjaljennospyynto-tilaus-direct_url}        https://www.hel.fi/fi/test-lomakkeet/todistusjaljennospyynto-tilaus
${test_example-app_url}                                         https://example-ui.test.hel.ninja/
${test_lomake-admin-login_url}                                  https://www.hel.fi/fi/test-lomakkeet?login=form
${test_lomake-lista-lahetetyista-lomakkeista_url}               https://www.hel.fi/fi/test-lomakkeet/node/1/webform/submissions  # Tämän ei pitäisi näkyä käyttäjälle
${test_lomake-assets_url}                                       https://www.hel.fi/test-lomake-assets   # Ei tietoa mikä tämä on
# STAGE
${stage_lomake-login_url}                                       https://www.hel.fi/fi/staging-lomakkeet
${stage_lomake-direct-logout_url}                               https://www.hel.fi/fi/staging-lomakkeet/user/logout
${stage_lomake-todistusjaljennospyynto-tilaus-direct_url}       https://www.hel.fi/fi/staging-lomakkeet/todistusjaljennospyynto-tilaus
${stage_example-app_url}                                        https://example-ui.stage.hel.ninja/
${stage_lomake-admin-login_url}                                 https://www.hel.fi/fi/stage-lomakkeet?login=form
# PROD
${prod_lomake-login_url}                                        https://www.hel.fi/fi/lomakkeet
${prod_lomake-direct-logout_url}                                https://www.hel.fi/fi/lomakkeet/user/logout
${prod_lomake-todistusjaljennospyynto-tilaus-direct_url}        https://www.hel.fi/fi/lomakkeet/todistusjaljennospyynto-tilaus



# Lomake
${lomake-login-button-FI}                                       id=edit-openid-connect-client-tunnistamo-login
${lomake-front-page-random-element}                             id=edit-todistuksen-antanut-helsinkilainen-koulu    #block-hdbt-subtheme-local-tasks

# Valitse tilattava todistus
## tjpt = Todistusjäljennöspyyntö tilaus
## vtt = Valitse tilattava todistus
${lomake-tjpt-vtt-peruskoulun-päättö-radiobutton-FI}            //label[contains(.,'Peruskoulun päättötodistus')]
${lomake-tjpt-vtt-peruskoulun-erotodistus-radiobutton-FI}       //label[contains(.,'Peruskoulun erotodistus')]
${lomake-tjpt-vtt-lisaopetuksen-10lk-todistus-radiobutton-FI}   //label[contains(.,'Lisäopetuksen 10.lk todistus')]
${lomake-tjpt-vtt-lukion-paattotodistus-radiobutton-FI}         //label[contains(.,'Lukion päättötodistus')]
${lomake-tjpt-vtt-lukion-erotodistus-radiobutton-FI}            //label[contains(.,'Lukion erotodistus')]
# Todistuksen antanut Helsinkiläinen koulu
${lomake-koulun-nimi-input}                                     //input[@data-drupal-selector='edit-todistuksen-antanut-helsinkilainen-koulu']
# Todistuksen antamisvuosi
${lomake-tjpt-vtt-todistuksen-antamisvuosi-input}               //input[@data-drupal-selector='edit-todistuksen-antamisvuosi']
# Toimitustapa
${lomake-tjpt-toimitustapa-postiennakko-radiobutton-FI}         //label[contains(.,'Postiennakko')]
${lomake-tjpt-toimitustapa-nouto-radiobutton-FI}                //label[contains(.,'Nouto')]

${lomake-tjpt-toimitustapa-etunimi-field-FI}                    id=edit-valitse-toimitustapa-cod-first-name
${lomake-tjpt-toimitustapa-sukunimi-field-FI}                   id=edit-valitse-toimitustapa-cod-last-name
${lomake-tjpt-toimitustapa-osoite-field-FI}                     id=edit-valitse-toimitustapa-cod-street-address
${lomake-tjpt-toimitustapa-postinumero-field-FI}                id=edit-valitse-toimitustapa-cod-zip-code
${lomake-tjpt-toimitustapa-kaupunki-field-FI}                   id=edit-valitse-toimitustapa-cod-city
${lomake-tjpt-toimitustapa-puhelinnumero-field-FI}              id=edit-valitse-toimitustapa-cod-phone-number

# Mahdolliset lisätiedot
${lomake-tjpt-lisätiedot-field}                                 id=edit-mahdolliset-lisatiedot

# Rekisteri seloste
${lomake-tjpt-rekisteriseloste-checkbox}                        //input[@data-drupal-selector='edit-olen-tutustunut-rekisteriselosteeseen']    #//input[@data-drupal-selector='edit-privacy-policy-acceptance']

# Lähetä lomake
${lomake-tjpt-laheta-lomake-button}                             id=edit-actions-submit      # Oli edit-submit

#Todistusjäljennöspyyntö lähetetty sivu
${lomake-tjpt-todistus-pyynto-lahetetty-text-FI}                Todistusjäljennöspyyntö lähetetty
${lomake-tjpt-sulje-ja-kirjaudu-ulos-button}                    (//span[contains(@class,'hds-button__label')])[1]
${lomake-tjpt-nayta-lomakkeen-tiedot}                           (//span[contains(@class,'hds-button__label')])[2]

# Olet poistumassa lomakkeelta
${lomake-tjpt-kirjaudu-ulos-button}                             //a[@class='hds-button hds-button--primary']
${lomake-tjpt-jatka-button}                                     //button[@class='hds-button hds-button--secondary dialog__close-button']

# Yläoikean valikko
${lomake-tjpt-upper-right-menu-button}                          //button[@class='nav-toggle__button nav-toggle__button--profile js-profile-button']
${lomake-tjpt-upper-right-menu-logout-button}                   //a[@class='profile__logout-link']

# Direct url
${lomake-direct-url-error-text-FI}                              Sivu näkyy vain kirjautuneille käyttäjille

# Sähköposti
${guerrillamail-url}                                            https://www.guerrillamail.com/inbox
${guerrillamail-user-field-button}                              //span[@id='inbox-id']
${guerrillamail-user-field}                                     //span[@id='inbox-id']//input
${guerrillamail-domain-dropdown}                                //select[@id='gm-host-select']/option[@value='guerrillamail.com']
${guerrillamail-user-set-button}                                //span[@id='inbox-id']/button[.='Set']
${guerrillamail-ekan-viestin-otsikko}                           //tbody[@id='email_list']/tr/td[@class='td3']
${guerrillamail-ekan-viestin-sisalto}                           //div[@class='email']

${guerrillamail-lomake-link}                                    //a[contains(.,'HEL-TODISTUS')]

${guerrillamail-sert-fail-advanced-button}                      id=details-button
${guerrillamail-sert-fail-proceed-link}                         id=proceed-link

# Lomake admin
${lomake-admin-login-page-username-field}                       id=edit-name
${lomake-admin-login-page-password-field}                       id=edit-pass
${lomake-admin-login-page-kirjaudu-button}                      id=edit-submit
${lomake-admin-front-page-asetukset-menu}                       //a[@data-drupal-link-system-path='admin/config']   # Näkyy vain VerkkolomakeAdminille
${lomake-admin-front-page-rakenne-menu}                         //a[@data-drupal-link-system-path='admin/structure'] 







