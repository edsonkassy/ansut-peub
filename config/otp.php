<?php

return [
    /*
    | Emails autorisés à se connecter SANS code OTP (pratique pour les tests).
    | Vide par défaut, et toujours ignoré en production.
    | Exemple (.env de dev/staging uniquement) : OTP_BYPASS_EMAILS="dev1@exemple.com,dev2@exemple.com"
    */
    'bypass_emails' => array_values(array_filter(array_map('trim', explode(',', (string) env('OTP_BYPASS_EMAILS', ''))))),
];
