<?php

namespace AppBundle\Service\Contracts;

/**
 * Interface LoggerInterface
 * @package AppBundle\ServiceInterface
 */
interface LoggerInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function init(array $data);
}