<?php

/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   (C) 2016 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Layout\LayoutHelper;

$params = $displayData->params;
$images = json_decode($displayData->images);

// Extract the thumbnail URL from image_intro
$imageIntro = $images->image_intro;
$thumbUrl = explode('#', $imageIntro)[0];

// Extract the full-size image URL from image_fulltext
$imageFulltext = $images->image_fulltext;
$fullImageUrl = explode('#', $imageFulltext)[0];

// Define attributes for the thumbnail image
$imageAttributes = [
    'src' => $thumbUrl,
    'alt' => $this->escape($displayData->images->image_intro_alt ?? ''),
    'class' => 'lightbox-image',
];

// Define attributes for the link (points to full image)
$linkAttributes = [
    'href' => $fullImageUrl,
    'class' => 'lightbox-trigger',
];

// Convert link attributes to string manually
function buildAttributesString($attributes)
{
    $parts = [];
    foreach ($attributes as $key => $value) {
        if (!empty($value)) {
            $parts[] = "$key=\"" . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . "\"";
        }
    }
    return implode(' ', $parts);
}

$linkAttributesString = buildAttributesString($linkAttributes);
?>
<!-- Render the clickable link with the thumbnail image -->
<a <?php echo $linkAttributesString; ?>>
    <?php echo LayoutHelper::render('joomla.html.image', $imageAttributes); ?>
</a>

<!-- Lightbox Container -->
<div id="lightbox" class="lightbox-overlay">
    <span class="lightbox-close">&times;</span>
    <img id="lightbox-image" src="" alt="Lightbox Image">
</div>