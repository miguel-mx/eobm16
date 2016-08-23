<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Registro
 *
 * @ORM\Table(name="registro")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RegistroRepository")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Registro
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=50)
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="paterno", type="string", length=50)
     * @Assert\NotBlank()
     */
    private $paterno;

    /**
     * @var string
     *
     * @ORM\Column(name="materno", type="string", length=50)
     * @Assert\NotBlank()
     */
    private $materno;

    /**
     * @var string
     *
     * @ORM\Column(name="sexo", type="string", length=2)
     */
    private $sexo;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=140)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=20)
     * @Assert\Type(type="digit")
     * @Assert\Length(
     *      min = 10,
     *      max = 15,
     *      minMessage = "El teléfono debe contener mínimo {{ limit }} caracteres",
     *      maxMessage = "El teléfono no puede se de más de {{ limit }} caracteres"
     * )
     */

    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="procedencia", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $procedencia;

    /**
     * @var string
     *
     * @ORM\Column(name="carrera", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $carrera;

    /**
     * @var string
     *
     * @ORM\Column(name="porcentaje", type="string", length=50, nullable=true)
     * @Assert\NotBlank(groups={"estudiantes"})
     */
    private $porcentaje;

    /**
     * @var string
     *
     * @ORM\Column(name="promedio", type="string", length=50, nullable=true)
     * @Assert\NotBlank(groups={"estudiantes"})
     */
    private $promedio;

    /**
     * @var string
     *
     * @ORM\Column(name="profesor", type="string", length=50, nullable=true)
     * @Assert\NotBlank(groups={"estudiantes"})
     */
    private $profesor;

    /**
     * @var string
     *
     * @ORM\Column(name="univprofesor", type="string", length=100, nullable=true)
     * @Assert\NotBlank(groups={"estudiantes"})
     */
    private $univprofesor;

    /**
     * @var string
     *
     * @ORM\Column(name="mailprofesor", type="string", length=50, nullable=true)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true)
     * @Assert\NotBlank(groups={"estudiantes"})
     */

    private $mailprofesor;


    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="eobm_historial", fileNameProperty="historialName", nullable=true)
     *
     * @Assert\File(
     *     maxSize = "2M",
     * uploadFormSizeErrorMessage = "El archivo debe ser menor a 2 MB",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Please upload a valid PDF"
     * )
     * @Assert\NotBlank(groups={"estudiantes"})
     *
     * @var File
     */
    private $historialFile;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $historialName;

    /**
     * @var string
     *
     * @ORM\Column(name="razones", type="string", length=1000, nullable=true)
     * @Assert\Length(
     *      max = 1000,
     *      maxMessage = "No se permiten más de {{ limit }} caracteres"
     * )
     */
    private $razones;

    /**
     * @var bool
     *
     * @ORM\Column(name="confirmado", type="boolean", nullable=true)
     */
    private $confirmado;

    /**
     * @var bool
     *
     * @ORM\Column(name="aceptado", type="boolean", nullable=true)
     */
    private $aceptado;

    /**
     * @var string
     *
     * @ORM\Column(name="eventos", type="string", length=1000, nullable=true)
     * @Assert\Length(
     *      max = 1000,
     *      maxMessage = "No se permiten más de {{ limit }} caracteres"
     * )
     */
    private $eventos;

    /**
     * @var string
     *
     * @ORM\Column(name="beca", type="string", length=50)
     * @Assert\NotBlank()
     */
    private $beca;

    /**
     * @var string
     *
     * @ORM\Column(name="comentarios", type="string", length=5000, nullable=true)
     * @Assert\Length(
     *      max = 3000,
     *      maxMessage = "No se permiten más de {{ limit }} caracteres"
     * )
     */
    private $comentarios;

    /**
     * @var string
     *
     * @ORM\Column(name="recomendacion", type="string", length=5000, nullable=true)
     * @Assert\Length(
     *      max = 3000,
     *      maxMessage = "No se permiten más de {{ limit }} caracteres"
     * )
     */
    private $recomendacion;

    /**
     * @var string
     *
     * @ORM\Column(name="charla", type="string", length=150, nullable=true)
     */
    private $charla;

    /**
     * @var string
     *
     * @ORM\Column(name="resumen", type="string", length=1000, nullable=true)
     * @Assert\Length(
     *      max = 1000,
     *      maxMessage = "No se permiten más de {{ limit }} caracteres"
     * )
     */
    private $resumen;

    /**
     * @Gedmo\Slug(fields={"nombre", "paterno", "materno"})
     * @ORM\Column(length=64, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $createdAt;

     public function __construct() {

        $this->setCreatedAt(new \DateTime());

        if ($this->getUpdatedAt() == null) {
            $this->setUpdatedAt(new \DateTime());
        }
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Form
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set paterno
     *
     * @param string $paterno
     * @return Form
     */
    public function setPaterno($paterno)
    {
        $this->paterno = $paterno;

        return $this;
    }

    /**
     * Get paterno
     *
     * @return string
     */
    public function getPaterno()
    {
        return $this->paterno;
    }

    /**
     * Set materno
     *
     * @param string $materno
     * @return Form
     */
    public function setMaterno($materno)
    {
        $this->materno = $materno;

        return $this;
    }

    /**
     * Get materno
     *
     * @return string
     */
    public function getMaterno()
    {
        return $this->materno;
    }

    /**
     * Set sexo
     *
     * @param string $sexo
     * @return Form
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return string
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return Form
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set tel
     *
     * @param string $tel
     * @return Form
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set procedencia
     *
     * @param string $procedencia
     * @return Form
     */
    public function setProcedencia($procedencia)
    {
        $this->procedencia = $procedencia;

        return $this;
    }

    /**
     * Get procedencia
     *
     * @return string
     */
    public function getProcedencia()
    {
        return $this->procedencia;
    }

    /**
     * Set carrera
     *
     * @param string $carrera
     * @return Form
     */
    public function setCarrera($carrera)
    {
        $this->carrera = $carrera;

        return $this;
    }

    /**
     * Get carrera
     *
     * @return string
     */
    public function getCarrera()
    {
        return $this->carrera;
    }

    /**
     * Set porcentaje
     *
     * @param string $porcentaje
     * @return Form
     */
    public function setPorcentaje($porcentaje)
    {
        $this->porcentaje = $porcentaje;

        return $this;
    }

    /**
     * Get porcentaje
     *
     * @return string
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * Set promedio
     *
     * @param string $promedio
     * @return Form
     */
    public function setPromedio($promedio)
    {
        $this->promedio = $promedio;

        return $this;
    }

    /**
     * Get promedio
     *
     * @return string
     */
    public function getPromedio()
    {
        return $this->promedio;
    }

    /**
     * Set profesor
     *
     * @param string $profesor
     * @return Form
     */
    public function setProfesor($profesor)
    {
        $this->profesor = $profesor;

        return $this;
    }

    /**
     * Get profesor
     *
     * @return string
     */
    public function getProfesor()
    {
        return $this->profesor;
    }

    /**
     * Set univprofesor
     *
     * @param string $univprofesor
     * @return Form
     */
    public function setUnivprofesor($univprofesor)
    {
        $this->univprofesor = $univprofesor;

        return $this;
    }

    /**
     * Get univprofesor
     *
     * @return string
     */
    public function getUnivprofesor()
    {
        return $this->univprofesor;
    }

    /**
     * Set mailprofesor
     *
     * @param string $mailprofesor
     * @return Form
     */
    public function setMailprofesor($mailprofesor)
    {
        $this->mailprofesor = $mailprofesor;

        return $this;
    }

    /**
     * Get mailprofesor
     *
     * @return string
     */
    public function getMailprofesor()
    {
        return $this->mailprofesor;
    }

    /**
     * Set eventos
     *
     * @param string $eventos
     * @return Form
     */
    public function setEventos($eventos)
    {
        $this->eventos = $eventos;

        return $this;
    }

    /**
     * Get eventos
     *
     * @return string
     */
    public function getEventos()
    {
        return $this->eventos;
    }

    /**
     * Set beca
     *
     * @param string $beca
     * @return Form
     */
    public function setBeca($beca)
    {
        $this->beca = $beca;

        return $this;
    }

    /**
     * Get beca
     *
     * @return string
     */
    public function getBeca()
    {
        return $this->beca;
    }


    /**
     * @return mixed
     */
    public function getHistorialName()
    {
        return $this->historialName;
    }

    /**
     * @param mixed $historialName
     */
    public function setHistorialName($historialName)
    {
        $this->historialName = $historialName;
    }


    /**
     * Set historialFile
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $historial
     */
    public function setHistorialFile(File $historial = null)
    {
        $this->historialFile = $historial;
        if ($historial) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->createdAt = new \DateTime('now');
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * Get historialFile
     *
     * @return File
     */
    public function getHistorialFile()
    {
        return $this->historialFile;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Form
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Form
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * @param string $comentarios
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;
    }

    /**
     * @return boolean
     */
    public function isConfirmado()
    {
        return $this->confirmado;
    }

    /**
     * @param boolean $confirmado
     */
    public function setConfirmado($confirmado)
    {
        $this->confirmado = $confirmado;
    }

    /**
     * @return boolean
     */
    public function isAceptado()
    {
        return $this->aceptado;
    }

    /**
     * @param boolean $aceptado
     */
    public function setAceptado($aceptado)
    {
        $this->aceptado = $aceptado;
    }

    /**
     * @return string
     */
    public function getRecomendacion()
    {
        return $this->recomendacion;
    }

    /**
     * @param string $recomendacion
     */
    public function setRecomendacion($recomendacion)
    {
        $this->recomendacion = $recomendacion;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set charla
     *
     * @param string $charla
     * @return Form
     */
    public function setCharla($charla)
    {
        $this->charla = $charla;

        return $this;
    }

    /**
     * Get charla
     *
     * @return string
     */
    public function getCharla()
    {
        return $this->charla;
    }

    /**
     * Set resumen
     *
     * @param string $resumen
     * @return Form
     */
    public function setResumen($resumen)
    {
        $this->resumen = $resumen;

        return $this;
    }

    /**
     * Get resumen
     *
     * @return string
     */
    public function getResumen()
    {
        return $this->resumen;
    }

    /**
     * Set razones
     *
     * @param string $razones
     * @return Form
     */
    public function setRazones($razones)
    {
        $this->razones = $razones;

        return $this;
    }

    /**
     * Get razones
     *
     * @return string
     */
    public function getRazones()
    {
        return $this->razones;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateModifiedDatetime() {

        $this->setUpdatedAt(new \DateTime());
    }


}
