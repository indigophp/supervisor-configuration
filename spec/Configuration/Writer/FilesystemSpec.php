<?php

namespace spec\Indigo\Supervisor\Configuration\Writer;

use Indigo\Supervisor\Configuration;
use Indigo\Supervisor\Configuration\Renderer;
use League\Flysystem\Filesystem as Flysystem;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FilesystemSpec extends ObjectBehavior
{
    function let(Flysystem $filesystem)
    {
        $this->beConstructedWith($filesystem, 'file');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Supervisor\Configuration\Writer\Filesystem');
    }

    function it_is_a_writer()
    {
        $this->shouldImplement('Indigo\Supervisor\Configuration\Writer');
    }

    function it_writes_a_configuration_to_a_file(Flysystem $filesystem, Renderer $renderer, Configuration $configuration)
    {
        $renderer->render($configuration)->willReturn('contents');
        $filesystem->put('file', 'contents')->willReturn(true);
        $this->beConstructedWith($filesystem, 'file', $renderer);

        $this->write($configuration)->shouldReturn(true);
    }

    function it_throws_an_exception_when_configuration_cannot_be_written(Flysystem $filesystem, Renderer $renderer, Configuration $configuration)
    {
        $renderer->render($configuration)->willReturn('contents');
        $filesystem->put('file', 'contents')->willReturn(false);
        $this->beConstructedWith($filesystem, 'file', $renderer);

        $this->shouldThrow('Indigo\Supervisor\Exception\WrittingFailed')->duringWrite($configuration);
    }
}
