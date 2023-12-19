<?php 
function get_email_header(){
	return '<!DOCTYPE html>
    <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width,initial-scale=1">
      <meta name="x-apple-disable-message-reformatting">
      <title></title>
      <!--[if mso]>
      <style>
        table {border-collapse:collapse;border-spacing:0;border:none;margin:0;}
        div, td {padding:0;}
        div {margin:0 !important;} 	 
      </style>
      <noscript>
        <xml>
          <o:OfficeDocumentSettings>
            <o:PixelsPerInch>96</o:PixelsPerInch>
          </o:OfficeDocumentSettings>
        </xml>
      </noscript>
      <![endif]-->
      <style>
        @import url("https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap");
          
        table, td, div, h1, h2,h3,h4,p {
          font-family: Open Sans, sans-serif;
        }
        
          
        @media screen and (max-width: 530px) {
          .unsub {
            display: block;
            padding: 8px;
            margin-top: 14px;
            border-radius: 6px;
            background-color: #555555;
            text-decoration: none !important;
            font-weight: bold;
          }
          .col-lge {
            max-width: 100% !important;
          }
        }
        @media screen and (min-width: 531px) {
          .col-sml {
            max-width: 50% !important;
          }
          .col-lge {
            max-width: 73% !important;
          }
        }
      </style>
    </head>
    <body style="margin:0;padding:0;word-spacing:normal;background-color:#f7f3ff;">
         <center>	 
      <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#f7f3ff; max-width: 600px; margin: 0 auto;">	 
        <table role="presentation" style="width:100%;border:none;border-spacing:0;">
          <tr>
            <td align="center" style="padding:0;">
              <!--[if mso]>
              <table role="presentation" align="center" style="width:600px;">
              <tr>
              <td>
              <![endif]-->
              <table role="presentation" border="0;" style="width:100%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636; background-color:#fff;margin: 15px 0px;" background="#fff">
                <tr>
                  <td style="padding: 10px 20px;"  bgcolor="#fff" bordercolor="fff"></td>
                </tr>
                <tr>
                  <td style="padding: 10px 20px;text-align:center;font-size:24px;font-weight:bold; background-color:#fff;">
                    <a href="#" target="_blank" style="text-decoration:none;"><img src="https://carefree.unbot.com/wp-content/uploads/2022/05/temp-logo-crop.png" width="130" alt="Logo" style="width:130px;max-width:300%;height:auto;border:none;text-decoration:none;color:#ffffff;"></a>
                  </td>
                </tr>
                <tr>
                    <td style="padding: 5px 20px; border-bottom: 1px solid #dedede;"  bgcolor="#fff" bordercolor="dedede">				  
                    </td>
                </tr>			 
                   <!-- Mail Body row start -->
                <tr>
                  <td style="padding:20px;background-color:#fff; color: #666666; text-align: center;" align="center">
                      <table border="0" cellpadding="0" cellspacing="0" width="100%">
                      <tr>
                        <td style="border: 0px solid #fff; width: 100%;padding:20px 0px;" align="left">						  ';
}

function get_email_footer()
{
    return '	<!-- Mail body content ends here-->
            </td>
            </tr>
        </table>         
        </td>
        </tr> <!-- Mail Body row ends -->
        <!-- Secure Home row start -->			 
        <tr>
            <td bgcolor="#151515" style="padding:20px;background-color:#151515; font-size: 12px; text-align: center; color: #fff; font-family: Arial,sans-serif;" align="center">
                <p style="margin:0;color: #fff;">&copy; 2023 Carefree of Colorado. All Rights Reserved.</p>
            </td>
        </tr>

        </table>
        <!--[if mso]>
        </td>
        </tr>
        </table>
        <![endif]-->
        </td>
        </tr>
        </table>		  
        </div>
        </center> 
        </body>
        </html>';
}

add_filter('wp_mail', 'my_wp_mail');

function my_wp_mail($atts)
{
	if ($atts['subject'] == 'Reset Password') {
		$atts['message'] = get_email_header() . $atts['message'] . get_email_footer();
	} else {
		$atts['message'] = get_email_header() . apply_filters('the_content', $atts['message']) . get_email_footer();
	}

	return $atts;
}