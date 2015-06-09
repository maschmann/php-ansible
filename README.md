# php-ansible library
[![Build Status](https://travis-ci.org/maschmann/php-ansible.png?branch=master)](https://travis-ci.org/maschmann/php-ansible)
[![phpci build status](http://phpci.br0ken.de/build-status/image/11)](http://phpci.br0ken.de)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/maschmann/php-ansible/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/maschmann/php-ansible/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/maschmann/php-ansible/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/maschmann/php-ansible/?branch=master)

This library is a oop-wrapper for the ansible provisioning tool.
I intend to use this library for a symfony2 bundle and also a deployment GUI, based on php/symfony2.
The current implementation is feature-complete for ansible-playbook and ansible-galaxy

Next steps for implementation are: wrapping the lib into a bundle and provide e.g. commandline-capabilities.

## prerequisites

Your OS should be a flavor of linux and ansible has to be installed. It's easiest if ansible is in PATH :-)
The library tries to find ansible-playbook and ansible-galaxy by itself or use the paths/executables you provide. 

## usage

First instantiate the base object which works as a factory for the commands.
Only the first parameter is mandatory, and provides the library with the path to your ansible deployment file structure. The other two params are optional paths to your local ansible installation's binaries, in case they are not in PATH.

```php
    $ansible = new Ansible(
        '/path/to/ansible/deployment',
        '/optional/path/to/ansible-playbook',
        '/optional/path/to/ansible-galaxy'
    );
```

### playbooks

Then you can use the object just like in your previous ansible deployment.
If you don't specify an inventory file with ```->inventoryFile('filename')```, the wrapper tries to determine one, based on your playbook name: 

```php
    $ansible
        ->playbook()
        ->play('mydeployment.yml') // based on deployment root 
        ->user('maschmann')
        ->extraVars(['project_release=20150514092022'])
        ->limit('test')
        ->execute();
```

This will create following ansible command:

```
$ ansible-playbook mydeployment.yml -i mydeployment --user=maschmann --extra-vars="project-release=20150514092022" --limit=test
```


For the execute command you can use a callback to get real-time output of the command:

```php
    $ansible
        ->playbook()
        ->play('mydeployment.yml') // based on deployment root 
        ->user('maschmann')
        ->extraVars(['project_release=20150514092022'])
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

### galaxy

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

```
$ ansible-galaxy init my_role --init-path=/tmp/my_path
```

You can access all galaxy commands:

 * init()
 * info()
 * install()
 * help()
 * modulelist() //list
 * remove()

You can combine the calls with their possible arguments, though I don't have any logic preventing e.g. ```--force``` from being applied to e.g. info().

Possible arguments/options:

 * initPath()
 * offline()
 * server()
 * force()
 * roleFile()
 * rolesPath()
 * ignoreErrors()
 * noDeps()

license
----

php-ansible is licensed under the MIT license. See the [LICENSE](LICENSE) for the full license text.
