<?php
namespace Cobra3\BraPersonUkd\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Rolf Nakielski <rolf@nakielski.de>
 */
class PersonTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Cobra3\BraPersonUkd\Domain\Model\Person
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Cobra3\BraPersonUkd\Domain\Model\Person();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getOldIdReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getOldId()
        );

    }

    /**
     * @test
     */
    public function setOldIdForStringSetsOldId()
    {
        $this->subject->setOldId('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'oldId',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getPositionReturnsInitialValueForInt()
    {
    }

    /**
     * @test
     */
    public function setPositionForIntSetsPosition()
    {
    }

    /**
     * @test
     */
    public function getSalutationReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getSalutation()
        );

    }

    /**
     * @test
     */
    public function setSalutationForStringSetsSalutation()
    {
        $this->subject->setSalutation('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'salutation',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getSexReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getSex()
        );

    }

    /**
     * @test
     */
    public function setSexForStringSetsSex()
    {
        $this->subject->setSex('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'sex',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getTitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getTitle()
        );

    }

    /**
     * @test
     */
    public function setTitleForStringSetsTitle()
    {
        $this->subject->setTitle('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'title',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getFirstnameReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getFirstname()
        );

    }

    /**
     * @test
     */
    public function setFirstnameForStringSetsFirstname()
    {
        $this->subject->setFirstname('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'firstname',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getLastnameReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getLastname()
        );

    }

    /**
     * @test
     */
    public function setLastnameForStringSetsLastname()
    {
        $this->subject->setLastname('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'lastname',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getJobReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getJob()
        );

    }

    /**
     * @test
     */
    public function setJobForStringSetsJob()
    {
        $this->subject->setJob('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'job',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getOldImageIdReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getOldImageId()
        );

    }

    /**
     * @test
     */
    public function setOldImageIdForStringSetsOldImageId()
    {
        $this->subject->setOldImageId('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'oldImageId',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getJobTitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getJobTitle()
        );

    }

    /**
     * @test
     */
    public function setJobTitleForStringSetsJobTitle()
    {
        $this->subject->setJobTitle('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'jobTitle',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCountryJobReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCountryJob()
        );

    }

    /**
     * @test
     */
    public function setCountryJobForStringSetsCountryJob()
    {
        $this->subject->setCountryJob('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'countryJob',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getMedicalAssociationReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getMedicalAssociation()
        );

    }

    /**
     * @test
     */
    public function setMedicalAssociationForStringSetsMedicalAssociation()
    {
        $this->subject->setMedicalAssociation('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'medicalAssociation',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getLinkMedicalAssociationReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getLinkMedicalAssociation()
        );

    }

    /**
     * @test
     */
    public function setLinkMedicalAssociationForStringSetsLinkMedicalAssociation()
    {
        $this->subject->setLinkMedicalAssociation('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'linkMedicalAssociation',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getAddressReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getAddress()
        );

    }

    /**
     * @test
     */
    public function setAddressForStringSetsAddress()
    {
        $this->subject->setAddress('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'address',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getImageReturnsInitialValueForFileReference()
    {
        self::assertEquals(
            null,
            $this->subject->getImage()
        );

    }

    /**
     * @test
     */
    public function setImageForFileReferenceSetsImage()
    {
        $fileReferenceFixture = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
        $this->subject->setImage($fileReferenceFixture);

        self::assertAttributeEquals(
            $fileReferenceFixture,
            'image',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getRoomNrReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getRoomNr()
        );

    }

    /**
     * @test
     */
    public function setRoomNrForStringSetsRoomNr()
    {
        $this->subject->setRoomNr('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'roomNr',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getBuildingLevelReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getBuildingLevel()
        );

    }

    /**
     * @test
     */
    public function setBuildingLevelForStringSetsBuildingLevel()
    {
        $this->subject->setBuildingLevel('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'buildingLevel',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getBuildingNrReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getBuildingNr()
        );

    }

    /**
     * @test
     */
    public function setBuildingNrForStringSetsBuildingNr()
    {
        $this->subject->setBuildingNr('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'buildingNr',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getStreetReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getStreet()
        );

    }

    /**
     * @test
     */
    public function setStreetForStringSetsStreet()
    {
        $this->subject->setStreet('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'street',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getStreetNrReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getStreetNr()
        );

    }

    /**
     * @test
     */
    public function setStreetNrForStringSetsStreetNr()
    {
        $this->subject->setStreetNr('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'streetNr',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getPostOfficePOstReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getPostOfficePOst()
        );

    }

    /**
     * @test
     */
    public function setPostOfficePOstForStringSetsPostOfficePOst()
    {
        $this->subject->setPostOfficePOst('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'postOfficePOst',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCountryCodeReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCountryCode()
        );

    }

    /**
     * @test
     */
    public function setCountryCodeForStringSetsCountryCode()
    {
        $this->subject->setCountryCode('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'countryCode',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCountryReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCountry()
        );

    }

    /**
     * @test
     */
    public function setCountryForStringSetsCountry()
    {
        $this->subject->setCountry('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'country',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getZipCodeReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getZipCode()
        );

    }

    /**
     * @test
     */
    public function setZipCodeForStringSetsZipCode()
    {
        $this->subject->setZipCode('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'zipCode',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCityReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCity()
        );

    }

    /**
     * @test
     */
    public function setCityForStringSetsCity()
    {
        $this->subject->setCity('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'city',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getPhone1ReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getPhone1()
        );

    }

    /**
     * @test
     */
    public function setPhone1ForStringSetsPhone1()
    {
        $this->subject->setPhone1('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'phone1',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getPhone2ReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getPhone2()
        );

    }

    /**
     * @test
     */
    public function setPhone2ForStringSetsPhone2()
    {
        $this->subject->setPhone2('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'phone2',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getFaxReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getFax()
        );

    }

    /**
     * @test
     */
    public function setFaxForStringSetsFax()
    {
        $this->subject->setFax('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'fax',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getMobilePhoneReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getMobilePhone()
        );

    }

    /**
     * @test
     */
    public function setMobilePhoneForStringSetsMobilePhone()
    {
        $this->subject->setMobilePhone('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'mobilePhone',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getEmailReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getEmail()
        );

    }

    /**
     * @test
     */
    public function setEmailForStringSetsEmail()
    {
        $this->subject->setEmail('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'email',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getHomepageReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getHomepage()
        );

    }

    /**
     * @test
     */
    public function setHomepageForStringSetsHomepage()
    {
        $this->subject->setHomepage('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'homepage',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getMiscReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getMisc()
        );

    }

    /**
     * @test
     */
    public function setMiscForStringSetsMisc()
    {
        $this->subject->setMisc('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'misc',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getPersonIdFkReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getPersonIdFk()
        );

    }

    /**
     * @test
     */
    public function setPersonIdFkForStringSetsPersonIdFk()
    {
        $this->subject->setPersonIdFk('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'personIdFk',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getUnitIdFkReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getUnitIdFk()
        );

    }

    /**
     * @test
     */
    public function setUnitIdFkForStringSetsUnitIdFk()
    {
        $this->subject->setUnitIdFk('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'unitIdFk',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getDepartmentIdFkReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getDepartmentIdFk()
        );

    }

    /**
     * @test
     */
    public function setDepartmentIdFkForStringSetsDepartmentIdFk()
    {
        $this->subject->setDepartmentIdFk('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'departmentIdFk',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getHomepageTitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getHomepageTitle()
        );

    }

    /**
     * @test
     */
    public function setHomepageTitleForStringSetsHomepageTitle()
    {
        $this->subject->setHomepageTitle('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'homepageTitle',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getHomepagePageTitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getHomepagePageTitle()
        );

    }

    /**
     * @test
     */
    public function setHomepagePageTitleForStringSetsHomepagePageTitle()
    {
        $this->subject->setHomepagePageTitle('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'homepagePageTitle',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getFilelinkTitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getFilelinkTitle()
        );

    }

    /**
     * @test
     */
    public function setFilelinkTitleForStringSetsFilelinkTitle()
    {
        $this->subject->setFilelinkTitle('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'filelinkTitle',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getHomepagePageReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getHomepagePage()
        );

    }

    /**
     * @test
     */
    public function setHomepagePageForStringSetsHomepagePage()
    {
        $this->subject->setHomepagePage('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'homepagePage',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getFilelinkReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getFilelink()
        );

    }

    /**
     * @test
     */
    public function setFilelinkForStringSetsFilelink()
    {
        $this->subject->setFilelink('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'filelink',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getEmail2ReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getEmail2()
        );

    }

    /**
     * @test
     */
    public function setEmail2ForStringSetsEmail2()
    {
        $this->subject->setEmail2('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'email2',
            $this->subject
        );

    }
}
