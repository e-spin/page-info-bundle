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

use Contao\Config;
use Contao\Database;
use Contao\Date;
use Contao\MemberGroupModel;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\System;
use Contao\UserModel;

/** ~~~~~~~~~~~ PAGE ~~~~~~~~~~~ */
/**
 * Page info - ID.
 */
$GLOBALS['PAGE_INFO']['id'] = static function ($arrRow) {
    return 'IDp: ' . $arrRow['id'];
};

/**
 * Page info - tstamp.
 */
$GLOBALS['PAGE_INFO']['tstamp'] = static function ($arrRow) {
    return Date::parse($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['tstamp']);
};

/**
 * Page info - alias.
 */
$GLOBALS['PAGE_INFO']['alias'] = static function ($arrRow) {
    return $arrRow['alias'];
};

/**
 * Page info - title.
 */
$GLOBALS['PAGE_INFO']['title'] = static function ($arrRow) {
    return $arrRow['title'];
};

/**
 * Page info - page title.
 */
$GLOBALS['PAGE_INFO']['pageTitle'] = static function ($arrRow) {
    return $arrRow['pageTitle'];
};

/**
 * Page info - description.
 */
$GLOBALS['PAGE_INFO']['description'] = static function ($arrRow) {
    return $arrRow['description'];
};

/**
 * Page info - protected.
 */
$GLOBALS['PAGE_INFO']['protected'] = static function ($arrRow) {
    if (!$arrRow['protected']) {
        return $GLOBALS['TL_LANG']['MSC']['no'];
    }
    $groupsModel = MemberGroupModel::findMultipleByIds(StringUtil::deserialize($arrRow['groups'], true));
    $groups      = [];
    foreach ($groupsModel ?? [] as $groupModel) {
        $groups[] = $groupModel->name;
    }

    return \sprintf('%s | %s', $GLOBALS['TL_LANG']['MSC']['yes'], \implode(', ', $groups));
};

/**
 * Page info - layout.
 */
$GLOBALS['PAGE_INFO']['layout'] = static function ($arrRow) {
    $objPage   = PageModel::findWithDetails($arrRow['id']);
    $objLayout = $objPage->getRelated('layout');

    if (null === $objLayout) {
        return '';
    }

    return ($objPage->includeLayout ? '! ' : '') . $objLayout->getRelated('pid')->name . ' &gt; ' . $objLayout->name;
};

/**
 * Page info - layout set.
 */
