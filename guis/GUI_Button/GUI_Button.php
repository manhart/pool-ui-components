<?php
/*
 * This file is part of the g7system.local project.
 *
 * @copyright Copyright (c) 2024 GROUP7 AG
 * @authors GROUP7 Software/Web Developer Team
 *
 * GUI_Button.php created on 10.08.24, 13:16.
 */

namespace GUI_Button;

use GUI_HTMLElement\GUI_HTMLElement;
use pool\classes\Core\Input\Input;

/**
 * Class GUI_Button
 *
 * @package pool\guis\GUI_Button
 * @since 2004/07/07
 */
class GUI_Button extends GUI_HTMLElement
{
    /**
     * @param int|null $superglobals Superglobals I_GET, I_POST, I_REQUEST....
     */
    public function init(?int $superglobals = Input::EMPTY): void
    {
        $this->Defaults->addVars([
                'name' => $this->getName(),
                'type' => 'button', // button, reset, submit
                'value' => null,
                'content' => '', // = Caption oder Image
            ],
        );
        parent::init($superglobals);
    }

    public function loadFiles(): static
    {
        $file = $this->Weblication->findTemplate('tpl_button.html', __CLASS__, true);
        $this->Template->setFilePath('stdout', $file);
        return $this;
    }

    protected function prepare(): void
    {
        parent::prepare();

        $id = $this->id;
        $name = $this->Input->getVar('name');
        $content = $this->Input->getVar('content');
        $type = $this->Input->getVar('type');

        // id mit name (sowie umgekehrt) abgleichen
        if ($name != $this->Defaults->getVar('name') && $id == $this->getName()) {
            $id = $name;
        }
        if ($id != $this->Defaults->getVar('name') && $name == $this->getName()) {
            $name = $id;
        }

        #### leere Attribute
        $emptyattributes = '';
        if ($this->Input->getVar('autofocus')) {
            $emptyattributes .= 'autofocus';
        }
        if ($this->Input->getVar('disabled')) {
            if ($emptyattributes != '') $emptyattributes .= ' ';
            $emptyattributes .= 'disabled';
        }
        if ($this->Input->getVar('formnovalidate')) {
            if ($emptyattributes != '') $emptyattributes .= ' ';
            $emptyattributes .= 'formnovalidate';
        }

        #### Attribute
        $attributes = $this->attributes;
        if ($form = $this->Input->getVar('form')) {
            $attributes .= ' ';
            $attributes .= 'form="'.$form.'"';
        }
        if ($formaction = $this->Input->getVar('formaction')) {
            $attributes .= ' ';
            $attributes .= 'formaction="'.$formaction.'"';
        }
        if ($formenctype = $this->Input->getVar('formenctype')) {
            $attributes .= ' ';
            $attributes .= 'formenctype="'.$formenctype.'"';
        }
        if ($formmethod = $this->Input->getVar('formmethod')) {
            $attributes .= ' ';
            $attributes .= 'formmethod="'.$formmethod.'"';
        }
        if ($formtarget = $this->Input->getVar('formtarget')) {
            $attributes .= ' ';
            $attributes .= 'formtarget="'.$formtarget.'"';
        }

        #### Set Template wildcards
        $this->Template->setVars(
            [
                'id' => $id,
                'NAME' => $name,
                'TYPE' => $type,
                'ATTRIBUTES' => ltrim($attributes),
                'EMPTYATTRIBUTES' => $emptyattributes,
                'CONTENT' => $content,
            ],
        );
    }
}