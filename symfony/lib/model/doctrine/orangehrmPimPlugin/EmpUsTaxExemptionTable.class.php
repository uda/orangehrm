<?php

/**
 * EmpUsTaxExemptionTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EmpUsTaxExemptionTable extends PluginEmpUsTaxExemptionTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object EmpUsTaxExemptionTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EmpUsTaxExemption');
    }
}