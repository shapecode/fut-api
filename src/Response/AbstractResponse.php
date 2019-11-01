<?php

namespace Shapecode\FUT\Client\Response;

/**
 * Class AbstractResponse
 */
class AbstractResponse
{

    /** @var mixed[] */
    private $rawBody = [];

    /**
     * @param mixed[] $rawBody
     */
    public function __construct(array $rawBody)
    {
        $this->rawBody = $rawBody;
    }

    /**
     * @return mixed[]
     */
    public function getRawBody(): array
    {
        return $this->rawBody;
    }
}
