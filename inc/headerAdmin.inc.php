<?php
/*******************************************************************************
 *  Title: Help Desk Software HESK
 *  Version: 2.6.8 from 10th August 2016
 *  Author: Klemen Stirn
 *  Website: https://www.hesk.com
 ********************************************************************************
 *  COPYRIGHT AND TRADEMARK NOTICE
 *  Copyright 2005-2014 Klemen Stirn. All Rights Reserved.
 *  HESK is a registered trademark of Klemen Stirn.
 *  The HESK may be used and modified free of charge by anyone
 *  AS LONG AS COPYRIGHT NOTICES AND ALL THE COMMENTS REMAIN INTACT.
 *  By using this code you agree to indemnify Klemen Stirn from any
 *  liability that might arise from it's use.
 *  Selling the code for this program, in part or full, without prior
 *  written consent is expressly forbidden.
 *  Using this code, in part or full, to create derivate work,
 *  new scripts or products is expressly forbidden. Obtain permission
 *  before redistributing this software over the Internet or in
 *  any other medium. In all cases copyright and header must remain intact.
 *  This Copyright is in full effect in any country that has International
 *  Trade Agreements with the United States of America or
 *  with the European Union.
 *  Removing any of the copyright notices without purchasing a license
 *  is expressly forbidden. To remove HESK copyright notice you must purchase
 *  a license for this script. For more information on how to obtain
 *  a license please visit the page below:
 *  https://www.hesk.com/buy.php
 *******************************************************************************/

require_once(HESK_PATH . 'build.php');

/* Check if this is a valid include */
if (!defined('IN_SCRIPT')) {
    die('Invalid attempt');
}

define('ADMIN_PAGE', true);

