<?php

namespace AppBundle\ServiceInterface;

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