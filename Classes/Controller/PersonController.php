<?php
namespace Cobra3\BraPersonUkd\Controller;

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
 * PersonController
 */
class PersonController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * personRepository
     *
     * @var \Cobra3\BraPersonUkd\Domain\Repository\PersonRepository
     * @inject
     */
    protected $personRepository = null;

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $personsArray = array();
        $personsUIdsArray = explode(',', $this->settings['persons']);
        foreach($personsUIdsArray as $personUid){
            $person = $this->personRepository->findOneByUid($personUid);
            if($person){
                $personsArray[] = $person;
            }
        }
        //$persons = $this->personRepository->findAll();
        $this->view->assign('persons',  $personsArray);
        //$this->view->assign('hallo', 'hallo');
    }
}
