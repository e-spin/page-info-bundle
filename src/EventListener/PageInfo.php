<?php

/**
 * This file is part of e-spin/page-info-bundle.
 *
 * Copyright (c) 2020-2025 e-spin
 *
 * @package   e-spin/page-info-bundle
 * @author    Ingolf Steinhardt <info@e-spin.de>
 * @author    Kamil Kuzminski <kamil.kuzminski@codefog.pl>
 * @copyright 2020-2025 e-spin
 * @license   LGPL-3.0-or-later
 */

declare(strict_types=1);

namespace Espin\PageInfoBundle\EventListener;

use Contao\DataContainer;
use Contao\Input;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;

class PageInfo
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    /**
     * Generate the panel and return it as HTML string.
     *
     * @return string
     */
    public function generatePanel(): string
    {
        if (!(\in_array(($currentType = \strtolower(Input::get('do'))), ['page', 'article']))) {
            return '';
        }

        $tableKey         = \sprintf('tl_%s', $currentType);
        $labelKey         = \sprintf('%s_info_filter', $currentType);
        $optionsKey       = \sprintf('%s_info_options', $currentType);
        $postKey          = \sprintf('tl_%s_info', $currentType);
        $sessionKey       = \sprintf('%s_info', $currentType);
        $configKey        = \sprintf('%s_INFO', \strtoupper($currentType));
        $configSortingKey = \sprintf('%s_INFO_SORTING', \strtoupper($currentType));

        if (Input::post('FORM_SUBMIT') === 'tl_filters') {
            $varValue = null;

            // Set new value
            if (
                Input::post($postKey) !== ''
                && \array_key_exists(Input::post($postKey), $GLOBALS[$configKey])
                && \in_array(Input::post($postKey), $GLOBALS[$configSortingKey])
            ) {
                $varValue = Input::post($postKey);
            }

            $sessionBag = $this->getSessionBag();
            if (null !== $sessionBag) {
                $sessionBag->set($sessionKey, $varValue);
            }
        }

        $isActive = false;
        $current  = $this->getCurrent($sessionKey, $configKey, $configSortingKey);
        $options  = ['<option value=""' . (empty($current) ? ' selected' : '') . '>---</option>'];

        // Generate options for page.
        foreach ($GLOBALS[$configSortingKey] as $v) {
            if (!\array_key_exists($v, $GLOBALS[$configKey])) {
                continue;
            }

            $options[] = '<option value="' . $v . '"' . (($current === $v) ? ' selected' : '') . '>' . $GLOBALS['TL_LANG'][$tableKey][$optionsKey][$v] . '</option>';

            // The field is active
            if (!$isActive && $current === $v) {
                $isActive = true;
            }
        }

        return '<div class="' . $postKey . ' tl_subpanel" style="float:left; margin-left: 15px; text-align: left;">
<strong>' . $GLOBALS['TL_LANG'][$tableKey][$labelKey] . '</strong>
<select name="' . $postKey . '" class="tl_select' . ($isActive ? ' active' : '') .
            '" onchange="this.form.submit()" style="width: 300px; margin-left: 3px;">
' . \implode("\n", $options) . '
</select>
</div>';
    }

    /**
     * Add hint to each page record.
     *
     * @param array              $row
     * @param string             $label
     * @param DataContainer|null $dc
     * @param string             $imageAttribute
     * @param boolean            $blnReturnImage
     * @param boolean            $blnProtected
     *
     * @return string
     */
    public function addPageHint(
        array $row,
        string $label,
        DataContainer $dc = null,
        string $imageAttribute = '',
        bool $blnReturnImage = false,
        bool $blnProtected = false
    ): string {

        $objDefault = new \tl_page();

        return $this->addHint($row, $label, $dc, $imageAttribute, $blnReturnImage, $blnProtected, $objDefault, 'page');
    }

    /**
     * Add hint to each article record.
     *
     * @param array              $row
     * @param string             $label
     * @param DataContainer|null $dc
     * @param string             $imageAttribute
     * @param boolean            $blnReturnImage
     * @param boolean            $blnProtected
     *
     * @return string
     */
    public function addArticleHint(
        array $row,
        string $label,
        DataContainer $dc = null,
        string $imageAttribute = '',
        bool $blnReturnImage = false,
        bool $blnProtected = false
    ): string {

        $objDefault = new \tl_article();

        return $this->addHint($row, $label, $dc, $imageAttribute, $blnReturnImage, $blnProtected, $objDefault, 'article');
    }

    /**
     * Add hint to each record.
     *
     * @param array         $row
     * @param string        $label
     * @param DataContainer $dc
     * @param string        $imageAttribute
     * @param boolean       $blnReturnImage
     * @param boolean       $blnProtected
     * @param object        $objDefault
     * @param string        $currentType
     *
     * @return string
     */
    public function addHint(
        array $row,
        string $label,
        DataContainer $dc,
        string $imageAttribute,
        bool $blnReturnImage,
        bool $blnProtected,
        $objDefault,
        $currentType
    ): string {
        $sessionKey       = \sprintf('%s_info', $currentType);
        $configKey        = \sprintf('%s_INFO', \strtoupper($currentType));
        $configSortingKey = \sprintf('%s_INFO_SORTING', \strtoupper($currentType));

        $strReturn  = $objDefault->addIcon($row, $label, $dc, $imageAttribute, $blnReturnImage, $blnProtected);
        $strCurrent = $this->getCurrent($sessionKey, $configKey, $configSortingKey);

        // Add a hint.
        if (null !== $strCurrent) {
            $varCallback = $GLOBALS[$configKey][$strCurrent];

            if (\is_callable($varCallback)) {
                $strReturn .= ' <span style="padding-left:3px;color:#8A8A8A;">[' . $varCallback($row) . ']</span>';
            }
        }

        return $strReturn;
    }

    /**
     * Get the current hint.
     *
     */
    public function getCurrent($sessionKey, $configKey, $configSortingKey)
    {
        $sessionBag = $this->getSessionBag();
        if (null === $sessionBag) {
            return null;
        }

        $info = $sessionBag->get($sessionKey);

        if (
            $info !== ''
            && \array_key_exists($info, $GLOBALS[$configKey])
            && \in_array($info, $GLOBALS[$configSortingKey])
        ) {
            return $info;
        }

        return null;
    }

    private function getSessionBag(): ?AttributeBagInterface
    {
        try {
            $sessionBag = $this->requestStack->getSession()->getBag('contao_backend');
        } catch (SessionNotFoundException | InvalidArgumentException $ignore) {
            return null;
        }

        if (!$sessionBag instanceof AttributeBagInterface) {
            return null;
        }

        return $sessionBag;
    }
}
