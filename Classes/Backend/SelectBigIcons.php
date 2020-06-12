<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace BusyNoggin\LayoutSelector\Backend;

use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Backend\Form\Utility\FormEngineUtility;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

/**
 * Render thumbnails of icons,
 * typically used with type=select.
 */
class SelectBigIcons extends AbstractNode
{


    public function from_camel_case($input) {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }
    /**
     * Render thumbnails of selected files
     *
     * @return array
     */
    public function render(): array
    {
        $selectIcons = [];
        $result = $this->initializeResultArray();

        $parameterArray = $this->data['parameterArray'];
        $selectItems = $parameterArray['fieldConf']['config']['items'];

        $selectItemCounter = 0;
        foreach ($selectItems as $item) {
            if ($item[1] === '--div--') {
                continue;
            }

            $icon = !empty($item[2]) ? FormEngineUtility::getIconHtml($item[2], $item[0], $item[0]) : '';

            if ($icon) {

                $full = true;

                if (count(explode('->', $item[1])) == 2) {
                    $icon = sprintf('/typo3conf/ext/%s/Resources/Public/Icons/Page/%s.jpg',
                        $this->from_camel_case(explode('.', (explode('->', $item[1])[0]))[1]),
                        ucfirst(explode('->', $item[1])[1])
                    );


                    $icon = '<div class="media ">
							<img style="border:1px solid #ddd;margin:0 auto;" class="img-responsive  media-object" src="'.$icon.'"  /><br />
							<div class="media-body">
								<h4 class="media-heading text-center">'.$item[0].'</h4>
							</div>
					</div>';
                    $full = false;
                } else {
                    $icon = '<strong>'.$item[0].'</strong>';
                }
                $fieldValue = $this->data['databaseRow'][$this->data['fieldName']];
                $selectIcons[] = [
                    'title'  => $item[0],
                    'active' => ($fieldValue[0] === (string)$item[1]) || (empty($fieldValue[0]) && empty($item[1]))? true : false,
                    'icon'   => $icon,
                    'index'  => $selectItemCounter,
                    'full'  => $full,
                ];
            }
            $selectItemCounter++;
        }

        $html = [];
        if (!empty($selectIcons)) {
            $html[] = '<div class="t3js-forms-select-single-icons">';
            $html[] =    '<div class="" style="display: flex;
    flex-flow: row wrap;
    align-items: stretch; ">';
            foreach ($selectIcons as $i => $selectIcon) {
                $active = $selectIcon['active'] ?  ' label-success' : '';
                $style = $selectIcon['full'] ? 'flex-basis:100%;cursor: pointer;margin: 15px 0;
   ' : 'flex-basis:24%;margin-right:1%;margin-bottom:15px;cursor: pointer;';

                if (is_array($selectIcon)) {
                    $html[] = '<a style="'.$style.($active ?'color:#ffffff':'') .'" href="#" title="' . htmlspecialchars($selectIcon['title'], ENT_COMPAT, 'UTF-8', false) . '" data-select-index="' . htmlspecialchars((string)$selectIcon['index']) . '">';
                    $html[] =   '<div class="img-thumbnail '.$active.'" style="display: block;">';
                    $html[] =   $selectIcon['icon'];
                    $html[] =   '</div>';


                    $html[] = '</a>';
                }
            }
            $html[] =    '</div>';
            $html[] = '</div>';
        }

        $result['html'] = implode(LF, $html);
        return $result;
    }
}

