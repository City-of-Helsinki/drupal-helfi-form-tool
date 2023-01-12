*** Variables ***

${environment}      null

${nordea-default-hetu}                                          210281-9988
${testuser1-lomake-hetu}                                        150552-9979     # Taavetti Järvites
                                                                                # Tarvitsee profiilin
${testdata-dev-lomake-tehty-hetulla-testuser1-direct-url}       https://www.hel.fi/fi/dev-lomakkeet/lomake/HEL-TODISTUS-00000085-DEV
                                                                # https://www.hel.fi/fi/dev-lomakkeet/lomake/HEL-TODISTUS-00000085-DEV?check_logged_in=1

# Urlit
# DEV
${dev_lomake-login_url}                                         https://www.hel.fi/fi/dev-lomakkeet/   #https://lomaketyokalu.dev.hel.ninja/fi
${dev_lomake-direct-logout_url}                                 https://www.hel.fi/fi/dev-lomakkeet/user/logout    #https://lomaketyokalu.dev.hel.ninja/user/logout
${dev_lomake-todistusjaljennospyynto-tilaus-direct_url}         https://www.hel.fi/fi/dev-lomakkeet/todistusjaljennospyynto-tilaus  #https://lomaketyokalu.dev.hel.ninja/fi/form/todistusjaljennospyynto-tilaus
${dev_example-app_url}                                          https://example-ui.dev.hel.ninja/

# Lomake
${lomake-login-button-FI}                                       id=edit-openid-connect-client-tunnistamo-login
${lomake-front-page-random-element}                             id=block-hdbt-subtheme-local-tasks

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
${lomake-tjpt-rekisteriseloste-checkbox}                        //input[@data-drupal-selector='edit-privacy-policy-acceptance']

# Lähetä lomake
${lomake-tjpt-laheta-lomake-button}                             id=edit-submit

#Todistusjäljennöspyyntö lähetetty sivu
${lomake-tjpt-todistus-pyynto-lahetetty-text-FI}                Todistusjäljennöspyyntö lähetetty
${lomake-tjpt-sulje-ja-kirjaudu-ulos-button}                    (//span[contains(@class,'hds-button__label')])[1]
${lomake-tjpt-nayta-lomakkeen-tiedot}                           (//span[contains(@class,'hds-button__label')])[2]

# Direct url
${lomake-direct-url-error-text-FI}                              Pääsy Kielletty









