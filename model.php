<?php

/**
 * Return list of users.
 */
function get_users($conn)
{
    $res = $conn->query('
        SELECT DISTINCT u.id, u.name FROM `users` AS u
        JOIN user_accounts AS ua ON u.id = ua.user_id
        JOIN transactions AS t ON ua.id = t.account_to OR ua.id = t.account_from
    ', PDO::FETCH_ASSOC);

    return $res->fetchAll(PDO::FETCH_KEY_PAIR);
}

/**
 * Return transactions balances of given user.
 */
function get_user_transactions_balances($user_id, $conn): array
{
    $statement = $conn->prepare("
        select u.name,
            iif (t.account_to = ua.id, t.amount, 0 - t.amount) as balance,
            strftime('%m', t.trdate) as month
        from users as u
        inner join user_accounts as ua on u.id = ua.user_id
        inner join transactions as t on ua.id = t.account_to OR ua.id = t.account_from
            where u.id = :user_id
    ");
    $statement->execute([
        'user_id' => $user_id,
    ]);

    $balance = [];
    foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $value) {
        if (!isset($balance[$value['month']])) {
            $balance[$value['month']] = [
                'name' => $value['name'],
                'amount' => 0,
                'month' => $value['month'],
            ];
        }

        $balance[$value['month']]['amount'] += (int)$value['balance'] ?? 0;
    }

    return $balance;
}