$GLOBALS['PAGE_INFO']['layoutSet'] = static function ($arrRow) {
    $objPage   = PageModel::findWithDetails($arrRow['id']);
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
 * Page info - CSS class.
 */
$GLOBALS['PAGE_INFO']['cssClass'] = static function ($arrRow) {
    return $arrRow['cssClass'];
};

/**
 * Page info - sitemap.
 */
$GLOBALS['PAGE_INFO']['sitemap'] = static function ($arrRow) {
    if (!$arrRow['sitemap']) {
        $arrRow['sitemap'] = 'map_default';
    }

    return $GLOBALS['TL_DCA']['tl_page']['fields']['sitemap']['reference'][$arrRow['sitemap']];
};

/**
 * Page info - search.
 */
$GLOBALS['PAGE_INFO']['search'] = static function ($arrRow) {
    return $arrRow['noSearch'] ? $GLOBALS['TL_LANG']['MSC']['no'] : $GLOBALS['TL_LANG']['MSC']['yes'];
};

/**
 * Page info - show dates.
 */
$GLOBALS['PAGE_INFO']['show'] = static function ($arrRow) {
    $start = $arrRow['start'] ? Date::parse($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['start']) : '*';
    $stop  = $arrRow['stop'] ? Date::parse($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['stop']) : '*';

    return $start . ' - ' . $stop;
};

/**
 * Page info - changelanguage fallback.
 */
$bundles = System::getContainer()->getParameter('kernel.bundles');
if (\array_key_exists('Terminal42ChangeLanguageBundle', $bundles)) {
    $GLOBALS['PAGE_INFO']['changelanguage_fallback'] = static function ($arrRow) {
        $page = PageModel::findByPk($arrRow['id']);
        if (null !== $page && empty($page->languageMain)) {
            return 'IDp: ' . $arrRow['id'];
        }

        $objFallbackPage = PageModel::findByPk($page->languageMain);
        if ($objFallbackPage === null) {
            return 'IDp: ' . $arrRow['id'] . ', *ID not found* (' . $page->languageMain . ')';
        }

        return 'IDp: ' . $arrRow['id'] . ', ' . $objFallbackPage->title . ' (' . $objFallbackPage->id . ')';
    };
}

/**
 * Page info - show version.
 */
$GLOBALS['PAGE_INFO']['version'] = static function ($arrRow) {
    return getLastVersionForTableById('tl_page', $arrRow['id']);
};

/**
 * Page info sorting
 */
$GLOBALS['PAGE_INFO_SORTING'] = \array_keys($GLOBALS['PAGE_INFO']);

/**
 * Page auto submit
 */
$GLOBALS['PAGE_AUTO_SUBMIT'] = true;

/** ~~~~~~~~~~~ ARTICLE ~~~~~~~~~~~ */

/**
 * Article info - ID.
 */
$GLOBALS['ARTICLE_INFO']['id'] = static function ($arrRow) {
    return 'IDa: ' . $arrRow['id'];
};

/**
 * Article info - tstamp.
 */
$GLOBALS['ARTICLE_INFO']['tstamp'] = static function ($arrRow) {
    return Date::parse($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['tstamp']);
};

/**
 * Article info - alias.
 */
$GLOBALS['ARTICLE_INFO']['alias'] = static function ($arrRow) {
    return $arrRow['alias'];
};

/**
 * Article info - author.
 */
$GLOBALS['ARTICLE_INFO']['author'] = static function ($arrRow) {
    if (null === $user = UserModel::findById($arrRow['author'])) {
        return 'Autor nicht gefunden!';
    }

    return \sprintf('%s (%s)', $user->name, $arrRow['author']);
};

/**
 * Article info - showTeaser.
 */
$GLOBALS['ARTICLE_INFO']['showTeaser'] = static function ($arrRow) {
    return $arrRow['showTeaser'] ? $GLOBALS['TL_LANG']['MSC']['yes'] : $GLOBALS['TL_LANG']['MSC']['no'];
};

/**
 * Article info - customTpl.
 */
$GLOBALS['ARTICLE_INFO']['customTpl'] = static function ($arrRow) {
    return $arrRow['customTpl'];
};

/**
 * Article info - protected.
 */
$GLOBALS['ARTICLE_INFO']['protected'] = static function ($arrRow) {
    if (!$arrRow['protected']) {
        return $GLOBALS['TL_LANG']['MSC']['no'];
    }
    $groupsModel = MemberGroupModel::findMultipleByIds(StringUtil::deserialize($arrRow['groups'], true));
    $groups      = [];
    foreach ($groupsModel ?? [] as $groupModel) {
        $groups[] = $groupModel->name;
    }

    return \sprintf('%s | %s', $GLOBALS['TL_LANG']['MSC']['yes'], \implode(', ', $groups));
};

/**
 * Article info - CSS id and classes.
 */
$GLOBALS['ARTICLE_INFO']['cssID'] = static function ($arrRow) {
    $css = StringUtil::deserialize($arrRow['cssID'], true);
    return \sprintf('%s | %s', $css[0] ?? '', $css[1] ?? '');
};

/**
 * Article info - published.
 */
$GLOBALS['ARTICLE_INFO']['published'] = static function ($arrRow) {
    return $arrRow['published'] ? $GLOBALS['TL_LANG']['MSC']['yes'] : $GLOBALS['TL_LANG']['MSC']['no'];
};

/**
 * Article info - show dates.
 */
$GLOBALS['ARTICLE_INFO']['show'] = static function ($arrRow) {
    $start = $arrRow['start'] ? Date::parse($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['start']) : '*';
    $stop  = $arrRow['stop'] ? Date::parse($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['stop']) : '*';

    return $start . ' - ' . $stop;
};

/**
 * Article info - show version.
 */
$GLOBALS['ARTICLE_INFO']['version'] = static function ($arrRow) {
    return getLastVersionForTableById('tl_article', $arrRow['id']);
};

/**
 * Article info sorting
 */
$GLOBALS['ARTICLE_INFO_SORTING'] = \array_keys($GLOBALS['ARTICLE_INFO']);

/**
 * Article auto submit
 */
$GLOBALS['ARTICLE_AUTO_SUBMIT'] = true;

/**
 * Get last version for table.
 *
 * @param $table
 * @param $id
 *
 * @return string
 */
function getLastVersionForTableById($table, $id): string
{
    $version = Database::getInstance()
        ->prepare(
            "SELECT v.tstamp, v.version, v.userid, u.name FROM tl_version v LEFT JOIN tl_user as u ON u.id = v.userid WHERE fromTable=? AND pid=? ORDER BY version DESC LIMIT 1"
        )
        ->execute($table, $id);

    if (!$version->numRows) {
        return '*';
    }

    return \sprintf(
        '%s %s (%s) %s',
        $GLOBALS['TL_LANG']['MSC']['version'],
        $version->version,
        Date::parse(Config::get('datimFormat'), $version->tstamp),
        $version->name
    );
}
