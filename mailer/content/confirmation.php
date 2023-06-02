<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:v="urn:schemas-microsoft-com:vml">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet' type='text/css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="font-size: 16px; font-family: 'Poppins', sans-serif;">

    <div>
        <!--[if gte mso 9]>
            <v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t">
                <v:fill type="tile" src="http://cherchertrouver-v004/views/public/img/background.png"/>
            </v:background>
        <![endif]-->
        <table height="100%" width="100%" cellpadding="30" cellspacing="0" border="0">
            <tbody>
                <tr>
                    <td valign="top" background="http://cherchertrouver-v004/views/public/img/background.png">
                        <table align="center" width="100%" cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td>
                                        <table bgcolor="#212529" width="100%" cellpadding="8" cellspacing="0" style="border-radius: 8px;">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table width="100%" style="color: #ffffff;">
                                                            <tr align="center">
                                                                <td style="font-size: 36px;">
                                                                    <b>Chercher & Trouver</b>
                                                                </td>
                                                            </tr>
                                                            <tr align="center">
                                                                <td>
                                                                    La référence des petites annonces de matériel informatique sur Sète et ses alentours
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tbody>
                                        <tr>
                                            <td height="30" style="font-size: 30px; line-height: 30px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="font-size: 28px;">
                                                <table bgcolor="#0d6efd" width="100%" cellpadding="8" cellspacing="0" style="color: #ffffff; border-radius: 8px;">
                                                    <tbody>
                                                        <tr>
                                                            <td align="center" style="font-size: 22px;">
                                                                <b>Bienvenue <?= $___MAIL_DATA___["user"]["pseudo"] ?></b>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="30" style="font-size: 30px; line-height: 30px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td align="left">
                                                Vous venez de vous inscrire sur notre site internet. Afin d'acceder à l'intégralité des fonctionnalités du site nous vous invitons à <b>confirmer votre adresse email en cliquant <a href="<?= RouterDictionnary::buildURL("EmailConf", [$___MAIL_DATA___["key"]]) ?>">ici</a></b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="30" style="font-size: 30px; line-height: 30px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td align="left">
                                                <table bgcolor="#0d6efd" width="100%" cellpadding="8" cellspacing="0" style="color: #ffffff; border-radius: 8px;">
                                                    <tbody>
                                                        <tr>
                                                            <td align="center" style="font-size: 22px;">
                                                                <b>Rappel de vos informations personnelles</b>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="30" style="font-size: 30px; line-height: 30px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td align="left">
                                                Pseudo : <?= $___MAIL_DATA___["user"]["pseudo"] ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left">
                                                Prénom : <?= $___MAIL_DATA___["user"]["firstname"] ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left">
                                                Nom : <?= $___MAIL_DATA___["user"]["lastname"] ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</html>