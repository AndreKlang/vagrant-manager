<?php

namespace Klang\App\Command;

use Klang\App\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AboutCommand extends Command
{

    protected function configure(){
        $this
            ->setName('about')
            ->setDescription('License & General info about vagma')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function execute(InputInterface $input, OutputInterface $output){
        $output->writeln(<<<EOF
<info>Background</info>
 The reason this app exists is that I (Andre Klang) use vagrant quite a lot,
 and I have many different boxes that O often switch between. In fact, I currently
 have 22 boxes installed on this computer alone.

 Starting and stopping boxes was a bit annoying, having to write one long command
 to get the status list (<info>vagrant global-status</info>), then copy the 7
 characters long id of the box, and then wite another command and paste the id on
 the end. Doesn't sound like much, but do it 10/15 times a day and you start
 looking for an alternative..

<info>About the Author</info>
 My name is Andre Klang, I've been working professionally with php since 2007,
 although very much centered around the e-commerce platform Magento (Community
 & Enterprise). So this app is pretty much my first taste at "new age" php.
 With fancy namespaces, composer, symfony and such..

 This is also my first real open source project, that is actually released to
 the public.

<info>Get the source</info>
 You can find the source for this app on github:
 <fg=yellow>https://github.com/AndreKlang/vagrant-manager</>

<info>License</info> <fg=blue>The MIT License</>

Copyright (c) 2015 Andre Klang (hej@andreklang.se)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.  IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.



EOF
        );
    }
}
