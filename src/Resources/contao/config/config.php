<?php

/**
 * This file is part of e-spin/page-info-bundle.
 *
 * Copyright (c) 2022 e-spin
 *
 * @package   e-spin/page-info-bundle
 * @author    Ingolf Steinhardt <info@e-spin.de>
 * @author    Kamil Kuzminski <kamil.kuzminski@codefog.pl>
 * @copyright 2022 e-spin
 * @license   LGPL-3.0-or-later
 */

declare(strict_types=1);

/**
 * Page info - ID
 */
$GLOBALS['PAGE_INFO']['id'] = function($arrRow) {
    return 'IDp: ' . $arrRow['id'];
};

/**
 * Page info - tstamp
 */
$GLOBALS['PAGE_INFO']['tstamp'] = function($arrRow) {
    return \Date::parse($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['tstamp']);
};

/**
 * Page info - alias
 */
$GLOBALS['PAGE_INFO']['alias'] = function($arrRow) {
    return $arrRow['alias'];
};

/**
 * Page info - title
 */
$GLOBALS['PAGE_INFO']['title'] = function($arrRow) {
    return $arrRow['title'];
};

/**
 * Page info - page title
 */
$GLOBALS['PAGE_INFO']['pageTitle'] = function($arrRow) {
    return $arrRow['pageTitle'];
};

/**
 * Page info - description
 */
$GLOBALS['PAGE_INFO']['description'] = function($arrRow) {
    return $arrRow['description'];
};

/**
 * Page info - layout
 */
$GLOBALS['PAGE_INFO']['layout'] = function ($arrRow) {
    $objPage   = \PageModel::findWithDetails($arrRow['id']);
    $objLayout = $objPage->getRelated('layout');

    if (null === $objLayout) {
        return '';
    }

    return ($objPage->includeLayout ? '! ' : '') . $objLayout->getRelated('pid')->name . ' &gt; ' . $objLayout->name;
};

/**
 * Page info - layout set
 */
$GLOBALS['PAGE_INFO']['layoutSet'] = function ($arrRow) {
    $objPage   = \PageModel::findWithDetails($arrRow['id']);
    $objLayout = $objPage->getRelated('layout');

    if (null === $objLayout) {
        return '';
    }

    if (!$objPage->includeLayout) {
        return '*';
    }

    return ($objPage->includeLayout ? '! ' : '') . $objLayout->getRelated('pid')->name . ' &gt; ' . $objLayout->name;
};

/**
 * Page info - CSS class
 */
$GLOBALS['PAGE_INFO']['cssClass'] = function($arrRow) {
    return $arrRow['cssClass'];
};

/**
 * Page info - sitemap
 */
$GLOBALS['PAGE_INFO']['sitemap'] = function($arrRow) {
    if (!$arrRow['sitemap']) {
        $arrRow['sitemap'] = 'map_default';
    }

    return $GLOBALS['TL_DCA']['tl_page']['fields']['sitemap']['reference'][$arrRow['sitemap']];
};

/**
 * Page info - search
 */
$GLOBALS['PAGE_INFO']['search'] = function($arrRow) {
    return $arrRow['noSearch'] ? $GLOBALS['TL_LANG']['MSC']['no'] : $GLOBALS['TL_LANG']['MSC']['yes'];
};

/**
 * Page info - show dates
 */
$GLOBALS['PAGE_INFO']['show'] = function($arrRow) {
    $start = $arrRow['start'] ? \Date::parse($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['start']) : '*';
    $stop = $arrRow['stop'] ? \Date::parse($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['stop']) : '*';

    return $start . ' - ' . $stop;
};

/**
 * Page info - changelanguage fallback
 */
if (in_array('Terminal42ChangeLanguageBundle', \ModuleLoader::getActive())) {
    $GLOBALS['PAGE_INFO']['changelanguage_fallback'] = function($arrRow) {
        $objPage = \PageModel::findByPk($arrRow['id']);
        if(empty($objPage->languageMain)) {
            return 'IDp: ' . $arrRow['id'];
        } else {
            $objFallbackPage = \PageModel::findByPk($objPage->languageMain);
            if (empty($objFallbackPage)) {
                return 'IDp: ' . $arrRow['id'] . ', *ID not found* (' . $objPage->languageMain . ')';
            } else {
                return 'IDp: ' . $arrRow['id'] . ', ' . $objFallbackPage->title . ' (' . $objFallbackPage->id . ')';
            }
        }
    };
}

/**
 * Page info sorting
 */
$GLOBALS['PAGE_INFO_SORTING'] = array_keys($GLOBALS['PAGE_INFO']);
