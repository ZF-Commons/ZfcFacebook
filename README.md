ZfcFacebook
===========

What is it?
-----------
ZfcFacebook is an integration library to enable easy creation of Facebook applications using Zend Framework to. Once completed, we have plans to include everything you'll need to interact with the Facebook Graph and Open Graph APIs, along with view helpers for common javascript tasks.

Where is it?
------------
Currently we only have support for authentication of iframe applications, with Facebook for Websites integration partially completed (formerly "Facebook Connect").


Using it...
-----------
 1. Copy the ZfcFacebook to your module directory
 2. Register the module in your application's application.config.php
 3. Edit the module.config.php to have your Facebook app's App Id and Secret
 4. Tell your iframe app to point to www.yourdomain.com/facebook?a=b
 (You need the a=b because facebook is silly and doesn't allow you to end an url without a ? or /)
 5. You'll see a `facebook` alias added to your service manager, retreiving this will allow you to access the Facebook class from within your controllers and models. Authentication will not occur until `getFacebookId` is called, meaning you can easily set up page that are not covered by the allow process.
 6. Currently there is only one view helper, `ZfcFacebookSignedRequest` (catchy), this allows you to access the signed request parameter from within your views; useful for passing it back to the server in the case of AJAX style applications.

On it's way...
-------
After some delay, I'm actually using this class myself in a project now, so expect some progress. Priority will be given to the view helpers for setting up the Facebook JS SDK, and performing common functions such as posting to stream and inviting friends. Eventually I would hope to have fully proxied access to the Graph API, and a FQL builder class.

Contributing
------------
All help greatly received! Ping me on IRC, Spabby in #zftalk.2 on Freenode, or drop me a mail.
