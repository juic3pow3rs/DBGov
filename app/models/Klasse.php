<?php

namespace Vokuro\Models;

class Klasse extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=3, nullable=false)
     */
    protected $id;

    /**
     *
     * @var string
     * @Column(type="string", length=3, nullable=false)
     */
    protected $name;

    /**
     *
     * @var string
     * @Column(type="string", length=5, nullable=false)
     */
    protected $jahrgang;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    protected $liste_schueler;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    protected $liste_schueler_ano;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    protected $liste_lehrer;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    protected $liste_lehrer_ano;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field jahrgang
     *
     * @param string $jahrgang
     * @return $this
     */
    public function setJahrgang($jahrgang)
    {
        $this->jahrgang = $jahrgang;

        return $this;
    }

    /**
     * Method to set the value of field liste_schueler
     *
     * @param string $liste_schueler
     * @return $this
     */
    public function setListeSchueler($liste_schueler)
    {
        $this->liste_schueler = $liste_schueler;

        return $this;
    }

    /**
     * Method to set the value of field liste_schueler_ano
     *
     * @param string $liste_schueler_ano
     * @return $this
     */
    public function setListeSchuelerAno($liste_schueler_ano)
    {
        $this->liste_schueler_ano = $liste_schueler_ano;

        return $this;
    }

    /**
     * Method to set the value of field liste_lehrer
     *
     * @param string $liste_lehrer
     * @return $this
     */
    public function setListeLehrer($liste_lehrer)
    {
        $this->liste_lehrer = $liste_lehrer;

        return $this;
    }

    /**
     * Method to set the value of field liste_lehrer_ano
     *
     * @param string $liste_lehrer_ano
     * @return $this
     */
    public function setListeLehrerAno($liste_lehrer_ano)
    {
        $this->liste_lehrer_ano = $liste_lehrer_ano;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field jahrgang
     *
     * @return string
     */
    public function getJahrgang()
    {
        return $this->jahrgang;
    }

    /**
     * Returns the value of field liste_schueler
     *
     * @return string
     */
    public function getListeSchueler()
    {
        return $this->liste_schueler;
    }

    /**
     * Returns the value of field liste_schueler_ano
     *
     * @return string
     */
    public function getListeSchuelerAno()
    {
        return $this->liste_schueler_ano;
    }

    /**
     * Returns the value of field liste_lehrer
     *
     * @return string
     */
    public function getListeLehrer()
    {
        return $this->liste_lehrer;
    }

    /**
     * Returns the value of field liste_lehrer_ano
     *
     * @return string
     */
    public function getListeLehrerAno()
    {
        return $this->liste_lehrer_ano;
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'klasse';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Klasse[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Klasse
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
