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

use Espin\PageInfoBundle\EventListener\PageInfo;

/**
 * Add panel layout to tl_page
 */
$GLOBALS['TL_DCA']['tl_page']['list']['sorting']['panelLayout']                 =
    'page_info,' . $GLOBALS['TL_DCA']['tl_page']['list']['sorting']['panelLayout'];
$GLOBALS['TL_DCA']['tl_page']['list']['sorting']['panel_callback']['page_info'] = [PageInfo::class, 'generatePanel'];

/**
 * Replace the tl_page label_callback
 */
$GLOBALS['TL_DCA']['tl_page']['list']['label']['label_callback'] = [PageInfo::class, 'addHint'];
