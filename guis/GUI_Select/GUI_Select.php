<?php
/*
 * This file is part of the g7system.local project.
 *
 * @copyright Copyright (c) 2024 GROUP7 AG
 * @authors GROUP7 Software/Web Developer Team
 *
 * GUI_Select.php created on 10.08.24, 13:17.
 */

namespace GUI_Select;

use GUI_HTMLElement\GUI_HTMLElement;
use pool\classes\Core\Input\Input;
use pool\classes\Core\Input\Session;

/**
 * GUI_Select
 *
 * @package pool\guis\GUI_Select
 * @since 2004/07/07
 */
class GUI_Select extends GUI_HTMLElement
{
    /**
     * Defaults
     */
    public function init(?int $superglobals = Input::EMPTY): void
    {
        $this->Defaults->addVars([
            'name' => $this->getName(),

            'options' => [],    // oder String getrennt mit ;
            'values' => [],    // oder String getrennt mit ;
            'styles' => [], // oder String getrennt mit ;
            'selected' => '',        // entspricht einem Wert von "values"
            'defaultselected' => '',
            'defaultvalue' => '',        // similar to defaultselected

            'save' => '',
            'use_session' => 0,
            'session_var' => $this->getName(),

            'datafld' => null,
            'datasrc' => null,
            'dataformatas' => null,
            'disabled' => null,
            'multiple' => null,
            'size' => null,
            'tabindex' => null,
        ]);

        parent::init(Input::GET | Input::POST);
    }

    public function loadFiles(): static
    {
        $file = $this->Weblication->findTemplate('tpl_option.html', self::class, true);
        $this->Template->setFilePath('stdoutOption', $file);

        $file = $this->Weblication->findTemplate('tpl_select.html', self::class, true);
        $this->Template->setFilePath('stdout', $file);

        $this->Template->useFile('stdout');
        return $this;
    }

    /**
     * Prepare template
     */
    protected function prepare(): void
    {
        if ($this->Input->getVar('defaultvalue')) {
            $this->Input->setVar('defaultselected', $this->Input->getVar('defaultvalue'));
        }

        parent::prepare();

        $id = $this->id;
        $name = $this->Input->getVar('name');

        $session_var = $this->Input->getVar('session_var');

        // id mit name (sowie umgekehrt) abgleichen
        if ($name != $this->Defaults->getVar('name') && $id == $this->getName()) {
            $id = $name;
        }
        if ($id != $this->Defaults->getVar('name') && $name == $this->getName()) {
            $name = $id;
        }

        $nameForValue = str_ends_with($name, '[]') ? substr($name, 0, strlen($name) - 2) : $name;
        $valueByName = $this->Input->getVar($nameForValue) != $name ? $this->Input->getVar($nameForValue) : '';

        // save value into session
        $buf_save = $this->Input->getVar('save');
        if ($this->Session instanceof Session && $this->Input->getAsInt('use_session') == 1) {
            if (!empty($buf_save) && $this->Input->getVar($buf_save) == 1) {
                $this->Session->setVar($session_var, $this->Input->getVar('selected') == '' ? $valueByName : $this->Input->getVar('selected'));
            }
            // Wert (value) ermitteln (session, object name, value, defaultvalue)
            $selected = $this->Session->getVar($session_var);
        } else {
            $selected = $this->Input->getVar('selected') != '' ? $this->Input->getVar('selected') : $valueByName;
        }
        if (empty($selected)) {
            $selected = $this->Input->getVar('defaultselected');
        }

        #### leere Attribute
        $emptyattributes = '';
        if ($this->Input->getVar('disabled')) {
            $emptyattributes .= 'disabled';
        }
        if ($this->Input->getVar('multiple')) {
            $emptyattributes .= ' ';
            $emptyattributes .= 'multiple';
        }

        #### Attribute
        $attributes = $this->attributes;
        if ($datafld = $this->Input->getVar('datafld')) {
            $attributes .= ' ';
            $attributes .= 'datafld="'.$datafld.'"';
        }
        if ($datasrc = $this->Input->getVar('datasrc')) {
            $attributes .= ' ';
            $attributes .= 'datasrc="'.$datasrc.'"';
        }
        if ($dataformatas = $this->Input->getVar('dataformatas')) {
            $attributes .= ' ';
            $attributes .= 'dataformatas="'.$dataformatas.'"';
        }
        if ($size = $this->Input->getVar('size')) {
            $attributes .= ' ';
            $attributes .= 'size="'.$size.'"';
        }
        if ($tabindex = $this->Input->getVar('tabindex')) {
            $attributes .= ' ';
            $attributes .= 'tabindex="'.$tabindex.'"';
        }
        if ($defaultvalue = $this->Input->getVar('defaultselected')) {
            $attributes .= ' ';
            $attributes .= 'defaultvalue="'.$defaultvalue.'"';
        }

        #### options
        $options = $this->Input->getVar('options');
        if (!is_array($options)) $options = explode(';', $options);
        $values = $this->Input->getVar('values');
        if (!is_array($values)) $values = explode(';', $values);
        $styles = $this->Input->getVar('styles');
        if (!is_array($styles)) $styles = explode(';', $styles);
        if (!$options) $options = $values;
        $option_content = '';
        $sizeofOptions = count($options);
        $this->Template->useFile('stdoutOption');
        for ($i = 0; $i < $sizeofOptions; $i++) {
            $value = $values[$i] ?? '';
            $content = $options[$i] ?? '';
            $style = $styles[$i] ?? '';

            $select = is_array($selected) ? (in_array($value, $selected) ? 1 : null) : (($selected == $value) ? 1 : null);

            $oemptyattributes = '';
            if ($select) {
                $oemptyattributes .= ' ';
                $oemptyattributes .= 'selected';
            }

            $this->Template->setVar([
                    'ID' => $name.'_'.$i,
                    'VALUE' => $value,
                    'CLASS' => $style,
                    'CONTENT' => $content,
                    'ATTRIBUTES' => '',
                    'EMPTYATTRIBUTES' => $oemptyattributes,
                ],
            );
            $this->Template->parse('stdoutOption');
            $option_content .= $this->Template->getContent('stdoutOption');
        }
        $this->Template->useFile('stdout');

        #### Set Template wildcards
        $this->Template->setVar([
                'ID' => $id,
                'NAME' => $name,
                'ATTRIBUTES' => ltrim($attributes),
                'EMPTYATTRIBUTES' => $emptyattributes,
                'CONTENT' => $option_content,
            ],
        );
    }
}