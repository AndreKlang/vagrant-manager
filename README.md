# Vagrant Manager
This is a small app that make your life a little bit easier if you are a heavy vagrant user with lots of different boxes that you often switch between.

It has one main purpose really, make it easier to manage one, or many boxes with as few commands as possible. For instance spin up 5, 10, or even all boxes at the same time. And what about halting them? Yep, that too is really easy!

![Screenshot](https://andreklang.github.io/vagrant-manager/images/screen-1.png)

## Some examples

Well, lets start with the basics, you run it like this: ```vagma command [identifier]``` If you don't know the identifier, add the -b option (short for --browse) and you will get a pretty list showing you all your boxes, with the current state of it, and a number (the identifier).

And the **identifier**, that's a cool thing, anywhere you enter an identifier, you can enter multiple of them, AS RULES! like this: "\*,-4" that matches all boxes (the "\*"), except number 4 (the "-4"). Or another example: "2-5,8,12-", matching 2,3,4,5,8,12,13,14,15,and-so-on.. Pretty cool huh?
 
So with that little background, these should be pretty self explanatory: *(but I'll describe them anyway)*

```bash
# start the current box (based on folder you're currently in)
# (this is exactly like "vagrant up")
vagma up

# start box number 2
vagma up 2

# start all boxes, except number 4
vagma up *,-4

# get current status of all boxes
vagma status
# will also do the job, but will print some help to
vagma

# halt all boxes
vagma halt "*"
# or
vagma halt:all

# browse for the right box (or boxes) to suspend
vagma suspend -b

```
As you can see above, pretty short, but powerful commands that match vagrants native commands very close. And that's where I'll leave you with the how-to. The rest the app will tell you, just ask it for help ```vagma help``` for general help, or ```vagma help [command]``` to get mor info about a specific command.

## Installation

The installation is super simple, only prerequisite is that you have php installed.

Just run this in the folder where you want to install the app, a good example is ~/bin
```bash
curl -LSs https://andreklang.github.io/vagrant-manager/installer.php | php
```

If that does not work for you for some reason, try this instead:
```bash
php -r "readfile('https://andreklang.github.io/vagrant-manager/installer.php');" | php
```

## Updates
There is a self-update feature built in, just run ```vagma self-update``` to get the latest updates.

## Compatibility
This is currently only tested on Linux, but SHOULD work on Mac, and maybe even Windows. Please drop me a line with feedback.
 
## Contributions
I'd love to get contributions in any form!
* Have problems? Open an issue.
* Want to develop?
 * Fork
 * run "composer install"
 * Test with bin/vagma.php as entry-point
 * build with build.sh
 * Make a pull-request
 
## Roadmap

 - [X] Write a good readme
 - [X] Add some pretty printscreens
 - [X] Add command for "vagrant ssh"
 - [X] Refactor "the ugly part"
 - [X] Apply a real License
 - [ ] Release version 1.0.0