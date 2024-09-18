<?php
/*
 * This file is part of the g7system.local project.
 *
 * @copyright Copyright (c) 2024 GROUP7 AG
 * @authors GROUP7 Software/Web Developer Team
 *
 * GUI_Label.php created on 10.08.24, 13:16.
 */

/**
 * -= PHP Object Oriented Library (POOL) =-
 * Das GUI_Label ist lediglich ein Anzeigefeld fuer Text.
 *
 * @version $Id: gui_label.class.php,v 1.1.1.1 2004/09/21 07:49:31 manhart Exp $
 * @version $revision 1.0$
 * @version
 * @since 2004-02-18
 * @author Alexander Manhart <alexander@manhart.bayern>
 * @link https://alexander-manhart.de
 */

namespace GUI_Label;

use GUI_HTMLElement\GUI_HTMLElement;
use pool\classes\Core\Input\Input;

/**
 * Das GUI_Label ist lediglich ein Anzeigefeld fuer Text.
 *
 * @package pool\guis\GUI_Label
 * @since 2004/02/18
 */
class GUI_Label extends GUI_HTMLElement
{
    protected int $superglobals = Input::EMPTY;

    /*
     * protected array $inputFilter = [
     * 'caption' => [DataType::ALPHANUMERIC, ''],
     * 'for' => [DataType::ALPHANUMERIC, ''],
     * ];
     */

    public function init(?int $superglobals = Input::EMPTY): void
    {
        $this->Defaults->addVars([
            'content' => '',
            'for' => '',
        ]);
        parent::init($superglobals);
    }

    protected function finalize(): string
    {
        $name = $this->Input->getVar('name');
        if ($name) $name = "name=\"$name\"";
        return "<label id=\"$this->id\" $name for=\"{$this->getVar('for')}\" {$this->getVar('attributes')}>{$this->getVar('caption')}</label>";
    }
}