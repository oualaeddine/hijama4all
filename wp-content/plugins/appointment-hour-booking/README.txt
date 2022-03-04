=== Appointment Hour Booking - WordPress Booking Plugin ===
Contributors: codepeople
Donate link: https://apphourbooking.dwbooster.com/download
Tags: hour,calendar,service,booking,appointment,schedule,sessions,events,reservation,classes,teaching,training
Requires at least: 3.0.5
Tested up to: 5.9
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Appointment Hour Booking is a plugin for creating booking forms for appointments with a start time and a defined duration over a schedule.

== Description ==

Appointment Hour Booking is a WordPress plugin for creating booking forms for **appointments with a start time and a defined duration** over a schedule. The start time is visually selected by the end user from a set of start times calculated based in the **"open" hours and service duration**. The duration/schedule is defined in the "service" selected by the customer. Each calendar can have multiple services with different duration and prices.

This plugin is useful for different cases like **booking of medical services** where services with different duration and prices may be available, for **personal training sessions**, for **booking rooms for events**, for **reserving language classes** or other type of classes and other type of **services/resources booking** where start times are selected and the availability is automatically managed using the defined service duration to avoid double-booking (the booked time is blocked once the booking is completed).

Main Features:

* Easy **visual configuration** of calendar data and schedules
* **Working dates**, invalid/holiday dates and special dates can be defined
* Supports restriction of **default, maximum and minimimum dates**
* **Open hours** can be defined for each date
* Each calendar can have **multiple services** defined
* Each service can have its own **price and duration**
* **Start-times** are calculated automatically based in the open hours and service duration
* Available times are managed automatically to **avoid double-booking**
* Multiple services can be selected on each booking
* Services can have multiple capacity
* Automatic price calculation
* Customizable **email notifications** for administrators and users
* Form **validation** and built it anti-spam **captcha** protection
* Manual and automatic **CSV reports**
* iCal addon with iCal export link and iCal file attached into emails
* Calendar available in 53+ languages
* Multiple date formats supported
* Blocks for Elementor and Gutemberg
* Multi-page calendars
* Printable **appointments list**

Features in commercial versions:

* **Visual form builder** for creating the booking form fields
* Booking form can be connected to **payment process** (Ex: PayPal Standard, PayPal Pro, Stripe, Skrill, Authorize.net, TargetPay/iDEAL, Mollie/iDEAL, SagePay, Redsys)
* Payments are SCA ready (Strong Customer Authentication), compatible with the new Payment services (PSD 2) - Directive (EU) 
* **Addons** for integration with external services: reCaptcha, MailChimp, SalesForce, WooCommerce and others
* **Addons** with additional features: appointment cancellation addon, appointment reminders addon, clickatell and twilio SMS add-ons, signature fields, iCal synchronization, Google Calendar API, Zoom Meetings ...

= Appointment Hour Booking can be used for: = 

**Booking services or resources:** Define schedule, open hours, services, prices and durations and let the calendar plugin manage the schedule.

**Sample cases:** Medical services, personal training, resource allocation, booking rooms, classes, etc...

The services can have a maximum capacity (example: number of persons that can book/attend the service at the same time). The default capacity is 1. If the service capacity has been set to 1 the time-slot will be blocked for new bookings after getting one booking. If the service capacity has been set to a greater number (example: service with capacity 10) the time-slot will be blocked after filling the capacity (example: after getting bookings for a total of 10 persons). This feature is described in detail at https://apphourbooking.dwbooster.com/blog/2019/01/24/bookings-for-multiple-persons/

== Installation ==

To install **Appointment Hour Booking**, follow these steps:

1.	Download and unzip the Appointment Hour Booking calendar plugin
2.	Upload the entire appointment-hour-booking/ directory to the /wp-content/plugins/ directory
3.	Activate the Appointment Hour Booking plugin through the Plugins menu in WordPress
4.	Configure the settings at the administration menu >> Settings >> Appointment Hour Booking. 
5.	To insert the appointment hour booking calendar form into some content or post use the icon that will appear when editing contents

== Frequently Asked Questions ==

= Q: What means each field in the appointment hour booking calendar settings area? =

A: The product's page contains detailed information about each appointment calendar field and customization:

https://apphourbooking.dwbooster.com


= Q: Where can I publish a appointment booking form? =

