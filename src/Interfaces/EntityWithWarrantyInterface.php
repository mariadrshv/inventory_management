<?php

namespace App\Interfaces;

use App\Entity\Warranties;

/**
 * Class EntityWithWarrantyInterface
 * @package App\Interfaces
 */
interface EntityWithWarrantyInterface
{
    public function getId();

    public function getUserId();

    public function getWarranties();

    /**
     * @param Warranties $warranties
     * @return mixed
     */
    public function addWarranty(Warranties $warranties);

    /**
     * @param Warranties $warranties
     * @return mixed
     */
    public function removeWarranty(Warranties $warranties);
}