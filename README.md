# AlfaAcquiring
The simple Internet Acquiring helper for Alfa-Bank (Belarus), non-official.

Contributors needed!

(English version below)

### [be_BY] Пра бібліятэку 

Простая бібліятэка-дапаможнік для выкарыстання інтэрнэт-эквайрынгу (онлайн аплата на сайце) 
праз плацёжный шлюз Альфа-Банку (Беларусь), неафіційная.
Часткова пакрыта базавымі тэстамі. Мае быць стабільнай і гнуткай для пашырэння.
Асноўная мэта: працаваць са зручнымі аб'ектамі (Request and Response) і мець лагіраванне памылак і іх адлюстраванне.
Як вынік гэта мусіць гарантаваць больш стабільную і зручную працу для сайтаў, якія ажыццяўляюць плацяжы 
з дапамогай дадзенай бібліятэкі.

Праверана на беларускіх сайтах і плацёжных шлюзах.
Тэарытычна павінна працаваць аналагічным чынам і ў іншых краінах, дзе існуе Альфа-Банк.

Прыклады выкарыстання зможаце знайсці ў дырэкторыі "example".

Я пакіну тут зверху апісанне па-беларуску каб зменшыць магчымую колькасць выкарыстання
гэтай бібліятэкі ў іншых краінах, пакуль яна не была праверана паўсюль.
Пазней будзе дададзена дэтальнае апісанне на англійскай і расійскай мовах.

Праект далёкі да завяршэння, таму любая дапамога вітаецца і будзе карыснай. Дзякуй.

### [ru_BY] Официальная информация 
Официальная документация от банка: https://alfa-biz.by/acquiring/docs/merchantmanual.pdf

Официальное описание интернет-эквайринга для сайта: https://alfa-biz.by/payment/internet-acquiring/

Официальные плагины: https://alfa-biz.by/upload/cms/wordpress.zip; https://alfa-biz.by/upload/cms/opencart3%D1%85_240120.zip

### [en_BY] How to use it 
Some examples of using the Acquiring API
you can find in the 'example' directory, there are few simple php files.

The aim is to create the Client for API connection (RbsClient) and 
use that for specific methods operations.
The mission of that library is to provide comfortable objects (Request and Response) 
to work with the payment gateway with error logging (if it's needed).
All classes might be easily extended for your aims.

At the moment the library is far from final version.
But it does work fine and better then the PHP code sample from bank's vendor (see code for WordPress and compare it).
Any fork/update/comment from you might be helpful. Thanks.

And an example of usage is below. See more in 'example' directory.
```
$apiClient = (new RbsClient('test-api', 'test'));

$orderId = '570116f7-2588-768a-93a4-8b300007a120';

$response = (new OrderStatusMethod($apiClient))
->setOrderId($orderId)
->run();

print '<pre>';
var_dump($response->getOrderNumber());
var_dump($response->getOrderStatus());
var_dump($response);
print '</pre>';
```
