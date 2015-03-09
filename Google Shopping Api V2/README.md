This is a read me file

This extension is a magento extension and this extension will provide a Backend utility to Connect your Google Merchant Account to magento Shop.


Configuration
=================

As the module has to use Google OAuth2 a ClientId and ClientSecret for Google Content API is required. This can be generated in the http://console.developers.google.com/

Create a project in Google developers console

Login to Google developers console or create an account
Create a Project

Name: Magento-GoogleShoppingApi
Project-ID: use the generated id or something like magento-gshopping-841
After the project is created go to "APIs & auth" -> "APIs"
Search for "Content API for Shopping" and enable it
Next go to "APIs & auth" -> "Credentials" and click "Create new Client ID"
Select "Web application"
Fill out the fields "Email address" and "Product name"
save
In the next step the shop backend data has to be enterend
"Authorized JavaScript origins": https://www.yourmagento.com/
"Authorized redirect uris":
https://www.yourmagento.com/index.php/admin/googleShoppingApi_oauth/auth/
After finishing the process you can see your API credentials
Client ID and Client Secret must be entered in the Magento Module Configuration
Magento Module Configuration

Basic Module configuration: Magento Admin -> System -> Configuration -> BlueVisionTec Modules -> GoogleShoppingApi

Account-ID: Your GoogleShopping Merchant ID
Google Developer Project Client ID: The Client ID generated above
Google Developer Project Client Secret: The Client Secret generated above
Target Country: The country for which you want to upload your products
Update Google Shopping Item when Product is Updated
Not implemented (observer disabled in current version, will be readded)
Renew not listed items
When syncing a product which is not listed on GoogleShopping, it will be added
Remove disabled items
Removes items which are disabled or out of stock from GoogleShopping
Product configuration

In Product edit view you will find a new tab "GoogleShopping". Here you can set the GoogleShopping Category. The language of the category is taken from the configured store language. The taxonomy files for de_DE and en_US are shipped with the module package. Further taxonomy files should be added to /var/bluevisiontec/googleshoppingapi/data .
Attributes configuration and item management can be found in Magento Admin -> Catalog -> Google Content APIv2

This extension is tested on magento 1.5 to 1.9 version and it's working fine in all those version but if you still found any problem on that please contact me.

My email address is : 5mehulhelp5@gmail.com
Skype Id: mehul.chaudhari.

It's My Pleasure if I can help you in anything.

Thanks For Your Time.
