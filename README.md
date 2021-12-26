# VRM GPS Tracking Application

## What is it?

This is a simple mapping application that downloads the GPS tracks from
a VRM portal for an installation and displays them on a map alonf with the
current location.

## Why Do I Care?

If you have a Victron GX monitoring device with a GPS attached in your RV, boat
or other vehicle, and want to share your location with friends or family, then
this application does it.

## How does it Work?

There is a ``index.php`` script that handles connecting to the VRM portal
and downloading the current GPS information along with the track for some
number of days in the past.

Each installation that is connected to VRM allows monitoring and control of
that configuration via the VRM portal. Since you probably don't want people
controlling your installation the script handles that and then emits an HTML
page with just the GPS current info and the request GPS track. Obviously you
need to make sure you have proper protection on whatever system you host this
on.

### Here is the VRM API Documentation

https://docs.victronenergy.com/vrmapi/overview.html

## How do I Install It?

This repository cantains an NPM project that uses OpenLayers web mapping
package displaying OpenStreetMap (OSM) map tiles. If you want to modify the
project you can install NPM and go for that, but if you just want to use
the existing project as is, then all you need to do is edit ``index.php``
and copy the following file to a directory on your webserver.

```
// ****************** edit these for your configuration ****************
// vrm info
$installationID = '*****';
$user = '******';
$userID = '******';
$pass = '******';
$ptoken = '******';

// set some defaults for the map in case we fail to reach vrm portal
// I picked my home lat, lon
$title = 'My Tracking Map Page';
$lat = '42.902734';
$lon = '-71.258601';
$speed = 'unknown';
$altitude = 'unknown';

// default days in the past you want you track to show
$days = 3;

// ****** don't edit below this line unless you know what your doing ****
```

Then copy the following files to a directory in your webserver. For example, if you web ROOT is like ``/html/`` and you create a directory ``/trackmyrv/``, 
then copy each of the files below into ``/html/trackmyrv/`` and you should be
able to access the from a browser at: ``http://example.com/trackmyrv/index.php``.
Make sure you have your webserver configured to run PHP.

```
dist/index.js.map
dist/main.1f19ae8e.css.map
dist/main.1f19ae8e.js.map
dist/index.js
dist/main.1f19ae8e.css
dist/main.1f19ae8e.js
index.php
```

## Notes on vrm info above

If you are using the vrm portal, then the ``installationID`` is in your
existing url. Your ``user`` and ``pass`` are the same you use to login to
the portal. Getting ``ptoken`` is a little convoluted as in my case I have
2 factor authentication turned on.

I have included a ``token-tool.php`` that might be useful for testing
 connections to the VRM API and for getting a ptoken needed for ``index.php``.

This script has four steps where each step requires uncommenting that step 
and commenting out the other step can copying information from each step 
back into the script for the next step. At the end, you will end up with a
ptoken that can be used in index.php.

Here is the VRM API documentation, but it took a few tries to figure it out
hence the script.

https://docs.victronenergy.com/vrmapi/overview.html

