<?php


?>

<html>
<head>
<title>OpenEMR Setup Tool</title>
<link href="public/assets/bootstrap-3-3-4/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <div class="page-header">
            <h1>OpenEMR</h1>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <div class="page-header">
            <h1><?=t('Install concrete5')?></h1>
            <p>
                <?=t('Version %s', Config::get('concrete.version'))?>
            </p>
        </div>
        <h3><?=t('Testing Required Items')?></h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-5 col-sm-offset-1">
        <table class="table table-striped requirements-table">
            <tbody>
                <tr>
                    <td class="ccm-test-phpversion">
                        <?php if ($phpVtest) {
    ?><i class="fa fa-check"></i>
                        <?php
} else {
    ?><i class="fa fa-exclamation-circle"></i>
                            <?php
}
    ?>
                    </td>
                    <td width="100%">
                        <?=t(/*i18n: %s is the php version*/'PHP %s', $phpVmin)?>
                    </td>
                    <td>
                        <?php if (!$phpVtest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="<?=t('concrete5 requires at least PHP %s', $phpVmin)?>"></i>
                        <?php
}
    ?>
                    </td>
                </tr>
                <tr>
                    <td class="ccm-test-js"><i id="ccm-test-js-success" class="fa fa-check" style="display: none"></i>
                        <i class="fa fa-exclamation-circle"></i></td>
                    <td width="100%">
                        <?=t('JavaScript Enabled')?>
                    </td>
                    <td class="ccm-test-js"><i class="fa fa-question-circle launch-tooltip" title="<?=t('Please enable JavaScript in your browser.')?>"></i></td>
                </tr>
                <tr>
                    <td>
                        <?php if ($mysqlTest) {
        ?><i class="fa fa-check"></i>
                        <?php
} else {
    ?><i class="fa fa-exclamation-circle"></i>
                    <?php
}
    ?>
                    </td>
                    <td width="100%">
                        <?=t('MySQL PDO Extension Enabled')?>
                    </td>
                    <td>
                        <?php if (!$mysqlTest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="<?=$this->controller->getDBErrorMsg()?>"></i>
                        <?php
}
    ?>
                    </td>
                </tr>
                <tr>
                    <td><i id="ccm-test-request-loading" class="fa fa-spinner fa-spin"></i></td>
                    <td width="100%">
                        <?=t('Supports concrete5 request URLs')?>
                    </td>
                    <td><i id="ccm-test-request-tooltip" class="fa fa-question-circle launch-tooltip" title="<?=t('concrete5 cannot parse the PATH_INFO or ORIG_PATH_INFO information provided by your server.')?>"></i></td>
                </tr>
                <tr>
                    <td>
                        <?php if ($jsonTest) {
        ?><i class="fa fa-check"></i>
                        <?php
} else {
    ?><i class="fa fa-exclamation-circle"></i>
                    <?php
}
    ?>
                    </td>
                    <td width="100%">
                        <?=t('JSON Extension Enabled')?>
                    </td>
                    <td>
                        <?php if (!$jsonTest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="<?=t('You must enable PHP\'s JSON support. This should be enabled by default in PHP 5.2 and above.')?>"></i>
                        <?php
}
    ?> 
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php if ($domTest) {
        ?><i class="fa fa-check"></i>
                        <?php
} else {
    ?><i class="fa fa-exclamation-circle"></i>
                <?php
} ?>
                    </td>
                    <td width="100%">
                        <?php echo t('DOM Extension Enabled')?>
                    </td>
                    <td>
                        <?php if (!$domTest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="<?php echo t('You must enable PHP\'s DOM support.')?>"></i>
                        <?php
} ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php if ($aspTagsTest) {
        ?><i class="fa fa-check"></i>
                        <?php
} else {
    ?><i class="fa fa-exclamation-circle"></i>
                <?php
} ?>
                    </td>
                    <td width="100%">
                        <?=t('ASP Style Tags Disabled')?>
                    </td>
                    <td>
                        <?php if (!$aspTagsTest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="<?=t('You must disable PHP\'s ASP Style Tags.')?>"></i>
                        <?php
}
    ?>
                    </td>
                </tr>
        </table>
    </div>
    <div class="col-sm-5">
        <table class="table table-striped requirements-table">
            <tr>
                <td>
                    <?php if ($imageTest) {
        ?><i class="fa fa-check"></i>
                    <?php
} else {
    ?><i class="fa fa-exclamation-circle"></i>
                <?php
}
    ?>
                </td>
                <td width="100%">
                    <?=t('Image Manipulation Available')?>
                </td>
                <td>
                    <?php if (!$imageTest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="<?=t('concrete5 requires GD library 2.0.1 with JPEG, PNG and GIF support. Doublecheck that your installation has support for all these image types.')?>"></i>
                    <?php
}
    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php if ($xmlTest) {
        ?><i class="fa fa-check"></i>
                    <?php
} else {
    ?><i class="fa fa-exclamation-circle"></i>
                <?php
}
    ?>
                </td>
                <td width="100%">
                    <?=t('XML Support')?>
                </td>
                <td>
                    <?php if (!$xmlTest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="<?=t('concrete5 requires PHP XML Parser and SimpleXML extensions')?>"></i>
                    <?php
}
    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php if ($fileWriteTest) {
        ?><i class="fa fa-check"></i>
                    <?php
} else {
    ?><i class="fa fa-exclamation-circle"></i>
                <?php
}
    ?>
                </td>
                <td width="100%">
                    <?=t('Writable Files and Configuration Directories')?>
                </td>
                <td>
                    <?php if (!$fileWriteTest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="<?=t('The packages/, application/config/ and application/files/ directories must be writable by your web server.')?>"></i>
                    <?php
}
    ?>
                </td>
            </tr>
            <tr>
                <td><i id="ccm-test-cookies-enabled-loading" class="fa fa-spinner fa-spin"></i></td>
                <td width="100%">
                    <?=t('Cookies Enabled')?>
                </td>
                <td><i id="ccm-test-cookies-enabled-tooltip" class="fa fa-question-circle launch-tooltip" title="<?=t('Cookies must be enabled in your browser to install concrete5.')?>"></i></td>
            </tr>
            <tr>
                <td>
                    <?php if ($i18nTest) {
        ?><i class="fa fa-check"></i>
                    <?php
} else {
    ?><i class="fa fa-exclamation-circle"></i>
                <?php
}
    ?>
                </td>
                <td width="100%">
                    <?=t('Internationalization Support')?>
                </td>
                <td>
                    <?php if (!$i18nTest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="<?=t('You must enable ctype and multibyte string (mbstring) support in PHP.')?>"></i>
                    <?php
}
    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php if ($docCommentTest) {
        ?><i class="fa fa-check"></i>
                    <?php
} else {
    ?><i class="fa fa-exclamation-circle"></i>
                <?php
}
    ?>
                </td>
                <td width="100%">
                    <?=t('PHP Comments Preserved')?>
                        <td>
                            <?php if (!$docCommentTest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="<?=t('concrete5 is not compatible with opcode caches that strip PHP comments. Certain configurations of eAccelerator and Zend opcode caching may use this behavior, and it must be disabled.')?>"></td><?php
}
    ?></td>