A: You can publish appointment booking forms on pages and posts. The shortcode can be also placed into the template. The commercial versions of the plugin also allow publishing it as a widget.


= Q: How can I apply CSS styles to the form fields? =

A: Please check complete instructions in the following page: https://apphourbooking.dwbooster.com/faq#q82

= Q: How can I align the form using various columns? =

A: Into the form editor click a field and into its settings there is one field named "**Additional CSS Class**". Into that field you can put the name of a CSS class that will be applied to the field.

There are some pre-defined CSS classes to use align two, three or four fields into the same line. The CSS classes are named:

**column2**
**column3**
**column4**

For example if you want to put two fields into the same line then specify for both fields the class name "**column2**".


= Q: How to hide the fields on forms? =

A: You should use a custom class name. All fields include the attribute "Additional CSS Class", you only should enter through this attribute a custom class name (the class name you prefer), for example myclass, and then define the new class in a css file of your website, or add the needed styles into the "Customization area >> Add Custom Styles" (at the bottom of the page that contains the list of calendars):

.myclass{ display:none; }

If you are using the latest version of the plugin, please enter in the "Additional CSS Class" attribute, the class name: hide, included in the plugin's distribution.

= Q: How to edit or remove the form title / header? =

A: Into the form builder in the administration area, click the "Form Settings" tab. That area is for editing the form title and header text.

It can be used also for different alignment of the field labels.


= Q: Can the emails be customized? =

A: In addition to the possibility of editing the email contents you can use the following tags:

%INFO%: Replaced with all the information posted from the form
%itemnumber%: Request ID.
%formid%: ID of the booking form.
%referrer%: Referrer URL if reported.
%additional%: IP address, server time.
%final_price%: Total cost.
%fieldname1%, %fieldname2%, ...: Data entered on each field

= Q: Can I add a reference to the item number (submission number) into the email? =

A: Use the tag %itemnumber% into the email content. That tag will be replaced by the submission item number.


= Q: How to insert an image in the notification emails? =

A: If you want send an image in the notification emails, like a header, you should insert an IMG tag in the "Email notification to admin" and/or "Email confirmation to user" textareas of form settings, with an absolute URL in the SRC attribute of IMG tag:

<IMG src="http://..." >

= Q: How to insert changes of lines in the notification emails, when the HTML format is selected? =

A: If you are using the HTML format in the notification emails, you should insert the BR tags for the changes of lines in the emails content:

<BR />


= Q: The form doesn't appear. Solution? =

A: If the form doesn't appear in the public website (in some cases only the captcha appear) then change the script load method to direct, this is the solution in most cases.

That can be changed in the "troubleshoot area" located below the list of calendars/items.

= Q: I'm not receiving the emails with the appointment data. =

A: Try first using a "from" email address that belongs to your website domain, this is the most common restriction applied in most hosting services.

If that doesn't work please check if your hosting service requires some specific configuration to send emails from PHP/WordPress websites. The plugin uses the settings specified into the WordPress website to deliver the emails, if your hosting has some specific requirements like a fixed "from" address or a custom "SMTP" server those settings must be configured into the WordPress website.


= Q: Non-latin characters aren't being displayed in the form. There is a workaround? =

A: Use the "throubleshoot area" to change the character encoding.


= Q: Can I display a list with the appointments? =

A: A list with the appointments set on the calendar can be displayed by using this shortcode in the page where you want to display the list:

**[CP_APP_HOUR_BOOKING_LIST]**

... it can be also customized with some parameters if needed, example:

**CP_APP_HOUR_BOOKING_LIST from="today" to="today +30 days" fields="DATE,TIME,email" calendar="1"]**

... the "from" and "to" are used to display only the appointments / bookings on the specified period. That can be either indicated as relative days to "today" or as fixed dates.

The styles for the list are located at the end of the file "css/stylepublic.css":

**.cpabc_field_0, .cpabc_field_1, .cpabc_field_2, ...**

Clear the browser cache if the list isn't displayed in a correct way (to be sure it loads the updated styles).

You can also add the needed styles into the "Customization area >> Add Custom Styles" (at the bottom of the page that contains the list of calendars):

= Q: Can I track the conversion referral? =

