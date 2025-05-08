<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.dp_template_j5
 *
 * @copyright   (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
/** @var Joomla\CMS\Document\HtmlDocument $this */
$app = Factory::getApplication();
$doc = Factory::getDocument();
$input = $app->getInput();
$wa = $this->getWebAssetManager();
// Browsers support SVG favicons
$this->addHeadLink(HTMLHelper::_('image', 'joomla-favicon.svg', '', [], true, 1), 'icon', 'rel', ['type' => 'image/svg+xml']);
$this->addHeadLink(HTMLHelper::_('image', 'favicon.ico', '', [], true, 1), 'alternate icon', 'rel', ['type' => 'image/vnd.microsoft.icon']);
$this->addHeadLink(HTMLHelper::_('image', 'joomla-favicon-pinned.svg', '', [], true, 1), 'mask-icon', 'rel', ['color' => '#000']);
// Detecting Active Variables
$option = $input->getCmd('option', '');
$view = $input->getCmd('view', '');
$layout = $input->getCmd('layout', '');
$task = $input->getCmd('task', '');
$itemid = $input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$menu = $app->getMenu()->getActive();
$pageclass = $menu !== null ? $menu->getParams()->get('pageclass_sfx', '') : '';
// Color Theme
$paramsColorName = $this->params->get('colorName', 'colors_standard');
$assetColorName = 'theme.' . $paramsColorName;

// Enable assets
$wa->usePreset('template.dp_template_j5.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr'))
    ->useStyle('template.active.language')
    ->registerAndUseStyle($assetColorName, 'global/' . $paramsColorName . '.css')
    ->useStyle('template.user')
    ->useScript('template.user');

// Register and use the additional CSS files
$wa->registerAndUseStyle('template.global', 'global/template.css')
    ->registerAndUseStyle('template.frame', 'site/frame.css');

// Override 'template.active' asset to set correct ltr/rtl dependency
$wa->registerStyle('template.active', '', [], [], ['template.dp_template_j5.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr')]);

// Logo file or site title param
if ($this->params->get('logoFile')) {
    $logo = HTMLHelper::_('image', Uri::root(false) . htmlspecialchars($this->params->get('logoFile'), ENT_QUOTES), $sitename, ['loading' => 'eager', 'decoding' => 'async'], false, 0);
} elseif ($this->params->get('siteTitle')) {
    $logo = '<span title="' . $sitename . '">' . htmlspecialchars($this->params->get('siteTitle'), ENT_COMPAT, 'UTF-8') . '</span>';
} else {
    $logo = HTMLHelper::_('image', 'logo.svg', $sitename, ['class' => 'logo d-inline-block', 'loading' => 'eager', 'decoding' => 'async'], true, 0);
}
$hasClass = '';
if ($this->countModules('sidebar-left', true)) {
    $hasClass .= ' has-sidebar-left';
}
if ($this->countModules('sidebar-right', true)) {
    $hasClass .= ' has-sidebar-right';
}
// Container
$wrapper = $this->params->get('fluidContainer') ? 'wrapper-fluid' : 'wrapper-static';
$this->setMetaData('viewport', 'width=device-width, initial-scale=1');
$stickyHeader = $this->params->get('stickyHeader') ? 'position-sticky sticky-top' : '';
// Defer fontawesome for increased performance. Once the page is loaded javascript changes it to a stylesheet.
$wa->getAsset('style', 'fontawesome')->setAttribute('rel', 'lazy-stylesheet');

// Add the script with versioning and defer attributes
$doc->addScript(
    '/media/templates/site/dp_template_j5/js/lightbox.js',
    ['version' => 'auto', 'defer' => true]
);
$doc->addScript(
    '/media/templates/site/dp_template_j5/js/slide.js',
    ['version' => 'auto', 'defer' => true]
);
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

<head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <link href='http://fonts.googleapis.com/css?family=Cuprum' rel='stylesheet' type='text/css'>
    <jdoc:include type="scripts" />
