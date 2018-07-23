<?php
namespace Cobra3\BraPersonUkd\Domain\Model;

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
 * Persons and Addresses
 */
class Person extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * @var bool
     */
    protected $hidden;

    /**
     * @var \DateTime
     */
    protected $crdate;

    /**
     * @var \DateTime
     */
    protected $tstamp;

    /**
     * oldId
     *
     * @var string
     */
    protected $oldId = '';

    /**
     * position
     *
     * @var int
     */
    protected $position = 0;

    /**
     * salutation
     *
     * @var string
     */
    protected $salutation = '';

    /**
     * sex
     *
     * @var string
     */
    protected $sex = '';

    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * firstname
     *
     * @var string
     */
    protected $firstname = '';

    /**
     * lastname
     *
     * @var string
     */
    protected $lastname = '';

    /**
     * job
     *
     * @var string
     */
    protected $job = '';

    /**
     * oldImageId
     *
     * @var string
     */
    protected $oldImageId = '';

    /**
     * jobTitle
     *
     * @var string
     */
    protected $jobTitle = '';

    /**
     * countryJob
     *
     * @var string
     */
    protected $countryJob = '';

    /**
     * medicalAssociation
     *
     * @var string
     */
    protected $medicalAssociation = '';

    /**
     * linkMedicalAssociation
     *
     * @var string
     */
    protected $linkMedicalAssociation = '';

    /**
     * address
     *
     * @var string
     */
    protected $address = '';

    /**
     * image
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @cascade remove
     */
    protected $image = null;

    /**
     * roomNr
     *
     * @var string
     */
    protected $roomNr = '';

    /**
     * buildingLevel
     *
     * @var string
     */
    protected $buildingLevel = '';

    /**
     * buildingNr
     *
     * @var string
     */
    protected $buildingNr = '';

    /**
     * street
     *
     * @var string
     */
    protected $street = '';

    /**
     * streetNr
     *
     * @var string
     */
    protected $streetNr = '';

    /**
     * postOfficePOst
     *
     * @var string
     */
    protected $postOfficePOst = '';

    /**
     * countryCode
     *
     * @var string
     */
    protected $countryCode = '';

    /**
     * country
     *
     * @var string
     */
    protected $country = '';

    /**
     * zipCode
     *
     * @var string
     */
    protected $zipCode = '';

    /**
     * city
     *
     * @var string
     */
    protected $city = '';

    /**
     * phone1
     *
     * @var string
     */
    protected $phone1 = '';

    /**
     * phone2
     *
     * @var string
     */
    protected $phone2 = '';

    /**
     * fax
     *
     * @var string
     */
    protected $fax = '';

    /**
     * mobilePhone
     *
     * @var string
     */
    protected $mobilePhone = '';

    /**
     * email
     *
     * @var string
     */
    protected $email = '';

    /**
     * homepage
     *
     * @var string
     */
    protected $homepage = '';

    /**
     * misc
     *
     * @var string
     */
    protected $misc = '';

    /**
     * personIdFk
     *
     * @var string
     */
    protected $personIdFk = '';

    /**
     * unitIdFk
     *
     * @var string
     */
    protected $unitIdFk = '';

    /**
     * departmentIdFk
     *
     * @var string
     */
    protected $departmentIdFk = '';

    /**
     * homepageTitle
     *
     * @var string
     */
    protected $homepageTitle = '';

    /**
     * homepagePageTitle
     *
     * @var string
     */
    protected $homepagePageTitle = '';

    /**
     * filelinkTitle
     *
     * @var string
     */
    protected $filelinkTitle = '';

    /**
     * homepagePage
     *
     * @var string
     */
    protected $homepagePage = '';

    /**
     * filelink
     *
     * @var string
     */
    protected $filelink = '';

    /**
     * email2
     *
     * @var string
     */
    protected $email2 = '';

    /**
     * Get hidden flag
     *
     * @return int
     */
    public function getHidden()
    {
        return $this->hidden;
    }
    /**
     * Set hidden flag
     *
     * @param int $hidden hidden flag
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * Get creation date
     *
     * @return int
     */
    public function getCrdate()
    {
        return $this->crdate;
    }
    /**
     * Set creation date
     *
     * @param int $crdate
     */
    public function setCrdate($crdate)
    {
        $this->crdate = $crdate;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }
    /**
     * Set time stamp
     *
     * @param \DateTime $tstamp time stamp
     */
    public function setTstamp($tstamp)
    {
        $this->tstamp = $tstamp;
    }

    /**
     * Returns the oldId
     *
     * @return string $oldId
     */
    public function getOldId()
    {
        return $this->oldId;
    }

    /**
     * Sets the oldId
     *
     * @param string $oldId
     * @return void
     */
    public function setOldId($oldId)
    {
        $this->oldId = $oldId;
    }

    /**
     * Returns the position
     *
     * @return int $position
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Sets the position
     *
     * @param int $position
     * @return void
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * Returns the salutation
     *
     * @return string $salutation
     */
    public function getSalutation()
    {
        return $this->salutation;
    }

    /**
     * Sets the salutation
     *
     * @param string $salutation
     * @return void
     */
    public function setSalutation($salutation)
    {
        $this->salutation = $salutation;
    }

    /**
     * Returns the sex
     *
     * @return string $sex
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Sets the sex
     *
     * @param string $sex
     * @return void
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
    }

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the firstname
     *
     * @return string $firstname
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Sets the firstname
     *
     * @param string $firstname
     * @return void
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Returns the lastname
     *
     * @return string $lastname
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Sets the lastname
     *
     * @param string $lastname
     * @return void
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Returns the job
     *
     * @return string $job
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Sets the job
     *
     * @param string $job
     * @return void
     */
    public function setJob($job)
    {
        $this->job = $job;
    }

    /**
     * Returns the oldImageId
     *
     * @return string $oldImageId
     */
    public function getOldImageId()
    {
        return $this->oldImageId;
    }

    /**
     * Sets the oldImageId
     *
     * @param string $oldImageId
     * @return void
     */
    public function setOldImageId($oldImageId)
    {
        $this->oldImageId = $oldImageId;
    }

    /**
     * Returns the jobTitle
     *
     * @return string $jobTitle
     */
    public function getJobTitle()
    {
        return $this->jobTitle;
    }

    /**
     * Sets the jobTitle
     *
     * @param string $jobTitle
     * @return void
     */
    public function setJobTitle($jobTitle)
    {
        $this->jobTitle = $jobTitle;
    }

    /**
     * Returns the countryJob
     *
     * @return string $countryJob
     */
    public function getCountryJob()
    {
        return $this->countryJob;
    }

    /**
     * Sets the countryJob
     *
     * @param string $countryJob
     * @return void
     */
    public function setCountryJob($countryJob)
    {
        $this->countryJob = $countryJob;
    }

    /**
     * Returns the medicalAssociation
     *
     * @return string $medicalAssociation
     */
    public function getMedicalAssociation()
    {
        return $this->medicalAssociation;
    }

    /**
     * Sets the medicalAssociation
     *
     * @param string $medicalAssociation
     * @return void
     */
    public function setMedicalAssociation($medicalAssociation)
    {
        $this->medicalAssociation = $medicalAssociation;
    }

    /**
     * Returns the linkMedicalAssociation
     *
     * @return string $linkMedicalAssociation
     */
    public function getLinkMedicalAssociation()
    {
        return $this->linkMedicalAssociation;
    }

    /**
     * Sets the linkMedicalAssociation
     *
     * @param string $linkMedicalAssociation
     * @return void
     */
    public function setLinkMedicalAssociation($linkMedicalAssociation)
    {
        $this->linkMedicalAssociation = $linkMedicalAssociation;
    }

    /**
     * Returns the address
     *
     * @return string $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets the address
     *
     * @param string $address
     * @return void
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Returns the image
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Sets the image
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
     * @return void
     */
    public function setImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $image)
    {
        $this->image = $image;
    }

    /**
     * Returns the roomNr
     *
     * @return string $roomNr
     */
    public function getRoomNr()
    {
        return $this->roomNr;
    }

    /**
     * Sets the roomNr
     *
     * @param string $roomNr
     * @return void
     */
    public function setRoomNr($roomNr)
    {
        $this->roomNr = $roomNr;
    }

    /**
     * Returns the buildingLevel
     *
     * @return string $buildingLevel
     */
    public function getBuildingLevel()
    {
        return $this->buildingLevel;
    }

    /**
     * Sets the buildingLevel
     *
     * @param string $buildingLevel
     * @return void
     */
    public function setBuildingLevel($buildingLevel)
    {
        $this->buildingLevel = $buildingLevel;
    }

    /**
     * Returns the buildingNr
     *
     * @return string $buildingNr
     */
    public function getBuildingNr()
    {
        return $this->buildingNr;
    }

    /**
     * Sets the buildingNr
     *
     * @param string $buildingNr
     * @return void
     */
    public function setBuildingNr($buildingNr)
    {
        $this->buildingNr = $buildingNr;
    }

    /**
     * Returns the street
     *
     * @return string $street
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Sets the street
     *
     * @param string $street
     * @return void
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * Returns the streetNr
     *
     * @return string $streetNr
     */
    public function getStreetNr()
    {
        return $this->streetNr;
    }

    /**
     * Sets the streetNr
     *
     * @param string $streetNr
     * @return void
     */
    public function setStreetNr($streetNr)
    {
        $this->streetNr = $streetNr;
    }

    /**
     * Returns the postOfficePOst
     *
     * @return string $postOfficePOst
     */
    public function getPostOfficePOst()
    {
        return $this->postOfficePOst;
    }

    /**
     * Sets the postOfficePOst
     *
     * @param string $postOfficePOst
     * @return void
     */
    public function setPostOfficePOst($postOfficePOst)
    {
        $this->postOfficePOst = $postOfficePOst;
    }

    /**
     * Returns the countryCode
     *
     * @return string $countryCode
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Sets the countryCode
     *
     * @param string $countryCode
     * @return void
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }

    /**
     * Returns the country
     *
     * @return string $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets the country
     *
     * @param string $country
     * @return void
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Returns the zipCode
     *
     * @return string $zipCode
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Sets the zipCode
     *
     * @param string $zipCode
     * @return void
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    }

    /**
     * Returns the city
     *
     * @return string $city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Sets the city
     *
     * @param string $city
     * @return void
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Returns the phone1
     *
     * @return string $phone1
     */
    public function getPhone1()
    {
        return $this->phone1;
    }

    /**
     * Sets the phone1
     *
     * @param string $phone1
     * @return void
     */
    public function setPhone1($phone1)
    {
        $this->phone1 = $phone1;
    }

    /**
     * Returns the phone2
     *
     * @return string $phone2
     */
    public function getPhone2()
    {
        return $this->phone2;
    }

    /**
     * Sets the phone2
     *
     * @param string $phone2
     * @return void
     */
    public function setPhone2($phone2)
    {
        $this->phone2 = $phone2;
    }

    /**
     * Returns the fax
     *
     * @return string $fax
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Sets the fax
     *
     * @param string $fax
     * @return void
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    }

    /**
     * Returns the mobilePhone
     *
     * @return string $mobilePhone
     */
    public function getMobilePhone()
    {
        return $this->mobilePhone;
    }

    /**
     * Sets the mobilePhone
     *
     * @param string $mobilePhone
     * @return void
     */
    public function setMobilePhone($mobilePhone)
    {
        $this->mobilePhone = $mobilePhone;
    }

    /**
     * Returns the email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email
     *
     * @param string $email
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Returns the homepage
     *
     * @return string $homepage
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    /**
     * Sets the homepage
     *
     * @param string $homepage
     * @return void
     */
    public function setHomepage($homepage)
    {
        $this->homepage = $homepage;
    }

    /**
     * Returns the misc
     *
     * @return string $misc
     */
    public function getMisc()
    {
        return $this->misc;
    }

    /**
     * Sets the misc
     *
     * @param string $misc
     * @return void
     */
    public function setMisc($misc)
    {
        $this->misc = $misc;
    }

    /**
     * Returns the personIdFk
     *
     * @return string $personIdFk
     */
    public function getPersonIdFk()
    {
        return $this->personIdFk;
    }

    /**
     * Sets the personIdFk
     *
     * @param string $personIdFk
     * @return void
     */
    public function setPersonIdFk($personIdFk)
    {
        $this->personIdFk = $personIdFk;
    }

    /**
     * Returns the unitIdFk
     *
     * @return string $unitIdFk
     */
    public function getUnitIdFk()
    {
        return $this->unitIdFk;
    }

    /**
     * Sets the unitIdFk
     *
     * @param string $unitIdFk
     * @return void
     */
    public function setUnitIdFk($unitIdFk)
    {
        $this->unitIdFk = $unitIdFk;
    }

    /**
     * Returns the departmentIdFk
     *
     * @return string $departmentIdFk
     */
    public function getDepartmentIdFk()
    {
        return $this->departmentIdFk;
    }

    /**
     * Sets the departmentIdFk
     *
     * @param string $departmentIdFk
     * @return void
     */
    public function setDepartmentIdFk($departmentIdFk)
    {
        $this->departmentIdFk = $departmentIdFk;
    }

    /**
     * Returns the homepageTitle
     *
     * @return string $homepageTitle
     */
    public function getHomepageTitle()
    {
        return $this->homepageTitle;
    }

    /**
     * Sets the homepageTitle
     *
     * @param string $homepageTitle
     * @return void
     */
    public function setHomepageTitle($homepageTitle)
    {
        $this->homepageTitle = $homepageTitle;
    }

    /**
     * Returns the homepagePageTitle
     *
     * @return string $homepagePageTitle
     */
    public function getHomepagePageTitle()
    {
        return $this->homepagePageTitle;
    }

    /**
     * Sets the homepagePageTitle
     *
     * @param string $homepagePageTitle
     * @return void
     */
    public function setHomepagePageTitle($homepagePageTitle)
    {
        $this->homepagePageTitle = $homepagePageTitle;
    }

    /**
     * Returns the filelinkTitle
     *
     * @return string $filelinkTitle
     */
    public function getFilelinkTitle()
    {
        return $this->filelinkTitle;
    }

    /**
     * Sets the filelinkTitle
     *
     * @param string $filelinkTitle
     * @return void
     */
    public function setFilelinkTitle($filelinkTitle)
    {
        $this->filelinkTitle = $filelinkTitle;
    }

    /**
     * Returns the homepagePage
     *
     * @return string $homepagePage
     */
    public function getHomepagePage()
    {
        return $this->homepagePage;
    }

    /**
     * Sets the homepagePage
     *
     * @param string $homepagePage
     * @return void
     */
    public function setHomepagePage($homepagePage)
    {
        $this->homepagePage = $homepagePage;
    }

    /**
     * Returns the filelink
     *
     * @return string $filelink
     */
    public function getFilelink()
    {
        return $this->filelink;
    }

    /**
     * Sets the filelink
     *
     * @param string $filelink
     * @return void
     */
    public function setFilelink($filelink)
    {
        $this->filelink = $filelink;
    }

    /**
     * Returns the email2
     *
     * @return string $email2
     */
    public function getEmail2()
    {
        return $this->email2;
    }

    /**
     * Sets the email2
     *
     * @param string $email2
     * @return void
     */
    public function setEmail2($email2)
    {
        $this->email2 = $email2;
    }
}
