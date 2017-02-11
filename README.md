# yii-apputil
Some useful and necessary utilities for Yii applications
Enjoy :D
## Installation
* Navigate to /protected folder of you yii 1.x application and require the package
```shell
    $ cd YiiAppPath/protected
    $ composer require easyapprepo/yii-apputil:"dev-master"
```
* Now, on your application configuration (eg: protected/config/main.php) add the following code to the components section
```PHP
'components'=>array(
...
        'apputil' => array(
            'class' => 'application.vendor.easyapprepo.yii-apputil.AppUtility',
        ),
```
* then, you can use it like this
```PHP
$persianNumber = Yii::app()->apputil->str->EN2PN('123456');
print $persianNumber; // ۱۲۳۴۵۶
```
## Methodes
###String Methodes
```PHP
Yii::app()->apputil->str->EN2PN('123456'); 
//۱۲۳۴۵۶

Yii::app()->apputil->str->PN2EN('۱۲۳۴۵۶'); 
//123456

Yii::app()->apputil->str->fixPersianString('ولي'); 
//ولی


```
