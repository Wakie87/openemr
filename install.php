<?php


?>

<html>
<head>
<title>OpenEMR Setup Tool</title>
<link href="public/assets/bootstrap-3-3-4/dist/css/bootstrap.min.css" rel="stylesheet">
<style type="text/css">@import "library/css/install.css" </style>

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
            <h1>Install concrete5</h1>
            <p>
                Version %s', <concrete class="versio"></concrete>n'))?>
            </p>
        </div>
        <h3>Testing Required Items</h3>
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
                        PHP %s
                    </td>
                    <td>
                        <?php if (!$phpVtest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="concrete5 requires at least PHP %s', $phpVmin)?>"></i>
                        <?php

    }
    ?>
                    </td>
                </tr>
                <tr>
                    <td class="ccm-test-js"><i id="ccm-test-js-success" class="fa fa-check" style="display: none"></i>
                        <i class="fa fa-exclamation-circle"></i></td>
                    <td width="100%">
                        JavaScript Enabled
                    </td>
                    <td class="ccm-test-js"><i class="fa fa-question-circle launch-tooltip" title="Please enable JavaScript in your browser."></i></td>
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
                        MySQL PDO Extension Enabled
                    </td>
                    <td>
                        <?php if (!$mysqlTest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="<?php echo $this->controller->getDBErrorMsg()?>"></i>
                        <?php

    }
    ?>
                    </td>
                </tr>
                <tr>
                    <td><i id="ccm-test-request-loading" class="fa fa-spinner fa-spin"></i></td>
                    <td width="100%">
                        Supports concrete5 request URLs
                    </td>
                    <td><i id="ccm-test-request-tooltip" class="fa fa-question-circle launch-tooltip" title="concrete5 cannot parse the PATH_INFO or ORIG_PATH_INFO information provided by your server."></i></td>
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
                        JSON Extension Enabled
                    </td>
                    <td>
                        <?php if (!$jsonTest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="You must enable PHP\'s JSON support. This should be enabled by default in PHP 5.2 and above."></i>
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
                        DOM Extension Enabled
                    </td>
                    <td>
                        <?php if (!$domTest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="You must enable PHP\'s DOM support."></i>
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
                        ASP Style Tags Disabled
                    </td>
                    <td>
                        <?php if (!$aspTagsTest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="You must disable PHP\'s ASP Style Tags."></i>
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
                    Image Manipulation Available
                </td>
                <td>
                    <?php if (!$imageTest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="concrete5 requires GD library 2.0.1 with JPEG, PNG and GIF support. Doublecheck that your installation has support for all these image types."></i>
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
                    XML Support
                </td>
                <td>
                    <?php if (!$xmlTest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="concrete5 requires PHP XML Parser and SimpleXML extensions"></i>
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
                    Writable Files and Configuration Directories
                </td>
                <td>
                    <?php if (!$fileWriteTest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="The packages/, application/config/ and application/files/ directories must be writable by your web server."></i>
                    <?php

    }
    ?>
                </td>
            </tr>
            <tr>
                <td><i id="ccm-test-cookies-enabled-loading" class="fa fa-spinner fa-spin"></i></td>
                <td width="100%">
                    Cookies Enabled
                </td>
                <td><i id="ccm-test-cookies-enabled-tooltip" class="fa fa-question-circle launch-tooltip" title="Cookies must be enabled in your browser to install concrete5."></i></td>
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
                    Internationalization Support
                </td>
                <td>
                    <?php if (!$i18nTest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="You must enable ctype and multibyte string (mbstring) support in PHP."></i>
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
                    PHP Comments Preserved
                        <td>
                            <?php if (!$docCommentTest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="concrete5 is not compatible with opcode caches that strip PHP comments. Certain configurations of eAccelerator and Zend opcode caching may use this behavior, and it must be disabled."></td><?php

    }
    ?></td>
</tr>
</table>

</div>
</div>


<div class="row">
<div class="col-sm-10 col-sm-offset-1">

<h3>Testing Optional Items</h3>

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
                            Remote File Importing Available
                        </td>
                        <td>
                            <?php if (!$remoteFileUploadTest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="Remote file importing through the file manager requires the iconv PHP extension."></i>
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
                        Zip Support
                    </td>
                    <td>
                        <?php if (!$fileZipTest) {
        ?><i class="fa fa-question-circle launch-tooltip" title="Downloading zipped files from the file manager, remote updating and marketplace integration requires the Zip extension."></i>
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
        <h3>Memory Requirements</h3>
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
                        <span class="text-danger">concrete5 will not install with less than 24MB of RAM.
                            Your memory limit is currently %sMB. Please increase your memory_limit using ini_set.', round(Core::make('helper/number')->formatSize($memoryBytes, 'MB'), 2))?>
                            </span>
                        <?php

    }
    ?>
                            <?php if ($memoryTest === 0) {
        ?>
                            <span class="text-warning">concrete5 runs best with at least 64MB of RAM.
                            Your memory limit is currently %sMB. You may experience problems uploading and resizing large images, and may have to install concrete5 without sample content.', round(Core::make('helper/number')->formatSize($memoryBytes, 'MB'), 2))?></span>
                            <?php

    }
    ?>
                                <?php if ($memoryTest === 1) {
        ?>
                                <span class="text-success">Memory limit %sMB.', round(Core::make('helper/number')->formatSize($memoryBytes, 'MB'), 2))?></span>
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