</head>

<body class="site <?php echo $option
    . ' ' . $wrapper
    . ' view-' . $view
    . ($layout ? ' layout-' . $layout : ' no-layout')
    . ($task ? ' task-' . $task : ' no-task')
    . ($itemid ? ' itemid-' . $itemid : '')
    . ($pageclass ? ' ' . $pageclass : '')
    . $hasClass
    . ($this->direction == 'rtl' ? ' rtl' : '');
?>">
    <div id="all">
        <div id="main-section">
            <div id="header">
                <div class="topo">
                    <div class="menu-hor">
                        <jdoc:include type="modules" name="menu-hor" style="xhtml" />
                    </div>
                    <div class="logo-slogan-container">
                        <div class="logoheader">
                            <a href="<?php echo $this->baseurl; ?>/">
                                <div id="logo">&nbsp;</div>
                            </a>
                        </div>
                        <div class="slogan">
                            <img src="images/ground-feel.png" width="353" height="36" loading="lazy"
                                data-path="local-images:/ground-feel.png">
                        </div>
                    </div>
                    <div id="breadcrumbs">
                        <jdoc:include type="modules" name="caminho" style="xhtml" />
                    </div>
                </div>
            </div><!-- end header -->
            <?php if ($this->countModules('banner')): ?>
                <div id="bannertopo">
                    <jdoc:include type="modules" name="banner" style="xhtml" />
                </div>
            <?php endif; ?>
            <?php if ($this->countModules('position-1') || $this->countModules('position-2') || $this->countModules('position-3')): ?>
                <div id="caixas1">
                    <?php if ($this->countModules('position-1')): ?>
                        <div class="box1">
                            <jdoc:include type="modules" name="position-1" style="xhtml" />
                        </div>
                    <?php endif; ?>
                    <?php if ($this->countModules('position-2')): ?>
                        <div class="box2">
                            <jdoc:include type="modules" name="position-2" style="xhtml" />
                        </div>
                    <?php endif; ?>
                    <?php if ($this->countModules('position-3')): ?>
                        <div class="box3">
                            <jdoc:include type="modules" name="position-3" style="xhtml" />
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div style="clear: both"></div>

            <div id="contentarea">
                <?php if ($this->countModules('left')): ?>
                    <div id="left">
                        <jdoc:include type="modules" name="left" style="xhtml" />
                    </div>
                <?php endif; ?>

                <div <?php
                $classes = ["full-content"];
                if ($this->countModules('left')) {
                    $classes[] = 'has-sidebar-left';
                }
                if ($this->countModules('right')) {
                    $classes[] = 'has-sidebar-right';
                }
                echo 'class="' . implode(' ', $classes) . '"';
                ?>>
                    <jdoc:include type="message" />
                    <jdoc:include type="component" style="xhtml" />
                </div><!-- end wrapper -->

                <?php if ($this->countModules('right')): ?>
                    <div id="right">
                        <jdoc:include type="modules" name="right" style="xhtml" />
                    </div>
                <?php endif; ?>
            </div>
            <div style="height: 50px;"></div>
            <div class="push"></div>
        </div>
    </div>
    <div id="footer-outer">
        <div id="footer-inner">
            <div id="bottom">
                <div id="caixas-footer">
                    <?php if ($this->countModules('position-4')): ?>
                        <div class="box4">
                            <jdoc:include type="modules" name="position-4" style="xhtml" />
                        </div>
                    <?php endif; ?>
                    <?php if ($this->countModules('position-5')): ?>
                        <div class="box5">
                            <jdoc:include type="modules" name="position-5" style="xhtml" />
                        </div>
                    <?php endif; ?>
                    <?php if ($this->countModules('position-6')): ?>
                        <div class="box6">
                            <jdoc:include type="modules" name="position-6" style="xhtml" />
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <jdoc:include type="modules" name="debug" />
</body>

</html>