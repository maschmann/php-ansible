# php-ansible library

This library is a oop-wrapper for the ansible provisioning tool.
I intend to use this library for a symfony2 bundle and also a deployment GUI, based on php.
The current implementation is feature-complete for ansible-playbook. ansible-galaxy still needs to be implemented.

## prerequisites

Your OS should be a flavor of linux and ansible has to be installed. It's easiest if ansible is in PATH :-)
The library try to find ansible-playbook and ansible-galaxy by itself or use a path you provide. 

## usage

First instantiate the base object which works as a factory for your commands.
Only the first parameter with the path to your ansible deployment. The other two params are optional paths to your local ansible installation's binaries, in case they are not on PATH.

```
    $ansible = new Ansible(
        '/path/to/ansible/deployment',
        '/optional/path/to/ansible-playbook',
        '/optional/path/to/ansible-galaxy'
    );
```

Then you can use the object just like in your previous ansible deployment.
If you don't specify an inventory file with ```->inventoryFile('filename')```, the wrapper tries to determine one, base on your playbook: 

```
    $ansible
        ->playbook()
        ->play('mydeployment.yml') // based on deployment root 
        ->user('maschmann')
        ->extraVars(['project_release=20150514092022'])
        ->limit('test')
        ->execute();
```

For the execute command you can use a callback to get real-time output of the command:

```
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
