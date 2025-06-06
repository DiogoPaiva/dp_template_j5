<?php
/**
 * @version     1.0
 * @package     Joomla.Site
 * @subpackage  Templates.dp_template_j5
 * @copyright   GNU General Public License v2+
 * @license     GNU General Public License v2+
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper as ContentRoute;

$app = Factory::getApplication();
$templateParams = $app->getTemplate(true)->params;

$params = $this->item->params;
$canEdit = $params->get('access-edit');

// Load necessary helpers
HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');

// Use Bootstrap tooltips (if needed)
HTMLHelper::_('bootstrap.tooltip');


// Decode images JSON
$images = json_decode($this->item->images);

// Output events
echo $this->item->event->beforeDisplayContent;
?>

<div class="image-gallery-item">
    <?php if ($params->get('show_title')): ?>
        <div class="titulo_wrapper">
            <h2>
                <a
                    href="<?= Route::_(ContentRoute::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language)); ?>">
                    <?= $this->escape($this->item->title); ?>
                </a>
            </h2>
        </div>
    <?php endif; ?>

    <?php if (!empty($images->image_intro)): ?>
        <div class="imagem_disc_wrapper">
            <a
                href="<?= Route::_(ContentRoute::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language)); ?>">
                <img class="imagem_disc" src="<?= htmlspecialchars($images->image_intro, ENT_QUOTES, 'UTF-8'); ?>"
                    alt="<?= htmlspecialchars($images->image_intro_alt, ENT_QUOTES, 'UTF-8'); ?>"
                    title="<?= htmlspecialchars($images->image_intro_caption, ENT_QUOTES, 'UTF-8'); ?>" />
            </a>
        </div>

        <?php if (!empty($images->image_intro_caption)): ?>
            <div class="imagem_descr text-muted small text-center mt-2">
                <?= htmlspecialchars($images->image_intro_caption, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="item-separator my-4"></div>
</div>

<?php
// Output events
echo $this->item->event->afterDisplayContent;