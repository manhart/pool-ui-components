<?php
/*
 * This file is part of the g7system.local project.
 *
 * @copyright Copyright (c) 2024 GROUP7 AG
 * @authors GROUP7 Software/Web Developer Team
 *
 * GUI_Checkbox.php created on 10.08.24, 13:17.
 */

namespace GUI_Checkbox;

use GUI_InputElement\GUI_InputElement;
use pool\classes\Core\Input\Input;

/**
 * Class GUI_Checkbox
 *
 * @package pool\guis\GUI_Checkbox
 * @since 2004/07/07
 */
class GUI_Checkbox extends GUI_InputElement
{
    function init(?int $superglobals = Input::EMPTY): void
    {
        $this->Defaults->addVar('type', 'checkbox');
        $this->Defaults->addVar('array', 0);
        $this->Defaults->addVar('label');
        parent::init(Input::GET | Input::POST);
    }

    public function loadFiles(): void
    {
        $file = $this->Weblication->findTemplate('tpl_checkbox.html', $this->getClassName(), true);
        $this->Template->setFilePath('stdout', $file);
    }

    protected function prepare(): void
    {
        if ($this->Input->getVar('array') == 1) {
            $this->Input->setVar('name', $this->Input->getVar('name').'[]');
        }
        if ($this->Input->getVar('label') != '') {
            $this->Template->newBlock('Label');
            $this->Template->setVar('label', $this->Input->getVar('label'));
            $this->Template->setVar('ID', $this->Input->getVar('id'));
            $this->Template->leaveBlock();
        }

        $this->prepareName();

        if ($this->Input->getVar('value') == $this->Input->getVar($this->Input->getVar('name'))) {
            $this->Input->setVar('checked', 1);
        }

        parent::prepare();
    }
}