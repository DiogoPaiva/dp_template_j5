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
// Use a font scheme if set in the template style options
$paramsFontScheme = $this->params->get('useFontScheme', false);
$fontStyles = '';
if ($paramsFontScheme) {
    if (stripos($paramsFontScheme, 'https://') === 0) {
        $this->getPreloadManager()->preconnect('https://fonts.googleapis.com/', ['crossorigin' => 'anonymous']);
        $this->getPreloadManager()->preconnect('https://fonts.gstatic.com/', ['crossorigin' => 'anonymous']);
        $this->getPreloadManager()->preload($paramsFontScheme, ['as' => 'style', 'crossorigin' => 'anonymous']);
        $wa->registerAndUseStyle('fontscheme.current', $paramsFontScheme, [], ['rel' => 'lazy-stylesheet', 'crossorigin' => 'anonymous']);
        if (preg_match_all('/family=([^?:]*):/i', $paramsFontScheme, $matches) > 0) {
            $fontStyles = '--dp_template_j5-font-family-body: "' . str_replace('+', ' ', $matches[1][0]) . '", sans-serif;
            --dp_template_j5-font-family-headings: "' . str_replace('+', ' ', $matches[1][1] ?? $matches[1][0]) . '", sans-serif;
            --dp_template_j5-font-weight-normal: 400;
            --dp_template_j5-font-weight-headings: 700;';
        }
    } elseif ($paramsFontScheme === 'system') {
        $fontStylesBody = $this->params->get('systemFontBody', '');
        $fontStylesHeading = $this->params->get('systemFontHeading', '');
        if ($fontStylesBody) {
            $fontStyles = '--dp_template_j5-font-family-body: ' . $fontStylesBody . ';
            --dp_template_j5-font-weight-normal: 400;';
        }
        if ($fontStylesHeading) {
            $fontStyles .= '--dp_template_j5-font-family-headings: ' . $fontStylesHeading . ';
            --dp_template_j5-font-weight-headings: 700;';
        }
    } else {
        $wa->registerAndUseStyle('fontscheme.current', $paramsFontScheme, ['version' => 'auto'], ['rel' => 'lazy-stylesheet']);
        $this->getPreloadManager()->preload($wa->getAsset('style', 'fontscheme.current')->getUri() . '?' . $this->getMediaVersion(), ['as' => 'style']);
    }
}
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
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

<head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
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
                    <div class="logoheader">
                        <a href="<?php echo $this->baseurl; ?>/">
                            <div id="logo">&nbsp;</div>
                        </a>
                    </div>
                    <div class="menu-hor">
                        <jdoc:include type="modules" name="menu-hor" style="xhtml" />
                    </div>
                    <?php if ($this->countModules('pesq_1')): ?>
                        <div class="pesq">
                            <jdoc:include type="modules" name="pesq_1" style="xhtml" />
                        </div>
                    <?php endif; ?>

                    <?php if ($this->countModules('opcoes')): ?>
                        <div id="opcoes">
                            <jdoc:include type="modules" name="opcoes" style="xhtml" />
                        </div>
                    <?php endif; ?>
                </div>
                <?php if ($this->countModules('banner')): ?>
                    <div id="bannertopo">
                        <jdoc:include type="modules" name="banner" style="xhtml" />
                    </div>
                <?php endif; ?>
            </div><!-- end header -->

            <div id="breadcrumbs">
                <jdoc:include type="modules" name="caminho" style="xhtml" />
            </div>

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
                <?php if ($this->countModules('position-4')): ?>
                    <div class="box9">
                        <jdoc:include type="modules" name="position-4" style="xhtml" />
                    </div>
                <?php endif; ?>
                <?php if ($this->countModules('position-9')): ?>
                    <div class="box11">
                        <jdoc:include type="modules" name="position-9" style="xhtml" />
                    </div>
                <?php endif; ?>
                <?php if ($this->countModules('position-5')): ?>
                    <div class="box10">
                        <jdoc:include type="modules" name="position-5" style="xhtml" />
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <jdoc:include type="modules" name="debug" />
</body>

</html>