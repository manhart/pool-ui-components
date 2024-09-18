<?php
/*
 * This file is part of the g7system.local project.
 *
 * @copyright Copyright (c) 2024 GROUP7 AG
 * @authors GROUP7 Software/Web Developer Team
 *
 * GUI_DBLookupLabel.php created on 10.08.24, 13:16.
 */

namespace GUI_DBLookupLabel;

use GUI_Label\GUI_Label;
use pool\classes\Core\Input\Input;
use pool\classes\Database\DAO;

/**
 * Class GUI_DBLookupLabel
 *
 * @package pool\guis\GUI_DBLookupLabel
 * @since 2004/02/18
 */
class GUI_DBLookupLabel extends GUI_Label
{
    /**
     * GUI_DBLookupLabel::init()
     * Initialisiert Standardwerte:
     * tabledefine        = ''    Tabellendefinition (siehe database.inc.php)
     * id                = 0        IDs (bei zusammengesetzten Primaerschluessel werden die IDs mit ; getrennt)
     * key                = ''    Keys (bei zusammengesetzten Primaerschluessel werden die Keys mit ; getrennt)
     * autoload_fields    = 1        1 laedt automatisch alle Felder, 0 nicht
     * pk                = ''    Primaerschluessel (mehrere Spaltennamen werden mit ; getrennt)
     * columns            = ''    Auszulesende Spalten (Spaltennamen werden mit ; getrennt)
     *
     * @access public
     **/
    public function init(?int $superglobals = Input::EMPTY): void
    {
        $this->Defaults->addVar('tabledefine');
        $this->Defaults->addVar('autoload_fields', 1);
        $this->Defaults->addVar('datafield');
        $this->Defaults->addVar('keyfield');
        $this->Defaults->addVar('keyvalue');

        parent::init($superglobals);
    }

    /**
     * Liest Daten aus der Datenbank und legt bei Erfolg den Wert des Feldes im Input ab.
     * Anschliessend wird der Parent GUI_Edit aufgerufen und setzt die Werte fuer das Eingabefeld.
     */
    protected function prepare(): void
    {
        if ($this->Input->getVar('keyvalue') != '') {
            if ($this->Input->getVar('tabledefine')) {
                $DAO = DAO::createDAO($this->Input->getVar('tabledefine'));
                $ResultSet = $DAO->get($this->Input->getVar('keyvalue'), $this->Input->getVar('keyfield'));
                if ($ResultSet->count() == 1) {
                    $datavalue = $ResultSet->getValue($this->Input->getVar('datafield'));
                    $this->Input->setVar('content', $datavalue);
                }
            }
        }
        parent::prepare();
    }
}