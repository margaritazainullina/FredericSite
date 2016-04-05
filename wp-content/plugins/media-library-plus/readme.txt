=== Media Library Plus ===
Contributors: maxfoundry, AlanP57
Tags: media library, image, images, gallery, album, photo, photos, photo albums, files, folders, media folders, media organizer
Requires at least: 4.0
Tested up to: 4.4.2
Stable tag: 2.34
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easier file and folder management for WordPress Media Library for Galleries and Albums

== Description ==

As the developers of [MaxGalleria](https://wordpress.org/plugins/maxgalleria/?utm_source=wordpress&utm_medium=mlprepo&utm_campaing=mlprepo) we had a number of users have ask us for a better way to manage the media for their albums and galleries as the number of galleries and albums and size of their collections have increased.  

Specifically they asked for a solution that gives greater organization of media than is available in the WordPress Media Library.  We are big fans of WML but over time we have seen its limitations when it comes to dealing with large media libraries.  So we built Media Library Plus.

[Here is a short video](https://maxgalleria.com/wp-content/uploads/2015/06/MGMLP-screen-demo.mp4) on how to use Media Library Plus.

The WordPress Media library only let’s you organize your files by month and year.  Media Library Plus (MLP) lets you add and build new folders to organize your media as you want.  

If you want to create a folder of media for a specific gallery, copy all of the images you want to use into the gallery and then load those images into a MaxGalleria gallery you can!  Everything is then organized in a more logical manner. The MLP sits on top of the WordPress Media Library and adds functionality to it. It does not replace it.

To get started download and install the Media Library Plus plugin. Once the Media Library Plus is activated you will see Media Library + in the WordPress dashboard menu.  And you are ready to go!

**Using Media Library Plus**

Now the fun begins.

When you click on Media Library + it displays the contents of the uploads folder where you will see the top level folders such as ‘2015’, ‘2014’.

We assume your site has the WordPress Media Library setting option ‘Organize my uploads into month- and year-based folders’ selected. If not the plugin automatically changes your settings to this. To view the contents of a folder, click on the folder image. To navigate up in the folder structure click on the links in the Location: breadcrumb string.

Clicking an image will take you to the image attachment details page. If you close that page when you are done you will be in the old media library. Instead **click your browser’s go back button twice** and you will be taken back to the folder page.

**Button Bar**

The Button Bar provides the main functionality to manage folders and files and is located below the breadcrumbs navigation. When the mouse hovers over a button its function is displayed in the message area below the button bar.

File/Folder Organizing Buttons

Clicking the Add New button displays the upload box.

Here you can select a single file to upload one or more files by dragging the image from the file manager and dropping them in the upload box. Uploaded files will be added to the current folder.
New Folder – Allows you to create a new folder in the current directory.
Rename – Rename a file in the current directory. Folders cannot be renames.
Copy – The copy function allows you to copy selected files to a folder. To copy click the Copy button in the tool bar. Then select the files to be copied, select the destination folder from the drop down list and click ‘Copy Files’.
Move – The move function lets you to move selected files to another folder. To move files click the Move button in the tool bar. Then select the files to be moved, select the destination folder from the drop down list and click ‘Move Files’.
Delete – The delete function let you delete select files or selected folders. Folders have to be empty to be deleted.
Select/Unselect – Select or unselect all files in the current directory.
Sort by Date/Sort by Name – changes the display order of items in the current directory; either by name or by date.
Search – Users can search for a file or folder by typing in the name of the file in the search box and pressing Enter.

The search results page will display files and/or folders that are similar to the search text. You can click on an image or file to go to its folder or click on a folder view its contents.

In the event that you need to rescan your database's image and folder data the Media Library Plus Reset plugin has been included. To use deactivated Media Library Plus, activate Media Library Plus Reset and select Media Library Plus Reset->Reset Database to erase the folder data. Then deactivate Media Library Plus Reset and reactivate Media Library Plus. MLP will perform a fresh scan of your database.

**Working with images and galleries after initial setup**

This plugin contains the stand alone version of the Media Library Plus. This same functionality is built into the [MaxGalleria](https://wordpress.org/plugins/maxgalleria/?utm_source=wordpress&utm_medium=mlprepo&utm_campaing=mlprepo) WordPress Gallery Platform plugin and allows you to add images directly to a gallery.

== Screenshots ==

1. Media Library Plus page
2. Clicking the Add New button displays the upload box
3. The Search results page


== Installation ==

For automatic installation:

1. Login to your website and go to the Plugins section of your admin panel.
2. Click the Add New button.
3. Under Install Plugins, click the Upload link.
4. Select the plugin zip file from your computer then click the Install Now button.
5. You should see a message stating that the plugin was installed successfully.
6. Click the Activate Plugin link.

For manual installation:

1. You should have access to the server where WordPress is installed. If you don't, see your system administrator.
2. Copy the plugin zip file up to your server and unzip it somewhere on the file system.
3. Copy the "media-library-extended" folder into the /wp-content/plugins directory of your WordPress installation.
4. Login to your website and go to the Plugins section of your admin panel.
5. Look for "Media Library Plus" and click Activate.

== Changelog ==
= 2.34 =
* Modified the code to allow the deletion of folder data even if the folder does not exist

= 2.33 =
* Made the stand alone version of MLP compatible with Maxgalleria

= 2.32 =
* Removed Maxgallery promo on MLP page
* Updated MLP top page promo

= 2.31 =
* Changed database engine used for creating the folders table to MyISAM 

= 2.3 =
* Added folder sync function to scan and update the database with new files and folders found on the server
* Fix problem with incorrect path to images in the new srcset parameter

= 1.04 =
* Included the media library plus reset plugin
* Placed Media Library Plus in its own menu to allow other plugins to add submenus to the Media menu

= 1.03 =
* Add support for user defined uploads folder
* Added code to handle attachment_id in attachement URLs

= 1.02 =
* Added facebook like and share buttons
* Added support for muilt site network activation
* Added code to update theme customizer data if a file used by the customizer is moved.

= 1.01 =
* Revisions to the readme file and banner image
* Added scan for folders in uploads directory during initial scan on plugin activation
* Added rating request notice

= 1.0 =
* Initial version of the Media Library Plus
