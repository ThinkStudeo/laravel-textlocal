
# Laravel Wrapper for [Textlocal](http://textlocal.in) API.

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

A convenience wrapper for the textlocal.in api requests for transactional as well as promotional accounts. The reseller account specific api are not available at this point in time.

Sms messaging can be used for transactional messages like:
- Sending OTP for Two Factor Authentication
- Confirmation of payment received or order placed etc

Transactional messages need to be sent through at any time without any DND (Do Not Disturb) restrictions. For this one needs to register a Transactional Account with Textlocal or request for conversion of the default account to a Transactional Account.

On the other hand Sms messaging may also be used for promotional activities like:
- Announcing product launch or discount sale
- Announcing an award receid=ved by the company or product
- Sending coupon code etc

Promotional messages need to adhere to the DND (Do Not Disturb) restrictions. The default signup at Textlocal creates a Promotional Account by default.



## Contents

- [Laravel Wrapper for Textlocal API.](#laravel-wrapper-for-textlocal-api)
	- [Contents](#contents)
	- [Installation](#installation)
	- [Usage](#usage)
		- [Transactional Account](#transactional-account)
		- [Available Message methods](#available-message-methods)
	- [Changelog](#changelog)
	- [Testing](#testing)
	- [Security](#security)
	- [Contributing](#contributing)
	- [Credits](#credits)
	- [License](#license)


## Installation

```bash
$ composer require thinkstudeo/textlocal
```

## Config
Package facilitates using Transactional as well as Promotional Account with Textlocal at the same time.

Add the following to your `config/services.php` file
```php
'textlocal' => [
    'transactional' => [
		'apiKey' => env('TEXTLOCAL_TRANSACTIONAL_KEY')
    ],
    'promotional' => [
        'apiKey' => env('TEXTLOCAL_PROMOTIONAL_KEY')
    ]
]
```
Don't forget to add the keys to your `.env` file

If you have just one  - i.e. Promotional Account or Transactional Account - make sure to enter the API key accordingly.
For example, for just a Textlocal Promotional Account, declare `TEXTLOCAL_PROMOTIONAL_KEY=api_key_for_your_promotional_account`.
```
TEXTLOCAL_TRANSACTIONAL_KEY= <your textlocal transactional account api key here>
TEXTLOCAL_PROMOTIONAL_KEY= <your textlocal promotional account api key here>
```

## Usage

There are two facades included with the package:
- `Sms` for sending messages
- `Account` for interacting with the Textlocal Account

For differentiating the api calls with respect to account type use `transactional()` or `promotional()` to set the account for the api call.

### Messaging - Transactional Account
You must have a transactional account with Textlocal inorder to send transactional sms without DND restriction 24x7.
Also don't forget to get your templates for the transactional messages approved by Textlocal.
Finally, you must generate an API KEY from your Textlocal dashboard
![API KEY](/docs/api_key.png)

Then you can use the facade like
### To send to a single user
```php
Sms::transactional()
    ->to('911234523451')
    ->from('SENDER')   //Your registered sender
    ->send('My message');  //as per your approved template
```
### To send to multiple users at once
```php
Sms::transactional()
    ->to('911234523451,919898456456')
    ->from('SENDER')   //Your registered sender
    ->send('My message');  //as per your approved template
```

OR, you can also provide the numbers as an array
```php
Sms::transactional()
    ->to(['911234523451', '919898456456'])
    ->from('SENDER')   //Your registered sender
    ->send('My message');  //as per your approved template
```
And if you want to schedule a message to be sent at a specified time in future
you can provide a datetime string in any of the formats supported by [Carbon](https://carbon.nesbot.com/) 
or you may also provide a unix timestamp.
```php

$schedule = "2019-01-01 09:30:00"; 

Sms::transactional()
    ->to('911234523451,919898456456')
    ->at($schedule) 
    ->from('SENDER')   //Your registered sender
    ->send('My message');  //as per your approved template
```
To cancel a scheduled message
```php
//Get a list of all the scheduled messages
$response = Account::transactional()->scheduledMessages();
$scheduledMessages = $response->scheduled;

//Identify the scheduled message you want to cancel
$msg = $scheduledMessages[0];

//Cancel the message
Account::transactional()->cancel($msg->id);
```
### Messaging - Promotional Account

### To send to a single user
```php
Sms::promotional()
    ->to('911234523451')
    ->from('TXTLCL')   //or Your registered sender
    ->send('My message');  
```
### To send to multiple users at once
```php
Sms::promotional()
    ->to('911234523451,919898456456')
    ->from('TXTLCL')   //or Your registered sender
    ->send('My message');  
```

OR, you can also provide the numbers as an array
```php
Sms::promotional()
    ->to(['911234523451', '919898456456'])
    ->from('TXTLCL')   //or Your registered sender
    ->send('My message');  
```
And if you want to schedule a message to be sent at a specified time in future
you can provide a datetime string in any of the formats supported by [Carbon](https://carbon.nesbot.com/) 
or you may also provide a unix timestamp.

```php
$schedule = "2019-01-01 09:30:00";

Sms::promotional()
    ->to('911234523451,919898456456')
    ->at($schedule) //unix timestamp
    ->from('TXTLCL')   //or Your registered sender
    ->send('My message');  //as per your approved template
```
To cancel a scheduled message
```php
//Get a list of all the scheduled messages
$response = Account::promotional()->scheduledMessages();
$scheduledMessages = $response->scheduled;

//Identify the scheduled message you want to cancel
$msg = $scheduledMessages[0];

//Cancel the message
Account::promotional()->cancel($msg->id);
```

### Message Status
To get the details of status of a message sent
```php
//Capture the message id and or batch id when you send the message
$response = Sms::promotional()->to(['911234523451', '919898456456'])->from('TXTLCL')->send('My Message');
$msgId = $response->messages[1]->id;
$batchId = $response->batch_id;

//Get the status
Account::promotional()->messageStatus($msgId);
//Or
Account::promotional()->batchStatus($batchId);
```

### Received Messages from Inbox
To retrieve the received messages from specific inbox
```php
//Get a list of all inboxes in your account
$inboxList = Account::promotional()->inboxes();

//Identify the inbox you want to fetch the messages from, and get its id
$inboxId = $inboxList[0]->id;

//Fetch the messages from the inbox
$messages = Account::promotional()->messages($inboxId);
```

### Templates in Account
To fetch all the templates associated with the account
```php
$templates = Account::transactional()->templates();
```

### Sender names
To fetch all the approved sender names from your account
```php
$senders = Account::transactional()->senders();
```

### Account Balance
To get the current account balance (credits remaining)
```php
$balance = Account::promotional()->balance();
```

### Check the availability of keyword
```php
$availability = Account::promotional()->checkKeyword('KEYWRD');
```

### Check if a Contact Group exists
```php
$exists = Account::promotional()->groupExists('Customers')
```

### Create a New Contact Group
```php
$groupId = Account::promotional()->createGroup('Tech')->group->id;
```

### Get a list of all Groups
```php
$groupList = Account::promotional()->groups();
```

### Delete a Contact Group
```php
//Note the id of the group when you create a new one
$customersId = Account::promotional()->createGroup('Customers')->group->id;
//Or get a list of all groups
$groupList = Account::promotional()->groups();
//Get the id of the group you want to delete
$groupId = $groupList[0]->id;

//Delete the group
$status = Account::promotional()->deleteGroup($customerId);
//Or
$status = Account::promotional()->deleteGroup($groupId);
//Or just give the name of the group to the command
$status = Account::promotional()->deleteGroup('Customers');
```

### List of all Members of a Contact Group
```php
$groupId = '66455';
$members = Account::promotional()->members($groupId);
```

### Add numbers to an existing contact group
```php
$groupId = '66455';
$numbers = '919033100026,919879612326';
$response = Account::promotional()->addNumbers($numbers, $groupId);
```

### Add Contacts to an existing group
```php
$groupId = '66455';

$members = [
	['number' => '911234567894', 'first_name' => 'John', 'last_name' => 'Doe'],
	['number' => '913355667798', 'first_name' => 'Jane', 'last_name' => 'Mclane']
];

$response = Account::promotional()->addMembers($members);
```

### Remove a contact from the group
```php
$response = Account::promotional()->removeMember('913355667798', $groupId);
```

### Get a list of all who have opted out
```php
$response = Account::promotional()->optOuts();
```

### History
```php
//Single message histr=ory
$response = Account::promotional()->history('single');

//Group message history
$response = Account::promotional()->history('group');

//Api message history
$response = Account::promotional()->history('api');
```

### Surveys
To fetch a list of all active surveys
```php
$response = Account::promotional()->surveys();
```

To get the details of a specific survey
```php
//Get a list of active surveys
$surveys = Account::promotional()->surveys();
//Identify the survey for which you want the details
$surveyId = $surveys->survey_ids[0]->id;
//Fetch the details
$response = Account::promotional()->surveyDetails($surveyId);
```

To get the results of a specific survey
```php
//Get a list of active surveys
$surveys = Account::promotional()->surveys();
//Identify the survey for which you want the results
$surveyId = $surveys->survey_ids[0]->id;
//Fetch the results
$response = Account::promotional()->surveyResults($surveyId);
```
## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing
Please note that all test are integration tests meaning they will actually hit the textlocal api and consume the credits.
``` bash
$ composer test
```

## Security

If you discover any security related issues, please email neerav@thinkstudeo.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Neerav Pandya](https://github.com/neeravp)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
