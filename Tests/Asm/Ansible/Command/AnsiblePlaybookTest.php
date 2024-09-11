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
     * @throws Exception
     */
    public function testDefaultDeployment(): AnsiblePlaybookInterface
    {
        $command = $this->testCreateInstance();
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

    public function testAskPassArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->askPass();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--ask-pass', $arguments);
    }

    public function testAskSuPassArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->askSuPass();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--ask-su-pass', $arguments);
    }


    public function testAskBecomePassArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->askBecomePass();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--ask-become-pass', $arguments);
    }


    public function testAskVaultPassArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->askVaultPass();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--ask-vault-pass', $arguments);
    }


    public function testConnectionArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->connection();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--connection=smart', $arguments);

        $command
            ->connection('test');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--connection=test', $arguments);
    }


    public function testDiffArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->diff();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--diff', $arguments);
    }


    public function testForceHandlersArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->forceHandlers();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--force-handlers', $arguments);
    }


    public function testForksArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->forks();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--forks=5', $arguments);

        $command
            ->forks(10);

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--forks=10', $arguments);
    }


    public function testHelpArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->help();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--help', $arguments);
    }


    public function testLimitArgumentPresent()
    {
        $command = $this->testCreateInstance();
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
    }


    public function testlistHostsArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->listHosts();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--list-hosts', $arguments);
    }


    public function testListTasksArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->listTasks();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--list-tasks', $arguments);
    }


    public function testModulePathArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->modulePath();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--module-path=/usr/share/ansible/', $arguments);

        $command
            ->modulePath(['/test', '/narf']);

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--module-path=/test,/narf', $arguments);
    }


    public function testPrivateKeyArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->privateKey('/path/to/private/key');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--private-key=/path/to/private/key', $arguments);
    }


    public function testSkipTagsArgumentPresent()
    {
        $command = $this->testCreateInstance();
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
    }


    public function testStartAtTaskArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->startAtTask('test');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--start-at-task=test', $arguments);
    }


    public function testStepArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->step();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--step', $arguments);
    }


    public function testSuArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->su();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--su', $arguments);
    }


    public function testSuUserArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->suUser();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--su-user=root', $arguments);

        $command
            ->suUser('maschmann');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--su-user=maschmann', $arguments);
    }


    public function testBecomeArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->become();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--become', $arguments);
    }


    public function testBecomeUserArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->becomeUser();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--become-user=root', $arguments);

        $command
            ->becomeUser('maschmann');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--become-user=maschmann', $arguments);
    }


    public function testSyntaxCheckArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->syntaxCheck();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--syntax-check', $arguments);
    }


    public function testTagsArgumentPresent()
    {
        $command = $this->testCreateInstance();
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
    }


    public function testTimeoutArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->timeout();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--timeout=10', $arguments);

        $command
            ->timeout(115);

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--timeout=115', $arguments);
    }


    public function testUserArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->user('maschmann');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--user=maschmann', $arguments);
    }


    public function testVaultPasswordFileArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->vaultPasswordFile('/path/to/vault');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--vault-password-file=/path/to/vault', $arguments);
    }


    public function testVerboseArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->verbose();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('-v', $arguments);

        $command
            ->verbose('vvv');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('-vvv', $arguments);
    }


    public function testVersionArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->version();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--version', $arguments);
    }


    public function testFlushCacheParameterPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->flushCache();

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--flush-cache', $arguments);
    }


    public function testNewVaultIdArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->newVaultId('someId');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--new-vault-id=someId', $arguments);
    }


    public function testNewVaultPasswordFileArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->newVaultPasswordFile('/path/to/vault');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--new-vault-password-file=/path/to/vault', $arguments);
    }


    public function testScpExtraArgsArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->scpExtraArgs('SomeExtraArgs');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--scp-extra-args=SomeExtraArgs', $arguments);
    }


    public function testSftpExtraArgsArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->sftpExtraArgs('SftExtraArgs');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--sftp-extra-args=SftExtraArgs', $arguments);
    }


    public function testSshCommonArgsArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->sshCommonArgs('SshCommonArgs');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--ssh-common-args=SshCommonArgs', $arguments);
    }


    public function testSshExtraArgsArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->sshExtraArgs('SshExtraArgs');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--ssh-extra-args=SshExtraArgs', $arguments);
    }


    public function testVaultIdArgumentPresent()
    {
        $command = $this->testCreateInstance();
        $command
            ->play($this->getPlayUri())
            ->vaultId('VaultId');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--vault-id=VaultId', $arguments);
    }


    public function testGetCommandlineArguments()
    {
        $command = $this->testCreateInstance();
        $arguments = $command
            ->play($this->getPlayUri())
            ->getCommandlineArguments();

        $this->assertTrue(is_array($arguments));
        $this->assertTrue(is_string($command->getCommandlineArguments(false)));
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

    public function testReturnsExitCodeIfCallbackWasPassed(): void
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
