<?php

namespace AppBundle\Service\Contracts;

/**
 * Interface ValidatorInterface
 * @package AppBundle\ServiceInterface
 */
interface ValidatorInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function init(array $data);
}