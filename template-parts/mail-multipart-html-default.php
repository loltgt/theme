<?php
/**
 * Mail multipart MIME html template part
 *
 * //TODO test
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN\" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" width="device-width">
<style type="text/css">
@media only screen and (min-device-width: 481px) { div[id="main"] { width: 480px !important; } }
</style>
<!--[if mso]>
<style>
body, table, tr, td, h1, h2, h3, h4, h5, h6, center, p, a, span { {{DEFAULT_STYLE}} }
</style>
<![endif]-->
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" marginheight="0" marginwidth="0" style="-webkit-font-smoothing: antialiased; width: 100% !important; -webkit-text-size-adjust: none; margin: 0; padding: 0;">
<table cellpadding="0" cellspacing="0" border="0" valign="top" width="100%" align="center" style="width:  100%; max-width: 480px;">
<tr><td valign="top" align="left" style="{{DEFAULT_STYLE}} word-break: normal; border-collapse: collapse;">
<center><div id="main">{{BODY}}</div></center>
</td></tr>
</table>
</body>
</html>