<?php

/**
 * This file is part of e-spin/page-info-bundle.
 *
 * Copyright (c) 2020 e-spin
 *
 * @package   e-spin/page-info-bundle
 * @author    Ingolf Steinhardt <info@e-spin.de>
 * @author    Kamil Kuzminski <kamil.kuzminski@codefog.pl>
 * @copyright 2020 e-spin
 * @license   LGPL-3.0-or-later
 */

declare(strict_types=1);

namespace Espin\PageInfoBundle\EventListener;

class PageInfo
{
    /**
     * Generate the panel and return it as HTML string
     * @return string
     */
    public function generatePanel()
    {
        if (\Input::post('FORM_SUBMIT') == 'tl_filters') {
            $varValue = null;

            // Set new value
            if (\Input::post('tl_page_info') != '' && array_key_exists(\Input::post('tl_page_info'), $GLOBALS['PAGE_INFO']) && in_array(\Input::post('tl_page_info'), $GLOBALS['PAGE_INFO_SORTING'])) {
                $varValue = \Input::post('tl_page_info');
            }

            \Session::getInstance()->set('page_info', $varValue);
        }

        $blnActive = false;
        $strCurrent = $this->getCurrent();
        $arrOptions = array('<option value=""' . (($strCurrent == '') ? ' selected' : '') . '>---</option>');

        // Generate options
        foreach ($GLOBALS['PAGE_INFO_SORTING'] as $v) {
            if (!array_key_exists($v, $GLOBALS['PAGE_INFO'])) {
                continue;
            }

            $arrOptions[] = '<option value="' . $v . '"' . (($strCurrent == $v) ? ' selected' : '') . '>' . $GLOBALS['TL_LANG']['tl_page']['page_info_options'][$v] . '</option>';

            // The field is active
            if (!$blnActive && $strCurrent == $v) {
                $blnActive = true;
            }
        }

        return '<div class="tl_page_info tl_subpanel" style="float:left;">
<strong>' . $GLOBALS['TL_LANG']['tl_page']['page_info_filter'] . '</strong>
<select name="tl_page_info" class="tl_select' . ($blnActive ? ' active' : '') . '" onchange="this.form.submit()">
' . implode("\n", $arrOptions) . '
</select>
</div>';
    }

    /**
     * Add hint to each record
     * @param array
     * @param string
     * @param \DataContainer
     * @param string
     * @param boolean
     * @param boolean
     * @return string
     */
    public function addHint($row, $label, \DataContainer $dc=null, $imageAttribute='', $blnReturnImage=false, $blnProtected=false)
    {
        $objDefault = new \tl_page();
        $strReturn = $objDefault->addIcon($row, $label, $dc, $imageAttribute, $blnReturnImage, $blnProtected);
        $strCurrent = $this->getCurrent();

        // Add a hint
        if ($strCurrent != '') {
            $varCallback = $GLOBALS['PAGE_INFO'][$strCurrent];

            if (is_callable($varCallback)) {
                $strReturn .= ' <span style="padding-left:3px;color:#8A8A8A;">[' . $varCallback($row) . ']</span>';
            }
        }

        return $strReturn;
    }

    /**
     * Get the current hint
     * @param mixed
     */
    public function getCurrent()
    {
        $strSession = \Session::getInstance()->get('page_info');

        if ($strSession != '' && array_key_exists($strSession, $GLOBALS['PAGE_INFO']) && in_array($strSession, $GLOBALS['PAGE_INFO_SORTING'])) {
            return $strSession;
        }

        return null;
    }
}
