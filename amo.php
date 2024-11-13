<?php
// загружаю конфиг из config.php
$config = require 'config.php';

// перебрасываю данные в переменные
$subdomain = $config['AMOCRM_SUBDOMAIN'];
$access_token = $config['AMOCRM_ACCESS_TOKEN'];

// функция для удобной отправки запросов
function sendRequest($url, $data, $access_token) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer {$access_token}",
        'Content-Type: application/json'
    ]);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);

    if ($error) {
        echo "Ошибка: $error";
        return false;
    }

    return json_decode($response, true);
}

// перебрасываю данные в переменные
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$price = $_POST['price'] ?? 0;
$userSpentMoreThan30Seconds = $_POST['userSpentMoreThan30Seconds'] ? true : false;

// проверяю что все данные точно заполнены
if (empty($name) || empty($email) || empty($phone) || empty($price)) {
    echo "Заполни все поля";
    exit;
}

// Создание контакта
$contactData = [
    [
        'name' => $name,
        'custom_fields_values' => [
            [
                'field_code' => 'EMAIL',
                'values' => [['value' => $email, 'enum_code' => 'WORK']]
            ],
            [
                'field_code' => 'PHONE',
                'values' => [['value' => $phone, 'enum_code' => 'WORK']]
            ]
        ]
    ]
];
// создаём контакт
$contactResponse = sendRequest("https://{$subdomain}.amocrm.ru/api/v4/contacts", $contactData, $access_token);

// сохраняем id контакта
$contactId = $contactResponse['_embedded']['contacts'][0]['id'];

// формирование запроса к api для добавления сделки
$leadData = [
    [
        'name' => 'Сделка',
        'price' => (int)$price,
        'custom_fields_values' => [
            [
                'field_id' => 2157665, // id кастомного свойства
                'values' => [
                    [
                        'value' => $userSpentMoreThan30Seconds
                    ]
                ]
            ]
        ],
        '_embedded' => [
            'contacts' => [
                ['id' => $contactId]
            ],
        ]
    ]
];

// отправка сделки
$leadResponse = sendRequest("https://{$subdomain}.amocrm.ru/api/v4/leads", $leadData, $access_token);

