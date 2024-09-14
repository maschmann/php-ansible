<?php

declare(strict_types=1);

namespace Asm\Ansible\Command;

use Asm\Ansible\Process\ProcessBuilder;
use Asm\Ansible\Process\ProcessBuilderInterface;
use Asm\Ansible\Testing\AnsibleTestCase;
use Asm\Ansible\Utils\Env;
use DateTime;
use Exception;
use InvalidArgumentException;
use Symfony\Component\Process\Process;
use TypeError;

class AnsiblePlaybookTest extends AnsibleTestCase
{
    /**
     * @return AnsiblePlaybookInterface
     */
    public function testCreateInstance(): AnsiblePlaybookInterface
    {
        $process = new ProcessBuilder($this->getPlaybookUri(), $this->getProjectUri());
        $ansible = new AnsiblePlaybook($process);

        $this->assertInstanceOf(AnsiblePlaybook::class, $ansible);

        return $ansible;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     * @throws Exception
     */
    public function testDefaultDeployment(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $today = new DateTime();

        $command
            ->play($this->getPlayUri())
            ->user('maschmann')
            ->extraVars(['project_release=' . $today->getTimestamp()])
            ->limit('test')
            ->check();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--user=maschmann', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testAskPassArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->askPass();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--ask-pass', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testAskSuPassArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->askSuPass();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--ask-su-pass', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testAskBecomePassArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->askBecomePass();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--ask-become-pass', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testAskVaultPassArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->askVaultPass();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--ask-vault-pass', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testConnectionArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->connection();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--connection=smart', $arguments);

        $command
            ->connection('test');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--connection=test', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testDiffArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->diff();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--diff', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testForceHandlersArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->forceHandlers();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--force-handlers', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testForksArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->forks();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--forks=5', $arguments);

        $command
            ->forks(10);

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--forks=10', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testHelpArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->help();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--help', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testLimitArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->limit('test');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--limit=test', $arguments);

        $command
            ->limit(['test']);

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--limit=test', $arguments);

