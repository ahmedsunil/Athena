<?php

return [
    'two-factor-authentication' => [
        // Allow ±4 periods (±2 minutes) of clock drift between server and authenticator app
        'window' => 4,
    ],
];
