<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit22ddb69348c7ed922c96325249cef3d0
{
    public static $files = array (
        'bf9f5270ae66ac6fa0290b4bf47867b7' => __DIR__ . '/..' . '/adodb/adodb-php/adodb.inc.php',
        'd5fa61a7f6cbc1df09dd4df84549a2dc' => __DIR__ . '/..' . '/rospdf/pdf-php/src/Cpdf.php',
        '2d15964294879de66053d54f6bde65d7' => __DIR__ . '/..' . '/rospdf/pdf-php/src/Cezpdf.php',
        'decc78cc4436b1292c6c0d151b19445c' => __DIR__ . '/..' . '/phpseclib/phpseclib/phpseclib/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'p' => 
        array (
            'phpseclib\\' => 10,
        ),
        'Z' => 
        array (
            'Zend\\' => 5,
        ),
        'D' => 
        array (
            'Dompdf\\' => 7,
            'Doctrine\\Common\\Cache\\' => 22,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'phpseclib\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpseclib/phpseclib/phpseclib',
        ),
        'Zend\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zendframework/library/Zend',
        ),
        'Dompdf\\' => 
        array (
            0 => __DIR__ . '/..' . '/dompdf/dompdf/src',
        ),
        'Doctrine\\Common\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/doctrine/cache/lib/Doctrine/Common/Cache',
        ),
    );

    public static $prefixesPsr0 = array (
        'Z' => 
        array (
            'ZendXml\\' => 
            array (
                0 => __DIR__ . '/..' . '/zendframework/zendxml/library',
            ),
        ),
        'S' => 
        array (
            'Svg\\' => 
            array (
                0 => __DIR__ . '/..' . '/phenx/php-svg-lib/src',
            ),
        ),
        'F' => 
        array (
            'FontLib\\' => 
            array (
                0 => __DIR__ . '/..' . '/phenx/php-font-lib/src',
            ),
        ),
        'D' => 
        array (
            'Doctrine\\CouchDB' => 
            array (
                0 => __DIR__ . '/..' . '/doctrine/couchdb/lib',
            ),
            'Doctrine\\Common\\Lexer\\' => 
            array (
                0 => __DIR__ . '/..' . '/doctrine/lexer/lib',
            ),
            'Doctrine\\Common\\Inflector\\' => 
            array (
                0 => __DIR__ . '/..' . '/doctrine/inflector/lib',
            ),
            'Doctrine\\Common\\Collections\\' => 
            array (
                0 => __DIR__ . '/..' . '/doctrine/collections/lib',
            ),
            'Doctrine\\Common\\Annotations\\' => 
            array (
                0 => __DIR__ . '/..' . '/doctrine/annotations/lib',
            ),
            'Doctrine\\Common\\' => 
            array (
                0 => __DIR__ . '/..' . '/doctrine/common/lib',
            ),
        ),
    );

    public static $classMap = array (
        'Address' => __DIR__ . '/../..' . '/library/classes/Address.class.php',
        'CategoryTree' => __DIR__ . '/../..' . '/library/classes/CategoryTree.class.php',
        'Company' => __DIR__ . '/../..' . '/library/classes/Company.class.php',
        'ConfigTree' => __DIR__ . '/../..' . '/library/classes/ConfigTree.class.php',
        'Config_File' => __DIR__ . '/..' . '/smarty/smarty/libs/Config_File.class.php',
        'Controller' => __DIR__ . '/../..' . '/library/classes/Controller.class.php',
        'CouchDB' => __DIR__ . '/../..' . '/library/classes/CouchDB.class.php',
        'Cpdf' => __DIR__ . '/..' . '/dompdf/dompdf/lib/Cpdf.php',
        'Document' => __DIR__ . '/../..' . '/library/classes/Document.class.php',
        'EasyPeasyICS' => __DIR__ . '/..' . '/phpmailer/phpmailer/extras/EasyPeasyICS.php',
        'HTML2Text' => __DIR__ . '/../..' . '/library/classes/HTML2Text.class.php',
        'HTML5_Data' => __DIR__ . '/..' . '/dompdf/dompdf/lib/html5lib/Data.php',
        'HTML5_InputStream' => __DIR__ . '/..' . '/dompdf/dompdf/lib/html5lib/InputStream.php',
        'HTML5_Parser' => __DIR__ . '/..' . '/dompdf/dompdf/lib/html5lib/Parser.php',
        'HTML5_Tokenizer' => __DIR__ . '/..' . '/dompdf/dompdf/lib/html5lib/Tokenizer.php',
        'HTML5_TreeBuilder' => __DIR__ . '/..' . '/dompdf/dompdf/lib/html5lib/TreeBuilder.php',
        'HTML_TreeMenu' => __DIR__ . '/../..' . '/library/classes/HTML_TreeMenu.class.php',
        'HTML_TreeMenu_DHTML' => __DIR__ . '/../..' . '/library/classes/HTML_TreeMenu_DHTML.class.php',
        'HTML_TreeMenu_Listbox' => __DIR__ . '/../..' . '/library/classes/HTML_TreeMenu_listbox.class.php',
        'HTML_TreeMenu_Presentation' => __DIR__ . '/../..' . '/library/classes/HTML_TreeMenu_Presentation.class.php',
        'HTML_TreeNode' => __DIR__ . '/../..' . '/library/classes/HTML_TreeNode.class.php',
        'Handler_HL7v2' => __DIR__ . '/../..' . '/library/classes/Handler_HL7v2.class.php',
        'InsuranceCompany' => __DIR__ . '/../..' . '/library/classes/InsuranceCompany.class.php',
        'InsuranceNumbers' => __DIR__ . '/../..' . '/library/classes/InsuranceNumbers.class.php',
        'MyMailer' => __DIR__ . '/../..' . '/library/classes/MyMailer.class.php',
        'Note' => __DIR__ . '/../..' . '/library/classes/Note.class.php',
        'NumberToText' => __DIR__ . '/../..' . '/library/classes/NumberToText.class.php',
        'OFX' => __DIR__ . '/../..' . '/library/classes/OFX.class.php',
        'ORDataObject' => __DIR__ . '/../..' . '/library/classes/ORDataObject.class.php',
        'PHPMailer' => __DIR__ . '/..' . '/phpmailer/phpmailer/class.phpmailer.php',
        'PHPMailerOAuth' => __DIR__ . '/..' . '/phpmailer/phpmailer/class.phpmaileroauth.php',
        'PHPMailerOAuthGoogle' => __DIR__ . '/..' . '/phpmailer/phpmailer/class.phpmaileroauthgoogle.php',
        'POP3' => __DIR__ . '/..' . '/phpmailer/phpmailer/class.pop3.php',
        'POSRef' => __DIR__ . '/../..' . '/library/classes/POSRef.class.php',
        'PQRIXml' => __DIR__ . '/../..' . '/library/classes/PQRIXml.class.php',
        'Parser_HL7v2' => __DIR__ . '/../..' . '/library/classes/Parser_HL7v2.class.php',
        'Patient' => __DIR__ . '/../..' . '/library/classes/Patient.class.php',
        'Person' => __DIR__ . '/../..' . '/library/classes/Person.class.php',
        'Pharmacy' => __DIR__ . '/../..' . '/library/classes/Pharmacy.class.php',
        'PhoneNumber' => __DIR__ . '/../..' . '/library/classes/PhoneNumber.class.php',
        'Prescription' => __DIR__ . '/../..' . '/library/classes/Prescription.class.php',
        'Provider' => __DIR__ . '/../..' . '/library/classes/Provider.class.php',
        'QRDAXml' => __DIR__ . '/../..' . '/library/classes/QRDAXml.class.php',
        'RxList' => __DIR__ . '/../..' . '/library/classes/RXList.class.php',
        'SMTP' => __DIR__ . '/..' . '/phpmailer/phpmailer/class.smtp.php',
        'Smarty' => __DIR__ . '/..' . '/smarty/smarty/libs/Smarty.class.php',
        'Smarty_Compiler' => __DIR__ . '/..' . '/smarty/smarty/libs/Smarty_Compiler.class.php',
        'Thumbnail' => __DIR__ . '/../..' . '/library/classes/thumbnail/Thumbnail.class.php',
        'ThumbnailGenerator' => __DIR__ . '/../..' . '/library/classes/thumbnail/ThumbnailGenerator.class.php',
        'Tree' => __DIR__ . '/../..' . '/library/classes/Tree.class.php',
        'X12Partner' => __DIR__ . '/../..' . '/library/classes/X12Partner.class.php',
        'XmlWriterOemr' => __DIR__ . '/../..' . '/library/classes/XmlWriterOemr.class.php',
        'ntlm_sasl_client_class' => __DIR__ . '/..' . '/phpmailer/phpmailer/extras/ntlm_sasl_client.php',
        'phpmailerException' => __DIR__ . '/..' . '/phpmailer/phpmailer/class.phpmailer.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit22ddb69348c7ed922c96325249cef3d0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit22ddb69348c7ed922c96325249cef3d0::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit22ddb69348c7ed922c96325249cef3d0::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit22ddb69348c7ed922c96325249cef3d0::$classMap;

        }, null, ClassLoader::class);
    }
}
