<?php

namespace Prospect;

use Prospect\Backends\Backend;

class Currency
{
    protected static $currencies = [
        'via'  => Currencies\Viacoin::class,
        'btc'  => Currencies\Bitcoin::class,
        'ltc'  => Currencies\Litecoin::class,
        'dash' => Currencies\Dash::class,
    ];

    public static function get(string $identifier): Backend
    {
        if (!array_key_exists($identifier, self::$currencies)) {
            throw new \InvalidArgumentException("currency [$identifier] was not found.");
        }
        $currency = self::$currencies[$identifier];

        // check if an instance is already avaible and use that instead
        if (is_object($currency)) {
            return $currency->getBackend();
        }

        // store the newly created instance so we can reuse it.
        self::$currencies[$identifier] = new $currency();

        return self::$currencies[$identifier]->getBackend();
    }
}
