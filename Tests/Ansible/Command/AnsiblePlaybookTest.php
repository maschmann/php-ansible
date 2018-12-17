<?php
/*
 * This file is part of the php-ansible package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Asm\Tests\Ansible\Command;

use Asm\Ansible\Command\AnsiblePlaybook;
use Asm\Ansible\Command\AnsiblePlaybookInterface;
use Asm\Ansible\Process\ProcessBuilder;
use Asm\Test\AnsibleTestCase;
use SebastianBergmann\Comparator\DateTimeComparator;
use Symfony\Component\Process\Process;

class AnsibleTest extends AnsibleTestCase
{
    /**
     * @return AnsiblePlaybookInterface
     */
    public function testCreateInstance()
    {
        $process = new ProcessBuilder($this->getPlaybookUri(), $this->getProjectUri());
        $ansible = new AnsiblePlaybook($process);

        $this->assertInstanceOf('\Asm\Ansible\Command\AnsiblePlaybook', $ansible);

        return $ansible;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     * @throws \Exception
     */
    public function testDefaultDeployment(AnsiblePlaybookInterface $command)
    {
        $today = new \DateTime();

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
    public function testAskPassArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testAskSuPassArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testAskBecomePassArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testAskVaultPassArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testConnectionArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testDiffArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testForceHandlersArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testForksArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testHelpArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testLimitArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testlistHostsArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testListTasksArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testModulePathArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testPrivateKeyArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testSkipTagsArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testStartAtTaskArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testStepArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testSuUserArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testBecomeArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testBecomeUserArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testSyntaxCheckArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testTagsArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testTimeoutArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testUserArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testVaultPasswordFileArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testVerboseArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testVersionArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testFlushCacheParameterPresent(AnsiblePlaybookInterface $command)
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
    public function testNewVaultIdArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testNewVaultPasswordFileArgumentPresent(AnsiblePlaybookInterface $command)
    {
        $command
            ->play($this->getPlayUri())
            ->newVaultPasswordFile('/path/to/vault');

        $arguments = array_flip($command->getCommandlineArguments());
        $this->assertArrayHasKey('--new-vault-password-file=/path/to/vault', $arguments);

        return $command;;
    }

    /**
     * @depends testCreateInstance
     * @param AnsiblePlaybookInterface $command
     * @return AnsiblePlaybookInterface
     */
    public function testScpExtraArgsArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testSftpExtraArgsArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testSshCommonArgsArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testSshExtraArgsArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testVaultIdArgumentPresent(AnsiblePlaybookInterface $command)
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
    public function testGetCommandlineArguments(AnsiblePlaybookInterface $command)
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
    public function testExecuteWithCallback(AnsiblePlaybookInterface $command)
    {
        $exitcode = $command
            ->execute(function ($type, $buffer) {
                if (Process::ERR === $type) {
                    $out = 'ERR > '.$buffer;
                } else {
                    $out = 'OUT > '.$buffer;
                }
            });

        $this->assertTrue(is_integer($exitcode));
    }

    /**
     * @depends testDefaultDeployment
     * @param AnsiblePlaybookInterface $command
     */
    public function textExecuteWithTextOutput(AnsiblePlaybookInterface $command)
    {
        $result = $command
            ->execute(null);

        $this->assertTrue(is_string($result));
    }

}
