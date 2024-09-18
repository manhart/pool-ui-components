<?php
/*
 * This file is part of the g7system.local project.
 *
 * @copyright Copyright (c) 2024 GROUP7 AG
 * @authors GROUP7 Software/Web Developer Team
 *
 * GUI_Radiobutton.php created on 10.08.24, 13:15.
 */

namespace GUI_Radiobutton;

use GUI_InputElement\GUI_InputElement;
use pool\classes\Core\Input\Input;

/**
 * Class GUI_Radiobutton
 *
 * @package pool\guis\GUI_Radiobutton
 * @since 2004/07/07
 */
class GUI_Radiobutton extends GUI_InputElement
{
    public function init(?int $superglobals = Input::EMPTY): void
    {
        $this->Defaults->addVar('type', 'radio');
        $this->Defaults->addVar('label');
        parent::init(Input::GET | Input::POST);
    }

    public function loadFiles(): void
    {
        $file = $this->Weblication->findTemplate('tpl_radiobutton.html', $this->getClassName(), true);
        $this->Template->setFilePath('stdout', $file);
    }

    protected function prepare(): void
    {
        $Template = &$this->Template;
        if ($this->Input->getVar('label') != '') {
            $Template->newBlock('Label');
            $Template->setVar('label', $this->Input->getVar('label'));
            $Template->setVar('id', $this->Input->getVar('id'));
            $Template->leaveBlock();
        }
        parent::prepare();
    }
}