$modsForHesk_settings = mfh_getSettings();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo(isset($hesk_settings['tmp_title']) ? $hesk_settings['tmp_title'] : $hesk_settings['hesk_title']); ?></title>
    <meta http-equiv="Content-Type" content="text/html;charset=<?php echo $hesklang['ENCODING']; ?>"/>
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <meta name="theme-color" content="<?php echo '#414a5c'; ?>">
    <link href="<?php echo HESK_PATH; ?>css/datepicker.css" type="text/css" rel="stylesheet"/>
    <link href="<?php echo HESK_PATH; ?>css/bootstrap.css?v=21" type="text/css" rel="stylesheet"/>
    <link href="<?php echo HESK_PATH; ?>css/bootstrap-iconpicker.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo HESK_PATH; ?>css/octicons.css" type="text/css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.css">
    <link rel="stylesheet" href="<?php echo HESK_PATH; ?>css/dropzone.min.css">
    <link rel="stylesheet" href="<?php echo HESK_PATH; ?>css/dropzone-basic.min.css">
    <link rel="stylesheet" href="<?php echo HESK_PATH; ?>css/fullcalendar.min.css">
    <link rel="stylesheet" href="<?php echo HESK_PATH; ?>css/bootstrap-clockpicker.min.css">
    <link rel="stylesheet" href="<?php echo HESK_PATH; ?>css/jquery.jgrowl.min.css">
    <link rel="stylesheet" href="<?php echo HESK_PATH; ?>css/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="<?php echo HESK_PATH; ?>css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?php echo HESK_PATH; ?>css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?php echo HESK_PATH; ?>css/mods-for-hesk-new.css">
    <link rel="stylesheet" href="<?php echo HESK_PATH; ?>css/colors.css">
    <link rel="stylesheet" href="<?php echo HESK_PATH; ?>css/positions.css">
    <link rel="stylesheet" href="<?php echo HESK_PATH; ?>css/displays.css">
    <?php if (defined('USE_JQUERY_2')): ?>
        <script src="<?php echo HESK_PATH; ?>js/jquery-2.2.4.min.js"></script>
    <?php else: ?>
        <script src="<?php echo HESK_PATH; ?>js/jquery-1.10.2.min.js"></script>
    <?php endif; ?>
    <script type="text/javascript" src="<?php echo HESK_PATH; ?>js/adminlte.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?php echo HESK_PATH; ?>hesk_javascript.js"></script>
    <script language="Javascript" type="text/javascript" src="<?php echo HESK_PATH; ?>js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo HESK_PATH; ?>js/dropzone.min.js"></script>
    <script language="Javascript" type="text/javascript"
            src="<?php echo HESK_PATH; ?>js/modsForHesk-javascript.js"></script>
    <script language="JavaScript" type="text/javascript"
            src="<?php echo HESK_PATH; ?>js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="<?php echo HESK_PATH; ?>js/bootstrap-clockpicker.min.js"></script>
    <script type="text/javascript" src="<?php echo HESK_PATH; ?>js/iconset-fontawesome-4.3.0.js"></script>
    <script type="text/javascript" src="<?php echo HESK_PATH; ?>js/iconset-octicon-2.1.2.js"></script>
    <script type="text/javascript" src="<?php echo HESK_PATH; ?>js/bootstrap-iconpicker.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.js"></script>
    <script type="text/javascript" src="<?php echo HESK_PATH; ?>js/platform.js"></script>
    <script type="text/javascript" src="<?php echo HESK_PATH; ?>js/bootstrap-validator.min.js"></script>
    <script type="text/javascript" src="<?php echo HESK_PATH; ?>internal-api/js/core-admin.php"></script>
    <script type="text/javascript" src="<?php echo HESK_PATH; ?>js/jquery.jgrowl.min.js"></script>
    <script type="text/javascript" src="<?php echo HESK_PATH; ?>js/bootstrap-colorpicker.min.js"></script>
    <script type="text/javascript" src="<?php echo HESK_PATH; ?>js/jquery.slimscroll.min.js"></script>
    <?php
    if (defined('EXTRA_JS')) {
        echo EXTRA_JS;
    }
    ?>
    <style>
        .settingsquestionmark {
            color: <?php echo $modsForHesk_settings['questionMarkColor']; ?>;
            cursor: pointer;
        }

        .h3questionmark {
            color: <?php echo $modsForHesk_settings['questionMarkColor']; ?>;
        }

        <?php if (defined('PAGE_TITLE') && PAGE_TITLE == 'LOGIN'): ?>
        body {
            background: #d2d6de;
        }
        <?php endif; ?>
    </style>

    <?php
    /* Prepare Javascript that browser should load on page load */
    $onload = "javascript:var i=new Image();i.src='" . HESK_PATH . "img/orangebtnover.gif';var i2=new Image();i2.src='" . HESK_PATH . "img/greenbtnover.gif';";

    /* Tickets shouldn't be indexed by search engines */
    if (defined('HESK_NO_ROBOTS')) {
        ?>
        <meta name="robots" content="noindex, nofollow"/>
        <?php
    }

    /* If page requires calendar include calendar Javascript and CSS */
    if (defined('CALENDAR')) {
        ?>
        <script language="Javascript" type="text/javascript"
                src="<?php echo HESK_PATH; ?>inc/calendar/tcal.php"></script>
        <link href="<?php echo HESK_PATH; ?>inc/calendar/tcal.css" type="text/css" rel="stylesheet"/>
        <?php
    }

    /* If page requires WYSIWYG editor include TinyMCE Javascript */
    if (defined('WYSIWYG') && $hesk_settings['kb_wysiwyg']) {
        ?>
        <script type="text/javascript" src="<?php echo HESK_PATH; ?>inc/tiny_mce/3.5.11/tiny_mce.js"></script>
        <?php
    }

    /* If page requires tabs load tabs Javascript and CSS */
    if (defined('LOAD_TABS')) {
        ?>
        <link href="<?php echo HESK_PATH; ?>inc/tabs/tabber.css" type="text/css" rel="stylesheet"/>
        <?php
    }

    if (defined('VALIDATOR')) {
        ?>
        <script type="text/javascript" src="<?php echo HESK_PATH; ?>js/validation-scripts.js"></script>
        <?php
    }

    /* If page requires timer load Javascript */
    if (defined('TIMER')) {
        ?>
        <script language="Javascript" type="text/javascript"
                src="<?php echo HESK_PATH; ?>inc/timer/hesk_timer.js"></script>
        <?php

        /* Need to load default time or a custom one? */
        if (isset($_SESSION['time_worked'])) {
            $t = hesk_getHHMMSS($_SESSION['time_worked']);
            $onload .= "load_timer('time_worked', " . $t[0] . ", " . $t[1] . ", " . $t[2] . ");";
            unset($t);
        } else {
            $onload .= "load_timer('time_worked', 0, 0, 0);";
        }

        /* Autostart timer? */
        if (!empty($_SESSION['autostart'])) {
            $onload .= "ss();";
        }
    }

    // Auto reload
    if (defined('AUTO_RELOAD') && hesk_checkPermission('can_view_tickets',0) && ! isset($_SESSION['hide']['ticket_list'])) {
        ?>
        <script type="text/javascript">
            var count = <?php echo empty($_SESSION['autoreload']) ? 30 : intval($_SESSION['autoreload']); ?>;
            var reloadcounter;
            var countstart = count;

            function heskReloadTimer() {
                count = count-1;
                if (count <= 0) {
                    clearInterval(reloadcounter);
                    window.location.reload();
                    return;
                }

                document.getElementById("timer").innerHTML = "(" + count + ")";
            }

            function heskCheckReloading() {
                if (<?php if ($_SESSION['autoreload']) echo "getCookie('autorefresh') == null || "; ?>getCookie('autorefresh') == '1') {
                    document.getElementById("reloadCB").checked=true;
                    document.getElementById("timer").innerHTML = "(" + count + ")";
                    reloadcounter = setInterval(heskReloadTimer, 1000);
                }
            }

            function toggleAutoRefresh(cb) {
                if (cb.checked) {
                    setCookie('autorefresh', '1');
                    document.getElementById("timer").innerHTML = "(" + count + ")";
                    reloadcounter = setInterval(heskReloadTimer, 1000);
                } else {
                    setCookie('autorefresh', '0');
                    count = countstart;
                    clearInterval(reloadcounter);
                    document.getElementById("timer").innerHTML = "";
                }
            }

        </script>
        <?php
    }

    if (defined('MFH_CALENDAR')) { ?>
        <script src="<?php echo HESK_PATH; ?>js/calendar/moment.js"></script>
        <script src="<?php echo HESK_PATH; ?>js/calendar/fullcalendar.min.js"></script>
        <script src="<?php echo HESK_PATH; ?>js/calendar/locale/<?php echo $hesk_settings['languages'][$hesk_settings['language']]['folder'] ?>.js"></script>
        <script src="<?php echo HESK_PATH; ?>js/calendar/mods-for-hesk-calendar.js"></script>
    <?php } else if (defined('MFH_CALENDAR_READONLY')) { ?>
        <script src="<?php echo HESK_PATH; ?>js/calendar/moment.js"></script>
        <script src="<?php echo HESK_PATH; ?>js/calendar/fullcalendar.min.js"></script>
        <script src="<?php echo HESK_PATH; ?>js/calendar/locale/<?php echo $hesk_settings['languages'][$hesk_settings['language']]['folder'] ?>.js"></script>
        <script src="<?php echo HESK_PATH; ?>js/calendar/mods-for-hesk-calendar-admin-readonly.js"></script>
    <?php
    }

    // Include custom head code
    include(HESK_PATH . 'head.txt');
    ?>

</head>
<?php
$layout_tag = '';
if (defined('MFH_PAGE_LAYOUT') && MFH_PAGE_LAYOUT == 'TOP_ONLY') {
    $layout_tag = 'layout-top-nav';
}
?>
<body onload="<?php echo $onload;
unset($onload); ?>" class="<?php echo $layout_tag ?> fixed js <?php echo $modsForHesk_settings['admin_color_scheme']; ?>">

<?php
include(HESK_PATH . 'header.txt');
$iconDisplay = 'style="display: none"';
if ($modsForHesk_settings['show_icons']) {
    $iconDisplay = '';
}
?>
