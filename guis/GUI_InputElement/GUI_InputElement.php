<?php
/*
 * This file is part of the g7system.local project.
 *
 * @copyright Copyright (c) 2024 GROUP7 AG
 * @authors GROUP7 Software/Web Developer Team
 *
 * GUI_InputElement.php created on 10.08.24, 13:24.
 */

namespace GUI_InputElement;

use GUI_HTMLElement\GUI_HTMLElement;
use pool\classes\Core\Input\Input;
use pool\classes\Core\Input\Session;
use Template;

/**
 * GUI_InputElement
 * Grundelemente von Input (<input type=text>).
 *
 * @package pool\guis\GUI_InputElement
 * @since 2004/07/07
 */
class GUI_InputElement extends GUI_HTMLElement
{
    /**
     * Initialisiert Standardwerte:
     * TODO Parameter
     * Ueberschreiben moeglich durch Variablen von INPUT_GET und INPUT_POST.
     */
    public function init(?int $superglobals = Input::EMPTY): void
    {
        $this->Defaults->addVars([
                'name' => $this->getName(),

                'type' => '',
                'value' => '',
                'defaultvalue' => '',
                'save' => '',
                'use_session' => 0,
                'session_var' => $this->getName(),

                'accept' => null,
                'accesskey' => null,
                'align' => null,
                'alt' => null,
                'checked' => null,
                'datafld' => null,
                'datasrc' => null,
                'dataformatas' => null,
                'disabled' => null,
                'ismap' => null,
                'maxlength' => null,
                'readonly' => null,
                'size' => null,
                'src' => null,
                'tabindex' => null,
                'usemap' => null,
                'placeholder' => null,
            ]
        );

        parent::init($superglobals);
    }

    /**
     * @return void
     */
    protected function prepare(): void
    {
        parent::prepare();

        $session_variable = $this->Input->getVar('session_var');

        // Namensabgleich
        $this->prepareName();

        $name = $this->Input->getVar('name');
        $id = $this->Input->getVar('id');

        #### leere Attribute
        $emptyattributes = '';
        $checked = $this->Input->getVar('checked');
        if($checked) {
            $emptyattributes .= 'checked';
        }
        $disabled = $this->Input->getVar('disabled');
        if($disabled) {
            $emptyattributes .= ' ';
            $emptyattributes .= 'disabled';
        }
        $ismap = $this->Input->getVar('ismap');
        if($ismap) {
            $emptyattributes .= ' ';
            $emptyattributes .= 'ismap';
        }
        $readonly = $this->Input->getVar('readonly');
        if($readonly) {
            $emptyattributes .= ' ';
            $emptyattributes .= 'readonly';
        }

        #### Attribute
        $attributes = $this->attributes;
        $accept = $this->Input->getVar('accept');
        if($accept) {
            $attributes .= ' ';
            $attributes .= 'accept="'.$accept.'"';
        }
        $accesskey = $this->Input->getVar('accesskey');
        if($accesskey) {
            $attributes .= ' ';
            $attributes .= 'accesskey="'.$accesskey.'"';
        }
        $align = $this->Input->getVar('align');
        if($align) {
            $attributes .= ' ';
            $attributes .= 'align="'.$align.'"';
        }
        $alt = $this->Input->getVar('alt');
        if($alt) {
            $attributes .= ' ';
            $attributes .= 'alt="'.$alt.'"';
        }
        $datafld = $this->Input->getVar('datafld');
        if($datafld) {
            $attributes .= ' ';
            $attributes .= 'datafld="'.$datafld.'"';
        }
        $datasrc = $this->Input->getVar('datasrc');
        if($datasrc) {
            $attributes .= ' ';
            $attributes .= 'datasrc="'.$datasrc.'"';
        }
        $dataformatas = $this->Input->getVar('dataformatas');
        if($dataformatas) {
            $attributes .= ' ';
            $attributes .= 'dataformatas="'.$dataformatas.'"';
        }
        $maxlength = $this->Input->getVar('maxlength');
        if($maxlength) {
            $attributes .= ' ';
            $attributes .= 'maxlength="'.$maxlength.'"';
        }
        $size = $this->Input->getVar('size');
        if($size) {
            $attributes .= ' ';
            $attributes .= 'size="'.$size.'"';
        }
        $tabindex = $this->Input->getVar('tabindex');
        if($tabindex) {
            $attributes .= ' ';
            $attributes .= 'tabindex="'.$tabindex.'"';
        }
        $type = $this->Input->getVar('type');
        if($type) {
            $attributes .= ' ';
            $attributes .= 'type="'.$type.'"';
        }
        $defaultValue = $this->Input->getVar('defaultvalue');
        if($defaultValue) {
            $attributes .= ' ';
            $attributes .= 'defaultvalue="'.$defaultValue.'"';
        }
        $placeholder = $this->Input->getVar('placeholder');
        if($placeholder) {
            $attributes .= ' ';
            $attributes .= 'placeholder="'.$placeholder.'"';
        }

        #### Set Template wildcards
        $this->Template->setVars(
            [
                'ID' => $id,
                'NAME' => $name,
                'ATTRIBUTES' => ltrim($attributes),
                'EMPTYATTRIBUTES' => ltrim($emptyattributes),
            ]
        );

        $valueByName = ($this->Input->getVar($name) != $name) ? $this->Input->getVar($name) : '';

        $buf_save = $this->Input->getVar('save');
        if($this->Session instanceof Session and $this->Input->getAsInt('use_session') == 1) {
            if(!empty($buf_save) and $this->Input->getAsInt($buf_save) == 1) {
                $this->Session->setVar($session_variable, $this->Input->getVar('value') == '' ? $valueByName : $this->Input->getVar('value'));
            }

            // Wert (value) ermitteln (session, object name, value, defaultvalue)
            $value = $this->Session->getVar($session_variable);
        }
        else {
            $value = $this->Input->getVar('value') != '' ? $this->Input->getVar('value') : $valueByName;
            if($value == '{'.$name.'}') {
                $value = '';
            }
        }
        if($value == '' or is_null($value)) {//  or (($Input -> getVar('value') == '') and $name == $Input -> getVar($name)) // fix
            $value = $this->Input->getVar('defaultvalue');
        }

        $this->Template->setVar('VALUE', $value, Template::CONVERT_HTMLSPECIALCHARS);
    }

    protected function prepareName(): void
    {
        $id = $this->id;
        $name = $this->Input->getVar('name');

        // id mit name (sowie umgekehrt) abgleichen
        if($name != $this->Defaults->getVar('name') and $id == $this->getName()) {
            $id = $name;
        }
        if($id != $this->Defaults->getVar('name') and $name == $this->getName()) {
            $name = $id;
        }
        $this->Input->setVars([
                'name' => $name,
                'id' => $id]
        );
    }
}