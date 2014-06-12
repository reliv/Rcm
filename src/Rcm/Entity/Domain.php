<?php
/**
 * Domain Name Database Entity
 *
 * This is a Doctrine 2 definition file for Domain Name Objects.  This file
 * is used for any module that needs to know Domain Name information.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Validator\Hostname;
use Zend\Validator\ValidatorInterface;
use Rcm\Exception\InvalidArgumentException;

/**
 * Country Database Entity
 *
 * This object contains registered domains names and also will note which domain
 * name is the primary domain.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 * @ORM\Entity (repositoryClass="Rcm\Repository\Domain")
 * @ORM\Table(name="rcm_domains")
 */
class Domain
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $domainId;

    /**
     * @var string Valid Domain Name
     *
     * @ORM\Column(type="string")
     */
    protected $domain;

    /**
     * @var \Rcm\Entity\Domain Site Object that the domain name belongs
     *                                to.
     *
     * @ORM\ManyToOne(targetEntity="Domain", inversedBy="additionalDomains")
     * @ORM\JoinColumn(
     *     name="primaryId",
     *     referencedColumnName="domainId",
     *     onDelete="CASCADE"
     * )
     */
    protected $primaryDomain;

    /**
     * @var ArrayCollection Array of Domain Objects that represent
     *                      all the additional domains that belong
     *                      to this one
     *
     * @ORM\OneToMany(targetEntity="Domain", mappedBy="primaryDomain")
     */
    protected $additionalDomains;

    /**
     * @var \Rcm\Entity\Language This domain's default language.  Needed for
     *                           translations by some plugins.
     *
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumn(
     *     name="defaultLanguageId",
     *     referencedColumnName="languageId",
     *     onDelete="SET NULL"
     * )
     */
    protected $defaultLanguage;

    /**
     * @var \Zend\Validator\ValidatorInterface
     */
    protected $domainValidator;

    /**
     * Constructor for Domain Entity.
     */
    public function __construct()
    {
        $this->additionalDomains = new ArrayCollection();
        $this->domainValidator = new Hostname(
            array(
                'allow' => Hostname::ALLOW_LOCAL | Hostname::ALLOW_IP
            )
        );
    }

    /**
     * Overwrite the default validator
     *
     * @param ValidatorInterface $domainValidator Domain Validator
     *
     * @return void
     */
    public function setDomainValidator(ValidatorInterface $domainValidator)
    {
        $this->domainValidator = $domainValidator;
    }

    /**
     * Check to see if this domain is the primary domain name.
     *
     * @return bool
     */
    public function isPrimary()
    {
        if (empty($this->primaryDomain)) {
            return true;
        }

        return false;
    }

    /**
     * Get the Unique ID of the Domain.
     *
     * @return int Unique Domain ID
     */
    public function getDomainId()
    {
        return $this->domainId;
    }

    /**
     * Set the ID of the Domain.  This was added for unit testing and should
     * not be used by calling scripts.  Instead please persist the object
     * with Doctrine and allow Doctrine to set this on it's own,
     *
     * @param int $domainId Unique Domain ID
     *
     * @return void
     */
    public function setDomainId($domainId)
    {
        $this->domainId = $domainId;
    }

    /**
     * Get the actual domain name.
     *
     * @return string
     */
    public function getDomainName()
    {
        return $this->domain;
    }

    /**
     * Set the domain name
     *
     * @param string $domain Domain name of object
     *
     * @throws \Rcm\Exception\InvalidArgumentException If domain name
     *                                                         is invalid
     *
     * @return void
     */
    public function setDomainName($domain)
    {
        if (!$this->domainValidator->isValid($domain)) {
            throw new InvalidArgumentException(
                'Domain name is invalid'
            );
        }

        $this->domain = $domain;
    }

    /**
     * Return the Primary Domain.
     *
     * @return \Rcm\Entity\Domain
     */
    public function getPrimary()
    {
        return $this->primaryDomain;
    }

    /**
     * Set the Primary Domain.
     *
     * @param Domain $primaryDomain Primary Domain Entity
     *
     * @return void
     */
    public function setPrimary(Domain $primaryDomain)
    {
        $this->primaryDomain = $primaryDomain;
    }

    /**
     * Get all the additional domains for domain.
     *
     * @return ArrayCollection Return an Array of Domain Entities.
     */
    public function getAdditionalDomains()
    {
        return $this->additionalDomains;
    }

    /**
     * Add an additional domain to primary
     *
     * @param \Rcm\Entity\Domain $domain Domain Entity
     *
     * @return void
     */
    public function setAdditionalDomain(Domain $domain)
    {
        $this->additionalDomains->add($domain);
    }

    /**
     * Sets the DefaultLanguage property
     *
     * @param \Rcm\Entity\Language $defaultLanguage DefaultLanguage
     *
     * @return null
     */
    public function setDefaultLanguage(Language $defaultLanguage)
    {
        $this->defaultLanguage = $defaultLanguage;
    }

    /**
     * Gets the DefaultLanguage property
     *
     * @return \Rcm\Entity\Language defaultLanguage DefaultLanguage
     *
     */
    public function getDefaultLanguage()
    {
        return $this->defaultLanguage;
    }
}