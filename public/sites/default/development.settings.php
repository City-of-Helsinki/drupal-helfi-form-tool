<?php

#$config['helfi_proxy.settings']['tunnistamo_return_url'] = '/fi/dev-avustukset/openid-connect/tunnistamo';

$config['helfi_proxy.settings']['asset_path'] = 'dev-lomake-assets';
$config['helfi_proxy.settings']['prefixes'] = [
  'en' => 'dev-forms',
  'fi' => 'dev-lomakkeet',
  'sv' => 'dev-blanketter'
];

$config['webform.webform.todistusjaljennospyynto_tilaus']['third_party_settings']['form_tool_webform_parameters']['form_tool_webform_parameters']['email_notify'] = 'janne.suominen@siili.com';
