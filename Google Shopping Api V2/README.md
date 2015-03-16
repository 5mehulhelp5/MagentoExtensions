This is a read me file

This extension is a magento extension and this extension will provide a Backend utility to Connect your Google Merchant Account to magento Shop.


Configuration
=================

As the module has to use Google OAuth2 a ClientId and ClientSecret for Google Content API is required. This can be generated in the http://console.developers.google.com/

Create a project in Google developers console
------------------------------------------------

<ul class="task-list">
<li>Login to Google developers console or create an account</li>
<li>Create a Project

<ul class="task-list">
<li>Name: Magento-GoogleShoppingApi</li>
<li>Project-ID: use the generated id or something like magento-gshopping-841</li>
</ul>
</li>
<li>After the project is created go to "APIs &amp; auth" -&gt; "APIs"</li>
<li>Search for "Content API for Shopping" and enable it</li>
<li>Next go to "APIs &amp; auth" -&gt; "Credentials" and click "Create new Client ID"</li>
<li>Select "Web application"

<ul class="task-list">
<li>Fill out the fields "Email address" and "Product name"</li>
<li>save</li>
</ul>
</li>
<li>In the next step the shop backend data has to be enterend

<ul class="task-list">
<li>"Authorized JavaScript origins": <a href="https://www.yourmagento.com/">https://www.yourmagentobackend.com/</a>
</li>
<li>"Authorized redirect uris":</li>
<li><a href="https://www.yourmagento.com/index.php/admin/googleShoppingApi_oauth/auth/">https://www.yourmagento.com/index.php/admin/googleShoppingApi_oauth/auth/</a></li>
</ul>
</li>
<li>After finishing the process you can see your API credentials

<ul class="task-list">
<li>Client ID and Client Secret must be entered in the Magento Module Configuration</li>
</ul>
</li>
</ul>

Magento Module Configuration
--------------------------------
<ul class="task-list">
<li>
<p>Basic Module configuration: Magento Admin -&gt; System -&gt; Configuration -&gt; 
BlueVisionTec Modules -&gt; GoogleShoppingApi</p>

<ul class="task-list">
<li>Account-ID: Your GoogleShopping Merchant ID</li>
<li>Google Developer Project Client ID: The Client ID generated above</li>
<li>Google Developer Project Client Secret: The Client Secret generated above</li>
<li>Target Country: The country for which you want to upload your products</li>
<li>Update Google Shopping Item when Product is Updated</li>
<li>Not implemented (observer disabled in current version, will be readded)</li>
<li>Renew not listed items</li>
<li>When syncing a product which is not listed on GoogleShopping, it will be added</li>
<li>Remove disabled items</li>
<li>Removes items which are disabled or out of stock from GoogleShopping</li>
</ul>
</li>
<li>
<p>Product configuration</p>

<ul class="task-list">
<li>In Product edit view you will find a new tab "GoogleShopping". 
Here you can set the GoogleShopping Category. 
The language of the category is taken from the configured store language.
The taxonomy files for de_DE and en_US are shipped with the module package.
Further taxonomy files should be added to /var/bluevisiontec/googleshoppingapi/data .</li>
</ul>
</li>
<li><p>Attributes configuration and item management can be found in Magento Admin -&gt;
Catalog -&gt; Google Content APIv2</p></li>
</ul>

This extension is tested on magento 1.5 to 1.9 version and it's working fine in all those version but if you still found any problem on that please contact me.

My email address is : 5mehulhelp5@gmail.com
Skype Id: mehul.chaudhari.

It's My Pleasure if I can help you in anything.

Thanks For Your Time.
