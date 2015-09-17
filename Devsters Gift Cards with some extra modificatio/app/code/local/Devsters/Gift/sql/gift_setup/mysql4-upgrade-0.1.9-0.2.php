<?php
$installer = $this;
/* @var $installer mag_core_email_template */

$installer->startSetup();
$installer->run('

CREATE TABLE IF NOT EXISTS `mag_core_email_template` (
  `template_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Template Id',
  `template_code` varchar(150) NOT NULL COMMENT 'Template Name',
  `template_text` text NOT NULL COMMENT 'Template Content',
  `template_styles` text COMMENT 'Templste Styles',
  `template_type` int(10) unsigned DEFAULT NULL COMMENT 'Template Type',
  `template_subject` varchar(200) NOT NULL COMMENT 'Template Subject',
  `template_sender_name` varchar(200) DEFAULT NULL COMMENT 'Template Sender Name',
  `template_sender_email` varchar(200) DEFAULT NULL COMMENT 'Template Sender Email',
  `added_at` timestamp NULL DEFAULT NULL COMMENT 'Date of Template Creation',
  `modified_at` timestamp NULL DEFAULT NULL COMMENT 'Date of Template Modification',
  `orig_template_code` varchar(200) DEFAULT NULL COMMENT 'Original Template Code',
  `orig_template_variables` text COMMENT 'Original Template Variables',
  PRIMARY KEY (`template_id`),
  UNIQUE KEY `UNQ_MAG_CORE_EMAIL_TEMPLATE_TEMPLATE_CODE` (`template_code`),
  KEY `IDX_MAG_CORE_EMAIL_TEMPLATE_ADDED_AT` (`added_at`),
  KEY `IDX_MAG_CORE_EMAIL_TEMPLATE_MODIFIED_AT` (`modified_at`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Email Templates' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `mag_core_email_template`
--

INSERT INTO `mag_core_email_template` (`template_id`, `template_code`, `template_text`, `template_styles`, `template_type`, `template_subject`, `template_sender_name`, `template_sender_email`, `added_at`, `modified_at`, `orig_template_code`, `orig_template_variables`) VALUES
(444, 'Order Update with Gift Card Number', '<body style="background:#F6F6F6; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">\r\n<div style="background:#F6F6F6; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">\r\n<table cellspacing="0" cellpadding="0" border="0" height="100%" width="100%">\r\n<tr>\r\n    <td align="center" valign="top" style="padding:20px 0 20px 0">\r\n        <!-- [ header starts here] -->\r\n        <table bgcolor="#FFFFFF" cellspacing="0" cellpadding="10" border="0" width="650" style="border:1px solid #E0E0E0;">\r\n            <tr>\r\n                <td valign="top"><a href="{{store url=""}}"><img src="{{var logo_url}}" alt="{{var logo_alt}}" style="margin-bottom:10px;" border="0"/></a></td>\r\n            </tr>\r\n            <!-- [ middle starts here] -->\r\n            <tr>\r\n                <td valign="top">\r\n                    <h1 style="font-size:22px; font-weight:normal; line-height:22px; margin:0 0 11px 0;">Dear {{htmlescape var=$order.getCustomerName()}},</h1>\r\n                    <p style="font-size:12px; line-height:16px; margin:0 0 10px 0;">\r\n                        Your order {{var order.increment_id}} for a Gift Card has been Completed.<br/>\r\n                        <strong>{{var order.getStatusLabel()}}</strong>.<br/>\r\n                        <strong>Your Gift Card Number is: {{var devstersGiftCardNumber}}</strong>\r\n                    </p>\r\n                    <p style="font-size:12px; line-height:16px; margin:0 0 10px 0;">You can check the status of your order by <a href="{{store url="customer/account/"}}" style="color:#1E7EC8;">logging into your account</a>.</p>\r\n                    <p style="font-size:12px; line-height:16px; margin:0 0 10px 0;">{{var comment}}</p>\r\n                    <p style="font-size:12px; line-height:16px; margin:0;">\r\n                        If you have any questions, please feel free to contact us at\r\n                        <a href="mailto:{{config path=''trans_email/ident_support/email''}}" style="color:#1E7EC8;">{{config path=''trans_email/ident_support/email''}}</a>\r\n                        or by phone at {{config path=''general/store_information/phone''}}.\r\n                    </p>\r\n                </td>\r\n            </tr>\r\n            <tr>\r\n                <td bgcolor="#EAEAEA" align="center" style="background:#EAEAEA; text-align:center;"><center><p style="font-size:12px; margin:0;">Thank you again, <strong>{{var store.getFrontendName()}}</strong></p></center>\r\n   </td>\r\n\r\n            </tr>\r\n        </table>\r\n    </td>\r\n</tr>\r\n</table>\r\n</div>\r\n</body>', 'body,td { color:"#2f2f2f"; font:11px/1.35em Verdana, Arial, Helvetica, sans-serif; }', 2, '{{var store.getFrontendName()}}: Order number {{var order.increment_id}} update', NULL, NULL, NULL, '2013-11-10 11:01:53', 'sales_email_order_comment_template', '{"store url=\\"\\"":"Store Url","var logo_url":"Email Logo Image Url","var logo_alt":"Email Logo Image Alt","htmlescape var=$order.getCustomerName()":"Customer Name","var order.increment_id":"Order Id","var order.getStatusLabel()":"Order Status","store url=\\"customer/account/\\"":"Customer Account Url","var comment":"Order Comment","var store.getFrontendName()":"Store Name"}');

');
$installer->endSetup();
