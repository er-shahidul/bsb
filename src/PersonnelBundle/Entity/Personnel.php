<?php

namespace PersonnelBundle\Entity;

use AppBundle\Entity\AttachmentsTrait;
use AppBundle\Entity\OfficeAwareEntityInterface;
use AppBundle\Traits\BlameableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Personnel
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class Personnel implements OfficeAwareEntityInterface
{
    const DEFAULT_AVATAR = 'assets/global/img/avatar.png';
    use BlameableEntity,
        TimestampableEntity,
        BaseEmploymentInfo,
        AttachmentsTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Office")
     * @ORM\JoinColumn(name="office_id")
     */
    private $office;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_of_wedding", type="date", nullable=true)
     */
    private $dateOfWedding;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\District")
     */
    private $permanentDistrict;

    /**
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\Upazila")
     */
    private $permanentUpazila;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="permanent_post_office", type="string", length=255)
     */
    private $permanentPostOffice;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="permanent_post_code", type="string", length=255, nullable=true)
     */
    private $permanentPostCode;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="permanent_village", type="string", length=255)
     */
    private $permanentVillage;

    /**
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\District")
     */
    private $district;

    /**
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\Upazila")
     */
    private $upazila;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="post_office", type="string", length=255)
     */
    private $postOffice;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="post_code", type="string", length=255, nullable=true)
     */
    private $postCode;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="village", type="string", length=255)
     */
    private $village;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="designation", type="string", length=255, nullable=true)
     */
    private $designation;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="nationality", type="string", length=255)
     */
    private $nationality = 'Bangladeshi';

    /**
     * @var string
     *
     *
     * @ORM\Column(name="identification_mark", type="string", length=255, nullable=true)
     */
    private $identificationMark;

    /**
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\Gender")
     */
    private $gender;

