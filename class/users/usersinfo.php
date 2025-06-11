<?php
function class_usersInfo($UsersId = null, $conn = null) {
    // For development environment, return dummy data
    $dummy_user = [
        'UsersId' => 1,
        'FullName' => 'Demo User',
        'Email' => 'demo@example.com',
        'Picture' => 'https://ui-avatars.com/api/?name=Demo+User',
        'CustomersId' => 1,
        'Status' => 1,
        'Personality' => 'Analitico'
    ];

    $dummy_customer = [
        'CustomersId' => 1,
        'Name' => 'Demo Company',
        'Default' => 1
    ];

    // Return formatted data structure
    return [
        'UsersId' => 1,
        'info' => $dummy_user,
        'picture' => $dummy_user['Picture'],
        'customers' => [$dummy_customer],
        'default_customer' => $dummy_customer,
        'customers_name' => 'Demo Company',
        'SessionId' => session_id() ?: '1'
    ];
}
