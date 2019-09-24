<?php

namespace App\Interfaces;

use App\Entity\Maintenance;

/**
 * Interface EntityWithMaintenanceInterface
 * @package App\Interfaces
 */
interface EntityWithMaintenanceInterface
{
    public function getUserId();

    public function getId();

    public function getMaintenances();

    /**
     * @param Maintenance $maintenance
     * @return mixed
     */
    public function addMaintenance(Maintenance $maintenance);

    /**
     * @param Maintenance $maintenance
     * @return mixed
     */
    public function removeMaintenance(Maintenance $maintenance);

}