//    /**
//     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\ServingType")
//     */
//    private $servingType;

    /**
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\BloodGroup")
     */
    private $bloodGroup;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile_number", type="string", length=255, nullable=true)
     */
    private $mobileNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone_number", type="string", length=255, nullable=true)
     */
    private $telephoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="nid", type="string", length=255, nullable=true)
     */
    private $nid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_of_birth", type="date", nullable=true)
     */
    private $dateOfBirth;

    /**
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\Religion")
     */
    private $religion;

    /**
     * @var string
     *
     * @ORM\Column(name="height", type="string", length=255, nullable=true)
     */
    private $height;

    /**
     * @var string
     *
     * @ORM\Column(name="chest_measurement", type="string", length=255, nullable=true)
     */
    private $chestMeasurement;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @Assert\File(maxSize="5M")
     */
    private $photoFile;
    private $tempPhoto;

    /**
     * @var ArrayCollection
     */
    protected $families;

    /**
     * @var ArrayCollection
     */
    protected $employmentInformations;

    /**
     * @var ArrayCollection
     */
    protected $specialDiseases;

    /**
     * @var ArrayCollection
     *
     */
    protected $careers;

    /**
     * @var ArrayCollection
     */
    protected $educations;

    /**
     * @var ArrayCollection
     */
    protected $servicesInfo;

    /**
     * @var ArrayCollection
     */
    protected $trainings;

    /**
     * @var string
     * @ORM\Column(name="fathers_name", type="string", length=255, nullable=true)
     */
    private $fathersName;

    /**
     * @var string
     * @ORM\Column(name="mothers_name", type="string", length=255, nullable=true)
     */
    private $mothersName;

    /**
     * @var string
     * @ORM\Column(name="fathers_occupation", type="string", length=255, nullable=true)
     */
    private $fathersOccupation;

    /**
     * @var string
     * @ORM\Column(name="mothers_occupation", type="string", length=255, nullable=true)
     */
    private $mothersOccupation;

    /**
     * @var string
     * @ORM\Column(name="fathers_address", type="string", length=255, nullable=true)
     */
    private $fathersAddress;

    /**
     * @var string
     * @ORM\Column(name="mothers_address", type="string", length=255, nullable=true)
     */
    private $mothersAddress;

    /**
     * @var string
     * @ORM\Column(name="fathers_mobile_number", type="string", length=255, nullable=true)
     */
    private $fathersMobileNumber;

    /**
     * @var string
     * @ORM\Column(name="mothers_mobile_number", type="string", length=255, nullable=true)
     */
    private $mothersMobileNumber;

    /**
     * @var string
     * @ORM\Column(name="fathers_nok_percentage", type="string", nullable=true)
     */
    private $fathersNokPercentage;

    /**
     * @var string
     * @ORM\Column(name="mothers_nok_percentage", type="string", nullable=true)
     */
    private $mothersNokPercentage;


    public function __construct()
    {
        $this->careers = new  ArrayCollection();
        $this->trainings = new  ArrayCollection();
        $this->servicesInfo = new  ArrayCollection();
        $this->educations = new  ArrayCollection();
        $this->attachments = new  ArrayCollection();
        $this->families = new  ArrayCollection();
        $this->employmentInformations = new  ArrayCollection();
        $this->specialDiseases = new  ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getOffice()
    {
        return $this->office;
    }

    /**
     * @param mixed $office
     */
    public function setOffice($office)
    {
        $this->office = $office;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Personnel
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set designation
     *
     * @param string $designation
     *
     * @return Personnel
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * Get designation
     *
     * @return string
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * Set identificationMark
     *
     * @param string $identificationMark
     *
     * @return Personnel
     */
    public function setIdentificationMark($identificationMark)
    {
        $this->identificationMark = $identificationMark;

        return $this;
    }

    /**
     * Get identificationMark
     *
     * @return string
     */
    public function getIdentificationMark()
    {
        return $this->identificationMark;
    }

    /**
     * Set gender
     *
     * @param \stdClass $gender
     *
     * @return Personnel
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return Gender
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set bloodGroup
     *
     * @param \stdClass $bloodGroup
     *
     * @return Personnel
     */
    public function setBloodGroup($bloodGroup)
    {
        $this->bloodGroup = $bloodGroup;

        return $this;
    }

    /**
     * Get bloodGroup
     *
     * @return BloodGroup
     */
    public function getBloodGroup()
    {
        return $this->bloodGroup;
    }

    /**
     * Set mobileNumber
     *
     * @param string $mobileNumber
     *
     * @return Personnel
     */
    public function setMobileNumber($mobileNumber)
    {
        $this->mobileNumber = $mobileNumber;

        return $this;
    }

    /**
     * Get mobileNumber
     *
     * @return string
     */
    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }

    /**
     * Set telephoneNumber
     *
     * @param string $telephoneNumber
     *
     * @return Personnel
     */
    public function setTelephoneNumber($telephoneNumber)
    {
        $this->telephoneNumber = $telephoneNumber;

        return $this;
    }

    /**
     * Get telephoneNumber
     *
     * @return string
     */
    public function getTelephoneNumber()
    {
        return $this->telephoneNumber;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Personnel
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set nid
     *
     * @param string $nid
     *
     * @return Personnel
     */
    public function setNid($nid)
    {
        $this->nid = $nid;

        return $this;
    }

    /**
     * Get nid
     *
     * @return string
     */
    public function getNid()
    {
        return $this->nid;
    }

    /**
     * Set dateOfBirth
     *
     * @param \DateTime $dateOfBirth
     *
     * @return Personnel
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set religion
     *
     * @param \stdClass $religion
     *
     * @return Personnel
     */
    public function setReligion($religion)
    {
        $this->religion = $religion;

        return $this;
    }

    /**
     * Get religion
     *
     * @return Religion
     */
    public function getReligion()
    {
        return $this->religion;
    }

    /**
     * Set height
     *
     * @param string $height
     *
     * @return Personnel
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set chestMeasurement
     *
     * @param string $chestMeasurement
     *
     * @return Personnel
     */
    public function setChestMeasurement($chestMeasurement)
    {
        $this->chestMeasurement = $chestMeasurement;

        return $this;
    }

    /**
     * Get chestMeasurement
     *
     * @return string
     */
    public function getChestMeasurement()
    {
        return $this->chestMeasurement;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Personnel
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @return mixed
     */
    public function getPhotoPath()
    {
        return $this->photo === null || !file_exists($this->getAbsolutePath()) ? self::DEFAULT_AVATAR : $this->getWebPhotoPath();
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Personnel
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getPhotoFile()
    {
        return $this->photoFile;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setPhotoFile(UploadedFile $file = null)
    {
        if($file !== NULL) {
            $this->setUpdatedAt(new \DateTime());
        }

        $this->photoFile = $file;
        // check if we have an old image path
        if (isset($this->photo)) {
            // store the old name to delete after the update
            $this->tempPhoto = $this->photo;
        }
    }

    private function getRandomFileName()
    {
        return md5(uniqid(mt_rand(), true));
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getPhotoFile()) {
            // do whatever you want to generate a unique name
            $this->photo = $this->getRandomFileName() . '.' . $this->getPhotoFile()->guessExtension();
        }

    }


    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null !== $this->getPhotoFile()) {
            $this->getPhotoFile()->move(
                $this->getUploadRootDir(),
                $this->photo
            );
            // check if we have an old image
            if (isset($this->tempPhoto)) {
                // delete the old image
                @unlink($this->getUploadRootDir() . '/' . $this->tempPhoto);
                // clear the temp image path
                $this->tempPhoto = null;
            }
            $this->photoFile = null;
        }
    }

    public function getUploadRootDir()
    {
        return WEB_PATH . DIRECTORY_SEPARATOR . $this->getUploadDir();
    }

    public function getUploadDir()
    {
        return 'uploads/serviceman';
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            @unlink($file);
        }
    }

    public function removePhotoFile($file)
    {
        $file_path = $this->getUploadRootDir().'/'.$file;

        if(file_exists($file_path)) unlink($file_path);
    }

    public function getAbsolutePath()
    {
        return null === $this->photo
            ? null
            : $this->getUploadRootDir() . '/' . $this->photo;
    }

    public function getWebPhotoPath()
    {
        return null === $this->photo ? null : $this->getUploadDir() . '/' . $this->photo;
    }

//    /**
//     * @return mixed
//     */
//    public function getServingType()
//    {
//        return $this->servingType;
//    }
//
//    /**
//     * @param mixed $servingType
//     */
//    public function setServingType($servingType)
//    {
//        $this->servingType = $servingType;
//    }

    /**
     * @return mixed
     */
    public function getPermanentDistrict()
    {
        return $this->permanentDistrict;
    }

    /**
     * @param mixed $permanentDistrict
     */
    public function setPermanentDistrict($permanentDistrict)
    {
        $this->permanentDistrict = $permanentDistrict;
    }

    /**
     * @return mixed
     */
    public function getPermanentUpazila()
    {
        return $this->permanentUpazila;
    }

    /**
     * @param mixed $permanentUpazila
     */
    public function setPermanentUpazila($permanentUpazila)
    {
        $this->permanentUpazila = $permanentUpazila;
    }

    /**
     * @return string
     */
    public function getPermanentPostOffice()
    {
        return $this->permanentPostOffice;
    }

    /**
     * @param string $permanentPostOffice
     */
    public function setPermanentPostOffice($permanentPostOffice)
    {
        $this->permanentPostOffice = $permanentPostOffice;
    }

    /**
     * Get permanentPostCode
     *
     * @return string
     */
    public function getPermanentPostCode()
    {
        return $this->permanentPostCode;
    }

    /**
     * Set permanentPostCode
     *
     * @param string $permanentPostCode
     *
     */
    public function setPermanentPostCode($permanentPostCode)
    {
        $this->permanentPostCode = $permanentPostCode;
    }

    /**
     * @return string
     */
    public function getPermanentVillage()
    {
        return $this->permanentVillage;
    }

    /**
     * @param string $permanentVillage
     */
    public function setPermanentVillage($permanentVillage)
    {
        $this->permanentVillage = $permanentVillage;
    }

    /**
     * @return mixed
     */
    public function getTempPhoto()
    {
        return $this->tempPhoto;
    }

    /**
     * @param mixed $tempPhoto
     */
    public function setTempPhoto($tempPhoto)
    {
        $this->tempPhoto = $tempPhoto;
    }

    /**
     * @return string
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * @param string $nationality
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;
    }

    /**
     * @param BaseFamilyInformation $family
     * @return $this
     */
    public function addFamily(BaseFamilyInformation $family)
    {
        if (!$this->getFamilies()->contains($family)) {
            $family->setServiceman($this);
            $this->getFamilies()->add($family);
        }

        return $this;
    }

    /**
     * @param BaseFamilyInformation $family
     * @return $this
     */
    public function removeFamily(BaseFamilyInformation $family)
    {
        if ($this->getFamilies()->contains($family)) {
            $this->getFamilies()->removeElement($family);
        }

        return $this;
    }

    /**
     * @param BaseEmploymentInformation $employmentInformation
     * @return $this
     */
    public function addEmploymentInformation(BaseEmploymentInformation $employmentInformation)
    {
        if (!$this->getEmploymentInformations()->contains($employmentInformation)) {
            $employmentInformation->setServiceman($this);
            $this->getEmploymentInformations()->add($employmentInformation);
        }

        return $this;
    }

    /**
     * @param BaseEmploymentInformation $employmentInformation
     * @return $this
     */
    public function removeEmploymentInformation(BaseEmploymentInformation $employmentInformation)
    {
        if ($this->getEmploymentInformations()->contains($employmentInformation)) {
            $this->getEmploymentInformations()->removeElement($employmentInformation);
        }

        return $this;
    }

    /**
     * @param BaseSpecialDisease $specialDisease
     * @return $this
     */
    public function addSpecialDisease(BaseSpecialDisease $specialDisease)
    {
        if (!$this->getSpecialDiseases()->contains($specialDisease)) {
            $specialDisease->setServiceman($this);
            $this->getSpecialDiseases()->add($specialDisease);
        }

        return $this;
    }

    /**
     * @param BaseSpecialDisease $specialDisease
     * @return $this
     */
    public function removeSpecialDisease(BaseSpecialDisease $specialDisease)
    {
        if ($this->getSpecialDiseases()->contains($specialDisease)) {
            $this->getSpecialDiseases()->removeElement($specialDisease);
        }

        return $this;
    }

    /**
     * @param BaseCareerInformation $career
     * @return $this
     */
    public function addCareer(BaseCareerInformation $career)
    {
        if (!$this->getCareers()->contains($career)) {
            $career->setServiceman($this);
            $this->getCareers()->add($career);
        }

        return $this;
    }

    /**
     * @param BaseCareerInformation $career
     * @return $this
     */
    public function removeCareer(BaseCareerInformation $career)
    {
        if ($this->getCareers()->contains($career)) {
            $this->getCareers()->removeElement($career);
        }

        return $this;
    }

    /**
     * @param BaseEducationalInformation $education
     * @return $this
     */
    public function addEducation(BaseEducationalInformation $education)
    {
        if (!$this->getEducations()->contains($education)) {
            $education->setServiceman($this);
            $this->getEducations()->add($education);
        }

        return $this;
    }

    /**
     * @param BaseEducationalInformation $education
     * @return $this
     */
    public function removeEducation(BaseEducationalInformation $education)
    {
        if ($this->getEducations()->contains($education)) {
            $this->getEducations()->removeElement($education);
        }

        return $this;
    }

    /**
     * @param BaseServiceInformation $serviceInfo
     * @return $this
     */
    public function addServicesInfo(BaseServiceInformation $serviceInfo)
    {
        if (!$this->getServicesInfo()->contains($serviceInfo)) {
            $serviceInfo->setServiceman($this);
            $this->getServicesInfo()->add($serviceInfo);
        }

        return $this;
    }

    /**
     * @param BaseServiceInformation $serviceInfo
     * @return $this
     */
    public function removeServicesInfo(BaseServiceInformation $serviceInfo)
    {
        if ($this->getServicesInfo()->contains($serviceInfo)) {
            $this->getServicesInfo()->removeElement($serviceInfo);
        }

        return $this;
    }


    /**
     * @param BaseTrainingInformation $training
     * @return $this
     */
    public function addTraining(BaseTrainingInformation $training)
    {
        if (!$this->getTrainings()->contains($training)) {
            $training->setServiceman($this);
            $this->getTrainings()->add($training);
        }

        return $this;
    }

    /**
     * @param BaseTrainingInformation $training
     * @return $this
     */
    public function removeTraining(BaseTrainingInformation $training)
    {
        if ($this->getTrainings()->contains($training)) {
            $this->getTrainings()->removeElement($training);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getFamilies()
    {
        return $this->families;
    }

    /**
     * @return ArrayCollection
     */
    public function getEmploymentInformations()
    {
        return $this->employmentInformations;
    }

    /**
     * @return ArrayCollection
     */
    public function getSpecialDiseases()
    {
        return $this->specialDiseases;
    }

    /**
     * @return ArrayCollection
     */
    public function getCareers()
    {
        return $this->careers;
    }

    /**
     * @return ArrayCollection
     */
    public function getEducations()
    {
        return $this->educations;
    }

    /**
     * @return ArrayCollection
     */
    public function getServicesInfo()
    {
        return $this->servicesInfo;
    }

    /**
     * @return ArrayCollection
     */
    public function getTrainings()
    {
        return $this->trainings;
    }

    /**
     * @return string
     */
    public function getFathersName()
    {
        return $this->fathersName;
    }

    /**
     * @param string $fathersName
     */
    public function setFathersName($fathersName)
    {
        $this->fathersName = $fathersName;
    }

    /**
     * @return string
     */
    public function getMothersName()
    {
        return $this->mothersName;
    }

    /**
     * @param string $mothersName
     */
    public function setMothersName($mothersName)
    {
        $this->mothersName = $mothersName;
    }

    /**
     * @return mixed
     */
    public function getFathersOccupation()
    {
        return $this->fathersOccupation;
    }

    /**
     * @param mixed $fathersOccupation
     */
    public function setFathersOccupation($fathersOccupation)
    {
        $this->fathersOccupation = $fathersOccupation;
    }

    /**
     * @return string
     */
    public function getMothersOccupation()
    {
        return $this->mothersOccupation;
    }

    /**
     * @param string $mothersOccupation
     */
    public function setMothersOccupation($mothersOccupation)
    {
        $this->mothersOccupation = $mothersOccupation;
    }

    /**
     * @return string
     */
    public function getFathersAddress()
    {
        return $this->fathersAddress;
    }

    /**
     * @param string $fathersAddress
     */
    public function setFathersAddress($fathersAddress)
    {
        $this->fathersAddress = $fathersAddress;
    }

    /**
     * @return string
     */
    public function getMothersAddress()
    {
        return $this->mothersAddress;
    }

    /**
     * @param string $mothersAddress
     */
    public function setMothersAddress($mothersAddress)
    {
        $this->mothersAddress = $mothersAddress;
    }

    /**
     * @return string
     */
    public function getFathersMobileNumber()
    {
        return $this->fathersMobileNumber;
    }

    /**
     * @param string $fathersMobileNumber
     */
    public function setFathersMobileNumber($fathersMobileNumber)
    {
        $this->fathersMobileNumber = $fathersMobileNumber;
    }

    /**
     * @return string
     */
    public function getMothersMobileNumber()
    {
        return $this->mothersMobileNumber;
    }

    /**
     * @param string $mothersMobileNumber
     */
    public function setMothersMobileNumber($mothersMobileNumber)
    {
        $this->mothersMobileNumber = $mothersMobileNumber;
    }

    /**
     * @return string
     */
    public function getFathersNokPercentage()
    {
        return $this->fathersNokPercentage;
    }

    /**
     * @param string $fathersNokPercentage
     */
    public function setFathersNokPercentage($fathersNokPercentage)
    {
        $this->fathersNokPercentage = $fathersNokPercentage;
    }

    /**
     * @return string
     */
    public function getMothersNokPercentage()
    {
        return $this->mothersNokPercentage;
    }

    /**
     * @param string $mothersNokPercentage
     */
    public function setMothersNokPercentage($mothersNokPercentage)
    {
        $this->mothersNokPercentage = $mothersNokPercentage;
    }

    /**
     * @return mixed
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * @param mixed $district
     */
    public function setDistrict($district)
    {
        $this->district = $district;
    }

    /**
     * @return mixed
     */
    public function getUpazila()
    {
        return $this->upazila;
    }

    /**
     * @param mixed $upazila
     */
    public function setUpazila($upazila)
    {
        $this->upazila = $upazila;
    }

    /**
     * @return string
     */
    public function getPostOffice()
    {
        return $this->postOffice;
    }

    /**
     * @param string $postOffice
     */
    public function setPostOffice($postOffice)
    {
        $this->postOffice = $postOffice;
    }

    /**
     * @return string
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * @param string $postCode
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;
    }

    /**
     * @return mixed
     */
    public function getVillage()
    {
        return $this->village;
    }

    /**
     * @param mixed $village
     */
    public function setVillage($village)
    {
        $this->village = $village;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return \DateTime
     */
    public function getDateOfWedding()
    {
        return $this->dateOfWedding;
    }

    /**
     * @param \DateTime $dateOfWedding
     */
    public function setDateOfWedding($dateOfWedding)
    {
        $this->dateOfWedding = $dateOfWedding;
    }
}