</tr>
</table>

</div>
</div>


<div class="row">
<div class="col-sm-10 col-sm-offset-1">

<h3><?=t('Testing Optional Items')?></h3>

</div>
</div>

<div class="row">
<div class="col-sm-5 col-sm-offset-1">

<table class="table table-striped requirements-table">
<tbody>
<tr>
    <td><?php if ($remoteFileUploadTest) {
        ?><i class="fa fa-check"></i>
                            <?php
} else {
    ?><i class="fa fa-warning"></i>
                        <?php
}
    ?>
                        </td>
                        <td width="100%">
                            <?=t('Remote File Importing Available')?>
                        </td>
                        <td>
                            <?php if (!$remoteFileUploadTest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="<?=t('Remote file importing through the file manager requires the iconv PHP extension.')?>"></i>
                            <?php
}
    ?>
                        </td>
            </tr>
        </table>
    </div>
    <div class="col-sm-5">
        <table class="table table-striped requirements-table">
            <tbody>
                <tr>
                    <td>
                        <?php if ($fileZipTest) {
        ?><i class="fa fa-check"></i>
                        <?php
} else {
    ?><i class="fa fa-warning"></i>
                    <?php
}
    ?>
                    </td>
                    <td width="100%">
                        <?=t('Zip Support')?>
                    </td>
                    <td>
                        <?php if (!$fileZipTest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="<?=t('Downloading zipped files from the file manager, remote updating and marketplace integration requires the Zip extension.')?>"></i>
                        <?php
}
    ?>
                    </td>
                </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <h3><?=t('Memory Requirements')?></h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <table class="table table-striped requirements-table">
            <tbody>
                <tr>
                    <td>
                        <?php if ($memoryTest === -1) {
        ?>
                        <i class="fa fa-exclamation-circle"></i>
                        <?php
} elseif ($memoryTest === 1) {
    ?>
                    <i class="fa fa-check"></i>
                    <?php
} else {
    ?>
                        <i class="fa fa-warning"></i>
                        <?php
}
    ?>
                    </td>
                    <td width="100%">
                        <?php if ($memoryTest === -1) {
        ?>
                        <span class="text-danger"><?=t('concrete5 will not install with less than 24MB of RAM.
                            Your memory limit is currently %sMB. Please increase your memory_limit using ini_set.', round(Core::make('helper/number')->formatSize($memoryBytes, 'MB'), 2))?>
                            </span>
                        <?php
}
    ?>
                            <?php if ($memoryTest === 0) {
        ?>
                            <span class="text-warning"><?=t('concrete5 runs best with at least 64MB of RAM.
                            Your memory limit is currently %sMB. You may experience problems uploading and resizing large images, and may have to install concrete5 without sample content.', round(Core::make('helper/number')->formatSize($memoryBytes, 'MB'), 2))?></span>
                            <?php
}
    ?>
                                <?php if ($memoryTest === 1) {
        ?>
                                <span class="text-success"><?=t('Memory limit %sMB.', round(Core::make('helper/number')->formatSize($memoryBytes, 'MB'), 2))?></span>
                                <?php
}
    ?>
                    </td>
                </tr>
        </table>
    </div>
</div>




</body>
</html>