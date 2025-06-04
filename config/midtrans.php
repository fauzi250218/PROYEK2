<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY'), // Ambil dari .env
    'client_key' => env('MIDTRANS_CLIENT_KEY'), // Ambil dari .env
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false), // Mode produksi
];


