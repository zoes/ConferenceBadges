Some rather quickly hacked together code to write badges for the PHPUK conference.

What it does

1. Gets all the ticket types for an event and builds template badges based on ticket type (make_templates.php)
2. Gets all the attendees and writes a name, job title and eventbrite barcode on the right template badge (make_badges.php)

Requirements

PHP built with --with-gd and --with-freetype-dir=[DIR] and all of the 
libraries needed to support this (jpeg, png, freetype)

An installation of ImageMagick is needed to get a command line tool (convert)  which will convert a number 
of .png files into a single PDF.

A config file - hardcoded as /etc/ebkeys at the moment. See sample ebkeys.txt.

An eventbrite API key, user key and the ID number of the event.

The eventbrite PHP client library (under EB for conveniencei - but available from EventBrite))

A copy of phpqrcode (again under qrcode for convenience - but available from Sourceforge)
