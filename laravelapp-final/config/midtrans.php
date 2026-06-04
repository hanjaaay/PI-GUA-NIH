<?php

return [
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'merchant_id' => env('MIDTRANS_MERCHANT_ID'), // Tambahkan baris ini
    'snap_url' => env('MIDTRANS_SNAP_URL'),
];
