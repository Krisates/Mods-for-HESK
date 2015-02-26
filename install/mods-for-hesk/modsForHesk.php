<?php
define('IN_SCRIPT',1);
define('HESK_PATH','../../');
require(HESK_PATH . 'install/install_functions.inc.php');
require(HESK_PATH . 'hesk_settings.inc.php');

hesk_dbConnect();
?>
<html>
    <head>
        <title>Mods For HESK 2.0.0 Install / Upgrade</title>
        <link href="../../hesk_style.css?<?php echo HESK_NEW_VERSION; ?>" type="text/css" rel="stylesheet" />
        <link href="<?php echo HESK_PATH; ?>css/bootstrap.css?v=<?php echo $hesk_settings['hesk_version']; ?>" type="text/css" rel="stylesheet" />
        <link href="<?php echo HESK_PATH; ?>css/bootstrap-theme.css?v=<?php echo $hesk_settings['hesk_version']; ?>" type="text/css" rel="stylesheet" />
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="../../css/hesk_newStyle.php" type="text/css" rel="stylesheet" />
        <script src="<?php echo HESK_PATH; ?>js/jquery-1.10.2.min.js"></script>
        <script language="Javascript" type="text/javascript" src="<?php echo HESK_PATH; ?>js/bootstrap.min.js"></script>
        <script language="Javascript" type="text/javascript" src="<?php echo HESK_PATH; ?>js/modsForHesk-javascript.js"></script>
        <script language="JavaScript" type="text/javascript" src="<?php echo HESK_PATH; ?>js/bootstrap-datepicker.js"></script>
    </head>
    <body>
        <div class="headersm">Mods for HESK 2.0.0 Install / Upgrade</div>
        <div class="container">
            <div class="page-header">
                <h1>Mods for HESK 2.0.0 Install / Upgrade</h1>
            </div>
            <?php
            $allowInstallation = true;
            ?>
            <div class="row">
                <div class="col-md-5 col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Database/File Requirements</div>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th colspan="2">Database Information / File Permissions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Database Host:</td>
                                <td><?php echo $hesk_settings['db_host']; ?></td>
                            </tr>
                            <tr>
                                <td>Database Name:</td>
                                <td><?php echo $hesk_settings['db_name']; ?></td>
                            </tr>
                            <tr>
                                <td>Database User:</td>
                                <td><?php echo $hesk_settings['db_user']; ?></td>
                            </tr>
                            <tr>
                                <td>Database Password:</td>
                                <td><?php echo $hesk_settings['db_pass']; ?></td>
                            </tr>
                            <tr>
                                <td>Database Prefix:</td>
                                <td><?php echo $hesk_settings['db_pfix']; ?></td>
                            </tr>
                            <tr>
                                <td>CREATE, ALTER, DROP Permissions:</td>
                                <td class="warning"><i class="fa fa-exclamation-triangle"></i> Please check before continuing!*</td>
                            </tr>
                            <tr>
                                <td>
                                    modsForHesk_settings.inc.php
                                </td>
                                <?php
                                $fileperm = substr(sprintf('%o', fileperms(HESK_PATH.'modsForHesk_settings.inc.php')), -4);
                                $class =  (intval($fileperm) < 666) ? 'class="danger"' : 'class="success"';
                                ?>
                                <td <?php echo $class; ?>>
                                    <?php if ($class == 'class="success"') {
                                        echo '<i class="fa fa-check-circle"></i> Success';
                                    } else {
                                        echo '<i class="fa fa-times-circle"></i> CHMOD to 0666, yours is '.$fileperm;
                                        $allowInstallation = false;
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    * Mods for HESK is unable to check database permissions automatically.
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-7 col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Install / Upgrade</div>
                        <div class="panel-body">
                            <?php if ($allowInstallation) {
                                $prereqDiv = 'none';
                                $installDiv = 'block';
                            } else {
                                $prereqDiv = 'block';
                                $installDiv = 'none';
                            }
                            ?>
                            <div class="prereqsFailedDiv" style="display:<?php echo $prereqDiv; ?>">
                                <div class="alert alert-danger">
                                    <p><i class="fa fa-times-circle"></i> You cannot install/upgrade Mods for HESK until the requirements on the left have been met.</p>
                                    <p><a href="modsForHesk.php" class="btn btn-default">Refresh</a></p>
                                </div>
                            </div>
                            <div class="installDiv"  style="display:<?php echo $installDiv; ?>">
                                <div class="alert alert-info">
                                    <p><i class="fa fa-exclamation-triangle"></i> Make sure that you have updated / installed HESK first; otherwise installation will <b>fail</b>!</p>
                                </div>
                                <p>What version of Mods for HESK do you currently have installed?</p>
                                <hr>
                                <?php
                                $tableSql = hesk_dbQuery('SHOW TABLES LIKE \''.hesk_dbEscape($hesk_settings['db_pfix']).'settings\'');
                                $version = NULL;
                                $disableAllExcept = NULL;
                                if (hesk_dbNumRows($tableSql) > 0) {
                                    $versionRS = hesk_dbQuery('SELECT `Value` FROM `'.hesk_dbEscape($hesk_settings['db_pfix']).'settings` WHERE `Key` = \'modsForHeskVersion\'');
                                    $versionArray = hesk_dbFetchAssoc($versionRS);
                                    $version = $versionArray['Value'];

                                    if ($version != MODS_FOR_HESK_NEW_VERSION) {
                                        echo '<div class="row">';
                                        echo '<div class="col-sm-12">';
                                        echo '<p id="updateText">Mods for HESK has detected that you currently have v' . $version . ' installed.
                                        The button you should click to upgrade has been highlighted for you. However, if
                                        Mods for HESK selected the wrong version, click <a href="javascript:void(0)" onclick="enableAllDisablable();">here</a> to reset them.</p>';
                                        echo '</div>';
                                        echo '</div>';
                                    }
                                }
                                ?>
                                <div class="row">
                                    <div class="col-md-3 col-sm-12">
                                        <?php
                                        if ($version == '1.7.0') {
                                            $v170btn = 'btn-success';
                                            $disableAllExcept = '170';
                                        } else {
                                            $v170btn = 'btn-default';
                                        }
                                        ?>
                                        <a id="170" class="btn <?php echo $v170btn; ?> btn-block disablable" href="installModsForHesk.php?v=170">v1.7.0</a>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <?php
                                        if ($version == '1.6.1') {
                                            $v161btn = 'btn-success';
                                            $disableAllExcept = '161';
                                        } else {
                                            $v161btn = 'btn-default';
                                        }
                                        ?>
                                        <a id="161" class="btn <?php echo $v161btn; ?> btn-block disablable" href="installModsForHesk.php?v=161">v1.6.1</a>
                                    </div>
                                    <div class="col-md-3 col-sm-12">

                                        <?php
                                        if ($version == '1.6.0') {
                                            $v160btn = 'btn-success';
                                            $disableAllExcept = '160';
                                        } else {
                                            $v160btn = 'btn-default';
                                        }
                                        ?>
                                        <a id="160" class="btn <?php echo $v160btn; ?> btn-block disablable" href="installModsForHesk.php?v=160">v1.6.0</a>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <a id="150" class="btn btn-default btn-block disablable" href="installModsForHesk.php?v=150">v1.5.0</a>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-3 col-sm-12">
                                        <a id="141" class="btn btn-default btn-block disablable" href="installModsForHesk.php?v=141">v1.4.1</a>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <a id="140" class="btn btn-default btn-block disablable" href="installModsForHesk.php?v=140">v1.4.0</a>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <a id="130" class="btn btn-default btn-block disablable" href="installModsForHesk.php?v=130">v1.3.0</a>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <a id="124" class="btn btn-default btn-block disablable" href="installModsForHesk.php?v=124">v1.2.4</a>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <a class="btn btn-default btn-block disablable" href="installModsForHesk.php?v=0">I do not currently have Mods for HESK installed</a>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-sm-12">
                                        By proceeding, you agree to the terms of the <a href="https://creativecommons.org/licenses/by-sa/4.0/" target="_blank">Creative Commons Attribution-ShareAlike 4.0 International License.</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if ($disableAllExcept !== NULL) {
            echo '<script>disableAllDisablable(\''.$disableAllExcept.'\')</script>';
        }
        ?>
    </body>
</html>