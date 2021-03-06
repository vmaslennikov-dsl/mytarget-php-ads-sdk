<?php

namespace MyTarget\Operator\V1\Remarketing;

use MyTarget\Client;
use MyTarget\Domain\V1\Remarketing\Remarketing;
use MyTarget\Domain\V1\Remarketing\RemarketingStat;
use MyTarget\Mapper\Mapper;

class RemarketingOperator
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Mapper
     */
    private $mapper;

    public function __construct(Client $client, Mapper $mapper)
    {
        $this->client = $client;
        $this->mapper = $mapper;
    }

    /**
     * @param string $username
     * @return ClientRemarketingOperator
     */
    public function forClient($username)
    {
        return new ClientRemarketingOperator($username, $this->client, $this->mapper);
    }

    /**
     * @param array|null $context
     *
     * @return RemarketingStat[]
     */
    public function all(array $context = null)
    {
        $json = $this->client->get("/api/v1/remarketings.json", null, $context);

        return array_map(function ($json) {
            return $this->mapper->hydrateNew(RemarketingStat::class, $json);
        }, $json);
    }

    /**
     * @param Remarketing $remarketing
     * @param array|null $context
     *
     * @return RemarketingStat
     */
    public function create(Remarketing $remarketing, array $context = null)
    {
        $rawRemarketing = $this->mapper->snapshot($remarketing);

        $json = $this->client->post("/api/v1/remarketings.json", null, $rawRemarketing, $context);

        return $this->mapper->hydrateNew(RemarketingStat::class, $json);
    }

    /**
     * @param int $id
     * @param Remarketing $remarketing
     * @param array|null $context
     *
     * @return RemarketingStat
     */
    public function edit($id, Remarketing $remarketing, array $context = null)
    {
        $rawRemarketing = $this->mapper->snapshot($remarketing, Remarketing::class);

        $path = sprintf("/api/v1/remarketings/%d.json", $id);
        $json = $this->client->post($path, null, $rawRemarketing, $context);

        return $this->mapper->hydrateNew(RemarketingStat::class, $json);
    }

    /**
     * @param int $id
     * @param array|null $context
     */
    public function delete($id, array $context = null)
    {
        $this->client->delete(sprintf("/api/v1/remarketings/%d.json", $id), null, $context);
    }
}
