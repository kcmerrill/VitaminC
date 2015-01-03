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
###Video Demo
[![Vitamin C in Action](https://raw.githubusercontent.com/kcmerrill/VitaminC/master/vitaminc/demo/VitaminC_LiveMode.png)] (https://www.youtube.com/watch?v=2L1LezvExL4)

###Project Mode
![Project Mode](https://raw.githubusercontent.com/kcmerrill/VitaminC/master/vitaminc/demo/VitaminC_ProjectMode.png)

###Debug Mode
![Debug Mode](https://raw.githubusercontent.com/kcmerrill/VitaminC/master/vitaminc/demo/VitaminC_DebugMode.png)

######Side Note
It's an early version prototype that will have quite a few bugs. If you see any please feel free to do a PR and I'd be happy to merge them in. I realize the irony in that there are not tests as of yet. Because it's such an early versioned prototype, I'll be adding them sooner rather than later.

###Installation Instructions

##### Via composer
- Either clone or download the Vitamin C repository to your machine where your code is located.
- Using composer(http://getcomposer.org) run composer.phar install in the root directory
- Once the installation is completed, cd into the WWW directory and using php >= 5.4 built in webserver, run php -S localhost:9999
- In a web browser, direct the url to http://localhost:9999


##### Via docker
- If you're a docker user, this is probably the easiest way, simply docker pull kcmerrill/vitaminc
- For it's FIRST use, simply cd into your projects folder, or if you're like me and have a folder with a bunch of projects inside of it, cd to it and run: 
```shell
docker run -d -p 9999:9999 -v $PWD:/code --name vitaminc kcmerrill/vitaminc
```
- For every use afterwards, simply run:
```shell
docker start vitaminc
```
- In your browser, head over to http://192.168.59.103:9999/ to begin!
* One quick note, the code will be volume mounted under /code so use /code/foldernamehere in the project path.

Of course if you have any questions, please feel free to email me. kcmerrill@gmail.com or leave me a comment.
