<?php
namespace Cobra3\BraPersonUkd\Domain\Repository;

/***
 *
 * This file is part of the "Personendatenbank UKD" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Rolf Nakielski <rolf@nakielski.de>, brandung
 *
 ***/

/**
 * The repository for Persons
 */
class PersonRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    
    /** 
     * functions for Migration
     */
    function getPersonsToMigrate($limit = 10){
        $query = $this->createQuery();
        //$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
        $statement = 'SELECT * FROM tx_ukdaddress_person '
            . ' WHERE deleted = 0'
            . ' AND done = 0'
            . ' AND skip = 0'
            //. ' AND image <> ""'
            //. ' AND pid <> 91399'
            . ' order by uid'
            . ' limit ' . $limit
            .' ;';
        $query->statement($statement);
        return $query->execute(true);
    }
    
    function getAddressforPerson($uid){
        $query = $this->createQuery();
        //$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
        $statement = 'SELECT * FROM tx_ukdaddress_address '
            . ' WHERE deleted = 0'
            . ' and tx_ukdaddress_person_id = ' . $uid
            . ' order by uid desc'
            .' ;';
        $query->statement($statement);
        return $query->execute(true);
    }
    
    function setImportDone($uid){
        
        $GLOBALS['TYPO3_DB']->exec_UPDATEquery( 
            'tx_ukdaddress_person',
            'deleted = 0 AND uid = ' . $uid,
            array ('done' => 1)
            ); 	
    }
}
