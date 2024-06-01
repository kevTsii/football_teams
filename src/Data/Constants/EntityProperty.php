<?php

namespace App\Data\Constants;

class EntityProperty
{
    public const TEAM = [
        'NAME'          => 'country',
        'MONEY_BALANCE' => 'moneyBalance',
        'COUNTRY'       => 'country',
        'CREATED_AT'    => 'createdAt',
        'UPDATED_AT'    => 'updatedAt',
    ];

    public const PLAYER = [
        'NAME'       => 'name',
        'SURNAME'    => 'surname',
        'TEAM'       => 'team',
        'CREATED_AT' => 'createdAt',
        'UPDATED_AT' => 'updatedAt',
    ];

    public const TRANSACTION = [
        'PLAYER'     => 'player',
        'SELLER'     => 'seller',
        'BUYER'      => 'buyer',
        'PRICE'      => 'price',
        'CREATED_AT' => 'createdAt',
        'UPDATED_AT' => 'updatedAt',
    ];
}