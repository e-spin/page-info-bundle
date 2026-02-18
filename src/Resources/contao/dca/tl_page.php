<?php

/**
 * This file is part of e-spin/page-info-bundle.
 *
 * Copyright (c) 2020-2026 e-spin
 *
 * @package   e-spin/page-info-bundle
 * @author    Ingolf Steinhardt <info@e-spin.de>
 * @author    Kamil Kuzminski <kamil.kuzminski@codefog.pl>
 * @copyright 2020-2026 e-spin
 * @license   LGPL-3.0-or-later
 */

declare(strict_types=1);

/**
 * Add info to tl_page panel.
 */
$GLOBALS['TL_DCA']['tl_page']['list']['sorting']['panelLayout'] =
    'page_info,' . $GLOBALS['TL_DCA']['tl_page']['list']['sorting']['panelLayout'];
