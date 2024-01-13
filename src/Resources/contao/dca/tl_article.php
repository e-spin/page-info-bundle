<?php

/**
 * This file is part of e-spin/page-info-bundle.
 *
 * Copyright (c) 2020-2024 e-spin
 *
 * @package   e-spin/page-info-bundle
 * @author    Ingolf Steinhardt <info@e-spin.de>
 * @author    Kamil Kuzminski <kamil.kuzminski@codefog.pl>
 * @copyright 2020-2024 e-spin
 * @license   LGPL-3.0-or-later
 */

declare(strict_types=1);

/**
 * Add info to tl_article panel.
 */
$GLOBALS['TL_DCA']['tl_article']['list']['sorting']['panelLayout'] .= ',article_info';
