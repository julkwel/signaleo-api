<?php
/**
 * @author <Bocasay>.
 */

namespace App\CustomInterface;

/**
 * Interface CustomManagerInterface.
 */
interface CustomManagerInterface
{
    /**
     * @param object $entityObject
     *
     * @return boolean
     */
    public function save($entityObject);

    /**
     * @param object $entityObject
     *
     * @return boolean
     */
    public function delete($entityObject);

    /**
     * @param object $entityObject
     *
     * @return boolean
     */
    public function update($entityObject);

    /**
     * @param object $entityObject
     *
     * @return boolean
     */
    public function getList($entityObject);
}