<?php
namespace Cobra3\BraPersonUkd\Utility;


class BackendUtility
{
    function getPersonLabel(&$parameters, $parentObject)
    {
        $record = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord($parameters['table'], $parameters['row']['uid']);
        $title = (empty($record['title'])) ? '' : $record['title'] . ' ';
        $firstName = (empty($record['firstname'])) ? '' : $record['firstname'] . ' ';
        $lastName = (empty($record['lastname'])) ? '' : $record['lastname'] . ' ';
        $newTitle = $title . $firstName . $lastName;
        $parameters['title'] = $newTitle;
    }

}