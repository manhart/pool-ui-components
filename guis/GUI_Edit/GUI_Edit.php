<?php
/*
 * This file is part of the g7system.local project.
 *
 * @copyright Copyright (c) 2024 GROUP7 AG
 * @authors GROUP7 Software/Web Developer Team
 *
 * GUI_Edit.php created on 10.08.24, 13:16.
 */

namespace GUI_Edit;

use Configurable;
use GUI_InputElement\GUI_InputElement;
use pool\classes\Core\Input\Input;

/**
 * Class GUI_Edit
 *
 * @package pool\guis\GUI_Edit
 * @since 2004/07/07
 */
class GUI_Edit extends GUI_InputElement
{
    use Configurable;

    //    protected string $storageType = 'JSON';
    //
    //    use \pool\classes\Configurable;
    //
    private array $inspectorProperties = [
        'placeholder' => [
            'attribute' => 'placeholder',
            'type' => 'string',
            'value' => 'placeholder',
            'element' => 'input',
            'inputType' => 'text',
            'caption' => 'Platzhalter',
            'configurable' => true,
        ],
    ];

    //
    //    private array $poolOptions = [];

    /**
     * Initialisiert Standardwerte:
     * Ueberschreiben moeglich ueber GET und POST.
     * Parameter:
     * - type Typ ist fest "text" (bitte diesen Parameter unberuehrt belassen!)
     * - size Bestimmt die Anzeigebreite des Elements (Standard: 20)
     *
     * @access public
     **/
    function init(?int $superglobals = Input::GET | Input::POST): void
    {
        // $this->Defaults->addVar('placeholder', 'hirsch');
        $this->Defaults->addVars([
                'type' => 'text',
                'size' => 20,
            ],
        );

        parent::init($superglobals);
    }

    //    public function hasConfiguration()
    //    {
    //        // TODO: Implement hasConfiguration() method.
    //    }
    //
    //    public function saveConfiguration()
    //    {
    //        // TODO: Implement saveConfiguration() method.
    //    }
    //
    //    public function loadConfiguration()
    //    {
    //        // TODO: Implement loadConfiguration() method.
    //    }

    /**
     * Laedt Template "tpl_edit.html". Ist im projekteigenen Skinordner ueberschreibbar!
     */
    public function loadFiles(): static
    {
        $file = $this->Weblication->findTemplate('tpl_edit.html', __CLASS__, true);
        $this->Template->setFilePath('stdout', $file);
        return $this;
    }
    /**
     * Provisioning data before preparing module and there children.
     */
    //    public function provision()
    //    {
    //        $data = $this->Input->getData();
    //        unset(
    //            $data['moduleName'],
    //            $data['ModuleName'],
    //            $data['modulename'],
    //            $data['framework'],
    //            $data['render']
    //        );
    //
    //        $this->setConfiguration($data);

    //        $config = [];
    //        $fileName = $this->getFixedParam('loadConfigFromJSON');
    //        if($fileName) {
    //            $config = $this->loadConfigFromJSON($fileName);
    //        }
    //        $this->setConfiguration($config);
    //    }

    //    public function loadConfigFromJSON($fileName)
    //    {
    //        $file = DIR_DATA_ROOT.'/ModuleConfiguratorTemp/'.$fileName;
    //        $json = file_get_contents($file);
    //
    //        $config = json_decode($json, JSON_OBJECT_AS_ARRAY);
    //        if(json_last_error() != JSON_ERROR_NONE) {
    //            return [];
    //        }
    //        return $config;
    //    }

    protected function prepare(): void
    {
        //        echo 'placeholder: '.$this->getVar('placeholder');
        //        $this->setVar($this->options);
        parent::prepare();
    }

    public function getInspectorProperties(): array
    {
        return $this->inspectorProperties + $this->getDefaultInspectorProperties();
    }
}