Vitamin C Test Runner
========

A multi language test runner that supports php, python out of the box. Javascript will be coming soon. Because Vitamin C runs commands via the command line, adding your language of choice should be rather simple by simply adding another test runner.

The results are a bar that is at the bottom of the page. 

- **Green Bar**
 - Indicates that all tests are passing
- **Yellow Bar**
 - Indicates that tests are running, and none have yet to return with a valid result
- **Red Bar**
 - Indicates that one or more tests have failed. The text displayed in the bar should indicate what test failed and why.


##How does it work?
A quick youtube video providing a demo on it's use.
[![Vitamin C in Action](https://raw.githubusercontent.com/kcmerrill/VitaminC/master/vitaminc/demo/Fullscreen_9_19_14__11_39_PM.png "Vitamin C in action!")] (https://www.youtube.com/watch?v=2L1LezvExL4)


###Side Note
It's an early version prototype that will have quite a few bugs. If you see any please feel free to do a PR and I'd be happy to merge them in. 

###Installation Instructions
- Either clone or download the Vitamin C repository to your machine where your code is located. 
- Using composer(http://getcomposer.org) run composer.phar install in the root directory
- Once the installation is completed, cd into the WWW directory and using php >= 5.4 built in webserver, run php -S localhost:9999
- In a web browser, direct the url to http://localhost:9999
