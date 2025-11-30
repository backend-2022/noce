<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$phoneCodes = [
    [
        'code' => '+966',
        'country' => 'Saudi Arabia',
        'country_ar' => 'المملكة العربية السعودية',
        'flag' => '🇸🇦',
        'min_length' => 9,
        'max_length' => 9,
        'format' => '5XXXXXXXX',
        'help_text' => 'رقم الهاتف يجب أن يبدأ بـ 5 ويكون 9 أرقام.'
    ]
    // ,
    // [
    //     'code' => '+200',
    //     'country' => 'Egypt',
    //     'country_ar' => 'مصر',
    //     'flag' => '🇪🇬',
    //     'min_length' => 9,
    //     'max_length' => 9,
    //     'format' => '01XXXXXXXX',
    //     'help_text' => 'رقم الهاتف يجب أن يبدأ بـ 01 ويكون 11 رقم.'
    // ]
];

echo json_encode([
    'success' => true,
    'data' => $phoneCodes,
    'total' => count($phoneCodes),
    'message' => 'Phone codes retrieved successfully'
], JSON_UNESCAPED_UNICODE);
?>