A: Yes, for this purpose you can use the plugin [CP Referrer and Conversion Tracking](https://wordpress.org/plugins/cp-referrer-and-conversions-tracking/) that already includes the automatic integration with this plugin.


= Q: Are the forms GDPR compliant? =

A: In all plugin versions you can turn off IP tracking to avoid saving that user info. Full GDPR compliant forms can be built using the commercial versions of the plugin.



== Other Notes ==

= The Troubleshoot Area =

Use the troubleshot if you are having problems with special or non-latin characters. In most cases changing the charset to UTF-8 through the option available for that in the troubleshot area will solve the problem.

You can also use this area to change the script load method if the booking calendar isn't appearing in the public website.

 
= The Notification Emails =

The notification emails with the appointment data entered in the booking form can sent in "Plain Text" format (default) or in "HTML" format. If you select "HTML" format, be sure to use the BR or P tags for the line breaks into the text and to use the proper formatting.

 
= Exporting Appointments to CSV / Excel Files =  

The appointment data can be exported to a CSV file (Excel compatible) to manage the data from other applications. That option is available from the "bookings list", the appointments can be filtered by date and by the text into them, so you can export just the needed appointments to the CSV file.
 

= Other Versions and Features = 
 
The free version published in this WordPress directory is a fully-functional version for accepting appointments as indicated in the plugin description. There are also commercial versions with additional features, for example:

- Ability to process forms/appointments linked to payment process (PayPal, Stripe, Skrill, ...)
- Form builder for a visual customization of the booking form
- Addons with multiple additional features

Payments processed through the plugin are SCA ready (Strong Customer Authentication), compatible with the new Payment services (PSD 2) - Directive (EU) that comes into full effect on 14 September, 2019.

Please note that the pro features aren't advised as part of the free plugin in the description shown in this WordPress directory. If you are interested in more information about the commercial features go to the plugin's page: https://apphourbooking.dwbooster.com/download
 
 
== Screenshots ==

1. Appointment booking form.
2. Inserting an appointment hour booking calendar into a page.
3. Managing the appointment hour booking  calendar.
4. Appointment Hour Booking calendar settings.
5. Integration with the new Gutemberg Editor

== Changelog ==

= 1.0.02 =
* First version published

= 1.0.03 =
* Bug fixes (notices with WP_DEBUG enabled) 

= 1.0.04 =
* Fixed bug when adding new form

= 1.0.05 =
* New banners and icons

= 1.0.06 =
* Fixed bug in insert query

= 1.0.07 =
* Added CSS and JavaScript customization panel

= 1.0.08 =
* New iCal export option
* New iCal attachment for emails option
* Supports multi-page (multi-month) calendars/items
* Support for multiple languages
* Support for multiple date formats
* Feature for limiting the number of appointments allowed on a single booking action
* Bugs corrections and fixes

= 1.0.09 =
* Fixed bug in availability management

= 1.0.10 =
* Docs and interface updates

= 1.0.11 =
* Improved interface and descrciption

= 1.0.12 =
* Fixed problem with the invalid dates in the public site

= 1.0.14 =
* Fixed problem with the invalid dates in the dashboard area

= 1.0.15 =
* Fixed admin menu links and website services

= 1.0.16 =
* Fixed bug in invalid dates feature

= 1.0.17 =
* Fix in description, tags and author links

= 1.0.18 =
* Fixed help texts in admin interface

= 1.0.19 =
* New improved admin interface

= 1.0.20 =
* New support and documentation options

= 1.0.21 =
* Fixed bug in visual form builder

= 1.0.22 =
* Fixed bug with more than one form in the booking form

= 1.0.23 =
* New feature for indepedent time-slot intervals

= 1.0.24 =
* Improved CSS customization section

= 1.0.25 =
* Fixed conflict with autoptimize plugin

= 1.0.26 =
* Fixed bug in form builder

= 1.0.27 =
* Fixed captcha reloading issue

= 1.0.28 =
* Fixed bug in date restrictions

= 1.0.29 =
* Increased max app length

= 1.0.30 =
* Bug fixes on iCal attachments

= 1.0.31 =
* Added GDPR acceptance field in the form builder

= 1.0.32 =
* Code memory and speed optimizations

= 1.0.33 =
* Updates to support links, documentation and scheduling

= 1.0.34 =
* Easier activation process

= 1.0.35 =
* Optional deactivation feedback

= 1.0.36 =
* Fixed bug in activation process

= 1.0.37 =
* Database creating encoding fix 

= 1.0.38 =
* Improved default format of submitted data

= 1.0.39 =
* Added service name to selection

= 1.0.40 =
* Fixed issue in activation process

= 1.0.41 =
* Avoided conflict with bootstrap datepicker implementations

= 1.0.42 =
* Compatible with Gutenberg

= 1.0.43 =
* New support and demo links

= 1.0.44 =
* Fix to Gutenberg integration

= 1.0.45 =
* Conflict avoided with Gutenberg editor

= 1.0.46 =
* Added installation wizard
* Calendar availability edition fixed
* iCal export format correction

= 1.0.47 =
* Fixed magic quotes issue

= 1.0.48 =
* Major update. Full redesign and improvements.

= 1.0.49 =
* Text field added to the form builder

= 1.0.50 =
* Improved CSS customization settings

= 1.0.51 =
* Blink effect to submit button

= 1.0.52 =
* Fixed bug in current date availability

= 1.0.53 =
* Added end-time display. Thank you to @andreastypo for the feedback and code.

= 1.0.54 =
* Improved availability verification

= 1.0.55 =
* Fixed conflict with third party plugins

= 1.0.56 =
* Added plugin translations

= 1.0.57 =
* Gutenberg compatibility updates

= 1.0.58 =
* Fix to plugin translations

= 1.0.59 =
* iCal export improvements

= 1.0.60 =
* AM/PM calendar fix and form builder fixes

= 1.0.61 =
* Fixed visualization issue in calendars list

= 1.0.62 =
* New features in available slots rendering

= 1.0.63 =
* Fixed display issue in form builder

= 1.0.64 =
* Feature for adding bookings from admin

= 1.0.65 =
* Fix to character encoding in CSV exports. New feature for showing booked slots.

= 1.0.66 =
* Set order to selected times

= 1.0.67 =
* Fixed bug in time calculation

= 1.0.68 =
* New Gutemberg Block

= 1.0.69 =
* Redirect / confirmation page supports now boking parameters

= 1.0.70 =
* Fixed bug in form edition

= 1.0.72 =
* Better CSS customization options

= 1.0.73 =
* Fixed submission processing bugs

= 1.0.74 =
* Clone calender feature

= 1.0.75 =
* Removed use of CURL

= 1.0.76 =
* Better date format management

= 1.0.77 =
* Timezone conversion updates

= 1.0.78 =
* Integration with Elementor
* New visual calendar to view the schedule

= 1.0.79 =
* Fixed bug in invalid dates

= 1.0.80 =
* Now accepts services with multiple capacity

= 1.0.81 =
* Fixed bug in dates selection on date change
* Skip current day if no available slots for it
* Min/max date restriction removed from admin bookings

= 1.0.82 =
* Fixed urlencode issue

= 1.0.83 =
* Fixed bug in services selection

= 1.0.84 =
* Fixed bug in submission redirection

= 1.0.85 =
* Fixed bug in services availability

= 1.0.86 =
* Appointment padding time feature

= 1.0.87 =
* Fixed compatibility with Visual Composer

= 1.0.88 =
* Fixed bug in time slot availability

= 1.0.89 =
* Fixed bug in "only" booking dates

= 1.0.90 =
* New CSV export feature and improved bookings list

= 1.0.91 =
* Improvements to the iCal settings

= 1.0.92 =
* New feature for making required selecting service

= 1.0.93 =
* Fixed backward compatibility of new features

= 1.0.94 =
* Fixed bug rendering 0:00 hours

= 1.0.95 =
* Fixed bug availability calculation

= 1.0.96 =
* Improved backward compatibility

= 1.0.97 =
* Fixed bug in special dates

= 1.0.98 =
* New feature for excluding fields from CSV exports
* Fixed bug in date format

= 1.0.99 =
* Fixed bug in date pre-selection

= 1.1.05 =
* New feature for hidding app end time

= 1.1.06 =
* Fixed bug in optional services selection

= 1.1.07 =
* Improved overbooking verification
* Improved "show used slots" feature

= 1.1.08 =
* New tags supported for the email customization https://apphourbooking.dwbooster.com/faq#q81
* Time slot price validation and improved formatting

= 1.1.09 =
* Fixed conflict of captcha image with Jetpack Lazy Loading feature

= 1.1.10 =
* Fixed conflict with javascript minifier

= 1.1.11 =
* Improvements to date formatting

= 1.1.12 =
* Better calendar responsive design

= 1.1.14 =
* Added missing translations
* Improvements to schedule list view

= 1.1.15 =
* Fixed conflict with Yoast SEO

= 1.1.16 =
* Min data supports hours limits. Ex: +1h

= 1.1.17 =
* Fixed bug in current date slots limits

= 1.1.18 =
* Fixed conflict between min and default date

= 1.1.19 =
* Improved support for military and AM/PM time format

= 1.1.20 =
* Fixed conflict with the Yoast SEO plugin

= 1.1.21 =
* Fixed bug in min date config

= 1.1.22 =
* Fixed bug in available dates

= 1.1.23 =
* Fixed Gutemberg editor error

= 1.1.24 =
* Fixed captcha bug

= 1.1.25 =
* Minor bugs update

= 1.1.26 =
* Fixed bug in min date settings (dashboard)

= 1.1.27 =
* Fixed bug in form builder and character encoding

= 1.1.28 =
* Added new case of use to padding time

= 1.1.29 =
* New dashboard widget add-on for displaying upcoming bookings

= 1.1.30 =
* Fixed bug in schedule calendar data loading

= 1.1.31 =
*  Compatible with WordPress 5.2

= 1.1.32 =
*  New calendar formatting options

= 1.1.33 =
*  New feature for multiple calendars in booking lists

= 1.1.34 =
*  Fixed issue with iconv text encoding function not present

= 1.1.35 =
*  Added new translations and texts with supported translations

= 1.1.36 =
*  Compatible with Google Translate

= 1.1.37 =
* Update for compatibility with WordPress 5.2

= 1.1.38 =
* Added option onlycurrentuser="yes" for the lists shortcode.

= 1.1.39 =
* Added option to store the ID of user making the booking

= 1.1.40 =
* Added new CA-French translation
* More CSS customization options

= 1.1.41 =
* Better way for linking the plugin scripts and code improvements

= 1.1.43 =
* Updated feature for marking used slots

= 1.1.44 =
* Improved iCal link and code optimizations

= 1.1.45 =
* Multiple code improvements

= 1.1.46 =
* New nonces and improvements

= 1.1.47 =
* Fix to table encoding

= 1.1.48 =
* Additional sanitization and improvements

= 1.1.49 =
* Fix to deal with page cache issues

= 1.1.50 =
* Fixed bug in min/max date rules

= 1.1.51 =
* Fixed bug in terms acceptance field

= 1.1.52 =
* Improved compatiblity with 3rd party plugins

= 1.1.53 =
* Improved user features

= 1.1.54 =
* Fix bug in click event

= 1.1.55 =
* Fixed language issue with autoptimize

= 1.1.56 =
* New feature for blocking times

= 1.1.57 =
* Fixed bug in schedule calendar

= 1.1.58 =
* Increased max number of appointments up to 99 per booking submission

= 1.1.59 =
* Bug fixes for blocking times feature

= 1.1.60 =
* Cancellation button improvements

= 1.1.61 =
* Fixed conflict with Elementor add-on

= 1.1.62 =
* Feature for hidding past booked appointment times

= 1.1.63 =
* Fixed bug in blocking times feature

= 1.1.64 =
* Language updates and bug fixes

= 1.1.65 =
* Fixing issue with padding times

= 1.1.66 =
* Bug fixes to availability calculation and improvements

= 1.1.67 =
* Translation updates

= 1.1.68 =
* Schedule calender even changed from mouseover to click

= 1.1.69 =
* Fixed bug in open hours

= 1.1.70 =
* Changed 0:00 AM to 12:00 AM in non-military time

= 1.1.71 =
* Feature for marking specific dates with different colors

= 1.1.72 =
* New settings field for CSV character encoding

= 1.1.73 =
* Added event for more visualization options

= 1.1.74 =
* Compatible with WordPress 5.3

= 1.1.75 =
* Fixed conflict with JavaScript optimizers

= 1.1.76 =
* Fixed bug in exported CSV filenames

= 1.1.77 =
* Fixed bug in services index and conflicts with third party plugins

= 1.1.78 =
* Bug fixes: Date filters in CSV Schedule exports and cost calculations

= 1.1.79 =
* Bug fixes: Email delivery
* Now supports appointments in smaller time intervals

= 1.1.80 =
* Fixed bug in global email reports

= 1.1.81 =
* Improved time formatting

= 1.1.82 =
* Fixed cross-day time calculations

= 1.1.83 =
* Compatibility fix with bootstrap datepicker

= 1.1.84 =
* Fixed bug in date format dd.mm.yyyy

= 1.1.85 =
* Autodetection feature for date format

= 1.1.86 =
* Fixed bug in invalid dates feature

= 1.1.87 =
* Added CSS style to avoid the conflict with some theme style

= 1.1.88 =
* Improved CSV / Excel exports

= 1.1.89 =
* Improved validation interface

= 1.1.90 =
* Improved antispam rules

= 1.1.91 =
* Interface improvements

= 1.1.92 =
* Fixed availability bug

= 1.1.93 =
* Fixed bug in days without available times

= 1.1.94 =
* Improved tags structure for further CSS customization

= 1.1.95 =
* Fixed bug in padding times

= 1.1.96 =
* More CSV config settings

= 1.1.97 =
* Fix to status column in booking orders

= 1.1.98 =
* Improved CSV formatting

= 1.1.99 =
* New add-ons & docs

= 1.2.05 =
* Updates to schedule list

= 1.2.06 =
* PHP 7.x compatibility update

= 1.2.07 =
* Compatible with WordPress 5.4

= 1.2.09 =
* Translations update

= 1.2.10 =
* Cost calculation improvements

= 1.2.11 =
* Fixed conflict with third party optimization/cache plugins

= 1.2.12 =
* CSS improvement to avoid conflicts with popular themes

= 1.2.14 =
* Fixed price display in emails

= 1.2.15 =
* Update to WordPress editor integration

= 1.2.16 =
* Support for 24 hours bookings and interface improvements

= 1.2.17 =
* Prevention of accidental clicks

= 1.2.18 =
* Fixed price display bug

= 1.2.19 =
* Admin interface improvements

= 1.2.20 =
* New options for styling selected time slots

= 1.2.21 =
* Improved calendar language support

= 1.2.22 =
* Scripts optimization

= 1.2.23 =
* More configuration for dashboard widget

= 1.2.24 =
* Form load speed improvement

= 1.2.25 =
* Fixed bug in capacity calculation

= 1.2.26 =
* Captcha improvements

= 1.2.27 =
* Automatic translation of date formatting

= 1.2.28 =
* Fix to capacity display

= 1.2.29 =
* Email notification fix

= 1.2.30 =
* New cross-day bookings feature

= 1.2.31 =
* Improved translation options

= 1.2.32 =
* Fixed date formatting bug in appoitment lists

= 1.2.33 =
* More data for lists shortcodes

= 1.2.34 =
* Min-date setting improvements

= 1.2.35 =
* New conditional tags for emails

= 1.2.36 =
* Improved booking interface

= 1.2.37 =
* Fixed bug in fast save button

= 1.2.38 =
* Fixed typo and improved time picker

= 1.2.39 =
* Fixed bug in padding features

= 1.2.40 =
* Better appointment selection interface

= 1.2.41 =
* Fixed bug in lists

= 1.2.42 =
* Improved cost calculations

= 1.2.43 =
* Auto-detect start weekday for schedule calendar

= 1.2.44 =
* Fix for disabling dates with no more available slots.

= 1.2.45 =
* Fix for translation plugin

= 1.2.46 =
* New translation and translation improvements

= 1.2.47 =
* Fixed captcha bug

= 1.2.48 =
* Dropdown fields CSS improvements

= 1.2.49 =
* Compatible with WordPress 5.5

= 1.2.50 =
* Code optimizations

= 1.2.51 =
* Translations update

= 1.2.52 =
* New %final_price_short% tag for emails

= 1.2.53 =
* Add-on updates

= 1.2.54 =
* CSS fixes and improvements to the block times feature

= 1.2.55 =
* jQuery compatibility update

= 1.2.56 =
* Replaced deprecated jQuery code

= 1.2.57 =
* Updated deprecated jQuery code
* Bizum / RedSys payments note

= 1.2.58 =
* Price calculation improvements

= 1.2.59 =
* Fixed bug in overbooking verification

= 1.2.60 =
* Compatibility with Elementor popups

= 1.2.61 =
* Fixed script issue in previous update

= 1.2.64 =
* Compatibility fix with Translatepress

= 1.2.65 =
* Fixed display issue in old Internet Explorer versions

= 1.2.66 =
* New schedule calendar view customization options

= 1.2.67 =
* New calendar theme: Modern responsive calendar with times aligned to the right side

= 1.2.68 =
* Added Dutch BE translation
* Improvement to non-available slots diplay

= 1.2.69 =
* Booking form accessibility improvements

= 1.2.70 =
* Fixed min-date issue

= 1.2.71 =
* Improve to the min date and max date features

= 1.2.72 =
* Fixed price calculation issues

= 1.2.73 =
* Improved price calculation

= 1.2.74 =
* Compatibility update for WordPress 5.6

= 1.2.75 =
* Better CSV export 

= 1.2.76 =
* Easier text/translations editing 

= 1.2.77 =
* Better responsive layout for iPhone

= 1.2.78 =
* Fix to calendar language detection

= 1.2.79 =
* Added "maxitems" and "limit" filters to custom lists

= 1.2.80 =
* Calendar visualization improvement

= 1.2.81 =
* New request_timestamp tag

= 1.2.82 =
* New features for the iCal add-on

= 1.2.83 =
* New calendar design theme

= 1.2.84 =
* New date formats

= 1.2.85 =
* iCal support for more time-zones
* PHP compatibility update

= 1.2.86 =
* Exclude fields feature for CSV reports

= 1.2.87 =
* Time-picker improvements

= 1.2.88 =
* Compatible with WordPress 5.7

= 1.2.89 =
* Support for new time zones
* Fix to dashboard widget add-on

= 1.2.90 =
* Multiple code optimizations

= 1.2.91 =
* Fix to previous update

= 1.2.92 =
* Lists using WordPress timezone

= 1.2.93 =
* Better prevention of double-booking

= 1.2.94 =
* Time slots selection improvements

= 1.2.95 =
* PHP 8.0 updates

= 1.2.96 =
* Script improvements and language updates

= 1.2.97 =
* Better validation interface

= 1.2.98 =
* Add bookings option in the admin defaults to "Approved"

= 1.3.01 =
* Administration preview improved

= 1.3.02 =
* Modification in internal split slots function

= 1.3.03 =
* Schedule list view and lists shortcodes updates

= 1.3.04 =
* Min date improvements

= 1.3.05 =
* PHP compatibility report update

= 1.3.06 =
* Translation for services field
* Improvements to lists and blocked times

= 1.3.07 =
* iCal attachments improvements

= 1.3.08 =
* Improved dashboard widget

= 1.3.09 =
* Add-ons section update

= 1.3.10 =
* Better WPML integration

= 1.3.11 =
* Fix to full day bookings

= 1.3.12 =
* Compatible with WordPress 5.8

= 1.3.14 =
* Schedule calendar update

= 1.3.15 =
* Translations update and mist improvements

= 1.3.16 =
* Restricted allowed tags in form builder to improve security

= 1.3.17 =
* Additional sanitization

= 1.3.18 =
* Accesibility improvements

= 1.3.19 =
* Optional auto-focus feature
* New anti-spam verification feature
* Optional IP address storage

= 1.3.20 =
* Improvement to avoid CSS conclifts

= 1.3.21 =
* Avoid issues with database cache

= 1.3.22 =
* Fixed time-slots initialization issue

= 1.3.23 =
* Calculated Fields Form plugin compatibility fix

= 1.3.24 =
* Settings fixes

= 1.3.25 =
* New max date rules

= 1.3.26 =
* CSS Improvements

= 1.3.27 =
* Improvements for cross-day time-slots

= 1.3.28 =
* Support for new styles

= 1.3.29 =
* Request timestamp improved

= 1.3.30 =
* CSV/Excel export update

= 1.3.31 =
* Min-date and duration fix

= 1.3.32 =
* Add-ons update

= 1.3.33 =
* Add-ons update (doc)

= 1.3.34 =
* New form layout

= 1.3.35 =
* Documentation/support update

= 1.3.36 =
* Minimize conflits with 3rd party plugins

= 1.3.37 =
* Compatible with WordPress 5.9

= 1.3.38 =
* Elementor integration fix

= 1.3.39 =
* Support for new add-ons

= 1.3.40 =
* Fix to min-date/current-date rules

= 1.3.41 =
* CSV export fix

= 1.3.42 =
* CSV export improvement

= 1.3.43 =
* CSV report fix

= 1.3.44 =
* Better calendar speed

== Upgrade Notice ==

= 1.3.44 =
* Better calendar speed