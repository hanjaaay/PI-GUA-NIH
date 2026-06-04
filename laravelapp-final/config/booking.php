<?php

return [

    'expiry_minutes' => (int) env(

        'BOOKING_EXPIRY_MINUTES',

        15
    ),
];
