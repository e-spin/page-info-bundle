<?php

/**
 * This file is part of e-spin/page-info-bundle.
 *
 * Copyright (c) 2022-2024 e-spin
 *
 * @package   e-spin/page-info-bundle
 * @author    Ingolf Steinhardt <info@e-spin.de>
 * @author    Kamil Kuzminski <kamil.kuzminski@codefog.pl>
 * @copyright 2022-2024 e-spin
 * @license   LGPL-3.0-or-later
 */

declare(strict_types=1);

/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_page']['page_info_filter'] = 'Show info:';

/**
 * Page info - options
 */
$GLOBALS['TL_LANG']['tl_page']['page_info_options']['id']                      = 'ID';
$GLOBALS['TL_LANG']['tl_page']['page_info_options']['tstamp']                  = 'Revision date';
$GLOBALS['TL_LANG']['tl_page']['page_info_options']['alias']                   = &
    $GLOBALS['TL_LANG']['tl_page']['alias'][0];
$GLOBALS['TL_LANG']['tl_page']['page_info_options']['title']                   = &
    $GLOBALS['TL_LANG']['tl_page']['title'][0];
$GLOBALS['TL_LANG']['tl_page']['page_info_options']['pageTitle']               = &
    $GLOBALS['TL_LANG']['tl_page']['pageTitle'][0];
$GLOBALS['TL_LANG']['tl_page']['page_info_options']['description']             = &
    $GLOBALS['TL_LANG']['tl_page']['description'][0];
$GLOBALS['TL_LANG']['tl_page']['page_info_options']['protected']               = &
    $GLOBALS['TL_LANG']['tl_page']['protected'][0];
$GLOBALS['TL_LANG']['tl_page']['page_info_options']['layout']                  = &
    $GLOBALS['TL_LANG']['tl_page']['layout'][0];
$GLOBALS['TL_LANG']['tl_page']['page_info_options']['layout']                  =
    $GLOBALS['TL_LANG']['tl_page']['layout'][0] . ' set';
$GLOBALS['TL_LANG']['tl_page']['page_info_options']['cssClass']                = &
    $GLOBALS['TL_LANG']['tl_page']['cssClass'][0];
$GLOBALS['TL_LANG']['tl_page']['page_info_options']['sitemap']                 = &
    $GLOBALS['TL_LANG']['tl_page']['sitemap'][0];
$GLOBALS['TL_LANG']['tl_page']['page_info_options']['search']                  = 'Search';
$GLOBALS['TL_LANG']['tl_page']['page_info_options']['show']                    = 'Show';
$GLOBALS['TL_LANG']['tl_page']['page_info_options']['changelanguage_fallback'] =
    '[Change Language] - Main language page';
$GLOBALS['TL_LANG']['tl_page']['page_info_options']['version']                 = 'Version (last)';