        $command
            ->limit(['test', 'more', 'some']);

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--limit=test,more,some', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testlistHostsArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->listHosts();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--list-hosts', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testListTasksArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->listTasks();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--list-tasks', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testModulePathArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->modulePath();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--module-path=/usr/share/ansible/', $arguments);

        $command
            ->modulePath(['/test', '/narf']);

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--module-path=/test,/narf', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testPrivateKeyArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->privateKey('/path/to/private/key');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--private-key=/path/to/private/key', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testSkipTagsArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->skipTags('test');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--skip-tags=test', $arguments);

        $command
            ->skipTags(['test']);

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--skip-tags=test', $arguments);

        $command
            ->skipTags(['test', 'another']);

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--skip-tags=test,another', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testStartAtTaskArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->startAtTask('test');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--start-at-task=test', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testStepArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->step();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--step', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testSuArgumentPresent(AnsiblePlaybookInterface $command)
    {
        $command
            ->play($this->getPlayUri())
            ->su();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--su', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testSuUserArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->suUser();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--su-user=root', $arguments);

        $command
            ->suUser('maschmann');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--su-user=maschmann', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testBecomeArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->become();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--become', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testBecomeUserArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->becomeUser();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--become-user=root', $arguments);

        $command
            ->becomeUser('maschmann');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--become-user=maschmann', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testSyntaxCheckArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->syntaxCheck();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--syntax-check', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testTagsArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->tags('oneTag');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--tags=oneTag', $arguments);

        $command
            ->tags(['oneTag']);

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--tags=oneTag', $arguments);

        $command
            ->tags(['oneTag', 'anotherTag']);

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--tags=oneTag,anotherTag', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testTimeoutArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->timeout();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--timeout=10', $arguments);

        $command
            ->timeout(115);

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--timeout=115', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testUserArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->user('maschmann');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--user=maschmann', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testVaultPasswordFileArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->vaultPasswordFile('/path/to/vault');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--vault-password-file=/path/to/vault', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testVerboseArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->verbose();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('-v', $arguments);

        $command
            ->verbose('vvv');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('-vvv', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testVersionArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->version();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--version', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testFlushCacheParameterPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->flushCache();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--flush-cache', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testNewVaultIdArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->newVaultId('someId');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--new-vault-id=someId', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testNewVaultPasswordFileArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->newVaultPasswordFile('/path/to/vault');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--new-vault-password-file=/path/to/vault', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testScpExtraArgsArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->scpExtraArgs('SomeExtraArgs');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--scp-extra-args=SomeExtraArgs', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testSftpExtraArgsArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->sftpExtraArgs('SftExtraArgs');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--sftp-extra-args=SftExtraArgs', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testSshCommonArgsArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->sshCommonArgs('SshCommonArgs');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--ssh-common-args=SshCommonArgs', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testSshExtraArgsArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->sshExtraArgs('SshExtraArgs');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--ssh-extra-args=SshExtraArgs', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testVaultIdArgumentPresent(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $command
            ->play($this->getPlayUri())
            ->vaultId('VaultId');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--vault-id=VaultId', $arguments);

        return $command;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testGetCommandlineArguments(AnsiblePlaybookInterface $command): AnsiblePlaybookInterface
    {
        $arguments = $command
            ->play($this->getPlayUri())
            ->getCommandlineArguments();

        $this->assertTrue(is_array($arguments));
        $this->assertTrue(is_string($command->getCommandlineArguments(false)));

        return $command;
    }

    /**
     * @depends testDefaultDeployment
     * @param AnsiblePlaybookInterface $command
     */
    public function testExecuteWithCallback(AnsiblePlaybookInterface $command): void
    {
        // Skipped on Windows
        if (Env::isWindows()) {
            $this->assertTrue(true);
            return;
        }

        $exitCode = $command
            ->execute(function (string $type, string $buffer) {
                if (Process::ERR === $type) {
                    $out = 'ERR > ' . $buffer;
                } else {
                    $out = 'OUT > ' . $buffer;
                }
                // Silly assert, just to remove the unused warning.
                $this->assertNotNull($out);
            });

        $this->assertTrue(is_integer($exitCode));
    }

    /**
     * @depends testDefaultDeployment
     * @param AnsiblePlaybookInterface $command
     */
    public function textExecuteWithTextOutput(AnsiblePlaybookInterface $command): void
    {
        $result = $command
            ->execute(null);

        $this->assertTrue(is_string($result));
    }

    public function testExtraVars(): void
    {
        //$playbookFile = $this->getSamplesPathFor(AnsiblePlaybook::class) . '/playbook1.yml';

        $tests = [
            // Test empty strings (with & without spaces).
            [
                'input' => '',
                'expect' => false,
            ],
            [
                'input' => '   ',
                'expect' => false,
            ],
            // Test empty array.
            [
                'input' => [],
                'expect' => false,
            ],
            // Test Arrays
            [
                'input' => ['key' => 'value'],
                'expect' => '--extra-vars=key=value',
            ],
            [
                'input' => ['key1' => 'value1', 'key2' => 'value2'],
                'expect' => '--extra-vars=key1=value1 key2=value2',
            ],
            // Test valid JSON.
            [
                'input' => '{ "key1": "value1", "key2": "value2" }',
                'expect' => '--extra-vars={ "key1": "value1", "key2": "value2" }',
            ],
            // Test key value string.
            [
                'input' => 'key=value',
                'expect' => '--extra-vars=key=value',
            ],
            //[
            //    'input' => $playbookFile,
            //    'expect' => sprintf('--extra-vars=@"%s"', $playbookFile),
            //],
        ];


        $builder = new ProcessBuilder($this->getPlaybookUri(), $this->getProjectUri());

        foreach ($tests as $test) {
            $input = $test['input'];
            $expect = $test['expect'];

            $ansible = new AnsiblePlaybook($builder);
            $ansible->extraVars($input);
            $arguments = array_flip($ansible->getCommandlineArguments());

            // Handles cases when the --extra-vars params should be missing.
            if ($expect === false) {
                $this->assertArrayNotHasKey('--extra-vars', $arguments);
                continue;
            }

            $this->assertArrayHasKey($expect, $arguments);
        }


        // Testing exceptions
        // ------------------
        // The following tests should throw an InvalidArgumentException

        $tests = [
            'string without equals',
            '{ key1: "value1" }', // Invalid JSON syntax (missing " from key1) which would trigger string without `=`.
            new DateTime() // Invalid type
        ];

        foreach ($tests as $input) {
            try {
                try {
                    $ansible = new AnsiblePlaybook($builder);
                    $ansible->extraVars($input);

                    // We should never reach this line!
                    $this->fail(
                        sprintf(
                            'Failing asserting that %s exception has been thrown',
                            InvalidArgumentException::class
                        )
                    );
                } catch (InvalidArgumentException $ignored) {
                }
            } catch (TypeError $ignored) {
                // not nice .. this is to keep a bit of BC, since php is more strict on argument types
            }
        }

        // Testing multiple params instances
        // (see https://github.com/metagusto/php-ansible/issues/7)
        // ------------------------------------------------------
        $ansible = new AnsiblePlaybook($builder);
        $ansible->extraVars(['key' => 'value']);
        //$ansible->extraVars($playbookFile);

        // We should get:
        $arguments = $ansible->getCommandlineArguments();
        $this->assertTrue(in_array('--extra-vars=key=value', $arguments));
        //$this->assertTrue(in_array(sprintf('--extra-vars=@"%s"', $playbookFile), $arguments));
    }

    public function testInventory(): void
    {
        $tests = [
            [
                'input' => [],
                'expect' => false,
            ],
            [
                'input' => ['localhost'],
                'expect' => '--inventory="localhost,"',
            ],
            [
                'input' => ['localhost', 'host1'],
                'expect' => '--inventory="localhost, host1"',
            ],

        ];

        $builder = new ProcessBuilder($this->getPlaybookUri(), $this->getProjectUri());

        foreach ($tests as $test) {
            $input = $test['input'];
            $expect = $test['expect'];

            $ansible = new AnsiblePlaybook($builder);
            $ansible->inventory($input);
            $arguments = array_flip($ansible->getCommandlineArguments());

            // Handles cases when the --inventory params should be missing.
            if ($expect === false) {
                $this->assertArrayNotHasKey('--inventory', $arguments);
                continue;
            }

            $this->assertArrayHasKey($expect, $arguments);
        }
    }

    public function testRolesPath(): void
    {
        $samplePath = $this->getSamplesPathFor(AnsiblePlaybook::class);
        $tests = [
            [
                'input' => '',
                'expect' => false,
            ],
            [
                'input' => $samplePath,
                'expect' => $samplePath,
            ],
        ];

        $ansible = null;
        $builder = new ProcessBuilder($this->getPlaybookUri(), $this->getProjectUri());

        foreach ($tests as $test) {
            $input = $test['input'];
            $expect = $test['expect'];

            $ansible = new AnsiblePlaybook($builder);
            $ansible->rolesPath($input);
            $env = $builder->getProcess()->getEnv();

            // Handles cases when the ANSIBLE_ROLES_PATH var should be missing.
            if ($expect === false) {
                $this->assertArrayNotHasKey('ANSIBLE_ROLES_PATH', $env);
                continue;
            }

            $this->assertArrayHasKey('ANSIBLE_ROLES_PATH', $env);
            $this->assertEquals($expect, $env['ANSIBLE_ROLES_PATH']);
        }

        // Ensuring that the InvalidArgumentException is thrown
        $this->expectException(InvalidArgumentException::class);
        $ansible->rolesPath('/really/not/existing/path');
    }

    public function testHostKeyChecking(): void
    {
        $tests = [
            [
                'input' => true,
                'expect' => 'True',
            ],
            [
                'input' => false,
                'expect' => 'False',
            ],
        ];

        $builder = new ProcessBuilder($this->getPlaybookUri(), $this->getProjectUri());

        foreach ($tests as $test) {
            $input = $test['input'];
            $expect = $test['expect'];

            $ansible = new AnsiblePlaybook($builder);
            $ansible->hostKeyChecking($input);
            $env = $builder->getProcess()->getEnv();

            $this->assertArrayHasKey('ANSIBLE_HOST_KEY_CHECKING', $env);
            $this->assertEquals($expect, $env['ANSIBLE_HOST_KEY_CHECKING']);
        }
    }

    public function testSshPipelining(): void
    {
        $tests = [
            [
                'input'  => true,
                'expect' => 'True',
            ],
            [
                'input'  => false,
                'expect' => 'False',
            ],
        ];

        $builder = new ProcessBuilder($this->getPlaybookUri(), $this->getProjectUri());

        foreach ($tests as $test) {
            $input  = $test['input'];
            $expect = $test['expect'];

            $ansible = new AnsiblePlaybook($builder);
            $ansible->sshPipelining($input);
            $env = $builder->getProcess()->getEnv();

            $this->assertArrayHasKey('ANSIBLE_SSH_PIPELINING', $env);
            $this->assertEquals($expect, $env['ANSIBLE_SSH_PIPELINING']);
        }
    }

    public function testReturnsErrorOutputIfProcessWasNotSuccessful(): void
    {
        $builder = $this->createMock(ProcessBuilderInterface::class);
        $builder
            ->expects(self::once())
            ->method('setArguments')
            ->willReturnSelf();
        $builder
            ->expects(self::once())
            ->method('getProcess')
            ->willReturn($process = $this->createMock(Process::class));
        $process
            ->expects(self::once())
            ->method('run');
        $process
            ->expects(self::once())
            ->method('isSuccessful')
            ->willReturn(false);
        $process
            ->expects(self::once())
            ->method('getErrorOutput')
            ->willReturn('error output');
        $process
            ->expects(self::never())
            ->method('getOutput');

        $playbook = new AnsiblePlaybook($builder);

        self::assertEquals('error output', $playbook->execute());
    }

    public function testReturnsNormalOutputIfProcessWasSuccessful(): void
    {
        $builder = $this->createMock(ProcessBuilderInterface::class);
        $builder
            ->expects(self::once())
            ->method('setArguments')
            ->willReturnSelf();
        $builder
            ->expects(self::once())
            ->method('getProcess')
            ->willReturn($process = $this->createMock(Process::class));
        $process
            ->expects(self::once())
            ->method('run');
        $process
            ->expects(self::once())
            ->method('isSuccessful')
            ->willReturn(true);
        $process
            ->expects(self::once())
            ->method('getOutput')
            ->willReturn('success');
        $process
            ->expects(self::never())
            ->method('getErrorOutput');

        $playbook = new AnsiblePlaybook($builder);

        self::assertEquals('success', $playbook->execute());
    }

    public function testReturnsExitCodeIfCallbackwasPassed(): void
    {
        $builder = $this->createMock(ProcessBuilderInterface::class);
        $builder
            ->expects(self::once())
            ->method('setArguments')
            ->willReturnSelf();
        $builder
            ->expects(self::once())
            ->method('getProcess')
            ->willReturn($process = $this->createMock(Process::class));
        $process
            ->expects(self::once())
            ->method('run')
            ->willReturn(0);
        $process
            ->expects(self::never())
            ->method('isSuccessful');
        $process
            ->expects(self::never())
            ->method('getOutput');
        $process
            ->expects(self::never())
            ->method('getErrorOutput');

        $playbook = new AnsiblePlaybook($builder);

        self::assertEquals(0, $playbook->execute(fn () => null));
    }
}
