<?php

namespace Prospect\Backends;

use GuzzleHttp\Client;
use Prospect\Address;
use Prospect\Backends\Insight\AddressMapper;
use Prospect\Backends\Insight\TransactionMapper;
use Prospect\Transaction;

class Insight implements Backend
{
    protected $url;
    protected $client;

    public function __construct(string $url)
    {
        $this->url = $url;
        $this->client = new Client([
            'base_uri' => $url,
        ]);
    }

    public function getTransaction(string $id): ?Transaction
    {
        $resp = $this->client->get('tx/'.$id);

        // please fix me
        return (new TransactionMapper())->fromResponse(
            json_decode($resp->getBody())
        );
    }

    public function getAddress(string $address): ?Address
    {
        $resp = $this->client->get('addr/'.$address);

        // please fix me
        return (new AddressMapper())->fromResponse(
            json_decode($resp->getBody())
        );
    }

    public function getAddresses(array $addresses): ?array
    {
        $data = [];

        foreach ($addresses as $address) {
            $data[] = $this->getAddress($address);
        }

        return $data;
    }
}
