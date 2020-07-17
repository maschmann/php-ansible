# php-ansible library
[![Build Status](https://travis-ci.org/maschmann/php-ansible.png?branch=master)](https://travis-ci.org/maschmann/php-ansible)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/maschmann/php-ansible/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/maschmann/php-ansible/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/maschmann/php-ansible/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/maschmann/php-ansible/?branch=master)

This library is a OOP-wrapper for the ansible provisioning tool.
I intend to use this library for a symfony2 bundle and also a deployment GUI, based on php/symfony2.
The current implementation is feature-complete for the `ansible-playbook` and `ansible-galaxy` commands.



## Prerequisites

Your OS should be a flavor of linux and ansible has to be installed. It's easiest if ansible is in PATH :-)
The library tries to find ansible-playbook and ansible-galaxy by itself or use the paths/executables you provide. 



## Usage

First instantiate the base object which works as a factory for the commands.
Only the first parameter is mandatory, and provides the library with the path to your ansible deployment file structure. 

```php
$ansible = new Ansible(
    '/path/to/ansible/deployment'
);
```

Optionally, you can specify the path of your `ansible-playbook` and `ansible-galaxy` commands, just in case they are not in the PATH.

```php
$ansible = new Ansible(
    '/path/to/ansible/deployment',
    '/optional/path/to/command/ansible-playbook',
    '/optional/path/to/command/ansible-galaxy'
);
```

You can also pass any PSR compliant logging class to have further details logged. This is **especially useful to have the actual run command logged**.

```php
$ansible = new Ansible(
    '/path/to/ansible/deployment'
);

// $logger is a PSR-compliant logging implementation (e.g. monolog)
$ansible->setLogger($logger);
```



### Playbooks

Then you can use the object just like in your previous ansible deployment.
If you don't specify an inventory file with ```->inventoryFile('filename')```, the wrapper tries to determine one, based on your playbook name: 

```php
$ansible
    ->playbook()
    ->play('mydeployment.yml') // based on deployment root 
    ->user('maschmann')
    ->extraVars(['project_release' => 20150514092022])
    ->limit('test')
    ->execute();
```

This will create following ansible command:

```bash
$ ansible-playbook mydeployment.yml -i mydeployment --user=maschmann --extra-vars="project-release=20150514092022" --limit=test
```


For the execute command you can use a callback to get real-time output of the command:

```php
$ansible
    ->playbook()
    ->play('mydeployment.yml') // based on deployment root 
    ->user('maschmann')
    ->extraVars(['project_release' => 20150514092022])
    ->limit('test')
    ->execute(function ($type, $buffer) {
        if (Process::ERR === $type) {
            echo 'ERR > '.$buffer;
        } else {
            echo 'OUT > '.$buffer;
        }
    });
```
If no callback is given, the method will return the ansible-playbook output as a string, so you can either ```echo``` or directly pipe it into a log/whatever.

You can also pass an external YML/JSON file as extraVars containing a complex data structure to be passed to Ansible:

```php
$ansible
    ->playbook()
    ->play('mydeployment.yml') // based on deployment root 
    ->extraVars('/path/to/your/extra/vars/file.yml')
    ->execute();
```

You can have a Json output adding json() option that enable 'ANSIBLE_STDOUT_CALLBACK=json' env vars to make a json output in ansible.

```php
$ansible
    ->playbook()
    ->json()
    ->play('mydeployment.yml') // based on deployment root 
    ->extraVars('/path/to/your/extra/vars/file.yml')
    ->execute();
```

### Galaxy

The syntax follows ansible's syntax with one deviation: list is a reserved keyword in php (array context) and
therefore I had to rename it to "modulelist()".

```php
$ansible
    ->galaxy()
    ->init('my_role')
    ->initPath('/tmp/my_path') // or default ansible roles path
    ->execute();
```
would generate:

```bash
$ ansible-galaxy init my_role --init-path=/tmp/my_path
```

You can access all galaxy commands:

 * `init()`
 * `info()`
 * `install()`
 * `help()`
 * `modulelist()`
 * `remove()`

You can combine the calls with their possible arguments, though I don't have any logic preventing e.g. ```--force``` from being applied to e.g. info().

Possible arguments/options:

 * `initPath()`
 * `offline()`
 * `server()`
 * `force()`
 * `roleFile()`
 * `rolesPath()`
 * `ignoreErrors()`
 * `noDeps()`



### Process timeout

Default process timeout is set to 300 seconds. If you need more time to execute your processes: Adjust the timeout :-) 

```php
$ansible
    ->galaxy()
    ->setTimeout(600)
    …
```



## Thank you for your contributions!

thank you for reviewing, bug reporting, suggestions and PRs :-)
[xabbuh](https://github.com/xabbuh), [emielmolenaar](https://github.com/emielmolenaar), [saverio](https://github.com/saverio), [soupdiver](https://github.com/soupdiver), [linaori](https://github.com/linaori), [paveldanilin](https://github.com/paveldanilin)



## Future features

The Next steps for implementation are:

-  wrapping the library into a bundle.
- provide commandline-capabilities.



License
----

php-ansible is licensed under the MIT license. See the [LICENSE](LICENSE) for the full license text.
