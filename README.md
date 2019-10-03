# CSSchedule

![Logo](images/logo.svg)

## Overview

- [Introduction](#introduction)
- [Usage](#usage)
    * [Add to Outlook](#add-ics-link-to-outlook)
    * [Add to Gmail](#add-ics-link-to-gmail)
- [How to pull](#how-to-pull)
- [About the author](#about-the-author)

## Introduction

This repository contains the code for a site that generates ICS calendar files with the individual schedules of Computer Science students at KU Leuven.
This schedule can be imported in most calendar apps, such as Google calendar or iCal. Students who follow the Dutch Master's Programme can also select the English counterparts of their courses to show up in the schedule.

## Usage

To generate an ICS file, the user simply marks the courses they want to include in the list. Afterwards, the user can generate the corresponding ICS calendar by clicking the "Download ICS File" button.

![Screenshot Usage](/images/Screenshot_20171003_150328.png)

As the web-application adds the selected courses to the URL of the webpage, the selected choice can easily be saved and shared with other students by simply copying the URL of the page, either by manually selecting it, or by clicking the "Copy!" button.

### Add ICS link to Outlook

1. Click on "Agenda" in the right corner

![Screenshot Agenda](/images/ClickAgenda.png)

2. Click right on "Andere Agenda's" and select "Agenda Openen"

![Screenshot Open Agenda](/images/OpenAgenda.png)

3. Past the link and click on "Openen"

![Screenshot Past Link](/images/PastLink.png)

### Add ICS link to GMAIL
1. Click on `plus` next to "De agenda van iemand and..." and select "Via URL"

![Screenshot Via URL](/images/ViaURL.png)

2. Paste the url and click on "Agenda toevoegen"

![Screenshot Agenda Toevoegen](/images/AgendaToevoegen.png)

It is also possible to set reminders for upcoming events when importing the ICS link/file.


## How to pull

Composer is a package manager for PHP. Install Composer and install it to the project.

```bash
    $ cd CSSchedule
    $ php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    $ php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
    $ php composer-setup.php
    $ php -r "unlink('composer-setup.php');"

    $ php composer.phar install
    $ php composer.phar dumpautoload -o
```

## About the author

This repository is created and maintained by Midas Lambrichts, Computer Science student at KU Leuven. This project was created in collaboration with [Pieter-Jan Coenen](https://github.com/pcoenen) and [Stijn Caerts](https://github.com/StijnCaerts). Contributions to this repository are welcomed in the form of pull requests.
