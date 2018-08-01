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
    function checkForNameInFolder($firstname, $lastname, $pid){
        //todo read hidden records as well - still to test
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);
        $query->getQuerySettings()->setIgnoreEnableFields(true);
        $query->matching(
            $query->logicalAnd(
                [
                    $query->equals('firstname', $firstname),
                    $query->equals('lastname', $lastname),
                    $query->equals('pid', $pid)
                ]
            )
        );
        return $query->execute();
    }

    function getPersonsToMigrate($limit = 10){
        $query = $this->createQuery();
        //$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
        $statement = 'SELECT * FROM tx_ukdaddress_person '
            . ' WHERE deleted = 0'
            . ' AND done = 0'
            . ' AND skip = 0'
            //. ' AND image <> ""'
            //. ' AND pid <> 91399'
            . ' order by hidden asc, tstamp desc'
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

    /**
     * @param int $pid
     * @return mixed
     */
    function getMappingForPid($pid){
        $query = $this->createQuery();
        //$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
        $statement = 'SELECT * FROM tx_ukdaddress_mapping '
            . ' where pid_old = ' . $pid
            .' ;';
        $query->statement($statement);
        return $query->execute(true);
    }

    /**
     * set record in old DB to done
     * @param int $uid
     */
    function setImportDone($uid){
        $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
            'tx_ukdaddress_person',
            'deleted = 0 AND uid = ' . $uid,
            array ('done' => 1)
        );
    }

    /**
     * set record in old DB to skip
     * @param int $uid
     */
    function setImportSkip($uid){
        $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
            'tx_ukdaddress_person',
            'deleted = 0 AND uid = ' . $uid,
            array ('skip' => 1)
        );
    }

     //functions for Conversion in tt_content

    /**
     * get all columns in the system
     * @param int $startPid
     * @param int $limit
     * @return mixed
     */
    function getColumns($startPid = 0, $limit = 1){
        $query = $this->createQuery();
        //$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
        $statement = 'SELECT DISTINCT pid, colPos FROM tt_content '
            . ' WHERE deleted = 0'
            . ' AND pid > ' . $startPid
            //. ' AND colPos = '. $colPos
            . ' order by pid, colPos'
            . ' limit ' . $limit
            .' ;';
        $query->statement($statement);
        return $query->execute(true);
    }

    /**
     * get all Content Elements of a Column
     * @param int $pid
     * @param int  $colPos
     * @return mixed
     */
    function getCEs($pid, $colPos){
        $query = $this->createQuery();
        //$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
        $statement = 'SELECT * FROM tt_content '
            . ' WHERE deleted = 0'
            . ' AND pid = ' . $pid
            . ' AND colPos = '. $colPos
            . ' order by sorting'
            //. ' limit ' . $limit
            .' ;';
        $query->statement($statement);
        return $query->execute(true);
    }

    /**
     * get matches
     * @param string $fullName
     * @param int $storagePid
     * @return array
     */
    function getPersonsByFullName($fullName, $storagePid){
        $query = $this->createQuery();
        //$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
        $statement = 'SELECT uid, pid, title, firstname, lastname FROM tx_brapersonukd_domain_model_person '
            . ' WHERE deleted = 0'
            . ' AND pid = ' . $storagePid
            . ' AND  ('
            . ' CONCAT(firstname, " ", lastname) = RIGHT("' . $fullName . '", CHAR_LENGTH(concat(firstname, " ", lastname)))'
            . ' OR firstname = "' . $fullName . '"'
            . ' OR lastname = "' . $fullName . '"'
            . ' )'
            . ' ;';
        $query->statement($statement);
        return $query->execute(true);
    }

    /**
     * old way to search for matches including title
     * @param string $fullName
     * @param int $storagePid
     * @return array
     */
    function getPersonsByFullNameOld($fullName, $storagePid){
        $query = $this->createQuery();
        //$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
        $statement = 'SELECT uid, pid, title, firstname, lastname FROM tx_brapersonukd_domain_model_person '
            . ' WHERE deleted = 0'
            . ' AND pid = ' . $storagePid
            . ' AND  ('
            . ' CONCAT(title, " ", firstname, " ", lastname) = "' . $fullName . '"'
            . ' OR CONCAT(firstname, " ", lastname) = "' . $fullName . '"'
            . ' OR CONCAT(title, " ", lastname) = "' . $fullName . '"'
            . ' OR CONCAT(title, " ", firstname) = "' . $fullName . '"'
            . ' OR firstname = "' . $fullName . '"'
            . ' OR lastname = "' . $fullName . '"'
            . ' )'
            . ' ;';
        $query->statement($statement);
        return $query->execute(true);
    }

    /**
     * @param int $page
     * @return int
     */
    function getTeamStorageFolder($page){
        $query = $this->createQuery();
        //$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
        $statement = 'SELECT uid FROM pages '
            . ' WHERE deleted = 0'
            . ' AND pid = ' . $page
            . ' AND doktype = 254 '
            . ' AND title = "Team" '
            . ' ;';
        $query->statement($statement);
        $res = $query->execute(true);
        if(count($res)){
            return $res[0]['uid'];
        }
        return 0;
    }

    /**
     * @param int $page
     * @return int
     */
    function getParentPage($page){
        $query = $this->createQuery();
        //$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
        $statement = 'SELECT pid FROM pages '
            . ' WHERE deleted = 0'
            . ' AND uid = ' . $page
            . ' ;';
        $query->statement($statement);
        $res = $query->execute(true);
        if(count($res)){
            return $res[0]['pid'];
        }
        return 0;
    }

    /**
     * @param array $data
     * @return mixed
     */
    function insertPlugin($data){
        $query = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tt_content', $data);
        return $query;
    }

    /**
     * set Content Element deleted
     * @param int $uid
     */
    function setCeDeleted($uid){
        $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
            'tt_content',
            'uid = ' . $uid,
            array ('deleted' => 1)
        );
    }




}
